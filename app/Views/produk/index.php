<?= $this->extend('layout/index'); ?>
<?= $this->section('content') ?>

<div class="card-header d-flex justify-content-between mb-3">
    <h4>Daftar Produk HP</h4>
    <!-- Tombol buka modal tambah -->
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
                        <td>Rp <?= number_format($p->harga, 0, ',', '.') ?></td>
                        <td class="text-center"><?= esc($p->stok) ?></td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $key ?>">
                                <i class="fa fa-pencil"></i> Edit
                            </button>
                            <a href="/produk/hapus/<?= esc($p->id_produk) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini?')">
                                <i class="fa fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>

                    <!-- Modal Ubah Produk -->
                    <div class="modal fade" id="editModal<?= $key ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Ubah Produk</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="/produk/ubah/<?= esc($p->id_produk) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <div class="modal-body">
                                        <input type="hidden" name="id_produk" value="<?= esc($p->id_produk) ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Produk</label>
                                            <input type="text" name="nama_produk" class="form-control form-control-sm" value="<?= esc($p->nama_produk) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Merek</label>
                                            <select name="id_merek" class="form-select form-select-sm" required>
                                                <option value="">-- Pilih Merek --</option>
                                                <?php foreach ($merek as $mr): ?>
                                                    <option value="<?= esc($mr->id_merek) ?>" <?= ($p->id_merek == $mr->id_merek ? 'selected' : '') ?>>
                                                        <?= esc($mr->nama_merek) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Harga</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" name="harga" class="form-control form-control-sm format-rupiah" value="<?= number_format($p->harga, 0, ',', '.') ?>" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Stok</label>
                                            <input type="number" name="stok" class="form-control form-control-sm" min="0" value="<?= esc($p->stok) ?>" required>
                                        </div>
                                        <small class="text-muted">Perbarui data produk lalu klik <b>Simpan Perubahan</b>.</small>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/produk/tambah" method="post" id="formTambah">
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
                            <input type="text" name="harga" class="form-control form-control-sm format-rupiah" required>
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

<!-- JS untuk format Rupiah -->
<script>
document.querySelectorAll('.format-rupiah').forEach(input => {
    input.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        this.value = new Intl.NumberFormat('id-ID').format(value);
    });
});
</script>

<?= $this->endSection() ?>
