<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierProdukModel extends Model
{
    protected $table      = 'supplier_produk';
    protected $primaryKey = 'id_supplier_produk';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_supplier',
        'id_produk',
        'id_discount',
        'harga_beli',
        'harga_jual',
        'stok'
    ];

    /**
     * =========================================
     * Ambil produk supplier + diskon (JOIN)
     * =========================================
     */
    public function getBySupplier($id_supplier)
    {
    $today = date('Y-m-d');

    return $this->db->table('supplier_produk sp')
        ->select('
            sp.id_supplier_produk,
            sp.id_supplier,
            sp.id_produk,
            sp.harga_beli,
            sp.harga_jual,
            sp.stok,
            sp.id_discount,
            p.nama_produk,
            d.besaran,
            d.dari_date,
            d.sampai_date
        ')
        ->join('produk p', 'p.id_produk = sp.id_produk')
        ->join(
            'discount d',
            'd.id_discount = sp.id_discount 
             AND d.dari_date <= "'.$today.'" 
             AND d.sampai_date >= "'.$today.'"',
            'left'
        )
        ->where('sp.id_supplier', $id_supplier)
        ->get()
        ->getResult();
    }



    /**
     * Update relasi diskon ke supplier_produk
     */
    public function updateDiskon($id_supplier_produk, $id_discount)
    {
        return $this->update($id_supplier_produk, [
            'id_discount' => $id_discount
        ]);
    }
}
