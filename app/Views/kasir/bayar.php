<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<?php
$items        = $items        ?? [];
$itemsJson    = $items_json   ?? json_encode([]);
$subtotal     = $subtotal     ?? 0;
$totalDiskon  = $total_diskon ?? 0;
$total        = $total        ?? 0;

$session   = session();
$namaKasir = $session->get('username') ?? 'Kasir';
$tanggal   = date('d-m-Y');
?>

<div class="card mb-3">
    <div class="card-header">
        <h4 class="mb-0">Pembayaran</h4>
    </div>
    <div class="card-body">

        <!-- INFO KASIR & TANGGAL -->
        <div class="row mb-3">
            <div class="col-md-4">
                <strong>Kasir :</strong> <?= esc($namaKasir); ?>
            </div>
            <div class="col-md-4">
                <strong>Tanggal :</strong> <?= esc($tanggal); ?>
            </div>
            <div class="col-md-4 text-md-end text-muted">
                <small>Pastikan nominal bayar dan kembalian sudah benar.</small>
            </div>
        </div>

        <!-- RINGKASAN ITEM -->
        <h5 class="mb-2">Ringkasan Item yang Dibeli</h5>
        <div class="table-responsive mb-3">
            <table class="table table-sm table-bordered table-hover mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width:6%;">No</th>
                        <th>Nama Produk</th>
                        <th style="width:10%;">Qty</th>
                        <th style="width:18%;">Harga</th>
                        <th style="width:12%;">Diskon (%)</th>   <!-- ✅ kolom diskon -->
                        <th style="width:20%;">Subtotal</th>     <!-- subtotal setelah diskon -->
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $i => $it): 
                            $qty    = (int)($it['qty']    ?? 0);
                            $harga  = (float)($it['harga'] ?? 0);
                            $diskon = (float)($it['diskon'] ?? 0);  // ✅ ambil diskon dari item
                            $bruto  = $qty * $harga;
                            $potong = $bruto * ($diskon / 100);
                            $sub    = $bruto - $potong;            // ✅ subtotal setelah diskon
                            $nama   = $it['nama'] ?? 'Produk manual';
                        ?>
                        <tr>
                            <td class="text-center"><?= $i + 1; ?></td>
                            <td><?= esc($nama); ?></td>
                            <td class="text-center"><?= $qty; ?></td>
                            <td class="text-end">
                                Rp <?= number_format($harga, 0, ',', '.'); ?>
                            </td>
                            <td class="text-center">
                                <?= $diskon; ?>%
                            </td>
                            <td class="text-end">
                                Rp <?= number_format($sub, 0, ',', '.'); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="5" class="text-end fw-bold">TOTAL BAYAR</td>
                            <td class="text-end fw-bold text-success">
                                Rp <?= number_format($total, 0, ',', '.'); ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Tidak ada item.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- RINGKASAN TRANSAKSI & PEMBAYARAN -->
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <h5 class="mb-3">Ringkasan Transaksi & Pembayaran</h5>

                <div class="row mb-1">
                    <div class="col-6 col-sm-5">Subtotal</div>
                    <div class="col-6 col-sm-7 text-end">
                        Rp <?= number_format($subtotal, 0, ',', '.'); ?>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-6 col-sm-5">Total Diskon</div>
                    <div class="col-6 col-sm-7 text-end">
                        Rp <?= number_format($totalDiskon, 0, ',', '.'); ?>
                    </div>
                </div>
                <hr class="my-2">
                <div class="row mb-2">
                    <div class="col-6 col-sm-5 fw-bold">Total Dibayar</div>
                    <div class="col-6 col-sm-7 text-end fw-bold text-success">
                        Rp <?= number_format($total, 0, ',', '.'); ?>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label">Metode Pembayaran</label>
                    <div class="d-flex gap-2">
                        <button type="button"
                                class="btn btn-outline-primary btn-sm flex-fill metode-btn active"
                                data-metode="CASH">
                            CASH
                        </button>
                        <button type="button"
                                class="btn btn-outline-primary btn-sm flex-fill metode-btn"
                                data-metode="TRANSFER">
                            TRANSFER
                        </button>
                    </div>
                    <input type="hidden" id="metode" value="CASH">
                </div>

                <div class="mb-2">
                    <label class="form-label">Nominal Bayar</label>
                    <input type="text"
                           id="input-bayar"
                           class="form-control"
                           value="<?= number_format($total, 0, ',', '.'); ?>">
                </div>

                <div class="mb-2">
                    <label class="form-label">Kembalian</label>
                    <input type="text"
                           id="input-kembalian"
                           class="form-control"
                           value="Rp 0"
                           readonly>
                </div>
            </div>

            <!-- AKSI (TENGAH & TOMBOL LEBAR) -->
            <div class="col-md-6 d-flex align-items-center">
                <div class="w-100">
                    <div class="text-center mb-2">
                        <strong>Aksi</strong>
                    </div>
                    <div class="d-flex flex-column gap-2 align-items-center">
                        <button type="button"
                                class="btn btn-success"
                                id="btnSimpan"
                                style="min-width: 220px;">
                            Simpan Transaksi
                        </button>

                        <!-- CETAK NOTA: khusus cetak tampilan halaman bayar -->
                        <button type="button"
                                class="btn btn-info text-white"
                                id="btnCetak"
                                style="min-width: 220px;">
                            Cetak Nota
                        </button>

                        <a href="<?= base_url('kasir'); ?>"
                           class="btn btn-secondary"
                           style="min-width: 220px;">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- FORM HIDDEN UNTUK SIMPAN KE DATABASE -->
<form id="formBayar" method="post" action="<?= base_url('kasir/simpan'); ?>">
    <?= csrf_field(); ?>
    <input type="hidden" name="items"        value='<?= esc($itemsJson); ?>'>
    <input type="hidden" name="subtotal"     value="<?= (float)$subtotal; ?>">
    <input type="hidden" name="total_diskon" value="<?= (float)$totalDiskon; ?>">
    <input type="hidden" name="grand_total"  value="<?= (float)$total; ?>">
    <input type="hidden" name="metode"       id="input-metode-hidden"  value="CASH">
    <input type="hidden" name="bayar"        id="input-bayar-hidden"   value="<?= (float)$total; ?>">
    <input type="hidden" name="kembali"      id="input-kembali-hidden" value="0">
</form>

<script>
    // tombol metode pembayaran
    const metodeButtons = document.querySelectorAll('.metode-btn');
    const metodeHidden  = document.getElementById('metode');
    const metodeInputH  = document.getElementById('input-metode-hidden');

    const inputBayar    = document.getElementById('input-bayar');
    const inputKembali  = document.getElementById('input-kembalian');

    const totalHarusBayar = <?= (float)$total; ?>;

    const formBayar    = document.getElementById('formBayar');
    const bayarHidden  = document.getElementById('input-bayar-hidden');
    const kembaliHidden= document.getElementById('input-kembali-hidden');

    function formatRupiah(num) {
        num = Number(num) || 0;
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    function parseNumber(str) {
        if (!str) return 0;
        return Number(str.replace(/\D/g, '')) || 0;
    }

    // set default
    inputBayar.value   = formatRupiah(totalHarusBayar);
    inputKembali.value = formatRupiah(0);

    // ganti metode
    metodeButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            metodeButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const m = this.getAttribute('data-metode');
            metodeHidden.value = m;
            metodeInputH.value = m;
        });
    });

    // hitung kembalian saat nominal bayar berubah
    inputBayar.addEventListener('input', function () {
        const nilai = parseNumber(this.value);
        this.value  = nilai ? formatRupiah(nilai) : '';

        const kembali = nilai - totalHarusBayar;
        inputKembali.value = formatRupiah(kembali > 0 ? kembali : 0);
    });

    // SIMPAN TRANSAKSI
    document.getElementById('btnSimpan').addEventListener('click', function () {
        const nilaiBayar = parseNumber(inputBayar.value);
        if (nilaiBayar < totalHarusBayar) {
            alert('Nominal bayar kurang dari total yang harus dibayar.');
            return;
        }

        const kembali = nilaiBayar - totalHarusBayar;

        bayarHidden.value   = nilaiBayar;
        kembaliHidden.value = kembali > 0 ? kembali : 0;

        if (!confirm('Simpan transaksi ini?')) {
            return;
        }

        formBayar.submit();
    });
    document.getElementById('btnCetak').addEventListener('click', function () {
    window.print();
});

</script>

<?= $this->endSection(); ?>
