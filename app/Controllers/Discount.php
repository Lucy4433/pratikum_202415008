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

    public function index($id = null)
    {
        $builder = $this->discountModel
            ->select('discount.*, produk.nama_produk')
            ->join('produk', 'produk.id_produk = discount.id_produk', 'left')
            ->orderBy('discount.id_discount', 'DESC');

        if ($id !== null) {
            $builder->where('discount.id_produk', $id);
        }

        $dataDb = $builder->findAll();

        // Hitung status tiap record + kumpulkan produk yang punya diskon
        $today = date('Y-m-d');
        $discountProductIds = []; // untuk statistik produk yg punya diskon
        $activeCount = 0;
        $upcomingCount = 0;
        $expiredCount = 0;

        foreach ($dataDb as $d) {
            $d->dari_date   = $d->dari_date   ?? $d->dari ?? null;
            $d->sampai_date = $d->sampai_date ?? $d->sampai ?? null;

            if (!empty($d->id_produk)) {
                $discountProductIds[] = (int)$d->id_produk;
            }

            if ($d->dari_date && $d->sampai_date) {
                if ($today >= $d->dari_date && $today <= $d->sampai_date) {
                    $d->status = 'Aktif';
                    $activeCount++;
                } elseif ($today < $d->dari_date) {
                    $d->status = 'Belum Dimulai';
                    $upcomingCount++;
                } else {
                    $d->status = 'Expired';
                    $expiredCount++;
                }
            } else {
                $d->status = '-';
            }
        }

        // statistik: jumlah produk yang belum punya diskon (unique per produk)
        $produks = $this->produkModel->findAll();
        $uniqueWithDiscount = array_unique($discountProductIds);
        $noDiscountCount = 0;
        foreach ($produks as $p) {
            if (!in_array((int)$p->id_produk, $uniqueWithDiscount, true)) {
                $noDiscountCount++;
            }
        }

        $data['discount'] = $dataDb;
        $data['produk']   = $produks;

        $data['stats'] = [
            'active'      => $activeCount,
            'upcoming'    => $upcomingCount,
            'expired'     => $expiredCount,
            'no_discount' => $noDiscountCount,
            'total'       => count($dataDb),
        ];

        return view('discount/index', $data);
    }

    public function tambah()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to(base_url('discount'));
        }

        $rules = [
            'id_produk'   => 'required|integer',
            'besaran'     => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'dari_date'   => 'required|valid_date[Y-m-d]',
            'sampai_date' => 'required|valid_date[Y-m-d]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $post = $this->request->getPost();

        // Validasi logika periode
        if ($post['dari_date'] > $post['sampai_date']) {
            return redirect()->back()->withInput()->with('errors', ['Periode tidak valid: Tanggal mulai harus <= tanggal selesai.']);
        }

        $this->discountModel->save([
            'id_produk'   => (int) $post['id_produk'],
            'besaran'     => (int) $post['besaran'],
            'dari_date'   => $post['dari_date'],
            'sampai_date' => $post['sampai_date'],
        ]);

        return redirect()->to(base_url('discount'))->with('success', 'Diskon berhasil ditambahkan.');
    }

    public function ubah($id = null)
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to(base_url('discount'));
        }

        $postId = $this->request->getPost('id_discount') ?? $this->request->getPost('id') ?? null;
        if ($id === null && $postId !== null) {
            $id = (int) $postId;
        }

        if (! $id) {
            return redirect()->to(base_url('discount'))->with('errors', ['ID tidak valid.']);
        }

        $rules = [
            'id_produk'   => 'required|integer',
            'besaran'     => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'dari_date'   => 'required|valid_date[Y-m-d]',
            'sampai_date' => 'required|valid_date[Y-m-d]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $post = $this->request->getPost();

        // Validasi logika periode
        if ($post['dari_date'] > $post['sampai_date']) {
            return redirect()->back()->withInput()->with('errors', ['Periode tidak valid: Tanggal mulai harus <= tanggal selesai.']);
        }

        $this->discountModel->update($id, [
            'id_produk'   => (int) $post['id_produk'],
            'besaran'     => (int) $post['besaran'],
            'dari_date'   => $post['dari_date'],
            'sampai_date' => $post['sampai_date'],
        ]);

        return redirect()->to(base_url('discount'))->with('success', 'Diskon berhasil diubah.');
    }

    public function hapus($id = null)
    {
        if ($this->request->getMethod() === 'POST') {
            $postId = $this->request->getPost('id') ?? $this->request->getPost('id_discount') ?? null;
            if ($postId) {
                $id = (int) $postId;
            }
        }

        if (! $id) {
            return redirect()->to(base_url('discount'))->with('errors', ['ID tidak valid.']);
        }

        $this->discountModel->delete($id);
        return redirect()->to(base_url('discount'))->with('success', 'Diskon berhasil dihapus.');
    }
}
