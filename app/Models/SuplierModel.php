<?php

namespace App\Models;

use CodeIgniter\Model;

class SuplierModel extends Model
{
    protected $table            = 'supplier'; // sesuai database
    protected $primaryKey       = 'id_suplier';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = ['nama', 'alamat', 'email', 'telepon'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    public function getSuplierWithPembelian(): array
    {
        return $this->db->table($this->table)
            ->select('suplier.*, pembelian.no_pembelian, pembelian.total')
            ->join('pembelian', 'pembelian.id_suplier = suplier.id_suplier', 'left')
            ->orderBy('suplier.id_suplier', 'DESC')
            ->get()
            ->getResult();
    }
}
