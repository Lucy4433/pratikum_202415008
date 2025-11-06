<?php

namespace App\Models;

use CodeIgniter\Model;

class Detail_orderModel extends Model
{
    protected $table            = 'detail_order';
    protected $primaryKey       = 'id_detail';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_order', 'jumlah_beli', 'harga_satuan'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    function getById($id = null) : object {
        $data =  $this->db->table($this->table)
        ->select('detail_order.*, produk.nama_produk, merek.nama_merek')
        ->join('produk', 'produk.id_produk=detail_order.id_produk', 'left')
        ->join('merek', 'merek.id_merek=produk.id_merek', 'left')
        ->where('id_order', $id)
        ->get()
        ->getRow();
        return $data;
    }
}
