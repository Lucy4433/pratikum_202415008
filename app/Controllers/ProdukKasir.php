<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiscountModel;
use App\Models\ProdukModel;

class ProdukKasir extends BaseController
{
    protected $produkModel;
    protected $discount;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
        $this->discount = new DiscountModel();
    }

    public function index()
    {
        // Ambil data produk + merek + diskon yang sedang aktif (berdasarkan tanggal)
        $produk = $this->produkModel
            ->select('
                produk.*,
                merek.nama_merek
            ')
            ->join('merek', 'merek.id_merek = produk.id_merek', 'left')
            // diskon aktif: hari ini di antara dari_date dan sampai_date
            ->orderBy('produk.nama_produk', 'ASC')
            ->findAll();
            foreach ($produk as $key => $value) {
                $value->discount = $this->discount->where('id_produk', $value->id_produk)->where("CURDATE() BETWEEN discount.dari_date AND discount.sampai_date")->first();
                // $value->besaran = $item->besaran ?? null;
            }
            
// dd($produk);
        return view('ProdukKasir/index', [
            'title'  => 'Daftar Produk (Kasir)',
            'produk' => $produk,
        ]);
    }
}
