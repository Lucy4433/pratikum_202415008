<?php

namespace App\Controllers;

class Kasir extends BaseController
{
    public function index()
    {
        // Opsional: cek login
        if (! session()->get('login')) {
            return redirect()->to('/login');
        }

        // Kalau role admin, balikin ke dashboard admin
        if (session()->get('role') === 'admin') {
            return redirect()->to('/dashboard');
        }

        return view('kasir/index');
    }
}
