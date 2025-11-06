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
}
