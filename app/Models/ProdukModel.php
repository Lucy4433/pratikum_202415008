<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table            = 'produk';
    protected $primaryKey       = 'id_produk';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    
    protected $allowedFields    = ['id_produk', 'id_merek', 'nama_produk', 'harga', 'stok'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    public function getById($id = null): object
    {
        return $this->db->table($this->table)
            ->select('produk.*, merek.nama_merek')
            ->join('merek', 'merek.id_merek = produk.id_merek', 'left')
            ->where('produk.id_produk', $id)
            ->get()
            ->getRow();
    }

    public function getProdukWithMerek(): array
    {
        return $this->db->table($this->table)
            ->select('produk.*, merek.nama_merek')
            ->join('merek', 'merek.id_merek = produk.id_merek', 'left')
            ->orderBy('produk.id_produk', 'DESC')
            ->get()
            ->getResult();
    }

    public function updateProduk($id, $data)
    {
        return $this->update($id, $data);
    }
}