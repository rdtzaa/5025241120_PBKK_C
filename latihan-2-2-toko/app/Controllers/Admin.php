<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\ProductModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Exceptions\PageNotFoundException;
use DomainException;

class Admin extends BaseController
{
    public function index()
    {
        $orders = model(OrderModel::class);
        return view('dashboard', [
            'title' => 'Dashboard toko', 'summary' => $orders->summary(),
            'lowStock' => model(ProductModel::class)->where('stock <=', 5)->orderBy('stock')->findAll(),
            'recent' => $orders->orderBy('id', 'DESC')->findAll(5),
        ]);
    }

    public function products()
    {
        return view('products', [
            'title' => 'Produk dan stok', 'products' => model(ProductModel::class)->orderBy('name')->findAll(),
        ]);
    }

    public function productForm(?int $id = null)
    {
        return view('product_form', [
            'title' => $id === null ? 'Tambah produk' : 'Edit produk',
            'product' => $id === null ? null : $this->product($id),
        ]);
    }

    public function saveProduct(?int $id = null)
    {
        if ($id !== null) {
            $this->product($id);
        }

        $fields = [
            'sku' => 'required|alpha_dash|max_length[30]',
            'name' => 'required|max_length[100]',
            'description' => 'required|max_length[300]',
            'price' => 'required|is_natural|less_than_equal_to[1000000000]',
            'stock' => 'required|is_natural|less_than_equal_to[999999]',
        ];
        $data = [];
        foreach (array_keys($fields) as $key) {
            $data[$key] = trim((string) $this->request->getPost($key, FILTER_UNSAFE_RAW, FILTER_REQUIRE_SCALAR));
        }
        $data['sku'] = strtoupper($data['sku']);
        $form = redirect()->to(site_url($id === null ? 'admin/produk/baru' : 'admin/produk/' . $id . '/edit'));
        if (! $this->validateData($data, $fields)) {
            return $form->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            if ($id === null) {
                model(ProductModel::class)->insert($data);
            } else {
                $previousStock = $this->request->getPost('previous_stock', FILTER_VALIDATE_INT, FILTER_REQUIRE_SCALAR);
                if (! is_int($previousStock)) {
                    return $form->with('error', 'Stok awal tidak valid. Muat ulang formulir.');
                }

                $db = db_connect();
                $db->table('products')->where('id', $id)->where('stock', $previousStock)->update($data);
                if ($db->affectedRows() !== 1) {
                    return $form->with('error', 'Stok berubah. Periksa stok terbaru sebelum menyimpan.');
                }
            }
        } catch (DatabaseException $error) {
            log_message('error', $error->getMessage());
            return $form->withInput()->with('error', 'Produk gagal disimpan. Pastikan SKU belum dipakai.');
        }

        return redirect()->to(site_url('admin/produk'))->with('success', 'Produk berhasil disimpan.');
    }

    public function orders()
    {
        return view('history', [
            'title' => 'Pesanan pelanggan', 'isAdmin' => true,
            'orders' => model(OrderModel::class)->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function order(int $id)
    {
        return view('order', [
            'title' => 'Detail pesanan', 'order' => $this->findOrder($id),
            'items' => model(OrderModel::class)->items($id), 'isAdmin' => true,
        ]);
    }

    public function changeStatus(int $id)
    {
        $order = $this->findOrder($id);
        $next = (string) $this->request->getPost('status', FILTER_UNSAFE_RAW, FILTER_REQUIRE_SCALAR);
        $target = redirect()->to(site_url('admin/pesanan/' . $id));

        try {
            model(OrderModel::class)->transition($order, $next);
        } catch (DomainException $error) {
            return $target->with('error', $error->getMessage());
        } catch (DatabaseException $error) {
            log_message('error', $error->getMessage());
            return $target->with('error', 'Status belum tersimpan. Coba lagi.');
        }

        return $target->with('success', 'Status pesanan diperbarui.');
    }

    private function product(int $id): array
    {
        return model(ProductModel::class)->find($id) ?? throw PageNotFoundException::forPageNotFound();
    }

    private function findOrder(int $id): array
    {
        return model(OrderModel::class)->find($id) ?? throw PageNotFoundException::forPageNotFound();
    }
}
