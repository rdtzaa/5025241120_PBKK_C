<?php

namespace App\Controllers;

use App\Models\PartModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Files\UploadedFile;

class Inventory extends BaseController
{
    protected $helpers = ['form'];

    public function index()
    {
        $query = trim((string) $this->request->getGet('q', FILTER_UNSAFE_RAW, FILTER_REQUIRE_SCALAR));
        $parts = model(PartModel::class);
        if ($query !== '') {
            $parts->groupStart()->like('code', $query)->orLike('name', $query)->groupEnd();
        }

        return view('parts', ['title' => 'Daftar komponen', 'parts' => $parts->orderBy('code')->findAll(), 'query' => $query]);
    }

    public function form(?int $id = null)
    {
        return view('part_form', [
            'title' => $id === null ? 'Tambah komponen' : 'Edit komponen',
            'part' => $id === null ? [] : $this->part($id),
        ]);
    }

    public function save(?int $id = null)
    {
        $part = $id === null ? [] : $this->part($id);
        $data = [];
        foreach (['code', 'name', 'category', 'stock', 'location'] as $field) {
            $data[$field] = trim((string) $this->request->getPost($field, FILTER_UNSAFE_RAW, FILTER_REQUIRE_SCALAR));
        }
        $data['code'] = strtoupper($data['code']);
        $rules = [
            'code' => 'required|alpha_dash|max_length[24]',
            'name' => 'required|max_length[100]',
            'category' => 'required|max_length[50]',
            'stock' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[99999]',
            'location' => 'required|max_length[60]',
        ];
        $photo = $this->request->getFile('photo');
        $hasPhoto = $photo instanceof UploadedFile && $photo->getError() !== UPLOAD_ERR_NO_FILE;
        if ($hasPhoto) {
            $rules['photo'] = 'uploaded[photo]|is_image[photo]|mime_in[photo,image/jpeg,image/png,image/webp]|max_size[photo,2048]';
        }

        if (! $this->validateData($data, $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $parts = model(PartModel::class);
        $existing = $parts->where('code', $data['code'])->first();
        if ($existing && (int) $existing['id'] !== $id) {
            return redirect()->back()->withInput()->with('error', 'Kode komponen sudah dipakai.');
        }

        try {
            if ($hasPhoto) {
                $data['photo'] = $photo->getRandomName();
                $photo->move(WRITEPATH . 'uploads', $data['photo']);
            }

            $saved = $id === null ? $parts->insert($data) : $parts->update($id, $data);
        } catch (DatabaseException | \CodeIgniter\HTTP\Exceptions\HTTPException $exception) {
            log_message('error', $exception->getMessage());
            $saved = false;
        }

        if (! $saved) {
            if ($hasPhoto && $photo->hasMoved()) {
                $this->removePhoto($data['photo']);
            }
            return redirect()->back()->withInput()->with('error', 'Komponen gagal disimpan. Periksa kode dan coba lagi.');
        }
        if ($hasPhoto) {
            $this->removePhoto($part['photo'] ?? null);
        }

        return redirect()->to(site_url('/'))->with('success', 'Komponen berhasil disimpan.');
    }

    public function delete(int $id)
    {
        $part = $this->part($id);
        if (! model(PartModel::class)->delete($id)) {
            return redirect()->to(site_url('/'))->with('error', 'Komponen gagal dihapus.');
        }
        $this->removePhoto($part['photo']);

        return redirect()->to(site_url('/'))->with('success', 'Komponen berhasil dihapus.');
    }

    public function photo(int $id)
    {
        $part = $this->part($id);
        $file = WRITEPATH . 'uploads/' . $part['photo'];
        if (! $part['photo'] || ! is_file($file)) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $this->response->download($file, null, true)->inline()->setHeader('Cache-Control', 'private, no-store');
    }

    private function part(int $id): array
    {
        return model(PartModel::class)->find($id) ?? throw PageNotFoundException::forPageNotFound();
    }

    private function removePhoto(?string $name): void
    {
        if ($name && is_file(WRITEPATH . 'uploads/' . $name)) {
            unlink(WRITEPATH . 'uploads/' . $name);
        }
    }
}
