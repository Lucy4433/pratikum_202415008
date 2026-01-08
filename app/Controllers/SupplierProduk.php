<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SupplierProdukModel;
use App\Models\ProdukModel;
use App\Models\SuplierModel;
use CodeIgniter\Database\RawSql;

class SupplierProduk extends BaseController
{
    protected $supplierProdukModel;
    protected $produkModel;

    public function __construct()
    {
        $this->supplierProdukModel = new SupplierProdukModel();
        $this->produkModel         = new ProdukModel();
    }

    // list barang masuk
public function index()
{
    $data = [
        'barang_masuk' => $this->supplierProdukModel->getAll()
    ];

    return view('supplierproduk/index', $data);
}


    // form barang masuk
    public function create()
    {
        $supplierModel = new SuplierModel();

        $data = [
            'produk'   => $this->produkModel->getAll(),
            'supplier' => $supplierModel->getAll()
        ];

        return view('supplierproduk/tambah', $data);
    }


    // simpan barang masuk
    public function store()
{
    $id_produk   = $this->request->getPost('id_produk');
    $id_supplier = $this->request->getPost('id_supplier');
    $stok_masuk  = (int) $this->request->getPost('stok_masuk');
    $harga_beli  = (int) $this->request->getPost('harga_beli');

    // margin 20%
    $margin = 20;
    $harga_jual = $harga_beli + ($harga_beli * $margin / 100);

    $db = \Config\Database::connect();
    $db->transStart();

    // ==================================
    // 1️⃣ INSERT ke supplier_produk
    // (HISTORI BARANG MASUK)
    // ==================================
    $this->supplierProdukModel->insert([
        'id_supplier' => $id_supplier,
        'id_produk'   => $id_produk,
        'harga_beli'  => $harga_beli,
        'stok_masuk'  => $stok_masuk
    ]);

    // ==================================
    // 2️⃣ UPDATE tabel produk
    // (stok + harga jual aktif)
    // ==================================
    $this->produkModel->update($id_produk, [
        'harga_jual' => $harga_jual,
        'stok'       => new RawSql('stok + ' . $stok_masuk)
    ]);

    $db->transComplete();

    if ($db->transStatus() === false) {
        return redirect()->back()
            ->with('error', 'Gagal menyimpan barang masuk');
    }

    return redirect()->to('/supplierproduk')
        ->with('success', 'Barang masuk berhasil disimpan');
}

}