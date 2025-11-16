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

    /**
     * Ambil semua discount beserta nama produk
     *
     * @return array Array of objects
     */
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

    /**
     * Ambil satu discount berdasarkan id
     *
     * @param int|null $id
     * @return object|null
     */
    public function getById($id = null): ?object
    {
        if ($id === null) {
            return null;
        }

        return $this->db->table($this->table)
            ->select('discount.*, produk.nama_produk')
            ->join('produk', 'produk.id_produk = discount.id_produk', 'left')
            ->where('discount.id_discount', $id)
            ->get()
            ->getRow();
    }

    /**
     * Ambil semua discount untuk sebuah produk
     *
     * @param int $productId
     * @return array
     */
    public function getByProductId(int $productId)
    {
        return $this->db->table($this->table)
            ->where('id_produk', $productId)
            ->orderBy('dari_date', 'DESC')
            ->get()
            ->getRow();
    }

    /**
     * Hitung diskon yang aktif pada tanggal tertentu (default hari ini)
     *
     * @param string|null $date format Y-m-d
     * @return int
     */
    public function countActive(string $date = null): int
    {
        $date = $date ?? date('Y-m-d');
        $builder = $this->db->table($this->table);
        $builder->where('dari_date <=', $date)
                ->where('sampai_date >=', $date);
        return (int) $builder->countAllResults();
    }

    /**
     * Hitung diskon yang belum dimulai (start > date)
     *
     * @param string|null $date
     * @return int
     */
    public function countUpcoming(string $date = null): int
    {
        $date = $date ?? date('Y-m-d');
        $builder = $this->db->table($this->table);
        $builder->where('dari_date >', $date);
        return (int) $builder->countAllResults();
    }

    /**
     * Hitung diskon yang sudah expired (end < date)
     *
     * @param string|null $date
     * @return int
     */
    public function countExpired(string $date = null): int
    {
        $date = $date ?? date('Y-m-d');
        $builder = $this->db->table($this->table);
        $builder->where('sampai_date <', $date);
        return (int) $builder->countAllResults();
    }

    /**
     * Ambil daftar unique id_produk yang punya diskon
     *
     * @return array Array of int ids
     */
    public function getDistinctProductIdsWithDiscount(): array
    {
        $rows = $this->db->table($this->table)
            ->select('DISTINCT id_produk')
            ->where('id_produk IS NOT NULL')
            ->get()
            ->getResultArray();

        return array_map(fn($r) => (int)$r['id_produk'], $rows);
    }

    /**
     * Total jumlah baris discount
     *
     * @return int
     */
    public function countAll(): int
    {
        return (int) $this->db->table($this->table)->countAllResults();
    }

    /**
     * Insert discount baru
     *
     * @param array $data
     * @return int|false inserted id or false
     */
    public function createDiscount(array $data)
    {
        return $this->insert($data);
    }

    /**
     * Update discount
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateDiscount($id, array $data): bool
    {
        return (bool) $this->update($id, $data);
    }

    /**
     * Hapus discount
     *
     * @param int $id
     * @return bool
     */
    public function deleteDiscount($id): bool
    {
        return (bool) $this->delete($id);
    }
}
