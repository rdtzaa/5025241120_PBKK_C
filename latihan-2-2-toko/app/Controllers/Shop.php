<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\ProductModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Exceptions\PageNotFoundException;
use DomainException;

class Shop extends BaseController
{
    public function index()
    {
        return view('catalog', [
            'title' => 'Katalog', 'products' => model(ProductModel::class)->orderBy('id')->findAll(),
        ]);
    }

    public function cart()
    {
        $cart = session('cart') ?? [];
        $items = [];
        $total = 0;
        if ($cart) {
            foreach (model(ProductModel::class)->whereIn('id', array_keys($cart))->findAll() as $product) {
                $quantity = $cart[$product['id']];
                $subtotal = $product['price'] * $quantity;
                $items[] = $product + compact('quantity', 'subtotal');
                $total += $subtotal;
            }
        }

        return view('cart', ['title' => 'Keranjang', 'items' => $items, 'total' => $total]);
    }

    public function setQuantity(int $id)
    {
        $cart = session('cart') ?? [];
        $product = model(ProductModel::class)->find($id);
        $input = $this->request->getPost('quantity', FILTER_VALIDATE_INT, FILTER_REQUIRE_SCALAR);
        $add = $this->request->getPost('add') === '1';
        $quantity = is_int($input) ? ($add ? ($cart[$id] ?? 0) + $input : $input) : -1;
        $target = site_url($add ? '/' : 'keranjang');

        if (! $product || $quantity < 0 || $quantity > $product['stock'] || ($add && $quantity <= ($cart[$id] ?? 0))) {
            return redirect()->to($target)->with('error', 'Jumlah produk harus sesuai stok yang tersedia.');
        }

        if ($quantity === 0) {
            unset($cart[$id]);
        } else {
            $cart[$id] = $quantity;
        }
        session()->set('cart', $cart);

        return redirect()->to($target)->with('success', $add ? 'Produk ditambahkan ke keranjang.' : 'Keranjang diperbarui.');
    }

    public function remove(int $id)
    {
        $cart = session('cart') ?? [];
        unset($cart[$id]);
        session()->set('cart', $cart);

        return redirect()->to(site_url('keranjang'))->with('success', 'Produk dikeluarkan dari keranjang.');
    }

    public function checkout()
    {
        $cart = session('cart') ?? [];
        $target = redirect()->to(site_url('keranjang'));
        if (! $cart) {
            return $target->with('error', 'Keranjang masih kosong.');
        }

        $data = [];
        foreach (['name', 'address'] as $field) {
            $data[$field] = trim((string) $this->request->getPost($field, FILTER_UNSAFE_RAW, FILTER_REQUIRE_SCALAR));
        }
        if (! $this->validateData($data, [
            'name' => ['label' => 'Nama penerima', 'rules' => 'required|max_length[100]'],
            'address' => ['label' => 'Alamat', 'rules' => 'required|max_length[300]'],
        ])) {
            return $target->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $id = model(OrderModel::class)->place(session('shop_account')['id'], $data, $cart);
        } catch (DomainException $error) {
            return $target->withInput()->with('error', $error->getMessage());
        } catch (DatabaseException $error) {
            log_message('error', $error->getMessage());
            return $target->withInput()->with('error', 'Pesanan belum tersimpan. Coba lagi.');
        }

        session()->remove('cart');

        return redirect()->to(site_url('pesanan/' . $id))->with('success', 'Pesanan berhasil dibuat.');
    }

    public function history()
    {
        return view('history', [
            'title' => 'Pesanan saya', 'isAdmin' => false,
            'orders' => model(OrderModel::class)->where('customer_id', session('shop_account')['id'])
                ->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function order(int $id)
    {
        $orders = model(OrderModel::class);
        $order = $orders->where('customer_id', session('shop_account')['id'])->find($id)
            ?? throw PageNotFoundException::forPageNotFound();

        return view('order', [
            'title' => 'Ringkasan pesanan', 'order' => $order, 'items' => $orders->items($id), 'isAdmin' => false,
        ]);
    }
}
