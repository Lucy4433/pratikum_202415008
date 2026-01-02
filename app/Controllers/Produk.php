<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiscountModel;
use App\Models\MerekModel;
use App\Models\ProdukModel;

class Produk extends BaseController
{
    protected $model;
    protected $discount;
    protected $merek;

    public function __construct()
    {
        $this->model = new ProdukModel();
        $this->discount = new DiscountModel();
        $this->merek = new MerekModel();
    }

    public function index()
    {
        $data['produk'] = $this->model->getProdukWithMerek();
        $data['merek'] = $this->merek->findAll();
        //Ambil data produk + merek

        foreach ($data['produk'] as $key => $value) {
            $value->discount = $this->discount->getByProductId($value->id_produk);
        } //Tambahkan data diskon ke setiap produk
        return view('produk/index', $data); //Kirim data ke view
    }

    public function tambah()
    {
        if ($this->request->getMethod() == 'POST') { //Cek apakah user menekan tombol SIMPAN
            $post = $this->request->getPost(['nama_produk', 'id_merek', 'harga', 'stok']); //Ambil data dari form
            $post['harga'] = preg_replace('/\D/', '', $post['harga']); //Bersihkan harga dari karakter selain angka
            $this->model->save($post); //Simpan produk ke database
            return redirect()->to(base_url('produk'))->with('success', 'Produk berhasil ditambahkan.'); //Setelah simpan, kembali ke halaman daftar produk
        }

        $data['merek'] = $this->merek->findAll();
        return view('produk/tambah', $data);
    } //Jika user belum klik SIMPAN (GET request), produk dan kirim daftar merek untuk dropdown.

    public function ubah($id = null)
    {
        if ($this->request->getMethod() === 'POST') { //Cek apakah user menekan tombol SIMPAN
            $post = $this->request->getPost(['id_produk', 'id_merek', 'nama_produk', 'harga', 'stok']); //Ambil data dari form edit
            $post['harga'] = preg_replace('/\D/', '', $post['harga']); //Bersihkan harga dari karakter selain angka
            $this->model->update($post['id_produk'], $post); //Update data produk berdasarkan id_produk
            return redirect()->to(base_url('produk'))->with('success', 'Produk berhasil diperbarui.'); //Setelah update, kembali ke halaman daftar produk
        }

        $data['produk'] = $this->model->find($id);
        $data['merek']  = $this->merek->findAll();
        return view('produk/ubah', $data);
    } //Jika bukan POST → tampilkan form edit

    public function hapus($id = null)
    {
        $this->model->delete($id); //Hapus produk berdasarkan ID
        return redirect()->to(base_url('produk'))->with('success', 'Produk berhasil dihapus.'); //Setelah dihapus, kembali ke halaman daftar produk
    }
}
