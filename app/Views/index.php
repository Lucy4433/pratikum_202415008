<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<div class="card-header d-flex justify-content-between mb-3">
    <h4>Daftar Supplier</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
        + Tambah Supplier
    </button>
</div>

<div class="card-body">
    <table class="table table-bordered table-hover">
        <thead class="table-dark text-center">
            <tr>
                <th width="6%">No</th>
                <th>Nama Supplier</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th width="18%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($model)): ?>
                <?php foreach ($model as $key => $s): ?>
                    <tr>
                        <td class="text-center"><?= $key + 1 ?></td>
                        <!-- gunakan property objek sesuai Model: nama, alamat, telepon, id_suplier -->
                        <td><?= esc($s->nama ?? '-') ?></td>
                        <td><?= esc($s->alamat ?? '-') ?></td>
                        <td class="text-center"><?= esc($s->telepon ?? '-') ?></td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= esc($s->id_suplier) ?>">
                                <i class="fa fa-pencil"></i> Edit
                            </button>
                            <a href="<?= base_url('supplier/hapus/' . esc($s->id_suplier)) ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Hapus supplier ini?')">
                                <i class="fa fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>

                    <!-- Modal Ubah -->
                    <div class="modal fade" id="editModal<?= esc($s->id_suplier) ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Ubah Supplier</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <!-- ubah route sesuai controller: supplier/ubah/{id} -->
                                <form action="<?= base_url('supplier/ubah/' . esc($s->id_suplier)) ?>" method="post">
                                    <?= csrf_field(); ?>
                                    <div class="modal-body">
                                        <input type="hidden" name="id_suplier" value="<?= esc($s->id_suplier) ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Supplier</label>
                                            <!-- nama sesuai kolom di DB: 'nama' -->
                                            <input type="text" name="nama" class="form-control form-control-sm"
                                                   value="<?= esc($s->nama ?? '') ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Alamat</label>
                                            <textarea name="alamat" class="form-control form-control-sm" required><?= esc($s->alamat ?? '') ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Telepon</label>
                                            <!-- telepon sesuai kolom 'telepon' -->
                                            <input type="text" name="telepon" class="form-control form-control-sm"
                                                   value="<?= esc($s->telepon ?? '') ?>" required>
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
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Belum ada data supplier.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah -->
<?php if(session()->getFlashdata('errors')): $errors = session()->getFlashdata('errors'); ?>
  <div class="alert alert-danger"><ul>
    <?php foreach($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
  </ul></div>
<?php endif; ?>

<div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- form tambah: endpoint supplier/tambah -->
            <form action="<?= base_url('supplier/tambah') ?>" method="post">
                <?= csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Supplier</label>
                        <input type="text" name="nama" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control form-control-sm" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="telepon" class="form-control form-control-sm" required>
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
