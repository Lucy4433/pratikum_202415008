<?php

namespace App\Models;

use CodeIgniter\Model;

class DiscountModel extends Model
{
    protected $table = 'discount';
    protected $primaryKey = 'id_discount';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $protectFields = true;

    protected $allowedFields = ['id_produk', 'besaran', 'dari_date', 'sampai_date'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    public function getDiscountWithProduk(): array
{
    /** @var \CodeIgniter\Database\BaseBuilder $builder */
    $builder = $this->db->table($this->table);

    $builder->select('discount.*, produk.nama_produk');
    $builder->join('produk', 'produk.id_produk = discount.id_produk', 'left');
    $builder->orderBy('discount.id_discount', 'DESC');

    $query = $builder->get();
    return $query->getResult();
}

}



