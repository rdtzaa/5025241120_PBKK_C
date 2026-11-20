<?php

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    public string $filesPath = APPPATH . 'Database/';
    public string $defaultGroup = 'default';

    public array $default = [
        'database' => 'inventaris.sqlite',
        'DBDriver' => 'SQLite3',
        'DBPrefix' => '',
        'DBDebug' => true,
        'foreignKeys' => true,
        'busyTimeout' => 5000,
    ];
}
