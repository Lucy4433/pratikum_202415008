<?= $this->extend('layout/index'); ?>
<?= $this->section('content') ?>

<div class="card-header d-flex justify-content-between mb-3">
    <h4>Daftar Diskon Produk</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
        + Tambah Diskon
    </button>
</div>

<div class="card-body">

    <!-- Notifikasi sukses -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Notifikasi error -->
    <?php if (session()->getFlashdata('errors')): 
        $errors = session()->getFlashdata('errors'); ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
        <thead class="table-dark text-center">
            <tr>
                <th width="6%">No</th>
                <th>Produk</th>
                <th>Besaran (%)</th>
                <th>Mulai</th>
                <th>Selesai</th>
                <th width="18%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($discount)): ?>
                <?php foreach ($discount as $key => $d): ?>
                    <tr>
                        <td class="text-center"><?= $key + 1 ?></td>
                        <td><?= esc($d->nama_produk ?? '-') ?></td>
                        <td class="text-center"><?= esc($d->besaran) ?>%</td>
                        <td class="text-center"><?= esc($d->dari_date) ?></td>
                        <td class="text-center"><?= esc($d->sampai_date) ?></td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-<?= esc($d->id_discount) ?>">
                                <i class="fa fa-pencil"></i> Edit
                            </button>
                            <a href="<?= base_url('discount/hapus/' . esc($d->id_discount)) ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus diskon ini?')">
                                <i class="fa fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>

                    <!-- Modal Edit Diskon -->
                    <div class="modal fade" id="editModal-<?= esc($d->id_discount) ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title">Ubah Diskon</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <form action="<?= base_url('discount/ubah/' . esc($d->id_discount)) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <div class="modal-body">

                                        <input type="hidden" name="id_discount" value="<?= esc($d->id_discount) ?>">

                                        <div class="mb-3">
                                            <label class="form-label">Produk</label>
                                            <select name="id_produk" class="form-select form-select-sm" required>
                                                <option value="">-- Pilih Produk --</option>
                                                <?php foreach ($produk as $p): ?>
                                                    <option value="<?= esc($p->id_produk) ?>"
                                                        <?= ($p->id_produk == $d->id_produk ? 'selected' : '') ?>>
                                                        <?= esc($p->nama_produk) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Besaran Diskon (%)</label>
                                            <input type="number"
                                                   name="besaran"
                                                   class="form-control form-control-sm"
                                                   min="0"
                                                   max="100"
                                                   value="<?= esc($d->besaran) ?>"
                                                   required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Periode Diskon</label>
                                            <div class="row g-2">
                                                <div class="col">
                                                    <input type="date"
                                                           name="dari_date"
                                                           class="form-control form-control-sm"
                                                           value="<?= esc($d->dari_date) ?>"
                                                           required>
                                                </div>
                                                <div class="col">
                                                    <input type="date"
                                                           name="sampai_date"
                                                           class="form-control form-control-sm"
                                                           value="<?= esc($d->sampai_date) ?>"
                                                           required>
                                                </div>
                                            </div>
                                        </div>

                                        <small class="text-muted">
                                            Perbarui data lalu klik <b>Simpan Perubahan</b>.
                                        </small>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                            Batal
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-save me-1"></i> Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal Edit -->
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Belum ada data diskon.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah Diskon -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">Tambah Diskon Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="/discount/tambah" method="post" id="formTambahDiskon">
                <?= csrf_field() ?>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Produk</label>
                        <select name="id_produk" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Produk --</option>
                            <?php foreach ($produk as $p): ?>
                                <option value="<?= esc($p->id_produk) ?>"><?= esc($p->nama_produk) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Besaran Diskon (%)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">%</span>
                            <input type="number" name="besaran" class="form-control form-control-sm" min="1" max="100" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Periode Diskon</label>
                        <div class="row g-2">
                            <div class="col">
                                <label class="form-label small text-muted">Mulai</label>
                                <input type="date" name="dari_date" class="form-control form-control-sm" required>
                            </div>
                            <div class="col">
                                <label class="form-label small text-muted">Selesai</label>
                                <input type="date" name="sampai_date" class="form-control form-control-sm" required>
                            </div>
                        </div>
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


<?= $this->endSection() ?>
