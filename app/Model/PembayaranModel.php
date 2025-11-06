<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'id_pembayaran';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_pembayaran', 'id_order', 'total', 'tanggal_bayar', 'metode_pembayaran'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    public function getById($id = null)
    {
        return $this->db->table($this->table)
            ->select('pembayaran.*, orders.no_penjualan, user.username')
            ->join('orders', 'orders.id_order = pembayaran.id_order', 'left')
            ->join('user', 'user.id_user = orders.id_user', 'left')
            ->where('pembayaran.id_pembayaran', $id)
            ->get()
            ->getRow();
    }
}
