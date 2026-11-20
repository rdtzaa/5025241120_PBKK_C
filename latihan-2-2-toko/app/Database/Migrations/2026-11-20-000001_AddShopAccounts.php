<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use RuntimeException;

class AddShopAccounts extends Migration
{
    public function up()
    {
        $this->change([
            "CREATE TABLE shop_users (
                id INTEGER PRIMARY KEY, username TEXT NOT NULL UNIQUE, name TEXT NOT NULL,
                password_hash TEXT NOT NULL, role TEXT NOT NULL CHECK (role IN ('pelanggan', 'admin'))
            )",
            'ALTER TABLE orders ADD COLUMN customer_id INTEGER REFERENCES shop_users(id)',
            'ALTER TABLE orders ADD COLUMN created_at TEXT',
            "ALTER TABLE orders ADD COLUMN status TEXT NOT NULL DEFAULT 'baru'
                CHECK (status IN ('baru', 'diproses', 'selesai', 'dibatalkan'))",
            'ALTER TABLE order_items ADD COLUMN product_id INTEGER REFERENCES products(id)',
            'CREATE INDEX orders_customer ON orders (customer_id, id)',
        ]);
    }

    public function down()
    {
        $this->change([
            'DROP INDEX orders_customer',
            'ALTER TABLE order_items DROP COLUMN product_id',
            'ALTER TABLE orders DROP COLUMN status',
            'ALTER TABLE orders DROP COLUMN created_at',
            'ALTER TABLE orders DROP COLUMN customer_id',
            'DROP TABLE shop_users',
        ]);
    }

    private function change(array $queries): void
    {
        if (! $this->db->transException(true)->transStart()) {
            throw new RuntimeException('Transaksi migrasi toko tidak dapat dimulai.');
        }
        foreach ($queries as $query) {
            $this->db->query($query);
        }
        if (! $this->db->transComplete()) {
            throw new RuntimeException('Migrasi toko tidak dapat disimpan.');
        }
    }
}
