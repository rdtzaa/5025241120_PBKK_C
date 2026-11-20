<?php

namespace App\Models;

use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Model;
use DomainException;

class OrderModel extends Model
{
    public const LABELS = [
        'baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan',
    ];

    public const NEXT = [
        'baru' => ['diproses', 'dibatalkan'],
        'diproses' => ['selesai', 'dibatalkan'],
    ];

    protected $table = 'orders';
    protected $allowedFields = ['name', 'address', 'total', 'customer_id', 'created_at', 'status'];

    public function items(int $orderId): array
    {
        return $this->db->table('order_items')->where('order_id', $orderId)->orderBy('id')->get()->getResultArray();
    }

    public function summary(): array
    {
        return $this->db->query("SELECT COUNT(*) AS total_orders,
            COALESCE(SUM(CASE WHEN status = 'selesai' THEN total ELSE 0 END), 0) AS revenue,
            COALESCE(SUM(CASE WHEN status IN ('baru', 'diproses') THEN 1 ELSE 0 END), 0) AS open_orders
            FROM orders")->getRowArray();
    }

    public function place(int $customerId, array $recipient, array $cart): int
    {
        $this->begin();
        try {
            $items = [];
            $total = 0;
            foreach ($cart as $id => $quantity) {
                $this->db->table('products')->where('id', $id)->where('stock >=', $quantity)
                    ->set('stock', 'stock - ' . (int) $quantity, false)->update();

                if ($this->db->affectedRows() !== 1) {
                    throw new DomainException('Stok berubah. Perbarui jumlah di keranjang.');
                }

                $product = $this->db->table('products')->where('id', $id)->get()->getRowArray();
                $items[] = ['product_id' => $id, 'product_name' => $product['name'], 'unit_price' => $product['price'], 'quantity' => $quantity];
                $total += $product['price'] * $quantity;
            }

            $this->db->table($this->table)->insert($recipient + [
                'customer_id' => $customerId, 'total' => $total, 'status' => 'baru', 'created_at' => date('Y-m-d H:i:s'),
            ]);
            $id = (int) $this->db->insertID();
            foreach ($items as $item) {
                $this->db->table('order_items')->insert($item + ['order_id' => $id]);
            }
            $this->commit();

            return $id;
        } catch (DatabaseException | DomainException $error) {
            $this->db->transRollback();
            throw $error;
        }
    }

    public function transition(array $order, string $next): void
    {
        if (! in_array($next, self::NEXT[$order['status']] ?? [], true)) {
            throw new DomainException('Perubahan status ini tidak tersedia.');
        }

        $this->begin();
        try {
            $this->db->table($this->table)->where('id', $order['id'])
                ->where('status', $order['status'])->update(['status' => $next]);

            if ($this->db->affectedRows() !== 1) {
                throw new DomainException('Status sudah berubah. Muat ulang halaman pesanan.');
            }

            if ($next === 'dibatalkan') {
                foreach ($this->items($order['id']) as $item) {
                    if ($item['product_id'] === null) {
                        throw new DomainException('Pesanan lama ini belum memiliki referensi produk untuk pengembalian stok.');
                    }
                    $this->db->table('products')->where('id', $item['product_id'])
                        ->set('stock', 'stock + ' . (int) $item['quantity'], false)->update();
                }
            }

            $this->commit();
        } catch (DatabaseException | DomainException $error) {
            $this->db->transRollback();
            throw $error;
        }
    }

    private function begin(): void
    {
        if (! $this->db->transException(true)->transBegin()) {
            throw new DatabaseException('Transaksi toko tidak dapat dimulai.');
        }
    }

    private function commit(): void
    {
        if (! $this->db->transStatus() || ! $this->db->transCommit()) {
            throw new DatabaseException('Transaksi toko tidak dapat disimpan.');
        }
    }
}
