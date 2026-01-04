<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table            = 'produk';
    protected $primaryKey       = 'id_produk';
    protected $returnType       = 'object';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_merek',
        'nama_produk'
    ];

    /**
     * ==================================================
     * PRODUK READ-ONLY (UNTUK MENU PRODUK ADMIN)
     * - HANYA produk yang ada di supplier_produk
     * - Stok = SUM dari semua supplier
     * - Diskon = dari tabel discount 
     * ==================================================
     */
    public function getProdukReadOnly()
    {
        return $this->db->table('supplier_produk sp')
            ->select([
                'p.id_produk',
                'p.nama_produk',
                'm.nama_merek',

                // harga jual tertinggi (aman untuk admin)
                'MAX(sp.harga_jual) AS harga_jual',

                // total stok dari semua supplier
                'SUM(sp.stok) AS stok',

                // diskon (jika ada)
                'MAX(d.besaran) AS besaran_diskon',
                'MAX(d.dari_date) AS dari_date',
                'MAX(d.sampai_date) AS sampai_date',
            ])
            ->join('produk p', 'p.id_produk = sp.id_produk')
            ->join('merek m', 'm.id_merek = p.id_merek', 'left')
            ->join('discount d', 'd.id_discount = sp.id_discount', 'left')
            ->groupBy([
                'p.id_produk',
                'p.nama_produk',
                'm.nama_merek'
            ])
            ->orderBy('p.nama_produk', 'ASC')
            ->get()
            ->getResult();
    }

    public function getById($id)
    {
        return $this->db->table('produk p')
            ->select('p.*, m.nama_merek')
            ->join('merek m', 'm.id_merek = p.id_merek', 'left')
            ->where('p.id_produk', $id)
            ->get()
            ->getRow();
    }
}
