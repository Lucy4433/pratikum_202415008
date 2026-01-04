<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<style>
.action-group { display:flex; gap:0.5rem; justify-content:center; align-items:center; }
.action-circle {
    width:38px; height:38px; border-radius:50%;
    display:flex; justify-content:center; align-items:center;
    padding:0; color:#fff; border:none; cursor:pointer;
}
.btn-orange { background:#f6a21a; }
.btn-red { background:#f14b5d; }
.action-circle img { width:18px; height:18px; }
.table thead th { background:#23262a; color:#fff; }
</style>

<div class="card-header mb-3 d-flex justify-content-between align-items-center">
    <h4>Detail Supplier</h4>
    <a href="<?= base_url('supplier') ?>" class="btn btn-secondary btn-sm">← Kembali</a>
</div>

<div class="card-body">

    <!-- INFO SUPPLIER -->
    <div class="card mb-4">
        <div class="card-body">
            <h5><?= esc($supplier->nama_suplier) ?></h5>
            <p><b>Alamat:</b> <?= esc($supplier->alamat) ?></p>
            <p><b>Telepon:</b> <?= esc($supplier->no_telp) ?></p>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between mb-3">
        <h5>Produk dari Supplier Ini</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahProduk">
            + Tambah Produk
        </button>
    </div>

    <!-- TABEL -->
    <table class="table table-bordered table-hover">
        <thead class="text-center">
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Diskon</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($produkSupplier as $i => $p): ?>
            <tr>
                <td class="text-center"><?= $i + 1 ?></td>
                <td><?= esc($p->nama_produk) ?></td>
                <td class="text-end">Rp <?= number_format($p->harga_beli,0,',','.') ?></td>
                <td class="text-end">Rp <?= number_format($p->harga_jual,0,',','.') ?></td>

                <!-- DISKON -->
                <td class="text-center">
                    <?php if (!empty($p->besaran)): ?>
                        <span class="badge bg-success">
                            <?= $p->besaran ?>% <br>
                            <small><?= $p->dari_date ?> s/d <?= $p->sampai_date ?></small>
                        </span>
                    <?php else: ?>
                        <span class="text-muted">-</span>
                    <?php endif; ?>
                </td>

                <td class="text-center"><?= $p->stok ?></td>

                <td class="text-center">
                    <div class="action-group">

                        <!-- EDIT -->
                        <button class="action-circle btn-orange"
                            data-bs-toggle="modal"
                            data-bs-target="#edit<?= $p->id_supplier_produk ?>">
                            <img src="https://img.icons8.com/ios-filled/50/edit--v1.png">
                        </button>

                        <!-- HAPUS ITEM -->
                        <form action="<?= base_url('supplier/hapus-produk') ?>" method="post"
                              onsubmit="return confirm('Hapus produk ini dari supplier?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_supplier_produk" value="<?= $p->id_supplier_produk ?>">
                            <button type="submit" class="action-circle btn-red">
                                <img src="https://img.icons8.com/fluency/20/delete-trash.png">
                            </button>
                        </form>

                    </div>
                </td>
            </tr>

            <!-- MODAL EDIT -->
            <div class="modal fade" id="edit<?= $p->id_supplier_produk ?>" tabindex="-1">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title">Edit Produk Supplier</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form action="<?= base_url('supplier/update-produk') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_supplier_produk" value="<?= $p->id_supplier_produk ?>">
                            <input type="hidden" name="id_discount" value="<?= $p->id_discount ?>">
                            <input type="hidden" name="id_produk" value="<?= $p->id_produk ?>">


                            <div class="modal-body">

                                <div class="mb-2">
                                    <label>Produk</label>
                                    <input type="text" class="form-control form-control-sm"
                                           value="<?= esc($p->nama_produk) ?>" readonly>
                                </div>

                                <div class="mb-2">
                                    <label>Harga Beli</label>
                                    <input type="number" name="harga_beli"
                                           class="form-control form-control-sm"
                                           value="<?= $p->harga_beli ?>">
                                </div>

                                <div class="mb-2">
                                    <label>Harga Jual</label>
                                    <input type="number" name="harga_jual"
                                           class="form-control form-control-sm"
                                           value="<?= $p->harga_jual ?>">
                                </div>

                                <hr>

                                <!-- DISKON -->
                                <div class="mb-2">
                                    <label>Diskon (%)</label>
                                    <input type="number" name="besaran"
                                           class="form-control form-control-sm"
                                           value="<?= $p->besaran ?? 0 ?>">
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <label>Dari</label>
                                        <input type="date" name="dari_date"
                                               class="form-control form-control-sm"
                                               value="<?= $p->dari_date ?>">
                                    </div>
                                    <div class="col-6">
                                        <label>Sampai</label>
                                        <input type="date" name="sampai_date"
                                               class="form-control form-control-sm"
                                               value="<?= $p->sampai_date ?>">
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <label>Stok</label>
                                    <input type="number" name="stok"
                                           class="form-control form-control-sm"
                                           value="<?= $p->stok ?>">
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
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>
