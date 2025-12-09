<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<?php
$items       = $items        ?? [];
$itemsJson   = $items_json   ?? json_encode([]);
$subtotal    = $subtotal     ?? 0;
$totalDiskon = $total_diskon ?? 0;
$total       = $total        ?? 0;

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
                        <th style="width:12%;">Diskon (%)</th>
                        <th style="width:20%;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $i => $it):
                            $qty    = (int)($it['qty']    ?? 0);
                            $harga  = (float)($it['harga'] ?? 0);
                            $diskon = (float)($it['diskon'] ?? 0);
                            $bruto  = $qty * $harga;
                            $potong = $bruto * ($diskon / 100);
                            $sub    = $bruto - $potong;
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
            <!-- KIRI: RINGKASAN & INPUT BAYAR -->
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

            <!-- KANAN: AKSI -->
            <div class="col-md-6 d-flex align-items-center">
                <div class="w-100">
                    <div class="text-center mb-2">
                        <strong>Aksi</strong>
                    </div>
                    <div class="d-flex flex-column gap-2 align-items-center">

                        <!-- SIMPAN TRANSAKSI -->
                        <button type="button"
                                class="btn btn-success"
                                id="btnSimpan"
                                style="min-width: 220px;">
                            Simpan
                        </button>

                        <!-- CETAK NOTA: SELALU AKTIF, CETAK ORDER TERAKHIR -->
                        <a href="<?= base_url('kasir/nota'); ?>"
                           target="_blank"
                           class="btn btn-info text-white"
                           style="min-width: 220px;">
                            Cetak Nota
                        </a>

                        <!-- KEMBALI -->
                        <a href="<?= base_url('kasir'); ?>"
                           class="btn btn-secondary"
                           id="btnKembali"
                           style="min-width: 220px;">
                            Kembali
                        </a>

                        <!-- BATAL -->
                        <button type="button"
                                class="btn btn-danger"
                                id="btnBatalBayar"
                                style="min-width: 220px;">
                            Batal
                        </button>

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
    // kirim nilai dari PHP ke JS global (dipakai di kasir_bayar.js)
    window.totalHarusBayar = <?= (float)$total; ?>;
    window.baseUrlKasir    = "<?= base_url('kasir'); ?>";
</script>
<script src="<?= base_url('assets/js/kasir_bayar.js'); ?>"></script>

<?= $this->endSection(); ?>
