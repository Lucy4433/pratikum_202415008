<?php

namespace App\Models;

use CodeIgniter\Model;

class SuplierModel extends Model
{
    protected $table            = 'supplier';   // Nama tabel di database
    protected $primaryKey       = 'id_suplier'; // Primary key
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = ['nama_suplier', 'alamat', 'no_telp'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    /**
     * Ambil semua supplier urut dari yang terbaru
     */
    public function getAll(): array
    {
        return $this->orderBy($this->primaryKey, 'DESC')->findAll();
    }

    /**
     * Ambil supplier by ID
     */
    public function getById($id)
    {
        return $this->find($id);
    }

    /**
     * Ambil supplier + pembelian jika ada (LEFT JOIN)
     */
    public function getSuplierWithPembelian(): array
    {
        return $this->db->table($this->table)
            ->select('supplier.*, pembelian.no_pembelian, pembelian.total, pembelian.tanggal_pembelian')
            ->join('pembelian', 'pembelian.id_suplier = supplier.id_suplier', 'left')
            ->orderBy('supplier.id_suplier', 'DESC')
            ->get()
            ->getResult();
    }

    /**
     * Hitung supplier yang sudah pernah melakukan pembelian (distinct)
     */
    public function countSuppliersWithPurchases(): int
    {
        $row = $this->db->table('pembelian')
            ->select('COUNT(DISTINCT id_suplier) AS total')
            ->get()
            ->getRow();

        return (int) ($row->total ?? 0);
    }

    /**
     * Hitung supplier yang belum pernah memiliki pembelian
     */
    public function countSuppliersWithoutPurchases(): int
    {
        $total = $this->countAllResults(false);
        $with  = $this->countSuppliersWithPurchases();

        return max(0, $total - $with);
    }
}
