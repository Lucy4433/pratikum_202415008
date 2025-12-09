<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiscountModel;
use App\Models\ProdukModel;

class Discount extends BaseController
{
    protected $discountModel; //mempersiapkan disocuntmodel
    protected $produkModel; //mempersiapkan produkmodel

    public function __construct() //Inisialisasi Model
    {
        $this->discountModel = new DiscountModel(); //Mengaktifkan model diskon
        $this->produkModel   = new ProdukModel(); //Mengaktifkan model produk
        helper('form'); //Mengaktifkan fungsi-fungsi helper form
    }

    public function index($id = null)                 // Fungsi utama untuk menampilkan daftar diskon, bisa difilter berdasarkan id produk
{
    $builder = $this->discountModel               // Mulai query ke tabel discount
        ->select('discount.*, produk.nama_produk')// megambil data diskon + nama produk
        ->join('produk', 'produk.id_produk = discount.id_produk', 'left') //LEFT JOIN antara tabel discount dan produk
        ->orderBy('discount.id_discount', 'DESC'); //diskon yang terbaru muncul paling atas

    if ($id !== null) {                           // Jika ada id produk dikirim dari URL discount/index
        $builder->where('discount.id_produk', $id);// Filter hanya id diskon untuk produk tertentu
    }

    $dataDb = $builder->findAll();                // Jalankan query dan ambil semua hasil

    $today = date('Y-m-d');                       // Ambil tanggal hari ini
    $discountProductIds = [];                     // Array untuk menyimpan id produk yg punya diskon
    $activeCount = 0;                              // Hitung jumlah diskon aktif
    $upcomingCount = 0;                            // Hitung jumlah diskon yang belum mulai
    $expiredCount = 0;                             // Hitung jumlah diskon expired

    foreach ($dataDb as $d) {                     // Loop semua data diskon
        $d->dari_date   = $d->dari_date ?? $d->dari ?? null;   // Normalisasi tanggal mulai
        $d->sampai_date = $d->sampai_date ?? $d->sampai ?? null; // Normalisasi tanggal akhir

        if (!empty($d->id_produk)) {              // Jika id produk ada
            $discountProductIds[] = (int)$d->id_produk; // Simpan ke array produk yang punya diskon
        }

        if ($d->dari_date && $d->sampai_date) {   // Jika kedua tanggal valid
            if ($today >= $d->dari_date && $today <= $d->sampai_date) { // Tanggal hari ini di dalam rentang
                $d->status = 'Aktif';             // Status diskon aktif
                $activeCount++;                   // Tambah hitungan aktif
            } elseif ($today < $d->dari_date) {   // Jika hari ini sebelum tanggal mulai
                $d->status = 'Belum Dimulai';     // Status belum dimulai
                $upcomingCount++;                 // Tambah hitungan upcoming
            } else {                              // Jika hari ini lewat tanggal akhir
                $d->status = 'Expired';           // Status expired
                $expiredCount++;                  // Tambah hitungan expired
            }
        } else {
            $d->status = '-';                     // Tidak ada tanggal → status tidak diketahui
        }
    }

    $produks = $this->produkModel->findAll();     // Ambil semua produk untuk statistik
    $uniqueWithDiscount = array_unique($discountProductIds); // Buat list unik produk yang punya diskon
    $noDiscountCount = 0;                         // Hitung produk tanpa diskon

    foreach ($produks as $p) {                    // Loop semua produk
        if (!in_array((int)$p->id_produk, $uniqueWithDiscount, true)) { // Jika produk tidak ada di list diskon
            $noDiscountCount++;                   // Tambah jumlah produk tanpa diskon
        }
    }

            $data['discount'] = $dataDb;                  // Kirim data diskon ke view
            $data['produk']   = $produks;                 // Kirim data produk ke view

            $data['stats'] = [                            // Kirim statistik ringkasan ke view
                'active'      => $activeCount,            // Jumlah diskon aktif
                'upcoming'    => $upcomingCount,          // Jumlah diskon belum mulai
                'expired'     => $expiredCount,           // Jumlah diskon expired
                'no_discount' => $noDiscountCount,        // Jumlah produk tanpa diskon
                'total'       => count($dataDb),          // Total diskon yang ada
            ];

        return view('discount/index', $data);         // Tampilkan view discount/index dan kirim seluruh data
}

    public function tambah()
    {
        if ($this->request->getMethod() !== 'POST') { // megecek apa ini dari from post
            return redirect()->to(base_url('discount')); //Jika bukan POST langsung alihkan ke halaman discount
        }

        $rules = [
            'id_produk'   => 'required|integer',
            'besaran'     => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'dari_date'   => 'required|valid_date[Y-m-d]',
            'sampai_date' => 'required|valid_date[Y-m-d]',
        ]; //validasi data sesuai kriteria diaatas 

        if (! $this->validate($rules)) { //jika validasi gagal
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors()); // proses dihentikan dan muncul tampilan error
        }

        $post = $this->request->getPost(); //megambil data post setelah berhasi;

        // Validasi logika periode
        if ($post['dari_date'] > $post['sampai_date']) {
            return redirect()->back()->withInput()->with('errors', ['Periode tidak valid: Tanggal mulai harus <= tanggal selesai.']); //proses dihentikan dan muncul tampilan error
        }

        $this->discountModel->save([ //meyimpan data ke databses
            'id_produk'   => (int) $post['id_produk'],
            'besaran'     => (int) $post['besaran'],
            'dari_date'   => $post['dari_date'],
            'sampai_date' => $post['sampai_date'],
        ]);

        return redirect()->to(base_url('discount'))->with('success', 'Diskon berhasil ditambahkan.'); //balik ke halaman discount dan ada tanda berhasil
    }

    public function ubah($id = null)
    {
        if ($this->request->getMethod() !== 'POST') { // megecek apa menggunakan metode post
            return redirect()->to(base_url('discount')); //diarahkan tampilan dicount
        }

        $postId = $this->request->getPost('id_discount') ?? $this->request->getPost('id') ?? null; //mencari ID diskon dari POST (id_discount atau id)
        if ($id === null && $postId !== null) { // Jika URL tidak kirim ID, tapi form mengirim ID, maka pakai ID dari form
            $id = (int) $postId; // ID dari form disimpan ke variabel $id
        } //Untuk memastikan bahwa sistem selalu mendapatkan ID diskon

        if (! $id) {
            return redirect()->to(base_url('discount'))->with('errors', ['ID tidak valid.']); //jika error kembali ke discount dengan tampilan error
        }

        $rules = [
            'id_produk'   => 'required|integer',
            'besaran'     => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'dari_date'   => 'required|valid_date[Y-m-d]',
            'sampai_date' => 'required|valid_date[Y-m-d]',
        ]; // kriteria yang wajib ada 

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors()); //proses dihentikan dan muncul tampilan error
        }

        $post = $this->request->getPost(); //megambil data post

        // Validasi logika periode
        if ($post['dari_date'] > $post['sampai_date']) {
            return redirect()->back()->withInput()->with('errors', ['Periode tidak valid: Tanggal mulai harus <= tanggal selesai.']);
        }

        $this->discountModel->update($id, [ //data yang tersimpan
            'id_produk'   => (int) $post['id_produk'],
            'besaran'     => (int) $post['besaran'],
            'dari_date'   => $post['dari_date'],
            'sampai_date' => $post['sampai_date'],
        ]);

        return redirect()->to(base_url('discount'))->with('success', 'Diskon berhasil diubah.'); // balik kehalaman dscount dnegan tanda berhasil
    }

    public function hapus($id = null)
    {
        if ($this->request->getMethod() === 'POST') { //apa bila ini form post
            $postId = $this->request->getPost('id') ?? $this->request->getPost('id_discount') ?? null; // //mencari ID diskon dari POST (id_discount atau id)
            if ($postId) { //jika ketemu
                $id = (int) $postId; //tersimpan di variabel $id
            }
        } //untuk megetahi id discount mana yang berubah

        if (! $id) {
            return redirect()->to(base_url('discount'))->with('errors', ['ID tidak valid.']); //jika gagal kembali ke halaman discount tampilan error
        }

        $this->discountModel->delete($id); //id discount yg di hapus
        return redirect()->to(base_url('discount'))->with('success', 'Diskon berhasil dihapus.'); //kembali halaman dicount dengna nontifikasi success
    }
}
