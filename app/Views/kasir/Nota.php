<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Penjualan</title>
    <style>
        /* Ukuran nota thermal ± 80mm */
        @page {
            size: auto;
            margin: 0;
        }

        body {
            font-family: "Courier New", monospace;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .nota-container {
            width: 80mm; /* ✅ lebar nota */
            margin: 0 auto;
            padding: 8px;
            box-sizing: border-box;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 4px 0;
        }

        .bold {
            font-weight: bold;
        }

        .small {
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
        }

        .item-name {
            max-width: 100px;
            word-wrap: break-word;
        }
    </style>
</head>
<body onload="window.print()">

<?php
    // siapkan data aman
    $order      = $order      ?? null;
    $detail     = $detail     ?? [];
    $pembayaran = $pembayaran ?? null;

    $noNota   = $order->no_penjualan ?? '-';
    $tglOrder = $order->tanggal_order ?? date('Y-m-d H:i:s');
    $tglTeks  = date('d-m-Y H:i', strtotime($tglOrder));

    $namaKasir = $order->username ?? 'Kasir';

    // hitung subtotal (sebelum diskon) dari detail
    $subtotal = 0;
    foreach ($detail as $d) {
        $qty   = (int) ($d->jumlah_beli ?? 0);
        $harga = (float) ($d->harga_satuan ?? 0);
        $subtotal += $qty * $harga;
    }

    $total       = (float) ($order->total ?? 0);
    $totalDiskon = $subtotal - $total;
    if ($totalDiskon < 0) {
        $totalDiskon = 0;
    }

    // kalau ada kolom bayar & kembali di tabel pembayaran, pakai itu
    $bayar    = isset($pembayaran->bayar)    ? (float) $pembayaran->bayar    : $total;
    $kembali  = isset($pembayaran->kembali)  ? (float) $pembayaran->kembali  : 0;
    $metode   = $pembayaran->metode_pembayaran ?? '-';
?>

<div class="nota-container">

    <!-- HEADER TOKO -->
    <div class="text-center">
        <div class="bold">TOKO HP JAYAPURA</div>
        <div class="small">Jl. Cenderawasih No. 123, Jayapura</div>
        <div class="small">Telp: 08xx-xxxx-xxxx</div>
    </div>

    <div class="line"></div>

    <!-- INFO TRANSAKSI -->
    <table>
        <tr>
            <td class="text-left">No. Nota</td>
            <td class="text-right">: <?= esc($noNota); ?></td>
        </tr>
        <tr>
            <td class="text-left">Tanggal</td>
            <td class="text-right">: <?= esc($tglTeks); ?></td>
        </tr>
        <tr>
            <td class="text-left">Kasir</td>
            <td class="text-right">: <?= esc($namaKasir); ?></td>
        </tr>
        <tr>
            <td class="text-left">Metode</td>
            <td class="text-right">: <?= esc($metode); ?></td>
        </tr>
    </table>

    <div class="line"></div>

    <!-- ITEM -->
    <table>
        <tr>
            <td class="bold">No</td>
            <td class="bold">Nama Barang</td>
            <td class="bold text-center">Qty</td>
            <td class="bold text-right">Harga</td>
            <td class="bold text-right">Sub</td>
        </tr>
        <?php if (!empty($detail)): ?>
            <?php $no = 1; ?>
            <?php foreach ($detail as $d): ?>
                <?php
                    $nama  = $d->nama_produk ?? 'Produk';
                    $qty   = (int) ($d->jumlah_beli ?? 0);
                    $harga = (float) ($d->harga_satuan ?? 0);
                    $sub   = $qty * $harga;
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td class="item-name"><?= esc($nama); ?></td>
                    <td class="text-center"><?= $qty; ?></td>
                    <td class="text-right"><?= number_format($harga, 0, ',', '.'); ?></td>
                    <td class="text-right"><?= number_format($sub, 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">Tidak ada item.</td>
            </tr>
        <?php endif; ?>
    </table>

    <div class="line"></div>

    <!-- RINGKASAN -->
    <table>
        <tr>
            <td class="text-left">Subtotal</td>
            <td class="text-right">
                <?= 'Rp ' . number_format($subtotal, 0, ',', '.'); ?>
            </td>
        </tr>
        <tr>
            <td class="text-left">Total Diskon</td>
            <td class="text-right">
                <?= 'Rp ' . number_format($totalDiskon, 0, ',', '.'); ?>
            </td>
        </tr>
        <tr>
            <td class="text-left bold">Total</td>
            <td class="text-right bold">
                <?= 'Rp ' . number_format($total, 0, ',', '.'); ?>
            </td>
        </tr>
        <tr>
            <td class="text-left">Bayar</td>
            <td class="text-right">
                <?= 'Rp ' . number_format($bayar, 0, ',', '.'); ?>
            </td>
        </tr>
        <tr>
            <td class="text-left">Kembalian</td>
            <td class="text-right">
                <?= 'Rp ' . number_format($kembali, 0, ',', '.'); ?>
            </td>
        </tr>
    </table>

    <div class="line"></div>

    <!-- FOOTER -->
    <div class="text-center">
        <div>Terima kasih telah berbelanja!</div>
        <div>Semoga datang kembali 😊</div>
    </div>

</div>

</body>
</html>

<?= $this->endSection(); ?>