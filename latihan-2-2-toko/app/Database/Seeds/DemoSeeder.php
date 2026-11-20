<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $this->db->query("INSERT OR IGNORE INTO products (sku, name, description, price, stock) VALUES
            ('ATK-01', 'Buku Catatan A5', 'Buku bergaris 80 lembar untuk catatan kuliah dan rapat.', 24000, 12),
            ('ATK-02', 'Pulpen Gel 0,5 mm', 'Tinta hitam dengan ujung halus untuk tulisan sehari-hari.', 8500, 20),
            ('ATK-03', 'Sticky Notes Pastel', 'Lima warna untuk penanda halaman dan pengingat singkat.', 15500, 10),
            ('ATK-04', 'Map Dokumen A4', 'Map kancing yang muat untuk lembar tugas dan berkas.', 12000, 8)");

        foreach (['admin' => 'Admin Toko', 'pelanggan' => 'Pelanggan Demo'] as $role => $name) {
            $this->db->query('INSERT OR IGNORE INTO shop_users (username, name, password_hash, role) VALUES (?, ?, ?, ?)',
                [$role, $name, password_hash('12345678', PASSWORD_DEFAULT), $role]);
        }
    }
}
