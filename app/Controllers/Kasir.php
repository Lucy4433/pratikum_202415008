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

        // ambil produk + merek + diskon aktif
        $produk = $produkModel
            ->select('
                produk.*,
                merek.nama_merek,
                discount.besaran AS besaran_discount
            ')
            ->join('merek', 'merek.id_merek = produk.id_merek', 'left')
            ->join(
                'discount',
                'discount.id_produk = produk.id_produk
                 AND CURDATE() BETWEEN discount.dari_date AND discount.sampai_date',
                'left'
            )
            ->orderBy('produk.nama_produk', 'ASC')
            ->findAll();

        return view('kasir/index', ['produk' => $produk]);
    }

    public function bayar()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to(base_url('kasir'));
        }

        $itemsJson = $this->request->getPost('items');
        $items     = json_decode($itemsJson, true) ?? [];

        if (empty($items)) {
            return redirect()->to(base_url('kasir'))
                             ->with('error', 'Keranjang kosong, tidak bisa lanjut ke pembayaran.');
        }

        $data = [
            'items'        => $items,
            'items_json'   => $itemsJson,
            'subtotal'     => (float) $this->request->getPost('subtotal'),
            'total_diskon' => (float) $this->request->getPost('total_diskon'),
            'total'        => (float) $this->request->getPost('grand_total'),
        ];

        return view('kasir/bayar', $data);
    }

    public function simpan()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to(base_url('kasir'));
        }

        $itemsJson = $this->request->getPost('items');
        $items     = json_decode($itemsJson, true) ?? [];

        if (empty($items)) {
            return redirect()->back()->with('error', 'Keranjang masih kosong, tidak ada yang disimpan.');
        }

        $subtotal    = (float) $this->request->getPost('subtotal');
        $totalDiskon = (float) $this->request->getPost('total_diskon');
        $grandTotal  = (float) $this->request->getPost('grand_total');
        $metode      = $this->request->getPost('metode');
        $bayar       = (float) $this->request->getPost('bayar');
        $kembali     = (float) $this->request->getPost('kembali');

        $ordersModel     = new OrdersModel();
        $detailModel     = new DetailOrderModel();
        $produkModel     = new ProdukModel();
        $pembayaranModel = new PembayaranModel();

        $db = \Config\Database::connect();
        $db->transBegin();

        // nomor penjualan
        $noPenjualan = 'INV-' . date('Ymd') . '-' . rand(100, 999);

        $idUser = session()->get('id_user') ?? 1;

        // simpan order
        $ordersModel->insert([
            'no_penjualan'  => $noPenjualan,
            'tanggal_order' => date('Y-m-d H:i:s'),
            'total'         => $grandTotal,
            'id_user'       => $idUser,
        ]);

        $idOrder = $ordersModel->getInsertID();

        // simpan detail order + pengurangan stok
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
                $itemProduk = $produkModel->find($item['id']);
                if ($itemProduk) {
                    $produkModel->update(
                        $item['id'],
                        ['stok' => $itemProduk->stok - $qty]
                    );
                }
            }
        }

        // simpan pembayaran
        $pembayaranModel->insert([
            'id_order'          => $idOrder,
            'total'             => $grandTotal,
            'tanggal_bayar'     => date('Y-m-d H:i:s'),
            'metode_pembayaran' => $metode,
        ]);

        if ($db->transStatus() === false) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi.');
        }

        $db->transCommit();

        return redirect()->to(base_url('RiwayatTransaksi'))
                         ->with('success', 'Transaksi berhasil disimpan, stok produk sudah diperbarui.');
    }




    /* ============================================================
     *            📌 METHOD BARU UNTUK CETAK NOTA
     * ============================================================*/
    public function nota($idOrder)
    {
        $ordersModel     = new OrdersModel();
        $detailModel     = new DetailOrderModel();
        $pembayaranModel = new PembayaranModel();

        // data order
        $order = $ordersModel
            ->select('orders.*, user.username')
            ->join('user', 'user.id_user = orders.id_user', 'left')
            ->where('orders.id_order', $idOrder)
            ->first();

        if (!$order) {
            return redirect()->to(base_url('RiwayatTransaksi'))
                             ->with('error', 'Nota tidak ditemukan.');
        }

        // detail item
        $detail = $detailModel
            ->select('detail_order.*, produk.nama_produk')
            ->join('produk', 'produk.id_produk = detail_order.id_produk', 'left')
            ->where('detail_order.id_order', $idOrder)
            ->findAll();

        // pembayaran
        $pembayaran = $pembayaranModel
            ->where('id_order', $idOrder)
            ->first();

        return view('kasir/Nota', [
            'order'      => $order,
            'detail'     => $detail,
            'pembayaran' => $pembayaran
        ]);
    }
}
