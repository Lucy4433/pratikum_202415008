<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<?php
$jsProduk = [];

if (!empty($produk)) {
    foreach ($produk as $p) {
        $jsProduk[] = [
            'id'     => (int) $p->id_produk,
            'nama'   => $p->nama_produk,
            'harga'  => (int) $p->harga_jual, // ✅ FIX
            'stok'   => (int) $p->stok,
            'diskon' => (int) ($p->besaran_discount ?? 0)
        ];
    }
}

$session        = session();
$namaKasir      = $session->get('username') ?? 'Kasir';
$tanggalHariIni = date('d-m-Y');
?>

<!-- =================== BARIS ATAS =================== -->
<div class="row mb-3">

    <div class="col-lg-6 mb-3 mb-lg-0">
        <div class="card">
            <div class="card-body py-3">
                <div>
                    <strong>Kode Transaksi :</strong>
                    <span id="kode-transaksi" class="text-primary">
                        -
                    </span>
                </div>
                <div><strong>Kasir :</strong> <?= esc($namaKasir); ?></div>
                <div><strong>Tanggal :</strong> <?= esc($tanggalHariIni); ?></div>
            </div>
        </div>
    </div>

    <!-- CARI PRODUK -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body py-3">
                <label class="form-label mb-1">Cari Produk</label>
                <div class="input-group">
                    <input type="text"
                        id="input-cari"
                        class="form-control"
                        placeholder="Ketik nama produk"
                        list="listProduk"
                        autocomplete="off">
                    <button class="btn btn-primary" type="button" id="btnTambahDariCari">
                        Tambah
                    </button>
                </div>

                <datalist id="listProduk">
                    <?php foreach ($produk as $p): ?>
                        <option value="<?= esc($p->nama_produk); ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>
        </div>
    </div>

</div>

<!-- =================== TABEL KERANJANG =================== -->
<div class="card mb-3">
    <div class="card-body">
        <table class="table table-bordered table-hover mb-2">
            <thead class="table-dark text-center">
                <tr>
                    <th width="5%">No</th>
                    <th>Produk</th>
                    <th width="10%">Qty</th>
                    <th width="15%">Harga</th>
                    <th width="10%">Diskon</th>
                    <th width="15%">Subtotal</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody id="cart-body">
                <!-- diisi oleh JS -->
            </tbody>
        </table>

        <button type="button" class="btn btn-outline-primary btn-sm" id="btnTambahManualKosong">
            + Tambah Produk Manual
        </button>
    </div>
</div>

<!-- =================== RINGKASAN =================== -->
<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-md-6">
                <h5>Ringkasan Transaksi</h5>
                <div class="d-flex justify-content-between">
                    <span>Subtotal</span>
                    <span id="txt-subtotal">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Total Diskon</span>
                    <span id="txt-total-diskon">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between fw-bold text-success">
                    <span>Total</span>
                    <span id="txt-total">Rp 0</span>
                </div>
            </div>

            <div class="col-md-6 text-end">
                <button class="btn btn-success w-100 mb-2" id="btnBayar">
                    Bayar
                </button>
                <button class="btn btn-secondary w-100" id="btnBatal">
                    Batal
                </button>
            </div>

        </div>
    </div>
</div>

<!-- =================== FORM HIDDEN =================== -->
<form id="formKasir" method="post" action="<?= base_url('kasir/bayar'); ?>">
    <?= csrf_field(); ?>
    <input type="hidden" name="items" id="input-items">
    <input type="hidden" name="subtotal" id="input-subtotal">
    <input type="hidden" name="total_diskon" id="input-total-diskon">
    <input type="hidden" name="grand_total" id="input-grand-total">
</form>

<script>
    window.PRODUCTS = <?= json_encode(array_map(function ($p) {
                            return [
                                'id'     => $p->id_produk,
                                'nama'   => $p->nama_produk,
                                'harga'  => (int) $p->harga_jual, 
                                'stok'   => (int) $p->stok,
                                'diskon' => 0
                            ];
                        }, $produk)) ?>;
</script>


<script src="<?= base_url('assets/js/kasir.js'); ?>"></script>

<?= $this->endSection(); ?>