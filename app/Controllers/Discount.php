<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiscountModel;
use App\Models\ProdukModel;

class Discount extends BaseController
{
    protected $discountModel;
    protected $produkModel;

    public function __construct()
    {
        $this->discountModel = new DiscountModel();
        $this->produkModel   = new ProdukModel();
        helper('form');
    }

    public function index()
    {
        $data['discount'] = $this->discountModel
            ->select('discount.*, produk.nama_produk')
            ->join('produk', 'produk.id_produk = discount.id_produk', 'left')
            ->orderBy('discount.id_discount', 'DESC')
            ->findAll();

        $data['produk'] = $this->produkModel->findAll();
        return view('discount/index', $data);
    }

    public function tambah()
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'id_produk'   => 'required|integer',
                'besaran'     => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
                'dari_date'   => 'required|valid_date',
                'sampai_date' => 'required|valid_date',
            ];
            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $post = $this->request->getPost();
            $this->discountModel->save([
                'id_produk'   => $post['id_produk'],
                'besaran'     => (int) $post['besaran'],
                'dari_date'   => $post['dari_date'],
                'sampai_date' => $post['sampai_date'],
            ]);

            return redirect()->to(base_url('discount'))->with('success', 'Diskon berhasil ditambahkan.');
        }

        return redirect()->to(base_url('discount'));
    }

    public function ubah($id = null)
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'id_produk'   => 'required|integer',
                'besaran'     => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
                'dari_date'   => 'required|valid_date',
                'sampai_date' => 'required|valid_date',
            ];
            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $post = $this->request->getPost();
            $this->discountModel->update($id, [
                'id_produk'   => $post['id_produk'],
                'besaran'     => (int) $post['besaran'],
                'dari_date'   => $post['dari_date'],
                'sampai_date' => $post['sampai_date'],
            ]);

            return redirect()->to(base_url('discount'))->with('success', 'Diskon berhasil diubah.');
        }

        return redirect()->to(base_url('discount'));
    }

    public function hapus($id = null)
    {
        if ($id) {
            $this->discountModel->delete($id);
        }
        return redirect()->to(base_url('discount'))->with('success', 'Diskon berhasil dihapus.');
    }
}
