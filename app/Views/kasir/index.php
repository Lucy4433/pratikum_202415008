<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<?php
$jsProduk = [];
if (!empty($produk)) {
    foreach ($produk as $p) {
        $jsProduk[] = [
            'id'    => (int) $p->id_produk, //megambil id_produk
            'nama'  => $p->nama_produk, //megambil nama produk
            'harga' => (int) $p->harga, // harga jual produk
            'stok'  => (int) $p->stok,   // ambil stokyg tersedia
            'diskon' => (int) ($p->besaran_discount ?? 0) //kirim diskon aktif ke JS/ambil dsikon yang aktif
        ];
    }
}

$session        = session(); //data user kasir
$namaKasir      = $session->get('username') ?? 'Kasir'; //menampilkan nama user kasir
$tanggalHariIni = date('d-m-Y');//tampil tgl
?>

<!-- =================== BARIS ATAS: KODE / KASIR / TANGGAL + CARI PRODUK =================== -->
<div class="row mb-3">

    <!-- KODE / KASIR / TANGGAL -->
    <div class="col-lg-6 mb-3 mb-lg-0">
        <div class="card">
            <div class="card-body py-3">
                <div>
                    <strong>Kode Transaksi :</strong>
                    <span id="kode-transaksi" class="text-primary">
                        Belum ada (tambahkan produk)
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
                <label class="form-label mb-1">
                    <i class="typcn typcn-zoom-outline"></i> Cari Produk :
                </label>
                <div class="input-group">
                    <input type="text"
                           id="input-cari"
                           class="form-control"
                           placeholder="Ketik nama produk di sini"
                           list="listProduk"
                           autocomplete="off">
                    <button class="btn btn-primary" type="button" id="btnTambahDariCari">
                        Tambah
                    </button>
                </div>

                <datalist id="listProduk">
                    <?php if (!empty($produk)): ?> <!-- cek produk-->
                        <?php foreach ($produk as $p): ?> <!--megambil setiap object dari daftar produk per item-->
                            <option value="<?= esc($p->nama_produk); ?>"></option> <!--tampilan nama produk-->
                        <?php endforeach; ?>
                    <?php endif; ?>
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
                    <th style="width:6%;">No</th>
                    <th>Produk</th>
                    <th style="width:10%;">Qty</th>
                    <th style="width:18%;">Harga</th>
                    <th style="width:12%;">Diskon (%)</th>
                    <th style="width:18%;">Subtotal</th>
                    <th style="width:10%;">Aksi</th>
                </tr>
                <tbody id="cart-body">
                <!-- baris item diisi oleh JS -->
            </tbody>
            </thead>
        </table>
            <!-- tombol manual kosong (tidak terikat stok) -->
        <button type="button" class="btn btn-outline-primary btn-sm" id="btnTambahManualKosong">
            + Tambah Produk
        </button>
    </div>
</div>

<!-- =================== RINGKASAN & TOMBOL BAYAR/BATAL =================== -->
<div class="card">
    <div class="card-body">
        <div class="row">

            <!-- RINGKASAN -->
            <div class="col-md-6 mb-3 mb-md-0">
                <h5 class="mb-3">RINGKASAN TRANSAKSI</h5>
                <div class="row mb-1">
                    <div class="col-6 col-sm-4">Subtotal</div>
                    <div class="col-6 col-sm-8 text-end" id="txt-subtotal">Rp 0</div>
                </div>
                <div class="row mb-1">
                    <div class="col-6 col-sm-4">Total Diskon</div>
                    <div class="col-6 col-sm-8 text-end" id="txt-total-diskon">Rp 0</div>
                </div>
                <div class="row mb-1">
                    <div class="col-6 col-sm-4 fw-bold">Total</div>
                    <div class="col-6 col-sm-8 text-end fw-bold text-success" id="txt-total">Rp 0</div>
                </div>
            </div>

            <!-- AKSI PEMBAYARAN: BAYAR & BATAL -->
            <div class="col-md-6 d-flex flex-column align-items-md-end">
                <div class="w-100" style="max-width: 260px;">
                    <button type="button" class="btn btn-success w-100 mb-2" id="btnBayar">
                        Bayar
                    </button>
                    <button type="button" class="btn btn-secondary w-100" id="btnBatal">
                        Batal
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- =================== FORM HIDDEN UNTUK KIRIM KE HALAMAN BAYAR =================== -->
<form id="formKasir" method="post" action="<?= base_url('kasir/bayar'); ?>">
    <?= csrf_field(); ?>
    <input type="hidden" name="no_penjualan"  id="input-no-penjualan">
    <input type="hidden" name="items"         id="input-items">
    <input type="hidden" name="subtotal"      id="input-subtotal">
    <input type="hidden" name="total_diskon"  id="input-total-diskon">
    <input type="hidden" name="grand_total"   id="input-grand-total">
</form>
<!--data meyimpan transaksi untuk tombol bayar-->

<!-- Kirim data produk dari PHP ke JS -->
<script>
    window.PRODUCTS = <?= json_encode($jsProduk); ?>;
</script>

<!-- File JS utama kasir -->
<script src="<?= base_url('assets/js/kasir.js'); ?>"></script>

<?= $this->endSection(); ?>


