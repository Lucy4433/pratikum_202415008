<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<?php
// kompatibilitas: terima $suppliers atau $model
$suppliers = $suppliers ?? $model ?? [];
?>

<!-- Styles tombol bundar & tabel -->
<style>
.action-group { display:flex; gap:0.5rem; justify-content:center; align-items:center; }
.action-circle {
    width:40px; height:40px; border-radius:50%;
    display:flex; justify-content:center; align-items:center;
    padding:0 !important; color:#fff; border:none; cursor:pointer;
    box-shadow:0 2px 6px rgba(0,0,0,0.06);
}
.btn-orange { background:#f6a21a; }
.btn-red { background:#f14b5d; }
.action-circle img.icon { width:18px; height:18px; }
@media (max-width:480px){ .action-circle{width:34px;height:34px;} .action-circle img.icon{width:16px;height:16px;} }
.table thead th { background:#23262a; color:#fff; border:none; }
.table tbody td { vertical-align: middle; }
.table td, .table th { padding: 16px 12px; }
.table thead th:last-child, .table tbody td:last-child { width:18%; }
</style>

<div class="card-header d-flex justify-content-between mb-3">
    <h4>Daftar Supplier</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
        + Tambah Supplier
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
                <?php foreach ($errors as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
        <thead class="table-dark text-center">
            <tr>
                <th width="6%">No</th>
                <th>Nama Supplier</th>
                <th>Alamat</th>
                <th width="12%">Telepon</th>
                <th width="18%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($suppliers)): ?>
                <?php foreach ($suppliers as $key => $s):?>
                    <tr>
                        <td class="text-center"><?= $key + 1 ?></td>
                        <td><?= esc($s->nama_suplier) ?></td>
                        <td><?= esc($s->alamat) ?></td>
                        <td class="text-center"><?= esc($s->no_telp) ?></td>
                        <td class="text-center">
                            <div class="action-group">
                                <!-- Edit (bundar) -->
                                <button type="button"
                                        class="action-circle btn-orange"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal-<?= esc($s->id_suplier) ?>"
                                        title="Ubah Supplier">
                                    <img src="https://img.icons8.com/ios-filled/50/edit--v1.png" class="icon" alt="edit">
                                </button>

                                <!-- Hapus (bundar) -->
                                <form action="<?= base_url('supplier/hapus') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus supplier ini?')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= esc($s->id_suplier) ?>">
                                    <button type="submit" class="action-circle btn-red" title="Hapus Supplier">
                                        <img src="https://img.icons8.com/fluency/20/delete-trash.png" class="icon" alt="hapus">
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal-<?= esc($s->id_suplier) ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title">Ubah Supplier</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <form action="<?= base_url('supplier/ubah/' . esc($s->id_suplier)) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <div class="modal-body">
                                        <input type="hidden" name="id_suplier" value="<?= esc($s->id_suplier) ?>">

                                        <div class="mb-3">
                                            <label class="form-label">Nama Supplier</label>
                                            <input type="text" name="nama_suplier" class="form-control form-control-sm" value="<?= esc($s->nama_suplier) ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Alamat</label>
                                            <textarea name="alamat" class="form-control form-control-sm" rows="3" required><?= esc($s->alamat) ?></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Telepon</label>
                                            <input type="text" name="no_telp" class="form-control form-control-sm" value="<?= esc($s->no_telp) ?>" required>
                                        </div>

                                        <small class="text-muted">Perbarui data lalu klik <b>Simpan</b>.</small>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal Edit -->

                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Belum ada data supplier.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah Supplier -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="<?= base_url('supplier/tambah') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Supplier</label>
                        <input type="text" name="nama_suplier" class="form-control form-control-sm" value="<?= esc(old('nama_suplier')) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control form-control-sm" rows="3" required><?= esc(old('alamat')) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="no_telp" class="form-control form-control-sm" value="<?= esc(old('no_telp')) ?>" required>
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

<?= $this->endSection(); ?>
