<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiscountModel;
use App\Models\ProdukModel;
use App\Models\SupplierProdukModel;

class Discount extends BaseController
{
    protected $discountModel;
    protected $produkModel;
    protected $supplierProdukModel;

    public function __construct()
    {
        $this->discountModel        = new DiscountModel();
        $this->produkModel          = new ProdukModel();
        $this->supplierProdukModel  = new SupplierProdukModel();
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

        $today = date('Y-m-d');
        $discountProductIds = [];
        $activeCount = $upcomingCount = $expiredCount = 0;

        foreach ($dataDb as $d) {
            $d->dari_date   = $d->dari_date ?? null;
            $d->sampai_date = $d->sampai_date ?? null;

            if ($d->id_produk) {
                $discountProductIds[] = (int) $d->id_produk;
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

        $produks = $this->supplierProdukModel
        ->join('produk', 'produk.id_produk = supplier_produk.id_produk')
        ->groupBy('produk.id_produk')
        ->select('produk.id_produk, produk.nama_produk')
        ->findAll();


        $uniqueWithDiscount = array_unique($discountProductIds);
        $noDiscountCount = 0;

        foreach ($produks as $p) {
            if (!in_array((int)$p->id_produk, $uniqueWithDiscount, true)) {
                $noDiscountCount++;
            }
        }

        return view('discount/index', [
            'discount' => $dataDb,
            'produk'   => $produks,
            'stats'    => [
                'active'      => $activeCount,
                'upcoming'    => $upcomingCount,
                'expired'     => $expiredCount,
                'no_discount' => $noDiscountCount,
                'total'       => count($dataDb),
            ]
        ]);
    }

    public function tambah()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to(base_url('discount'));
        }

        $post = $this->request->getPost();

        if ($post['dari_date'] > $post['sampai_date']) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['Tanggal mulai tidak boleh lebih besar dari tanggal akhir']);
        }

        $this->supplierProdukModel
            ->where('id_produk', (int)$post['id_produk'])
            ->set(['id_discount' => null])
            ->update();

        $this->discountModel->insert([
            'id_produk'   => (int)$post['id_produk'],
            'besaran'     => (int)$post['besaran'],
            'dari_date'   => $post['dari_date'],
            'sampai_date' => $post['sampai_date'],
        ]);

        $idDiscount = $this->discountModel->insertID();

        // SINKRON ke supplier_produk
        $this->supplierProdukModel
            ->where('id_produk', (int)$post['id_produk'])
            ->set(['id_discount' => $idDiscount])
            ->update();

        return redirect()->to(base_url('discount'))
            ->with('success', 'Diskon berhasil ditambahkan.');
    }

    public function ubah($id = null)
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to(base_url('discount'));
        }

        $postId = $this->request->getPost('id_discount');
        $id     = $id ?? $postId;

        if (! $id) {
            return redirect()->to(base_url('discount'))
                ->with('errors', ['ID diskon tidak valid']);
        }

        $post = $this->request->getPost();

        if ($post['dari_date'] > $post['sampai_date']) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['Tanggal mulai tidak boleh lebih besar dari tanggal akhir']);
        }

        $this->discountModel->update($id, [
            'id_produk'   => (int)$post['id_produk'],
            'besaran'     => (int)$post['besaran'],
            'dari_date'   => $post['dari_date'],
            'sampai_date' => $post['sampai_date'],
        ]);

        $this->supplierProdukModel
            ->where('id_discount', $id)
            ->set(['id_discount' => null])
            ->update();

        $this->supplierProdukModel
            ->where('id_produk', (int)$post['id_produk'])
            ->set(['id_discount' => $id])
            ->update();

        return redirect()->to(base_url('discount'))
            ->with('success', 'Diskon berhasil diperbarui.');
    }

    public function hapus()
{
    $id = $this->request->getPost('id_discount')
       ?? $this->request->getPost('id')
       ?? null;

    if (! $id) {
        return redirect()->to(base_url('discount'))
            ->with('errors', ['ID diskon tidak valid']);
    }

    // Lepas relasi ke supplier_produk
    $this->supplierProdukModel
        ->where('id_discount', $id)
        ->set(['id_discount' => null])
        ->update();

    // Hapus diskon
    $this->discountModel->delete($id);

    return redirect()->to(base_url('discount'))
        ->with('success', 'Diskon berhasil dihapus.');
}

}
