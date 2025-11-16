<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SuplierModel;

class Supplier extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new SuplierModel();
        helper('form');
    }

    public function index()
    {
        // ambil semua supplier — gunakan getAll() jika ada, kalau tidak pakai findAll()
        if (method_exists($this->model, 'getAll')) {
            $suppliers = $this->model->getAll();
        } else {
            $suppliers = $this->model->findAll();
        }

        // statistik singkat (optional untuk view)
        $total = is_array($suppliers) ? count($suppliers) : 0;

        $withPurchases = method_exists($this->model, 'countSuppliersWithPurchases')
            ? $this->model->countSuppliersWithPurchases()
            : 0;

        $withoutPurchases = method_exists($this->model, 'countSuppliersWithoutPurchases')
            ? $this->model->countSuppliersWithoutPurchases()
            : 0;

        $data = [
            // kirim kedua variabel supaya view kompatibel ($suppliers & $model)
            'suppliers' => $suppliers,
            'model'     => $suppliers, // fallback jika view masih pakai $model
            'stats'     => [
                'total'            => $total,
                'with_purchases'   => $withPurchases,
                'without_purchases'=> $withoutPurchases,
            ],
        ];

        return view('supplier/index', $data);
    }

    public function tambah()
    {
        if ($this->request->getMethod() !== 'POST') {
            return view('supplier/tambah');
        }

        $rules = [
            'nama_suplier'   => 'required|min_length[2]|max_length[255]',
            'alamat' => 'permit_empty|max_length[1000]',
            'no_telp'=> 'permit_empty|max_length[50]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $post = $this->request->getPost(['nama_suplier', 'alamat', 'no_telp']);

        $this->model->save($post);

        return redirect()->to(base_url('supplier'))->with('success', 'Suplier berhasil disimpan.');
    }

    public function ubah($id = null)
    {
        // proses hanya POST
        if ($this->request->getMethod() === 'POST') {
            // ambil id dari POST jika tidak ada di URL (berguna untuk modal)
            $postId = $this->request->getPost('id_suplier') ?? $this->request->getPost('id') ?? null;
            if ($id === null && $postId !== null) {
                $id = (int) $postId;
            }

            if (! $id) {
                return redirect()->to(base_url('supplier'))->with('errors', ['ID tidak valid.']);
            }

            $rules = [
                'nama_suplier'   => 'required|min_length[2]|max_length[255]',
                'alamat' => 'permit_empty|max_length[1000]',
                'no_telp'=> 'permit_empty|max_length[50]',
            ];

            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }


            $this->model->save($this->request->getPost());

            return redirect()->to(base_url('supplier'))->with('success', 'Suplier berhasil diperbarui.');
        }

        // GET: tampilkan form ubah (jika kamu punya halaman terpisah)
        if (! $id) {
            return redirect()->to(base_url('supplier'));
        }

        $supplier = $this->model->getById($id);
        if (! $supplier) {
            return redirect()->to(base_url('supplier'))->with('errors', ['Supplier tidak ditemukan.']);
        }

        return view('supplier/ubah', ['supplier' => $supplier]);
    }

    public function hapus($id = null)
    {
        // Support POST (form) atau param URL
        if ($this->request->getMethod() === 'post') {
            $postId = $this->request->getPost('id') ?? $this->request->getPost('id_suplier') ?? null;
            if ($postId) {
                $id = (int) $postId;
            }
        }

        if (! $id) {
            return redirect()->to(base_url('supplier'))->with('errors', ['ID tidak valid.']);
        }

        // cek existensi (opsional)
        $supplier = $this->model->getById($id);
        if (! $supplier) {
            return redirect()->to(base_url('supplier'))->with('errors', ['Supplier tidak ditemukan.']);
        }

        $this->model->delete($id);

        return redirect()->to(base_url('supplier'))->with('success', 'Suplier berhasil dihapus.');
    }
}
