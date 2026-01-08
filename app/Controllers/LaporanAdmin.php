<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrdersModel;
use App\Models\DetailOrderModel;
use App\Models\PembayaranModel;
use Dompdf\Dompdf;
use Dompdf\Options;


class LaporanAdmin extends BaseController
{
    public function index()
    {
        $session = session();

        // ========================= DATA STATISTIK =========================
        $db = \Config\Database::connect();

        // Pendapatan bulanan (semua kasir)
        $pendapatanBulanan = $db->table('orders')
            ->selectSum('total')
            ->where('MONTH(tanggal_order)', date('m'))
            ->where('YEAR(tanggal_order)', date('Y'))
            ->get()->getRow()->total ?? 0;

        // Total order 7 hari terakhir
        $totalOrderMingguan = $db->table('orders')
            ->where('tanggal_order >=', date('Y-m-d', strtotime('-7 days')))
            ->countAllResults();

        // Jumlah item terjual 7 hari terakhir
        $barangLakuMingguan = $db->table('detail_order')
            ->selectSum('jumlah_beli')
            ->join('orders', 'orders.id_order = detail_order.id_order')
            ->where('orders.tanggal_order >=', date('Y-m-d', strtotime('-7 days')))
            ->get()->getRow()->jumlah_beli ?? 0;

        // ========================= LAPORAN TRANSAKSI =========================
        $laporan = $db->table('orders')
            ->select("
                orders.tanggal_order AS tanggal,
                orders.no_penjualan AS no_nota,
                orders.total,
                pembayaran.metode_pembayaran AS metode,
                user.username AS kasir
            ")
            ->join('user', 'user.id_user = orders.id_user', 'left')
            ->join('pembayaran', 'pembayaran.id_order = orders.id_order', 'left')
            ->orderBy('orders.tanggal_order', 'DESC')
            ->get()->getResultArray();

        $data = [
            'namaUser' => $session->get('username'),
            'role' => 'Admin',
            'tanggalHariIni' => date('d-m-Y'),

            'pendapatanBulanan' => $pendapatanBulanan,
            'totalOrderMingguan' => $totalOrderMingguan,
            'barangLakuMingguan' => $barangLakuMingguan,

            'laporanTransaksi' => $laporan,
        ];

        return view('LaporanAdmin/index', $data);
    }

    public function pdf()
{
    $db = \Config\Database::connect();

    $laporan = $db->table('orders')
        ->select("
            orders.tanggal_order AS tanggal,
            orders.no_penjualan AS no_nota,
            orders.total,
            pembayaran.metode_pembayaran AS metode,
            user.username AS kasir
        ")
        ->join('user', 'user.id_user = orders.id_user', 'left')
        ->join('pembayaran', 'pembayaran.id_order = orders.id_order', 'left')
        ->orderBy('orders.tanggal_order', 'DESC')
        ->get()->getResultArray();

    $html = view('LaporanAdmin/pdf', [
        'laporan' => $laporan,
        'tanggal' => date('d-m-Y')
    ]);

    $options = new Options();
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $dompdf->stream('laporan-transaksi.pdf', [
        'Attachment' => false
    ]);
}

}
