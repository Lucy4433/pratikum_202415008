<?php

namespace App\Models;

use CodeIgniter\Model;

class OrdersModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id_order';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_order', 'no_penjualan', 'tanggal_order', 'total', 'id_user'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    public function getById($id = null)
    {
        return $this->db->table($this->table)
            ->select('orders.*, user.username, detail_order.id_produk, detail_order.jumlah_beli, detail_order.harga_satuan')
            ->join('user', 'user.id_user = orders.id_user', 'left')
            ->join('detail_order', 'detail_order.id_order = orders.id_order', 'left')
            ->where('orders.id_order', $id)
            ->get()
            ->getRow();
    }
}
