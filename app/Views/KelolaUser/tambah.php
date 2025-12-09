<div class="modal fade" id="modalTambahKasir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah User Kasir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="<?= base_url('KelolaUser/tambah'); ?>" method="POST">
                <?= csrf_field(); ?>

                <div class="modal-body">

                    <!-- NAMA -->
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control form-control-sm" placeholder="Masukkan nama kasir" required>
                    </div>

                    <!-- USERNAME -->
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control form-control-sm" placeholder="Masukkan username" required>
                    </div>

                    <!-- PASSWORD -->
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group input-group-sm">
                            <input type="password" 
                                   name="password" 
                                   id="passwordKasir" 
                                   class="form-control" 
                                   required>

                            <button type="button" 
                                    class="btn btn-outline-secondary" 
                                    onclick="togglePassword('passwordKasir', this)">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- KONFIRMASI PASSWORD -->
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <div class="input-group input-group-sm">
                            <input type="password" 
                                   name="konfirmasi" 
                                   id="passwordKasirKonfirmasi" 
                                   class="form-control" 
                                   required>

                            <button type="button" 
                                    class="btn btn-outline-secondary" 
                                    onclick="togglePassword('passwordKasirKonfirmasi', this)">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>
