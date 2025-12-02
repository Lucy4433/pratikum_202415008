<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<div class="card-header d-flex justify-content-between mb-3">
    <h4>Daftar Produk HP (Kasir)</h4>
    <span class="badge bg-secondary align-self-center">Read Only</span>
</div>

<div class="card-body">
    <p class="text-muted" style="font-size: .85rem;">
        Halaman ini hanya digunakan untuk melihat daftar produk. 
        Perubahan data produk dilakukan melalui panel <b>Admin</b>.
    </p>

    <table class="table table-bordered table-hover">
        <thead class="table-dark text-center">
            <tr>
                <th width="6%">No</th>
                <th>Nama Produk</th>
                <th>Merek</th>
                <th>Harga</th>
                <th width="8%">Stok</th>
                <th width="12%">Diskon</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($produk)): ?>
                <?php foreach ($produk as $key => $p): ?>
                    <tr>
                        <td class="text-center"><?= $key + 1 ?></td>
                        <td><?= esc($p->nama_produk) ?></td>
                        <td><?= esc($p->nama_merek ?? '-') ?></td>
                        <td>Rp <?= number_format($p->harga ?? 0, 0, ',', '.') ?></td>

                        <td class="text-center">
                            <?= is_numeric($p->stok) ? esc($p->stok) : '0' ?>
                        </td>

                        <td class="text-center">
                            <?php if (!empty($p->discount)): ?>
                                <?= esc($p->discount->besaran ?? 0) ?>%
                            <?php else: ?>
                                Tidak ada Discount
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Belum ada data produk.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>
