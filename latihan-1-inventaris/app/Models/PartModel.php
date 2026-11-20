<?php

namespace App\Models;

use CodeIgniter\Model;

class PartModel extends Model
{
    protected $table = 'parts';
    protected $allowedFields = ['code', 'name', 'category', 'stock', 'location', 'photo'];
}
