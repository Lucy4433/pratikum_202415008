<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrdersModel;

class RiwayatTransaksi extends BaseController
{
    public function index()
    {
        $orders = new OrdersModel(); //mengambil data riwayat transaksi

        // ambil filter
        $tanggal = $this->request->getGet('tanggal'); //untuk filter tanggal transaksi
        $keyword = $this->request->getGet('q'); //kata kunci pencarian (no penjualan / nama kasir)

        // query dasar + join user + pembayaran
        $orders->select('
                orders.*,
                user.username,
                pembayaran.metode_pembayaran
            ') //data orders + username kasir + metode pembayaran untuk ditampilkan dalam riwayat transaksi

            ->join('user', 'user.id_user = orders.id_user', 'left') //Menggabungkan data order dengan data kasir
            ->join('pembayaran', 'pembayaran.id_order = orders.id_order', 'left') //Menggabungkan order dengan tabel pembayaran
            ->orderBy('orders.tanggal_order', 'DESC'); //Urutkan transaksi dari yang terbaru

        // filter tanggal (kalau diisi)
        if ($tanggal) {
            $orders->where('DATE(orders.tanggal_order)', $tanggal); //Filter tanggal
        }

        // filter pencarian (no transaksi / nama kasir)
        if ($keyword) {
            $orders->groupStart()
                   ->like('orders.no_penjualan', $keyword) //cari berdasarkan nomor penjualan
                   ->orLike('user.username', $keyword) //atau berdasarkan username kasir
                   ->groupEnd();
        }

        //Kirim data ke view riwaya
        $data['riwayat'] = $orders->findAll(); //berisi semua data transaksi (setelah filter & join tadi)
        $data['tanggal'] = $tanggal; //menyimpan nilai filter tanggal yang dipilih use
        $data['q']       = $keyword; //menyimpan keyword pencarian (no penjualan / nama kasir)

        return view('RiwayatTransaksi/index', $data); //menampilkan halaman view
    }

    public function detail($id_order = null)
{
    if (!$this->request->isAJAX()) {
        return redirect()->to(base_url('riwayattransaksi'));
    }

    $db = \Config\Database::connect();

    $header = $db->table('orders')
        ->select('orders.*, user.username, pembayaran.metode_pembayaran, pembayaran.tanggal_bayar')
        ->join('user', 'user.id_user = orders.id_user', 'left')
        ->join('pembayaran', 'pembayaran.id_order = orders.id_order', 'left')
        ->where('orders.id_order', $id_order)
        ->get()
        ->getRowArray();

    $items = $db->table('detail_order')
        ->select('detail_order.*, produk.nama_produk, merek.nama_merek')
        ->join('produk', 'produk.id_produk = detail_order.id_produk', 'left')
        ->join('merek', 'merek.id_merek = produk.id_merek', 'left')
        ->where('detail_order.id_order', $id_order)
        ->get()
        ->getResultArray();

    return $this->response->setJSON([
        'header' => $header,
        'items'  => $items,
    ]);
}

}
