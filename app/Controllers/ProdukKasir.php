<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukModel;

class ProdukKasir extends BaseController
{
    protected $produkModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
    }

    public function index()
    {
        // Ambil data produk + merek + diskon yang sedang aktif (berdasarkan tanggal)
        $produk = $this->produkModel
            ->select('
                produk.*,
                merek.nama_merek,
                discount.besaran AS besaran_discount
            ')
            ->join('merek', 'merek.id_merek = produk.id_merek', 'left')
            // diskon aktif: hari ini di antara dari_date dan sampai_date
            ->join(
                'discount',
                'discount.id_produk = produk.id_produk
                 AND CURDATE() BETWEEN discount.dari_date AND discount.sampai_date',
                'left'
            )
            ->orderBy('produk.nama_produk', 'ASC')
            ->findAll();

        return view('ProdukKasir/index', [
            'title'  => 'Daftar Produk (Kasir)',
            'produk' => $produk,
        ]);
    }
}
