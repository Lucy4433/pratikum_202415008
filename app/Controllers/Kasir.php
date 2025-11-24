<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukModel;
use App\Models\OrdersModel;
use App\Models\DetailOrderModel;
use App\Models\PembayaranModel;

class Kasir extends BaseController
{
    public function index()
    {
        $produkModel = new ProdukModel();
        $data['produk'] = $produkModel->findAll();

        return view('kasir/index', $data);
    }

    public function simpan()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('kasir');
        }

        $itemsJson = $this->request->getPost('items');
        $items     = json_decode($itemsJson, true) ?? [];

        if (empty($items)) {
            return redirect()->back()->with('error', 'Keranjang masih kosong, tidak ada yang disimpan.');
        }

        $subtotal    = (float) $this->request->getPost('subtotal');
        $totalDiskon = (float) $this->request->getPost('total_diskon');
        $grandTotal  = (float) $this->request->getPost('grand_total');
        $metode      = $this->request->getPost('metode');   // cash / transfer
        $bayar       = (float) $this->request->getPost('bayar');
        $kembali     = (float) $this->request->getPost('kembali');

        $ordersModel    = new OrdersModel();
        $detailModel    = new DetailOrderModel();
        $produkModel    = new ProdukModel();
        $pembayaranModel= new PembayaranModel();

        $db = \Config\Database::connect();
        $db->transBegin();

        // nomor penjualan
        $today       = date('Ymd');
        $random      = rand(100, 999);
        $noPenjualan = 'INV-' . $today . '-' . $random;

        $idUser = session()->get('id_user') ?? 1;

        // --- SIMPAN ORDERS ---
        $ordersModel->insert([
            'no_penjualan'  => $noPenjualan,
            'tanggal_order' => date('Y-m-d H:i:s'),
            'total'         => $grandTotal,
            'id_user'       => $idUser,
        ]);

        $idOrder = $ordersModel->getInsertID();

        // --- DETAIL_ORDER + KURANGI STOK ---
        foreach ($items as $item) {
            $isManual = !empty($item['isManual']);
            $qty      = (int) ($item['qty'] ?? 0);
            $harga    = (float)($item['harga'] ?? 0);
            $idProduk = $isManual ? null : ($item['id'] ?? null);

            $detailModel->insert([
                'id_order'     => $idOrder,
                'id_produk'    => $idProduk,
                'jumlah_beli'  => $qty,
                'harga_satuan' => $harga,
            ]);

            if (!$isManual && $idProduk && $qty > 0) {
                $produkModel
                    ->set('stok', 'stok - ' . (int)$qty, false)
                    ->where('id_produk', (int)$idProduk)
                    ->update();
            }
        }

        // --- PEMBAYARAN ---
        $pembayaranModel->insert([
            'id_order'          => $idOrder,
            'total'             => $grandTotal,
            'tanggal_bayar'     => date('Y-m-d H:i:s'),
            'metode_pembayaran' => $metode ? ucfirst(strtolower($metode)) : null,
        ]);

        if ($db->transStatus() === false) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi.');
        }

        $db->transCommit();

        return redirect()->to(base_url('riwayattransaksi'))
                         ->with('success', 'Transaksi berhasil disimpan, stok produk sudah diperbarui.');
    }
}
