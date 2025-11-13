<?php

namespace App\Model;

use CodeIgniter\Model;

class Profil_tokoModel extends Model
{
    protected $table            = 'profil_toko';
    protected $primaryKey       = 'id_profil_toko';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_profil', 'nama_toko', 'alamat', 'no_telp'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

}

