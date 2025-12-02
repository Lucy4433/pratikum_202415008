<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<div class="row mt-4">
    <div class="col-md-10 offset-md-1">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">Kelola User Kasir</h4>
                    <small class="text-muted">
                        Tambah, ubah, aktif/nonaktifkan, dan hapus akun kasir.
                    </small>
                </div>
                <a href="<?= base_url('KelolaUser/tambah'); ?>" 
                class="btn btn-primary btn-sm">
                    + Tambah Kasir
                </a>

            </div>

            <div class="card-body">

                <!-- FLASH MESSAGE -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th style="width:5%;">No</th>
                                <th style="width:25%;">Nama Lengkap</th>
                                <th style="width:20%;">Username</th>
                                <th style="width:10%;">Status</th>
                                <th style="width:25%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($kasir)): ?>
                                <?php $no = 1; foreach ($kasir as $row): ?>
                                    <?php $status = $row->status ?? 'aktif'; ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><?= esc($row->nama ?? '-'); ?></td>
                                        <td><?= esc($row->username); ?></td>
                                        <td class="text-center">
                                            <?php if ($status === 'aktif'): ?>
                                                <span class="badge bg-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <!-- Tombol Edit -->
                                            <button type="button"
                                                    class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditKasir<?= $row->id_user; ?>">
                                                Edit
                                            </button>

                                            <!-- Tombol Aktif/Nonaktif -->
                                            <?php if ($status === 'aktif'): ?>
                                                <a href="<?= base_url('UserAdmin/kasir/nonaktif/' . $row->id_user); ?>"
                                                   class="btn btn-outline-secondary btn-sm"
                                                   onclick="return confirm('Nonaktifkan kasir ini?');">
                                                    Nonaktif
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= base_url('UserAdmin/kasir/aktif/' . $row->id_user); ?>"
                                                   class="btn btn-outline-success btn-sm"
                                                   onclick="return confirm('Aktifkan kasir ini?');">
                                                    Aktifkan
                                                </a>
                                            <?php endif; ?>

                                            <!-- Tombol Hapus -->
                                            <a href="<?= base_url('UserAdmin/kasir/hapus/' . $row->id_user); ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Yakin hapus kasir ini?');">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- MODAL EDIT KASIR -->
                                    <div class="modal fade"
                                         id="modalEditKasir<?= $row->id_user; ?>"
                                         tabindex="-1"
                                         aria-labelledby="modalEditKasirLabel<?= $row->id_user; ?>"
                                         aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="<?= base_url('UserAdmin/kasir/ubah/' . $row->id_user); ?>" method="post">
                                                    <?= csrf_field(); ?>
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalEditKasirLabel<?= $row->id_user; ?>">
                                                            Edit User Kasir
                                                        </h5>
                                                        <button type="button"
                                                                class="btn-close"
                                                                data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Lengkap</label>
                                                            <input type="text"
                                                                   name="nama"
                                                                   class="form-control"
                                                                   value="<?= esc($row->nama); ?>">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Username</label>
                                                            <input type="text"
                                                                   name="username"
                                                                   class="form-control"
                                                                   value="<?= esc($row->username); ?>"
                                                                   required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Password Baru</label>
                                                            <input type="password"
                                                                   name="password"
                                                                   class="form-control"
                                                                   placeholder="Kosongkan jika tidak diganti">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Status</label>
                                                            <select name="status" class="form-select">
                                                                <option value="aktif"    <?= $status === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                                                <option value="nonaktif" <?= $status === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button"
                                                                class="btn btn-secondary"
                                                                data-bs-dismiss="modal">
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">
                                                            Simpan Perubahan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END MODAL EDIT -->

                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Belum ada user kasir terdaftar.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
