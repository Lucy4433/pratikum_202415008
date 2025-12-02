<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrdersModel;

class RiwayatTransaksi extends BaseController
{
    public function index()
    {
        $orders = new OrdersModel();

        // ambil filter
        $tanggal = $this->request->getGet('tanggal');
        $keyword = $this->request->getGet('q');

        // query dasar + join user + pembayaran
        $orders->select('
                orders.*,
                user.username,
                pembayaran.metode_pembayaran
            ')
            ->join('user', 'user.id_user = orders.id_user', 'left')
            ->join('pembayaran', 'pembayaran.id_order = orders.id_order', 'left')
            ->orderBy('orders.tanggal_order', 'DESC');

        // filter tanggal (kalau diisi)
        if ($tanggal) {
            $orders->where('DATE(orders.tanggal_order)', $tanggal);
        }

        // filter pencarian (no transaksi / nama kasir)
        if ($keyword) {
            $orders->groupStart()
                   ->like('orders.no_penjualan', $keyword)
                   ->orLike('user.username', $keyword)
                   ->groupEnd();
        }

        $data['riwayat'] = $orders->findAll();
        $data['tanggal'] = $tanggal;
        $data['q']       = $keyword;

        return view('RiwayatTransaksi/index', $data);
    }

    /**
     * Dipanggil via fetch() untuk isi pop-up detail (JSON).
     */
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
