<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;

class LaporanKasir extends BaseController
{
    public function index()
    {
        $db      = Database::connect();
        $session = session();

        // Ambil id user kasir dari session
        $idKasir = $session->get('id_user');

        // ========= RANGE TANGGAL =========
        $today        = date('Y-m-d');                         // hari ini
        $firstDay     = date('Y-m-01');                        // awal bulan ini
        $lastDay      = date('Y-m-t');                         // akhir bulan ini
        $sevenDaysAgo = date('Y-m-d', strtotime('-6 days'));   // 7 hari terakhir

        // ========= KARTU 1: Pendapatan Bulanan Kasir =========
        $rowMonth = $db->table('orders')
            ->select('SUM(total) AS pendapatan')
            ->where('id_user', $idKasir)
            ->where('DATE(tanggal_order) >=', $firstDay)
            ->where('DATE(tanggal_order) <=', $lastDay)
            ->get()
            ->getRowArray();

        $pendapatanBulanan = (float) ($rowMonth['pendapatan'] ?? 0);

        // ========= KARTU 2: Total Order 7 Hari Terakhir =========
        $rowOrders = $db->table('orders')
            ->select('COUNT(*) AS jml_order')
            ->where('id_user', $idKasir)
            ->where('DATE(tanggal_order) >=', $sevenDaysAgo)
            ->where('DATE(tanggal_order) <=', $today)
            ->get()
            ->getRowArray();

        $totalOrderMingguan = (int) ($rowOrders['jml_order'] ?? 0);

        // ========= KARTU 3: Barang Laku 7 Hari Terakhir =========
        $rowSold = $db->table('detail_order')
            ->select('SUM(detail_order.jumlah_beli) AS qty_terjual')
            ->join('orders', 'orders.id_order = detail_order.id_order', 'inner')
            ->where('orders.id_user', $idKasir)
            ->where('DATE(orders.tanggal_order) >=', $sevenDaysAgo)
            ->where('DATE(orders.tanggal_order) <=', $today)
            ->get()
            ->getRowArray();

        $barangLakuMingguan = (int) ($rowSold['qty_terjual'] ?? 0);

        // ========= TABEL: TRANSAKSI KASIR INI =========
        $laporanTransaksi = $db->table('orders')
            ->select("
                orders.tanggal_order,
                orders.no_penjualan,
                orders.total,
                pembayaran.metode_pembayaran
            ")
            ->join('pembayaran', 'pembayaran.id_order = orders.id_order', 'left')
            ->where('orders.id_user', $idKasir)
            ->orderBy('orders.tanggal_order', 'DESC')
            ->limit(20)
            ->get()
            ->getResultArray();

        // ========= DATA UNTUK VIEW =========
        $data = [
            'namaUser'           => $session->get('username') ?? 'Kasir',
            'role'               => 'Kasir',
            'tanggalHariIni'     => date('d-m-Y'),

            'pendapatanBulanan'  => $pendapatanBulanan,
            'totalOrderMingguan' => $totalOrderMingguan,
            'barangLakuMingguan' => $barangLakuMingguan,

            'laporanTransaksi'   => $laporanTransaksi,
        ];

        return view('laporankasir/index', $data);
    }

    public function pdf()
    {
        // nanti diisi logika export PDF
        return 'Export PDF laporan kasir (belum diimplementasi).';
    }
}
