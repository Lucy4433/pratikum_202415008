<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukModel;
use App\Models\OrdersModel;
use App\Models\DetailOrderModel;
use App\Models\PembayaranModel;

class Kasir extends BaseController
{
    protected $produkModel;
    protected $ordersModel;
    protected $detailModel;
    protected $pembayaranModel;

    public function __construct() //run pertama
    {
        $this->produkModel     = new ProdukModel(); 
        $this->ordersModel     = new OrdersModel();
        $this->detailModel     = new DetailOrderModel();
        $this->pembayaranModel = new PembayaranModel();
    } //controller terhubung maisng2 model

    // ================= HALAMAN KASIR (DAFTAR PRODUK) =================
    public function index()
    {
        // ambil produk + merek + diskon aktif
        $produk = $this->produkModel
            ->select('
                produk.*,
                merek.nama_merek,
                discount.besaran AS besaran_discount
            ')
            ->join('merek', 'merek.id_merek = produk.id_merek', 'left') //ambil nama mereknya berdasarkan id_merek.
            ->join(
                'discount',
                'discount.id_produk = produk.id_produk
                 AND CURDATE() BETWEEN discount.dari_date AND discount.sampai_date',
                'left'
            ) //ambil diskon produk, tapi hanya diskon yang sedang aktif hari ini.
            ->orderBy('produk.nama_produk', 'ASC')
            ->findAll(); 

        return view('kasir/index', [ 
            'produk' => $produk,
        ]); ////kirim data produk kehalaman kasir
    }

    // ================= HALAMAN PEMBAYARAN =================
    public function bayar()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to(base_url('kasir'));
        }//cek akses post, jika tidak kembali ke halaman kasir
        
        $itemsJson = $this->request->getPost('items'); //data item dikirim dari JavaScript dalam bentuk JSON
        $items     = json_decode($itemsJson, true) ?? []; //JSON diubah ke array PHP agar mudah ditampilkan
        //ambil data item, dari POST (format JSON)
        if (empty($items)) {
            return redirect()->to(base_url('kasir'))
                             ->with('error', 'Keranjang kosong, tidak bisa lanjut ke pembayaran.');
        } //jika item KOSONG → kembali ke kasir

        $data = [
            'items'        => $items,
            'items_json'   => $itemsJson,
            'subtotal'     => (float) $this->request->getPost('subtotal'),
            'total_diskon' => (float) $this->request->getPost('total_diskon'),
            'total'        => (float) $this->request->getPost('grand_total'),
            'idOrder'      => null,
        ]; // data yang dikirm ke halaman kasir

        return view('kasir/bayar', $data); //tampil halaaman kasir dan data muncul di halaaman tersebut
    }

    // ================= PROSES SIMPAN TRANSAKSI =================
    public function simpan()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to(base_url('kasir'));
        }//cek akses post, jika tidak ada kemabli ke halaman kasir

        $itemsJson = $this->request->getPost('items');
        $items     = json_decode($itemsJson, true) ?? [];
        //ambil data item, dari POST (format JSON)
        if (empty($items)) {
            return redirect()->back()->with('error', 'Keranjang masih kosong, tidak ada yang disimpan.');
        } //jika item KOSONG → kembali ke kasir
        //angka-angka penting dari form, untuk diinput
        $subtotal    = (float) $this->request->getPost('subtotal'); //total sebuelum diskon
        $totalDiskon = (float) $this->request->getPost('total_diskon'); //jumlah semua diskon
        $grandTotal  = (float) $this->request->getPost('grand_total'); //total akhir
        $metode      = $this->request->getPost('metode');  //metode pembayaran
        $bayar       = (float) $this->request->getPost('bayar'); //uang yang diberikan pelanggan
        $kembali     = (float) $this->request->getPost('kembali'); //unag kembalian
        //mulai transaksi databases (99-100)
        $db = \Config\Database::connect();
        $db->transBegin();

        // nomor penjualan
        $noPenjualan = 'INV-' . date('Ymd') . '-' . rand(100, 999);

        // ambil id user dari session (fallback ke 1 kalau belum ada)
        $idUser = session()->get('id_user') ?? 1;

        // ========== SIMPAN KE TABEL ORDERS ==========
        $this->ordersModel->insert([
            'no_penjualan'  => $noPenjualan, //nomor penjualan 
            'tanggal_order' => date('Y-m-d H:i:s'), //tgl dan jam
            'total'         => $grandTotal, //total transaksi
            'id_user'       => $idUser, //user kasir
        ]);

        $idOrder = $this->ordersModel->getInsertID(); //
        //ambil ID transaksi yang baru dibuat, menghubungkan ke tabel detail_order dan pembayaran

        // ========== SIMPAN DETAIL ORDER + KURANGI STOK ==========
        foreach ($items as $item) { //ambil setiap barang yang ada di item
            $isManual = !empty($item['isManual']); // cek tambah manual/dari databases
            $qty      = (int) ($item['qty'] ?? 0); //jumlah barang dibeli
            $harga    = (float) ($item['harga'] ?? 0); //harga per item
            $idProduk = $isManual ? null : ($item['id'] ?? null); //kalau manual null, dari databases = id produk

            // simpan detail barang ke tabel detail_order
            $this->detailModel->insert([ //Setiap barang yang dibeli pelanggan → dibuat 1 baris di tabel detail_order.
                'id_order'     => $idOrder,
                'id_produk'    => $idProduk,
                'jumlah_beli'  => $qty,
                'harga_satuan' => $harga,
            ]);

        
            if (!$isManual && $idProduk && $qty > 0) {
                $itemProduk = $this->produkModel->find($idProduk);
                if ($itemProduk) {
                    $this->produkModel->update(
                        $idProduk,
                        ['stok' => $itemProduk->stok - $qty]
                    );
                }
            }
        }

        // ========== SIMPAN PEMBAYARAN ==========
        $this->pembayaranModel->insert([
            'id_order'          => $idOrder,
            'total'             => $grandTotal,
            'tanggal_bayar'     => date('Y-m-d H:i:s'),
            'metode_pembayaran' => $metode,
        ]);

        // ========== CEK TRANSAKSI DB ==========
        if ($db->transStatus() === false) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi.');
        }

        $db->transCommit();

       return redirect()->to(base_url('RiwayatTransaksi'))
        ->with('success', 'Transaksi berhasil disimpan, stok produk sudah diperbarui.');
        }

    // ================= CETAK NOTA (PAKAI ID ORDER / ORDER TERAKHIR) =================
    public function nota($idOrder = null)
    {
    $idUser = session()->get('id_user');

    // Jika idOrder tidak dikirim, ambil order terakhir milik kasir ini
    if ($idOrder === null) {
        $order = $this->ordersModel
            ->select('orders.*, user.username AS nama_kasir')
            ->join('user', 'user.id_user = orders.id_user', 'left')
            ->where('orders.id_user', $idUser)
            ->orderBy('orders.tanggal_order', 'DESC')
            ->first();

        if (!$order) {
            return redirect()->to(base_url('kasir'))
                             ->with('error', 'Belum ada transaksi untuk dicetak.');
        }

        $idOrder = $order->id_order;
    } else {
        // kalau idOrder dikirim, ambil sesuai ID
        $order = $this->ordersModel
            ->select('orders.*, user.username AS nama_kasir')
            ->join('user', 'user.id_user = orders.id_user', 'left')
            ->where('orders.id_order', $idOrder)
            ->first();

        if (!$order) {
            return redirect()->to(base_url('kasir'))
                             ->with('error', 'Nota tidak ditemukan.');
        }
    }

    // detail item
    $detail = $this->detailModel
        ->select('detail_order.*, produk.nama_produk')
        ->join('produk', 'produk.id_produk = detail_order.id_produk', 'left')
        ->where('detail_order.id_order', $idOrder)
        ->findAll();

    // pembayaran
    $pembayaran = $this->pembayaranModel
        ->where('id_order', $idOrder)
        ->first();

    return view('kasir/nota', [
        'order'      => $order,
        'detail'     => $detail,
        'pembayaran' => $pembayaran,
    ]);
}

}
