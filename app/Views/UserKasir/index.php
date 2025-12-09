<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<?php
$foto = !empty($kasir->foto) ? $kasir->foto : 'default.png';
?>

<div class="row mt-4">
    <!-- ========== NOTIFIKASI (LEBAR PENUH) ========== -->
    <div class="col-12 mb-2">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>

    <!-- ========== KOLOM KIRI: FOTO PROFIL KASIR ========== -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Foto Profil Kasir</h5>
            </div>
            <div class="card-body text-center">

                <!-- PREVIEW FOTO PROFIL -->
                <div class="mb-3">
                    <img src="<?= base_url('assets/images/foto_user/' . $foto); ?>"
                         alt="Foto Kasir"
                         class="img-thumbnail rounded-circle"
                         style="width: 150px; height: 150px; object-fit: cover;">
                </div>

                <h6 class="mb-1"><?= esc($kasir->nama ?? 'Nama Kasir'); ?></h6>
                <small class="text-muted d-block mb-1">
                    <?= esc(ucfirst($kasir->role ?? 'kasir')); ?>
                </small>

                <?php if (!empty($kasir->status)): ?>
                    <small class="badge bg-<?= $kasir->status === 'aktif' ? 'success' : 'secondary'; ?> mb-3">
                        <?= esc(ucfirst($kasir->status)); ?>
                    </small>
                <?php endif; ?>

                <!-- FORM EDIT FOTO (AUTO SUBMIT) -->
                <form id="formFotoKasir"
                      action="<?= base_url('UserKasir/updateFoto'); ?>"
                      method="post"
                      enctype="multipart/form-data">
                    <?= csrf_field(); ?>
                    <!-- input file disembunyikan, dipicu lewat tombol Edit Foto -->
                    <input type="file"
                           name="foto"
                           id="inputFotoKasir"
                           accept="image/*"
                           style="display: none;"
                           onchange="submitFotoKasir()">

                    <button type="button"
                            class="btn btn-outline-primary btn-sm"
                            onclick="pilihFotoKasir()">
                        Edit Foto
                    </button>

                    <small class="d-block text-muted mt-2">
                        Format JPG / PNG, maks. 2MB.
                    </small>
                </form>

            </div>
        </div>
    </div>

    <!-- ========== KOLOM KANAN: PROFIL & PASSWORD KASIR ========== -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Profil Kasir</h4>
                <small class="text-muted">Kelola data akun kasir</small>
            </div>

            <div class="card-body">

                <!-- ========== FORM PROFIL KASIR (NAMA) ========== -->
                <form action="<?= base_url('UserKasir/updateProfil'); ?>" method="post" class="mb-4">
                    <?= csrf_field(); ?>

                    <!-- ROLE (readonly) -->
                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Role</label>
                        <div class="col-md-9">
                            <input type="text"
                                   class="form-control"
                                   value="<?= esc($kasir->role ?? 'kasir'); ?>"
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
                                   value="<?= esc($kasir->nama ?? ''); ?>"
                                   placeholder="Masukkan nama lengkap kasir"
                                   required>
                        </div>
                    </div>

                    <!-- USERNAME (READONLY) -->
                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Username</label>
                        <div class="col-md-9">
                            <input type="text"
                                   class="form-control"
                                   value="<?= esc($kasir->username ?? ''); ?>"
                                   readonly>
                        </div>
                    </div>

                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary">
                            Simpan Profil
                        </button>
                    </div>
                </form>

                <hr>

                <!-- ========== FORM GANTI PASSWORD KASIR ========== -->
                <form action="<?= base_url('UserKasir/updatePassword'); ?>" method="post">
                    <?= csrf_field(); ?>

                    <!-- PASSWORD LAMA -->
                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Password Lama</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="password"
                                       name="password_lama"
                                       id="password_lama"
                                       class="form-control"
                                       placeholder="Isi untuk verifikasi saat ganti password"
                                       required>
                                <button class="btn btn-outline-secondary"
                                        type="button"
                                        onclick="togglePassword('password_lama', this)">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- PASSWORD BARU -->
                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Password Baru</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="password"
                                       name="password_baru"
                                       id="password_baru"
                                       class="form-control"
                                       placeholder="Minimal 6 karakter"
                                       required>
                                <button class="btn btn-outline-secondary"
                                        type="button"
                                        onclick="togglePassword('password_baru', this)">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- KONFIRMASI PASSWORD BARU -->
                    <div class="mb-3 row">
                        <label class="col-md-3 col-form-label">Konfirmasi Password</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="password"
                                       name="password_konfirmasi"
                                       id="password_konfirmasi"
                                       class="form-control"
                                       placeholder="Ulangi password baru"
                                       required>
                                <button class="btn btn-outline-secondary"
                                        type="button"
                                        onclick="togglePassword('password_konfirmasi', this)">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-warning">
                            Simpan Password Baru
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script>
// Tombol mata password (sama seperti admin)
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');

    if (!input) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// ==== FOTO PROFIL: PILIH & SUBMIT OTOMATIS (KASIR) ====
function pilihFotoKasir() {
    const input = document.getElementById('inputFotoKasir');
    if (input) {
        input.click();
    }
}

function submitFotoKasir() {
    const form = document.getElementById('formFotoKasir');
    const input = document.getElementById('inputFotoKasir');

    if (form && input && input.files.length > 0) {
        form.submit();
    }
}
</script>

<?= $this->endSection(); ?>
