<form action="<?= base_url('KelolaUser/tambah'); ?>" method="post">
    <?= csrf_field(); ?>

    <div class="modal-header">
        <h5 class="modal-title">Tambah User Kasir</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text"
                   name="nama"
                   class="form-control"
                   placeholder="Masukkan nama kasir"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text"
                   name="username"
                   class="form-control"
                   placeholder="Masukkan username"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password"
                   name="password"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password"
                   name="konfirmasi"
                   class="form-control"
                   required>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal">
            Batal
        </button>
        <button type="submit"
                class="btn btn-primary">
            Simpan
        </button>
    </div>
</form>
