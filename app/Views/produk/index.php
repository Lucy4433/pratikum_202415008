<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<!-- Styles tombol bulat -->
<style>
.action-group {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    align-items: center;
}

/* Tombol bulat */
.action-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0 !important;
}

/* Icon di dalam tombol */
.action-circle img.icon {
    width: 20px;
    height: 20px;
}

@media (max-width:480px){
    .action-circle {
        width: 34px;
        height: 34px;
    }
    .action-circle img.icon {
        width: 16px;
        height: 16px;
    }
}
</style>

<div class="card-header d-flex justify-content-between mb-3">
    <h4>Daftar Produk HP</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
        + Tambah Produk
    </button>
</div>

<div class="card-body">
    <table class="table table-bordered table-hover">
        <thead class="table-dark text-center">
            <tr>
                <th width="6%">No</th>
                <th>Nama Produk</th>
                <th>Merek</th>
                <th>Harga</th>
                <th width="8%">Stok</th>
                <th width="8%">Discount</th>
                <th width="18%">Aksi</th>
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
                        <td><?= is_null($p->discount) ? 'Tidak ada Discount' : $p->discount->besaran.'%' ?></td>

                        <td class="text-center">
                            <div class="action-group">

                                <!-- Tombol Edit (bulat) -->
                                <button class="btn btn-warning action-circle"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal<?= $key ?>">
                                    🖊️
                                </button>

                                <!-- Tombol Hapus (bulat) -->
                                <a href="/produk/hapus/<?= esc($p->id_produk) ?>"
                                   class="btn btn-danger action-circle"
                                   onclick="return confirm('Hapus produk ini?')">
                                    <img src="https://img.icons8.com/fluency/20/delete-trash.png"
                                         class="icon" alt="hapus">
                                </a>

                                <!-- Tombol Discount (bulat) -->
                                <a href="/produk/discount/<?= esc($p->id_produk) ?>"
                                   class="btn btn-info action-circle text-white">
                                    <img src="https://img.icons8.com/color/20/discount--v1.png"
                                         class="icon" alt="discount">
                                </a>

                            </div>
                        </td>
                    </tr>

                    <!-- Modal Edit Produk -->
                    <div class="modal fade" id="editModal<?= $key ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Ubah Produk</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <form action="/produk/ubah/<?= esc($p->id_produk) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <div class="modal-body">
                                        <input type="hidden" name="id_produk" value="<?= esc($p->id_produk) ?>">

                                        <div class="mb-3">
                                            <label class="form-label">Nama Produk</label>
                                            <input type="text" name="nama_produk"
                                                   class="form-control form-control-sm"
                                                   value="<?= esc($p->nama_produk) ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Merek</label>
                                            <select name="id_merek" class="form-select form-select-sm" required>
                                                <option value="">-- Pilih Merek --</option>
                                                <?php foreach ($merek as $mr): ?>
                                                    <option value="<?= esc($mr->id_merek) ?>"
                                                        <?= ($p->id_merek == $mr->id_merek ? 'selected' : '') ?>>
                                                        <?= esc($mr->nama_merek) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Harga</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" name="harga"
                                                       class="form-control form-control-sm format-rupiah"
                                                       value="<?= number_format($p->harga ?? 0, 0, ',', '.') ?>"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Stok</label>
                                            <input type="number" name="stok"
                                                   class="form-control form-control-sm"
                                                   min="0"
                                                   value="<?= is_numeric($p->stok) ? esc($p->stok) : 0 ?>"
                                                   required>
                                        </div>

                                        <small class="text-muted">Klik <b>Simpan</b> untuk memperbarui.</small>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Belum ada data produk.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah Produk -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="/produk/tambah" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control form-control-sm" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Merek</label>
                        <select name="id_merek" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Merek --</option>
                            <?php foreach ($merek as $mr): ?>
                                <option value="<?= esc($mr->id_merek) ?>"><?= esc($mr->nama_merek) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rp</span>
                            <input type="text" name="harga"
                                   class="form-control form-control-sm format-rupiah"
                                   required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control form-control-sm" min="0" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- JS Format Rupiah -->
<script>
document.querySelectorAll('.format-rupiah').forEach(input => {
    input.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        this.value = new Intl.NumberFormat('id-ID').format(value);
    });
});
</script>

<?= $this->endSection() ?>
