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
        'nama_produk',
        'harga_jual',
        'stok'
    ];

    /**
     * LIST PRODUK
     */
    public function getAll()
{
    return $this->db->table('produk p')
        ->select('
            p.id_produk,
            p.nama_produk,
            m.nama_merek,
            p.harga_jual,
            p.stok,

            (
                SELECT sp.harga_beli
                FROM supplier_produk sp
                WHERE sp.id_produk = p.id_produk
                ORDER BY sp.created_at DESC
                LIMIT 1
            ) AS harga_beli_terakhir
        ')
        ->join('merek m', 'm.id_merek = p.id_merek', 'left')
        ->orderBy('p.nama_produk', 'ASC')
        ->get()
        ->getResult();
}

    /**
     * GET PRODUK BY ID
     */
    public function getById($id)
    {
        return $this->db->table('produk p')
            ->select('p.*, m.nama_merek')
            ->join('merek m', 'm.id_merek = p.id_merek', 'left')
            ->where('p.id_produk', $id)
            ->get()
            ->getRow();
    }

    /**
     * UPDATE PRODUK DARI BARANG MASUK
     */
    public function updateDariBarangMasuk(
        $id_produk,
        $harga_beli,
        $harga_jual,
        $stok_masuk
    ) {
        return $this->db->table($this->table)
            ->set('harga_jual', $harga_jual)
            ->set('stok', "stok + {$stok_masuk}", false)
            ->where('id_produk', $id_produk)
            ->update();
    }
    public function getReadOnlyKasir()
{
    $today = date('Y-m-d');

    return $this->db->table('produk p')
        ->select('
            p.id_produk,
            p.nama_produk,
            m.nama_merek,
            p.harga_jual,
            p.stok,

            d.besaran AS besaran_diskon
        ')
        ->join('merek m', 'm.id_merek = p.id_merek', 'left')
        ->join(
            'discount d',
            'd.id_produk = p.id_produk
             AND d.dari_date <= "'.$today.'"
             AND d.sampai_date >= "'.$today.'"',
            'left'
        )
        ->orderBy('p.nama_produk', 'ASC')
        ->get()
        ->getResult();
}

}
