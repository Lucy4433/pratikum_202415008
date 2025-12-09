<?= $this->extend('layout/index'); ?>
<?= $this->section('content') ?>

<!-- Styles tombol bundar -->
<style>
.action-group {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    align-items: center;
}

/* Tombol bundar */
.action-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0 !important;
    color: #fff;
    border: none;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

/* Warna tombol */
.btn-orange { background: #f6a21a; } /* edit */
.btn-red    { background: #f14b5d; } /* delete */

/* icon ukuran */
.action-circle img.icon {
    width: 18px;
    height: 18px;
}

/* responsive */
@media (max-width:480px){
    .action-circle { width: 34px; height: 34px; }
    .action-circle img.icon { width: 16px; height: 16px; }
}

/* Statistik cards */
.stat-row { margin-bottom: 1.25rem; }
.stat-card {
    border-radius: 6px;
    color: #fff;
    padding: 16px;
    box-shadow: 0 6px 18px rgba(28,39,61,0.06);
}
.stat-title { font-size: 0.95rem; opacity: 0.9; }
.stat-num { font-size: 1.6rem; font-weight: 600; margin-top: 6px; }
.stat-note { margin-top: 8px; opacity: 0.9; font-size: 0.9rem; }
.stat-purple { background:#7b42d6; }   /* aktif */
.stat-orange { background:#f6a21a; }   /* belum aktif */
.stat-red    { background:#e74c3c; }   /* expired */
.stat-blue   { background:#2f9bf0; }   /* belum ada diskon */
@media (max-width:767px){ .stat-num { font-size:1.25rem; } }
</style>

<div class="card-header d-flex justify-content-between mb-3"> <!--Membuat header card yang isinya dibagi kiri dan kanan-->
    <h4>Daftar Diskon Produk</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal"> 
        <!--data-bs-toggle="modal" = perintah Bootstrap untuk membuka modal.
            data-bs-target="#tambahModal" = modal yang dibuka bernama tambahModal.-->
        + Tambah Diskon
    </button> <!--untuk form diskon-->
</div>

<div class="card-body">

    <!-- KARTU STATISTIK -->
    <div class="row stat-row">
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-card stat-purple">
                <div class="stat-num"><?= esc($stats['active'] ?? 0) ?></div> <!--menmapilkan angka diskon aktif-->
                <div class="stat-title">Diskon Aktif</div>
                <div class="stat-note">Jumlah diskon yang sedang aktif hari ini</div>
            </div>
        </div>

        <div class="col-md-3 col-6 mb-3">
            <div class="stat-card stat-orange">
                <div class="stat-num"><?= esc($stats['upcoming'] ?? 0) ?></div> <!--menampilkan angak dsikon yg tidak aktif-->
                <div class="stat-title">Belum Dimulai</div>
                <div class="stat-note">Diskon yang belum mulai</div>
            </div>
        </div>

        <div class="col-md-3 col-6 mb-3">
            <div class="stat-card stat-red">
                <div class="stat-num"><?= esc($stats['expired'] ?? 0) ?></div> <!--menampilkan angka diskon expired-->
                <div class="stat-title">Expired</div>
                <div class="stat-note">Diskon yang sudah lewat</div>
            </div>
        </div>

        <div class="col-md-3 col-6 mb-3">
            <div class="stat-card stat-blue">
                <div class="stat-num"><?= esc($stats['no_discount'] ?? 0) ?></div> <!--menampilkan angak produk yg blm diskon-->
                <div class="stat-title">Produk Belum Diskon</div>
                <div class="stat-note">Jumlah produk yang belum memiliki diskon</div>
            </div>
        </div>
    </div>
    <!-- /KARTU STATISTIK -->

    <!-- Notifikasi sukses -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert"> //membuat nontifikasi berwarna hijau
            <?= session()->getFlashdata('success') ?> //tampilan sukses
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button> //tombol X
        </div>
    <?php endif; ?>

    <!-- Notifikasi error -->
    <?php if (session()->getFlashdata('errors')): // megecek data error di controller
        $errors = session()->getFlashdata('errors'); ?>
        <div class="alert alert-danger"> <!--nonftikasi merah-->
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?> <!--baris 122-124 looping data yang eror-->
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
                <th>Status</th>
                <th width="18%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($discount)): ?> <!--cek data-->
                <?php foreach ($discount as $key => $d): ?> <!--Mengambil setiap item diskon dari database-->
                    <tr>
                        <td class="text-center"><?= $key + 1 ?></td> <!--tampilan nomor urut-->
                        <td><?= esc($d->nama_produk ?? '-') ?></td> <!--menampilkan nama produk yang medapatkan diskon-->
                        <td class="text-center"><?= esc($d->besaran) ?>%</td> <!--Menampilkan angka diskon, misalnya 10%, 25%.-->
                        <td class="text-center"><?= esc($d->dari_date) ?></td> <!--Menampilkan tanggal kapan diskon mulai berlaku-->
                        <td class="text-center"><?= esc($d->sampai_date) ?></td> <!--Menampilkan tanggal kapan diskon berakhir-->
                        <td class="text-center"><?= esc($d->status ?? '-') ?></td> <!--Menampilkan status diskon: aktif, belum dimulai, expired-->
                        <td class="text-center">
                            <div class="action-group">

                                <!-- Edit (oranye) - buka modal edit -->
                                <button type="button"
                                        class="action-circle btn-orange"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModal-<?= esc($d->id_discount) ?>"
                                        title="Edit Diskon"> <!-- birs 157 membuka pop up, brus 159 memagil modal edt sesuai id_discount-->
                                    <img src="https://img.icons8.com/ios-filled/50/edit--v1.png" class="icon" alt="edit">
                                </button>

                                <!-- Hapus (merah) - form POST dengan CSRF -->
                                <form method="post" action="<?= base_url('discount/hapus') ?>" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus diskon ini?')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= esc($d->id_discount) ?>">  <!-- Mengirim ID diskon yang mau dihapus ke serve-->
                                    <button type="submit" class="action-circle btn-red" title="Hapus Diskon">
                                        <img src="https://img.icons8.com/fluency/20/delete-trash.png" class="icon" alt="hapus">
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                    <!-- Modal Edit Diskon -->
                    <div class="modal fade" id="editModal-<?= esc($d->id_discount) ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered">
                            <div class="modal-content"> <!-- bris 176-179 jendala pop up -->
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title">Ubah Diskon</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div> <!-- header modal brs 180-183 -->

                                <form action="<?= base_url('discount/ubah/' . esc($d->id_discount)) ?>" method="post"> <!--mengirim data ke controller mengubah diskon tertentu dengan metode post-->
                                    <?= csrf_field() ?> 
                                    <div class="modal-body">

                                        <input type="hidden" name="id_discount" value="<?= esc($d->id_discount) ?>"> <!-- sistem tahu id discount mana yang di edit-->

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
                                        </div> <!-- bris 204-213 input besaran dsikon-->

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
                                                </div> <!-- tgl diskon mulai dan selesai -->
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
                    <td colspan="7" class="text-center">Belum ada data diskon.</td> <!--menampilkan pesan jika kosong-->
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah Diskon -->

<?= view('discount/tambah'); ?>

<?= $this->endSection() ?>
