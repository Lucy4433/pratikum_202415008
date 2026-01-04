<?php

namespace App\Models;

use CodeIgniter\Model;

class DiscountModel extends Model
{
    protected $table            = 'discount';
    protected $primaryKey       = 'id_discount';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_produk',
        'besaran',
        'dari_date',
        'sampai_date'
    ];

    /* =============================
     * DISCOUNT LIST (MENU DISKON)
     * ============================= */
    public function getDiscountWithProduk()
    {
        return $this->db->table('discount d')
            ->select('
                d.id_discount,
                d.id_produk,
                d.besaran,
                d.dari_date,
                d.sampai_date,
                p.nama_produk
            ')
            ->join('produk p', 'p.id_produk = d.id_produk', 'left')
            ->orderBy('d.id_discount', 'DESC')
            ->get()
            ->getResult();
    }

    /* =============================
     * DISCOUNT BY ID
     * ============================= */
    public function getById($id)
    {
        return $this->db->table('discount d')
            ->select('
                d.*,
                p.nama_produk
            ')
            ->join('produk p', 'p.id_produk = d.id_produk', 'left')
            ->where('d.id_discount', $id)
            ->get()
            ->getRow();
    }

    /* =============================
     * DISCOUNT AKTIF PER PRODUK
     * ============================= */
    public function getActiveByProduct($id_produk)
    {
        $today = date('Y-m-d');

        return $this->db->table('discount')
            ->where('id_produk', $id_produk)
            ->where('dari_date <=', $today)
            ->where('sampai_date >=', $today)
            ->orderBy('dari_date', 'DESC')
            ->get()
            ->getRow();
    }

    /* =============================
     * STATISTIK DISKON
     * ============================= */
    public function countActive()
    {
        $today = date('Y-m-d');

        return $this->where('dari_date <=', $today)
                    ->where('sampai_date >=', $today)
                    ->countAllResults();
    }

    public function countExpired()
    {
        $today = date('Y-m-d');

        return $this->where('sampai_date <', $today)
                    ->countAllResults();
    }

    public function countUpcoming()
    {
        $today = date('Y-m-d');

        return $this->where('dari_date >', $today)
                    ->countAllResults();
    }
}
