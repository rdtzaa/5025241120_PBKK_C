<?php

namespace App\Models;

use CodeIgniter\Model;

class ShopAccountModel extends Model
{
    protected $table = 'shop_users';
    protected $allowedFields = ['username', 'name', 'password_hash', 'role'];
}
