<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;

class Dashboard extends BaseController
{
    public function index()
    {
        $db      = Database::connect();
        $session = session();

        // ========== RANGE TANGGAL ==========
        $today        = date('Y-m-d');                 // hari ini
        $firstDay     = date('Y-m-01');                // awal bulan ini
        $lastDay      = date('Y-m-t');                 // akhir bulan ini
        $sevenDaysAgo = date('Y-m-d', strtotime('-6 days')); // 7 hari terakhir (termasuk hari ini)

        // ========== KARTU MERAH: PENDAPATAN BULANAN ==========
        $rowMonth = $db->table('orders') //tabel order sebagai sumber data
            ->select('SUM(total) AS pendapatan') //mejumlahkan nilai total, di pendapatan bulanan
            ->where('DATE(tanggal_order) >=', $firstDay) //filter tgl awal
            ->where('DATE(tanggal_order) <=', $lastDay) //filter tgl akhir
            ->get() //menjalakan query
            ->getRowArray(); //query dijalankan, database mengembalikan hasil 1 baris

        $pendapatanBulanan = (float) ($rowMonth['pendapatan'] ?? 0); //hasil pendapatan

        // ========== KARTU BIRU: TOTAL ORDER BARANG 7 HARI TERAKHIR ==========
        $rowOrders = $db->table('orders') //sumber data dari tabel order
            ->select('COUNT(*) AS jml_order') //hitung jumlah baris data transaksi di juml_order
            ->where('DATE(tanggal_order) >=', $sevenDaysAgo) //tgl 7 hari 
            ->where('DATE(tanggal_order) <=', $today) // batas dari 7 hari 
            ->get()
            ->getRowArray();

        $totalOrderMingguan = (int) ($rowOrders['jml_order'] ?? 0);//hasil total order

        // ========== KARTU HIJAU: BARANG LAKU 7 HARI TERAKHIR ==========
        $rowSold = $db->table('detail_order') //lihat daftar barang yang terjual dari tabel detail_order
            ->select('SUM(detail_order.jumlah_beli) AS qty_terjual') //Jumlahkan semua angka di kolom jumlah_beli, di qty_terjual
            ->join('orders', 'orders.id_order = detail_order.id_order', 'inner') //Hubungkan tabel detail_order dengan tabel orders, berdasarkan id_order yang sama.
            ->where('DATE(orders.tanggal_order) >=', $sevenDaysAgo) //ambil data 7 hari
            ->where('DATE(orders.tanggal_order) <=', $today) //batas dari 7 hari 
            ->get()
            ->getRowArray();

        $barangLakuMingguan = (int) ($rowSold['qty_terjual'] ?? 0);//hasil total item

        // ========== TRANSAKSI TERBARU ==========
        $laporanTransaksi = $db->table('orders') //sumber data dari tabel orders 
            ->select("
                orders.tanggal_order,
                orders.no_penjualan,
                orders.total,
                user.username,
                pembayaran.metode_pembayaran
            ") //memilih kolom mana saja yang akan ditampilkan
            ->join('user', 'user.id_user = orders.id_user', 'left') //tavel user unutk megetahui nama kasir transaksi
            ->join('pembayaran', 'pembayaran.id_order = orders.id_order', 'left') //tabel pembayran untuk megetahui metode pembayaran
            ->orderBy('orders.tanggal_order', 'DESC') //Urutkan transaksi dari yang paling baru ke paling lama.
            ->limit(10) //batas 10 transaksi saja
            ->get()
            ->getResultArray();   // “Ambil hasilnya dalam bentuk array

        // ========== DATA UNTUK VIEW ==========
        $data = [
            'namaUser'           => $session->get('username') ?? 'Pengguna', //nama user
            'role'               => $session->get('role') ?? 'Admin', //role user kalau tidak ada kasir, admin yang muncul
            'tanggalHariIni'     => date('d-m-Y'),
        // ======== Mengirim angka statistik ke dashboard ========
            'pendapatanBulanan'  => $pendapatanBulanan,
            'totalOrderMingguan' => $totalOrderMingguan,
            'barangLakuMingguan' => $barangLakuMingguan,
            'laporanTransaksi'   => $laporanTransaksi, //mengrim 10 transaksi baru
        ];

        return view('dashboard/index', $data); //tampilkan di dashboard/index dan kirimkan semua data di array $data
    }
}
