<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateShop extends Migration
{
    public function up()
    {
        $this->db->query('CREATE TABLE products (
            id INTEGER PRIMARY KEY, sku TEXT NOT NULL UNIQUE, name TEXT NOT NULL, description TEXT NOT NULL,
            price INTEGER NOT NULL CHECK (price >= 0), stock INTEGER NOT NULL CHECK (stock >= 0)
        )');
        $this->db->query('CREATE TABLE orders (
            id INTEGER PRIMARY KEY, name TEXT NOT NULL, address TEXT NOT NULL, total INTEGER NOT NULL
        )');
        $this->db->query('CREATE TABLE order_items (
            id INTEGER PRIMARY KEY, order_id INTEGER NOT NULL REFERENCES orders(id),
            product_name TEXT NOT NULL, unit_price INTEGER NOT NULL, quantity INTEGER NOT NULL CHECK (quantity > 0)
        )');
    }

    public function down()
    {
        $this->forge->dropTable('order_items');
        $this->forge->dropTable('orders');
        $this->forge->dropTable('products');
    }
}
