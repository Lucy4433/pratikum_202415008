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
            <h4 class="mb-0">Histori Barang Masuk</h4>
            <div class="note-info">
                Data barang masuk dari supplier
            </div>
        </div>

        <a href="<?= site_url('supplierproduk/tambah') ?>"
           class="btn btn-primary btn-sm">
            + Barang Masuk
        </a>
    </div>
</div>

<div class="card-body">

    <!-- SEARCH -->
    <div class="mb-3">
        <input type="text"
               id="searchBarang"
               class="form-control form-control-sm search-box"
               placeholder="🔍 Cari produk atau supplier...">
    </div>

    <!-- TABEL BARANG MASUK -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="tabelBarang">
            <thead class="text-center">
                <tr>
                    <th width="5%">No</th>
                    <th>Produk</th>
                    <th>Supplier</th>
                    <th width="15%">Harga Beli</th>
                    <th width="15%">Harga Jual</th>
                    <th width="10%">Stok Masuk</th>
                    <th width="15%">Tanggal</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($barang_masuk)): ?>
                <?php foreach ($barang_masuk as $i => $b): ?>
                    <tr>
                        <td class="text-center"><?= $i + 1 ?></td>

                        <td><?= esc($b->nama_produk) ?></td>

                        <td><?= esc($b->nama_supplier ?? '-') ?></td>

                        <td class="text-end">
                            Rp <?= number_format($b->harga_beli, 0, ',', '.') ?>
                        </td>

                        <td class="text-end">
                            Rp <?= number_format($b->harga_jual, 0, ',', '.') ?>
                        </td>

                        <td class="text-center">
                            <?= (int) $b->stok_masuk ?>
                        </td>

                        <td class="text-center">
                            <?= date('d-m-Y', strtotime($b->created_at)) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        Belum ada data barang masuk.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- SEARCH SCRIPT -->
<script>
document.getElementById('searchBarang').addEventListener('keyup', function () {
    let keyword = this.value.toLowerCase();
    let rows = document.querySelectorAll('#tabelBarang tbody tr');

    rows.forEach(function (row) {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(keyword) ? '' : 'none';
    });
});
</script>

<?= $this->endSection(); ?>
