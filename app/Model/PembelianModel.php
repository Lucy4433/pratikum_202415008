<?php

namespace App\Models;

use CodeIgniter\Model;

class PembelianModel extends Model
{
    protected $table            = 'pembelian';
    protected $primaryKey       = 'id_pembelian';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_pembelian', 'no_pembelian', 'tanggal_pembelian', 'total', 'id_suplier'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    public function getById($id = null): object
    {
        $data = $this->db->table($this->table)
            ->select('pembelian.*, supplier.nama_suplier, supplier.alamat, supplier.no_telp')
            ->join('supplier', 'supplier.id_supplier = pembelian.id_supplier', 'left')
            ->where('pembelian.id_pembelian', $id)
            ->get()
            ->getRow(); 
        return $data;
    }
}
