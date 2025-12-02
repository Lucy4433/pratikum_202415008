<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<div class="row mt-4">
    <div class="col-md-8 offset-md-2">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Profil Admin</h4>
                <small class="text-muted">Kelola data akun administrator</small>
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

                <!-- FORM PROFIL ADMIN -->
                <form action="<?= base_url('UserAdmin/updateProfil'); ?>" method="post">
                    <?= csrf_field(); ?>

                    <!-- ROLE (readonly) -->
                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Role</label>
                        <div class="col-md-9">
                            <input type="text"
                                   class="form-control"
                                   value="<?= esc($admin->role ?? 'admin'); ?>"
                                   readonly>
                        </div>
                    </div>

                    <!-- NAMA LENGKAP -->
                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Nama Lengkap</label>
                        <div class="col-md-9">
                            <input type="text"
                                   name="nama"
                                   class="form-control"
                                   value="<?= esc($admin->nama ?? ''); ?>"
                                   placeholder="Masukkan nama lengkap admin">
                        </div>
                    </div>

                    <!-- USERNAME -->
                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Username</label>
                        <div class="col-md-9">
                            <input type="text"
                                   name="username"
                                   class="form-control"
                                   value="<?= esc($admin->username ?? ''); ?>"
                                   required>
                        </div>
                    </div>

                    <!-- PASSWORD LAMA -->
                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Password Lama</label>
                        <div class="col-md-9">
                            <input type="password"
                                   name="password_lama"
                                   class="form-control"
                                   placeholder="Isi untuk verifikasi saat ganti password">
                        </div>
                    </div>

                    <!-- PASSWORD BARU -->
                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Password Baru</label>
                        <div class="col-md-9">
                            <input type="password"
                                   name="password_baru"
                                   class="form-control"
                                   placeholder="Kosongkan jika tidak ganti password">
                        </div>
                    </div>

                    <!-- KONFIRMASI PASSWORD BARU -->
                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Konfirmasi Password</label>
                        <div class="col-md-9">
                            <input type="password"
                                   name="password_konfirmasi"
                                   class="form-control"
                                   placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <!-- TOMBOL SIMPAN -->
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
