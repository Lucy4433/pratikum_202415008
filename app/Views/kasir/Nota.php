<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Penjualan</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: "Courier New", monospace;
            font-size: 11px;
            margin: 0;
            padding: 8px;
        }
        .nota {
            width: 80mm; /* kira-kira lebar kertas thermal */
            margin: 0 auto;
        }
        .center {
            text-align: center;
        }
        .right {
            text-align: right;
        }
        .line {
            border-top: 1px dashed #000;
            margin: 4px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .small {
            font-size: 10px;
        }
    </style>
</head>
<body onload="window.print();">

<?php
$namaToko   = $profilToko->nama_toko ?? 'PHONE STORE';
$alamatToko = $profilToko->alamat     ?? 'Alamat Toko';
$noTelp     = $profilToko->no_telp    ?? '';
?>

<div class="nota">
    <!-- HEADER TOKO -->
    <div class="center">
        <strong><?= esc($namaToko); ?></strong><br>
        <span class="small">
            <?= esc($alamatToko); ?><br>
            <?= $noTelp ? 'Telp: ' . esc($noTelp) : ''; ?>
        </span>
    </div>

    <div class="line"></div>

    <!-- INFO TRANSAKSI -->
    <table class="small">
        <tr>
            <td>No. Nota</td>
            <td>:</td>
            <td><?= esc($order->no_penjualan ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>
                <?php
                    $tgl = $order->tanggal_order ?? date('Y-m-d H:i:s');
                    echo date('d-m-Y H:i', strtotime($tgl));
                ?>
            </td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td>:</td>
            <td><?= esc($order->nama_kasir ?? (session('username') ?? 'Kasir')); ?></td>
        </tr>
    </table>

    <div class="line"></div>

    <!-- DETAIL ITEM -->
    <table class="small">
        <tbody>
        <?php foreach ($detail as $row): ?>
            <tr>
                <!-- Nama barang di baris sendiri -->
                <td colspan="3">
                    <?= esc($row['nama_produk'] ?? $row['nama'] ?? ''); ?>
                </td>
            </tr>
            <tr>
                <td class="small">
                    <?= (int)($row['jumlah_beli'] ?? 0); ?> x
                    Rp <?= number_format($row['harga_satuan'] ?? 0, 0, ',', '.'); ?>
                </td>
                <td></td>
                <td class="right">
                    Rp <?= number_format($row['subtotal'] ?? (($row['jumlah_beli'] ?? 0) * ($row['harga_satuan'] ?? 0)), 0, ',', '.'); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="line"></div>

    <!-- RINGKASAN TOTAL -->
    <?php
    $subtotal = $order->subtotal ?? $order->total ?? 0;
    $diskon   = $order->total_diskon ?? 0;
    $total    = $order->total ?? ($subtotal - $diskon);

    $bayar    = $pembayaran->jumlah_bayar ?? $total;
    $kembali  = $pembayaran->kembalian    ?? ($bayar - $total);
    ?>

    <table class="small">
        <tr>
            <td>Subtotal</td>
            <td class="right">
                Rp <?= number_format($subtotal, 0, ',', '.'); ?>
            </td>
        </tr>
        <?php if ($diskon > 0): ?>
        <tr>
            <td>Diskon</td>
            <td class="right">
                - Rp <?= number_format($diskon, 0, ',', '.'); ?>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td><strong>TOTAL</strong></td>
            <td class="right">
                <strong>Rp <?= number_format($total, 0, ',', '.'); ?></strong>
            </td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td class="right">
                Rp <?= number_format($bayar, 0, ',', '.'); ?>
            </td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td class="right">
                Rp <?= number_format($kembali, 0, ',', '.'); ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                Metode: <?= esc($pembayaran->metode_pembayaran ?? '-'); ?>
            </td>
        </tr>
    </table>

    <div class="line"></div>

    <!-- FOOTER -->
    <div class="center small">
        Terima kasih atas kunjungan Anda<br>
        Barang yang sudah dibeli<br>
        tidak dapat dikembalikan.
    </div>
</div>

</body>
</html>
