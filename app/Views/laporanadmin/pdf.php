<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            letter-spacing: 1px;
        }

        .header p {
            margin: 3px 0;
            font-size: 11px;
        }

        .info {
            margin-bottom: 15px;
        }

        .info table {
            width: 100%;
            font-size: 11px;
        }

        .info td {
            padding: 3px 0;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.report th {
            background-color: #f5f5f5;
            border: 1px solid #000;
            padding: 6px;
            font-size: 11px;
            text-align: center;
        }

        table.report td {
            border: 1px solid #000;
            padding: 5px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .total {
            margin-top: 10px;
            width: 100%;
        }

        .total td {
            padding: 6px;
            font-weight: bold;
            border: 1px solid #000;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <h2>LAPORAN TRANSAKSI PENJUALAN</h2>
    <p>Sistem Informasi Penjualan</p>
    <p>Dicetak oleh Admin</p>
</div>

<!-- INFO -->
<div class="info">
    <table>
        <tr>
            <td width="15%">Tanggal Cetak</td>
            <td width="2%">:</td>
            <td><?= $tanggal ?></td>
        </tr>
    </table>
</div>

<!-- TABEL LAPORAN -->
<table class="report">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="15%">Tanggal</th>
            <th width="20%">No Nota</th>
            <th width="15%">Kasir</th>
            <th width="15%">Metode</th>
            <th width="15%">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1; 
        $grandTotal = 0;
        foreach ($laporan as $row): 
            $grandTotal += $row['total'];
        ?>
        <tr>
            <td class="text-center"><?= $no++ ?></td>
            <td class="text-center"><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
            <td class="text-center"><?= $row['no_nota'] ?></td>
            <td><?= $row['kasir'] ?></td>
            <td class="text-center"><?= $row['metode'] ?></td>
            <td class="text-right">
                Rp <?= number_format($row['total'], 0, ',', '.') ?>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<!-- TOTAL -->
<table class="total">
    <tr>
        <td width="85%" class="text-right">TOTAL PENDAPATAN</td>
        <td width="15%" class="text-right">
            Rp <?= number_format($grandTotal, 0, ',', '.') ?>
        </td>
    </tr>
</table>

<!-- FOOTER -->
<div class="footer">
    Laporan ini dihasilkan secara otomatis oleh sistem | Halaman {PAGE_NUM} dari {PAGE_COUNT}
</div>

</body>
</html>
