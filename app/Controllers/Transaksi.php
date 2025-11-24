<?php

namespace App\Controllers;

class Kasir extends BaseController
{
    public function index()
    {
        // sementara stats kosong dulu
        $data['stats'] = [
            'transaksi_hari_ini' => 0,
            'pendapatan_hari_ini' => 0,
            'produk_terjual' => 0,
        ];
        $data['transaksi_terbaru'] = [];

        return view('transaksi/index', $data);
    }

    public function transaksi()
    {
        return view('transaksi/index');
    }
}
