<?php
namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        //cek sudah login atau belum
        if (! session()->get('login')) {
            return redirect()->to('/login');
        }

        // Kalau role kasir, tendang ke halaman kasir saja
        if (session()->get('role') === 'kasir') {
            return redirect()->to('/kasir');
        }

        return view('layout/index');
    }
}
