<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilTokoModel extends Model
{
    protected $table            = 'profil_toko';
    protected $primaryKey       = 'id_profil';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = ['nama_toko', 'alamat', 'no_telp', 'foto'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

}
