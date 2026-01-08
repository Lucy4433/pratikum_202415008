<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<style>
    .table thead th {
        background: #23262a;
        color: #fff;
        vertical-align: middle;
    }

    .table tbody td {
        vertical-align: middle;
    }

    .search-box {
        max-width: 350px;
    }

    .note-info {
        font-size: 13px;
        color: #6c757d;
    }
</style>

<div class="card-header mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Daftar Produk</h4>
            <div class="note-info">
                Master data produk (harga & stok diisi dari barang masuk)
            </div>
        </div>

        <a href="<?= base_url('produk/tambah') ?>"
            class="btn btn-sm btn-primary">
            + Tambah Produk
        </a>
    </div>
</div>

<div class="card-body">

    <!-- SEARCH -->
    <div class="mb-3">
        <input type="text"
            id="searchProduk"
            class="form-control form-control-sm search-box"
            placeholder="🔍 Cari produk atau merek...">
    </div>

    <!-- TABEL PRODUK -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="tabelProduk">
            <thead class="text-center">
                <tr>
                    <th width="5%">No</th>
                    <th>Produk</th>
                    <th>Merek</th>
                    <th width="15%">Harga Beli</th>
                    <th width="15%">Harga Jual</th>
                    <th width="10%">Stok</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($produk)): ?>
                    <?php foreach ($produk as $i => $p): ?>
                        <tr>
                            <td class="text-center"><?= $i + 1 ?></td>

                            <td><?= esc($p->nama_produk) ?></td>

                            <td><?= esc($p->nama_merek ?? '-') ?></td>

                            <td class="text-end">
                                <?= $p->harga_beli_terakhir !== null
                                    ? 'Rp ' . number_format($p->harga_beli_terakhir, 0, ',', '.')
                                    : '<span class="text-muted">-</span>' ?>
                            </td>

                            <td class="text-end">
                                <?= $p->harga_jual !== null
                                    ? 'Rp ' . number_format($p->harga_jual, 0, ',', '.')
                                    : '<span class="text-muted">-</span>' ?>
                            </td>

                            <td class="text-center">
                                <?= (int) $p->stok ?>
                            </td>

                            <!-- AKSI -->
                            <td class="text-center">
                                <a href="<?= base_url('produk/edit/' . $p->id_produk) ?>"
                                    class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <a href="<?= base_url('produk/delete/' . $p->id_produk) ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin hapus produk ini?')">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Belum ada data produk.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- SEARCH SCRIPT -->
<script>
    document.getElementById('searchProduk').addEventListener('keyup', function() {
        let keyword = this.value.toLowerCase();
        let rows = document.querySelectorAll('#tabelProduk tbody tr');

        rows.forEach(function(row) {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(keyword) ? '' : 'none';
        });
    });
</script>

<?= $this->endSection(); ?>