<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SuplierModel;
use App\Models\ProdukModel;
use App\Models\SupplierProdukModel;

class Supplier extends BaseController
{
    protected $model;
    protected $produk;
    protected $supplierProduk;
    public function __construct()
    {
        $this->model = new SuplierModel();
            $this->produk = new ProdukModel();
    $this->supplierProduk = new SupplierProdukModel();

        helper('form');
    }

    public function index()
    {
        // ambil semua supplier — gunakan getAll() jika ada, kalau tidak pakai findAll()
        if (method_exists($this->model, 'getAll')) {
            $suppliers = $this->model->getAll();
        } else {
            $suppliers = $this->model->findAll();
        } //ambil semua datasupplier

        // hitung jumlah supplier
        $total = is_array($suppliers) ? count($suppliers) : 0;

        //Hitung supplier yang pernah dan belum pernah ada pembelian
        $withPurchases = method_exists($this->model, 'countSuppliersWithPurchases')
            ? $this->model->countSuppliersWithPurchases()
            : 0;

        $withoutPurchases = method_exists($this->model, 'countSuppliersWithoutPurchases')
            ? $this->model->countSuppliersWithoutPurchases()
            : 0;

        $data = [
            // kirim data ke view
            'suppliers' => $suppliers,
            'model'     => $suppliers, 
            'stats'     => [
                'total'            => $total,
                'with_purchases'   => $withPurchases,
                'without_purchases'=> $withoutPurchases,
            ]
        ];

        return view('supplier/index', $data); //tampilan halaaman supplier dengan data yang sudah dikirm
    }

    public function detail($id) //detail_supplier
    {
        $supplier = $this->model->getById($id);

        if (! $supplier) {
            return redirect()->to(base_url('supplier'))
                ->with('errors', ['Supplier tidak ditemukan.']);
        }

        $data = [
            'supplier'       => $supplier,
            'produkSupplier' => $this->supplierProduk->getBySupplier($id),
            'produk'         => $this->produk->findAll(),
        ];

        return view('supplier/detail', $data);
    }

    public function tambahProduk()
    {
        // simpan produk supplier
        $this->supplierProduk->insert([
            'id_supplier' => $this->request->getPost('id_supplier'),
            'id_produk'   => $this->request->getPost('id_produk'),
            'harga_beli'  => $this->request->getPost('harga_beli'),
            'harga_jual'  => $this->request->getPost('harga_jual'),
            'stok'        => $this->request->getPost('stok'),
            'id_discount' => null,
        ]);

        $supplierProdukId = $this->supplierProduk->insertID();

        // data diskon
        $namaDiskon = $this->request->getPost('nama_discount');
        $persen     = $this->request->getPost('persen');
        $dariDate   = $this->request->getPost('dari_date');
        $sampaiDate = $this->request->getPost('sampai_date');

        // simpan diskon JIKA LENGKAP
        if ($namaDiskon && $persen > 0 && $dariDate && $sampaiDate) {

            $discountModel = new \App\Models\DiscountModel();

            $discountModel->insert([
                'id_produk'     => $this->request->getPost('id_produk'),
                'nama_discount' => $namaDiskon,
                'persen'        => $persen,
                'dari_date'     => $dariDate,
                'sampai_date'   => $sampaiDate,
                'status'        => 1,
            ]);

            $this->supplierProduk->update($supplierProdukId, [
                'id_discount' => $discountModel->insertID()
            ]);
        }

        return redirect()->back()->with('success', 'Produk supplier berhasil ditambahkan.');
    }

    public function updateProduk()
    {
        $idSupplierProduk = $this->request->getPost('id_supplier_produk');
        $idProduk         = $this->request->getPost('id_produk');

        // update produk supplier
        $this->supplierProduk->update($idSupplierProduk, [
            'harga_beli' => $this->request->getPost('harga_beli'),
            'harga_jual' => $this->request->getPost('harga_jual'),
            'stok'       => $this->request->getPost('stok'),
        ]);

        // DATA DISKON
        $idDiscount = $this->request->getPost('id_discount');
        $persen     = (int) $this->request->getPost('persen');
        $dariDate   = $this->request->getPost('dari_date');
        $sampaiDate = $this->request->getPost('sampai_date');

        $discountModel = new \App\Models\DiscountModel();

        // =========================
        // JIKA DISKON DIISI
        // =========================
        if ($persen > 0 && $dariDate && $sampaiDate) {

            if ($idDiscount) {
                // UPDATE DISKON
                $discountModel->update($idDiscount, [
                    'besaran'     => $persen,
                    'dari_date'   => $dariDate,
                    'sampai_date' => $sampaiDate,
                ]);
            } else {
                // INSERT DISKON BARU
                $discountModel->insert([
                    'id_produk'   => $idProduk,
                    'besaran'     => $persen,
                    'dari_date'   => $dariDate,
                    'sampai_date' => $sampaiDate,
                ]);

                $this->supplierProduk->update($idSupplierProduk, [
                    'id_discount' => $discountModel->insertID()
                ]);
            }

        } 
        // =========================
        // JIKA DISKON DIHAPUS
        // =========================
        else {
            if ($idDiscount) {
                $discountModel->delete($idDiscount);
            }

            $this->supplierProduk->update($idSupplierProduk, [
                'id_discount' => null
            ]);
        }

        return redirect()->back()->with('success', 'Produk supplier & diskon berhasil diperbarui.');
    }



    public function hapusProduk()
    {
    $id = $this->request->getPost('id_supplier_produk');

    if (! $id) {
        return redirect()->back()
            ->with('errors', ['ID produk supplier tidak valid.']);
    }

    // HANYA hapus item produk dari supplier
    $this->supplierProduk->delete($id);

    return redirect()->back()
        ->with('success', 'Item produk berhasil dihapus dari supplier.');
    }

    public function tambah()
    {
        if ($this->request->getMethod() !== 'POST') {
            return view('supplier/tambah');
        } //Cek apakah user membuka halaman atau menekan tombol SIMPAN

        $rules = [
            'nama_suplier'   => 'required|min_length[2]|max_length[255]',
            'alamat' => 'permit_empty|max_length[1000]',
            'no_telp'=> 'permit_empty|max_length[50]',
        ]; //Aturan validasi input

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        } //Cek apakah input valid

        $post = $this->request->getPost(['nama_suplier', 'alamat', 'no_telp']); //Ambil data yang sudah lolos validasi

        $this->model->save($post); //Simpan supplier baru

        return redirect()->to(base_url('supplier'))->with('success', 'Suplier berhasil disimpan.'); //Setelah berhasil simpan, kembali ke daftar supplier
    }

    public function ubah($id = null)
    {
        if ($this->request->getMethod() === 'POST') { //Cek apakah ini permintaan POST (simpan perubahan)
            $postId = $this->request->getPost('id_suplier') ?? $this->request->getPost('id') ?? null;
            if ($id === null && $postId !== null) {
                $id = (int) $postId;
            }

            if (! $id) {
                return redirect()->to(base_url('supplier'))->with('errors', ['ID tidak valid.']);
            }//Tentukan ID supplier yang mau diubah baris 79-86

            $rules = [ 
                'nama_suplier'   => 'required|min_length[2]|max_length[255]',
                'alamat' => 'permit_empty|max_length[1000]',
                'no_telp'=> 'permit_empty|max_length[50]',
            ];//aturan validasi input

            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            } //Jika input tidak valid, kembali ke form


            $this->model->save($this->request->getPost()); //Simpan perubahan supplier

            return redirect()->to(base_url('supplier'))->with('success', 'Suplier berhasil diperbarui.'); //Setelah update berhasil, kembali ke daftar supplier
        }

            if (! $id) {
                return redirect()->to(base_url('supplier'));
            } //Kalau ID kosong, jangan tampilkan form, langsung kembali ke daftar.

            $supplier = $this->model->getById($id);
            if (! $supplier) {
                return redirect()->to(base_url('supplier'))->with('errors', ['Supplier tidak ditemukan.']);
            } //Cari supplier berdasarkan ID

            return view('supplier/ubah', ['supplier' => $supplier]);
        } //Kirim data supplier ke halaman ubah, agar form menampilkan data lama untuk diedit

    public function hapus($id = null)
    {
        if ($this->request->getMethod() === 'post') {
            $postId = $this->request->getPost('id') ?? $this->request->getPost('id_suplier') ?? null;
            if ($postId) {
                $id = (int) $postId;
            }
        } //Ambil ID supplier yang mau dihapus

        if (! $id) {
            return redirect()->to(base_url('supplier'))->with('errors', ['ID tidak valid.']);
        } //Cek apakah ID valid

        $supplier = $this->model->getById($id);
        if (! $supplier) {
            return redirect()->to(base_url('supplier'))->with('errors', ['Supplier tidak ditemukan.']);
        } //Cek apakah suppliernya benar-benar ada

        $this->model->delete($id); //Hapus data supplier

        return redirect()->to(base_url('supplier'))->with('success', 'Suplier berhasil dihapus.');
    } //Setelah dihapus, kembali ke halaman daftar
}
