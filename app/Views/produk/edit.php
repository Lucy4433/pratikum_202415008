<?= $this->extend('layout/index'); ?>
<?= $this->section('content') ?>

<div class="card-header d-flex justify-content-between mb-3">
    <h4 class="mb-0">Edit Produk</h4>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card p-3">

            <form action="<?= site_url('produk/update/' . $produk->id_produk) ?>" method="post">
                <?= csrf_field() ?>

                <!-- NAMA PRODUK -->
                <div class="mb-2">
                    <label class="form-label">Nama Produk</label>
                    <input type="text"
                           name="nama_produk"
                           class="form-control form-control-sm"
                           value="<?= esc($produk->nama_produk) ?>"
                           required>
                </div>

                <!-- MEREK -->
                <div class="mb-3">
                    <label class="form-label">Merek</label>
                    <select name="id_merek"
                            class="form-select form-select-sm"
                            required>
                        <option value="">-- Pilih Merek --</option>
                        <?php foreach ($merek as $mr): ?>
                            <option value="<?= $mr->id_merek ?>"
                                <?= $mr->id_merek == $produk->id_merek ? 'selected' : '' ?>>
                                <?= esc($mr->nama_merek) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- BUTTON -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        Update
                    </button>
                    <a href="<?= site_url('produk') ?>"
                       class="btn btn-outline-secondary btn-sm">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
