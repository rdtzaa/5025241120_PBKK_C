<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventory extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE users (
            id INTEGER PRIMARY KEY, username TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL, role TEXT NOT NULL CHECK (role IN ('admin', 'peneliti'))
        )");
        $this->db->query('CREATE TABLE parts (
            id INTEGER PRIMARY KEY, code TEXT NOT NULL UNIQUE, name TEXT NOT NULL, category TEXT NOT NULL,
            stock INTEGER NOT NULL CHECK (stock >= 0), location TEXT NOT NULL, photo TEXT
        )');
    }

    public function down()
    {
        $this->forge->dropTable('parts');
        $this->forge->dropTable('users');
    }
}
