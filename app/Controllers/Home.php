<?php
namespace App\Controllers;

class Produk extends BaseController
{
    public function index()
    {
        return view('layout/index'); // atau return "OK";
    }
}
