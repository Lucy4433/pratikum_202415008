<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierProdukModel extends Model
{
    protected $table = 'supplier_produk';
    protected $primaryKey = 'id_supplier_produk';
    protected $returnType = 'object';
    protected $allowedFields = [
        'id_supplier',
        'id_produk',
        'harga_beli',
        'harga_jual',
        'diskon',
        'stok'
    ];

    public function getBySupplier($id_supplier)
    {
        return $this->select('supplier_produk.*, produk.nama_produk')
            ->join('produk', 'produk.id_produk = supplier_produk.id_produk')
            ->where('supplier_produk.id_supplier', $id_supplier)
            ->findAll();
    }
}
