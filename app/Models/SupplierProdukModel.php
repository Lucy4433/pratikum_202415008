<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierProdukModel extends Model
{
    protected $table            = 'supplier_produk';
    protected $primaryKey       = 'id_supplier_produk';
    protected $returnType       = 'object';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_supplier',
        'id_produk',
        'harga_beli',
        'stok_masuk'
    ];

    /**
     * ==========================================
     * INSERT BARANG MASUK
     * (TIDAK ADA EDIT & DELETE)
     * ==========================================
     */
    public function insertBarangMasuk($data)
    {
        return $this->insert($data);
    }

    /**
     * ==========================================
     * HISTORI BARANG MASUK
     * ==========================================
     */
    public function getAll()
    {
        return $this->db->table('supplier_produk sp')
            ->select('
            sp.id_supplier_produk,
            sp.harga_beli,
            sp.stok_masuk,
            sp.created_at,

            p.nama_produk,
            p.harga_jual,

            s.nama_suplier AS nama_supplier
        ')
            ->join('produk p', 'p.id_produk = sp.id_produk')
            ->join('supplier s', 's.id_suplier = sp.id_supplier', 'left')
            ->orderBy('sp.id_supplier_produk', 'DESC')
            ->get()
            ->getResult();
    }
}
