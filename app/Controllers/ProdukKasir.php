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
        $data = [
            'produk' => $this->produkModel->getReadOnlyKasir()
        ];

        return view('ProdukKasir/index', $data);
    }
}
