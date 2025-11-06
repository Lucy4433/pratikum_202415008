<?php

namespace App\Models;

use CodeIgniter\Model;

class Detail_pembelianModel extends Model
{
    protected $table            = 'detail_pembelian';
    protected $primaryKey       = 'id_detail';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_detail', 'id_pembelian', 'id_produk', 'jumlah', 'harga_satuan'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    function getById($id = null): object
    {
        $data = $this->db->table($this->table)
            ->select('detail_pembelian.*, produk.nama_produk, merek.nama_merek')
            ->join('produk', 'produk.id_produk = detail_pembelian.id_produk', 'left')
            ->join('merek', 'merek.id_merek = produk.id_merek', 'left')
            ->where('id_pembelian', $id)
            ->get()
            ->getRow(); 
        return $data;
    }
}
