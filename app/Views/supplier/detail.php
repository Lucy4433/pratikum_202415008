<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<style>
.action-group { display:flex; gap:0.5rem; justify-content:center; align-items:center; }
.action-circle {
    width:38px; height:38px; border-radius:50%;
    display:flex; justify-content:center; align-items:center;
    padding:0; color:#fff; border:none; cursor:pointer;
    box-shadow:0 2px 6px rgba(0,0,0,0.08);
}
.btn-orange { background:#f6a21a; }
.btn-blue { background:#4e73df; }
.btn-red { background:#f14b5d; }
.action-circle img { width:18px; height:18px; }
.table thead th { background:#23262a; color:#fff; }
</style>

<div class="card-header mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <h4>Detail Supplier</h4>
        <a href="<?= base_url('supplier') ?>" class="btn btn-secondary btn-sm">
            ← Kembali
        </a>
    </div>
</div>

<div class="card-body">

    <!-- INFO SUPPLIER -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-2"><?= esc($supplier->nama_suplier) ?></h5>
            <p class="mb-1"><b>Alamat:</b> <?= esc($supplier->alamat) ?></p>
            <p class="mb-0"><b>Telepon:</b> <?= esc($supplier->no_telp) ?></p>
        </div>
    </div>

    <!-- NOTIFIKASI -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- HEADER TABEL -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Produk dari Supplier Ini</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahProduk">
            + Tambah Produk
        </button>
    </div>

    <!-- TABEL PRODUK -->
    <table class="table table-bordered table-hover">
        <thead class="text-center">
            <tr>
                <th width="5%">No</th>
                <th>Produk</th>
                <th width="15%">Harga Beli</th>
                <th width="15%">Harga Jual</th>
                <th width="10%">Diskon</th>
                <th width="10%">Stok</th>
                <th width="15%">Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($produkSupplier)): ?>
            <?php foreach ($produkSupplier as $i => $p): ?>
                <tr>
                    <td class="text-center"><?= $i + 1 ?></td>
                    <td><?= esc($p->nama_produk) ?></td>
                    <td class="text-end">Rp <?= number_format($p->harga_beli, 0, ',', '.') ?></td>
                    <td class="text-end">Rp <?= number_format($p->harga_jual,0,',','.') ?></td>
                    <td class="text-center"><?= $p->diskon ?>%</td>
                    <td class="text-center"><?= $p->stok ?></td>
                    <td class="text-center">
                        <div class="action-group">
                            <!-- EDIT -->
                            <button class="action-circle btn-orange"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editProduk<?= $p->id_supplier_produk ?>">
                                <img src="https://img.icons8.com/ios-filled/50/edit--v1.png">
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- MODAL EDIT PRODUK -->
                <div class="modal fade" id="editProduk<?= $p->id_supplier_produk ?>" tabindex="-1">
                    <div class="modal-dialog modal-md modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title">Edit Produk Supplier</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <form action="<?= base_url('supplier/update-produk') ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id_supplier_produk"
                                    value="<?= $p->id_supplier_produk ?>">

                                <div class="modal-body">
                                    <!-- PRODUK (READONLY) -->
                                    <div class="mb-3">
                                        <label class="form-label">Produk</label>
                                        <input type="text"
                                            class="form-control form-control-sm"
                                            value="<?= esc($p->nama_produk) ?>"
                                            readonly>
                                    </div>

                                    <!-- HARGA Beli -->
                                    <div class="mb-3">
                                        <label class="form-label">Harga Beli</label>
                                        <input type="text"
                                            class="form-control form-control-sm rupiah"
                                            placeholder="Rp 0"
                                            autocomplete="off">

                                        <input type="hidden"
                                            name="harga_beli"
                                            class="harga_asli"
                                            value="<?= $p->harga_beli ?>">
                                    </div>

                                    <!-- HARGA Jual -->                            
                                    <div class="mb-3">
                                        <label class="form-label">Harga Jual</label>
                                        <input type="text"
                                            class="form-control form-control-sm rupiah"
                                            placeholder="Rp 0"
                                            autocomplete="off">

                                        <input type="hidden"
                                            name="harga_jual"
                                            class="harga_asli"
                                            value="0">
                                    </div>

                                    <!-- DISKON & STOK (SEJAJAR) -->
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label class="form-label">Diskon (%)</label>
                                                <input type="number" name="diskon"
                                                    class="form-control form-control-sm"
                                                    value="<?= $p->diskon ?>">
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label class="form-label">Stok</label>
                                                <input type="number" name="stok"
                                                    class="form-control form-control-sm"
                                                    value="<?= $p->stok ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button"
                                            class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal">Batal</button>
                                    <button type="submit"
                                            class="btn btn-primary btn-sm">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">
                    Belum ada produk untuk supplier ini.
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

                <!-- MODAL TAMBAH PRODUK -->
                <div class="modal fade" id="modalTambahProduk" tabindex="-1">
                    <div class="modal-dialog modal-md modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Produk ke Supplier</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <form action="<?= base_url('supplier/tambah-produk') ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id_supplier" value="<?= $supplier->id_suplier ?>">

                                <div class="modal-body">
                                    <!-- PRODUK -->
                                    <div class="mb-3">
                                        <label class="form-label">Produk</label>
                                        <select name="id_produk" class="form-control form-control-sm" required>
                                            <option value="">-- Pilih Produk --</option>
                                            <?php foreach ($produk as $pr): ?>
                                                <option value="<?= $pr->id_produk ?>">
                                                    <?= esc($pr->nama_produk) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- HARGA  Beli-->
                                    <div class="mb-3">
                                        <label class="form-label">Harga Beli</label>
                                        <input type="text"
                                            class="form-control form-control-sm rupiah"
                                            placeholder="Rp 0"
                                            autocomplete="off">

                                        <input type="hidden"
                                            name="harga_beli"
                                            class="harga_asli"
                                            value="0">
                                    </div>

                                    <!-- HARGA Jual -->                            
                                    <div class="mb-3">
                                        <label class="form-label">Harga Jual</label>
                                        <input type="text"
                                            class="form-control form-control-sm rupiah"
                                            placeholder="Rp 0"
                                            autocomplete="off">

                                        <input type="hidden"
                                            name="harga_jual"
                                            class="harga_asli"
                                            value="0">
                                    </div>

                                    <!-- DISKON & STOK (SEJAJAR) -->
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label class="form-label">Diskon (%)</label>
                                                <input type="number" name="diskon"
                                                    class="form-control form-control-sm"
                                                    value="0">
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label class="form-label">Stok</label>
                                                <input type="number" name="stok"
                                                    class="form-control form-control-sm"
                                                    value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button"
                                            class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal">Batal</button>
                                    <button type="submit"
                                            class="btn btn-success btn-sm">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    function formatRupiah(angka) {
        let numberString = angka.replace(/[^,\d]/g, '');
        let split = numberString.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return 'Rp ' + rupiah;
    }

    document.querySelectorAll('.rupiah').forEach(function (input) {

        // format saat mengetik
        input.addEventListener('input', function () {
            let angka = this.value.replace(/[^0-9]/g, '');
            this.value = formatRupiah(angka);

            // simpan angka murni ke hidden input
            this.nextElementSibling.value = angka;
        });

        // format awal (khusus edit)
        let hidden = input.nextElementSibling;
        if (hidden && hidden.value > 0) {
            input.value = formatRupiah(hidden.value);
        }
    });
});
</script>

<?= $this->endSection(); ?>


