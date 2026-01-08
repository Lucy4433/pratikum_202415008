<?= $this->extend('layout/index'); ?>
<?= $this->section('content') ?>

<div class="card-header d-flex justify-content-between mb-3">
    <h4 class="mb-0">Tambah Barang Masuk</h4>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card p-3">

            <form action="<?= site_url('supplierproduk/store') ?>" method="post">
                <?= csrf_field() ?>

                <!-- PRODUK -->
                <div class="mb-2">
                    <label class="form-label">Produk</label>
                    <select name="id_produk"
                        class="form-select form-select-sm"
                        required>
                        <option value="">-- Pilih Produk --</option>
                        <?php foreach ($produk as $p): ?>
                            <option value="<?= $p->id_produk ?>">
                                <?= esc($p->nama_produk) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- SUPPLIER -->
                <div class="mb-2">
                    <label class="form-label">Supplier</label>
                    <select name="id_supplier"
                        class="form-select form-select-sm"
                        required>
                        <option value="">-- Pilih Supplier --</option>
                        <?php foreach ($supplier as $s): ?>
                            <option value="<?= $s->id_suplier ?>">
                                <?= esc($s->nama_suplier) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- HARGA BELI -->
                <div class="mb-2">
                    <label class="form-label">Harga Beli</label>
                    <input type="number"
                        name="harga_beli"
                        class="form-control form-control-sm"
                        min="0"
                        placeholder="Masukkan harga beli"
                        required>
                </div>

                <!-- STOK MASUK -->
                <div class="mb-3">
                    <label class="form-label">Stok Masuk</label>
                    <input type="number"
                        name="stok_masuk"
                        class="form-control form-control-sm"
                        min="1"
                        placeholder="Jumlah barang masuk"
                        required>
                </div>

                <!-- BUTTON -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        Simpan
                    </button>
                    <a href="<?= site_url('supplier-produk') ?>"
                        class="btn btn-outline-secondary btn-sm">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>