<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        foreach (['admin' => 'admin', 'pembina' => 'peneliti'] as $username => $role) {
            $users = $this->db->table('users');
            if (! $users->where('username', $username)->countAllResults()) {
                $users->insert(['username' => $username, 'password_hash' => password_hash('12345678', PASSWORD_DEFAULT), 'role' => $role]);
            }
        }

        foreach ([
            ['RB-001', 'ESP32 DevKit', 'Mikrokontroler', 12, 'Rak A1'],
            ['RB-002', 'HC-SR04', 'Sensor', 7, 'Rak B2'],
            ['RB-003', 'Servo MG996R', 'Aktuator', 3, 'Rak C1'],
        ] as [$code, $name, $category, $stock, $location]) {
            $parts = $this->db->table('parts');
            if (! $parts->where('code', $code)->countAllResults()) {
                $parts->insert(compact('code', 'name', 'category', 'stock', 'location'));
            }
        }
    }
}
