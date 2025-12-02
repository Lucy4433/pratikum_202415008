<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<div class="row mb-3">
    <div class="col-12">
        <h4>Dashboard Toko HP</h4>
        <small>
            Tampilan untuk Admin & Kasir<br>
            Selamat datang,
            <strong><?= esc($namaUser ?? 'Pengguna'); ?></strong>
            (<?= esc($role ?? '-'); ?>) — <?= esc($tanggalHariIni ?? date('d-m-Y')); ?>
        </small>
    </div>
</div>

<!-- ====================== KARTU STATISTIK (3 KOLOM) ====================== -->
<div class="row mb-4">

    <!-- Merah: Pendapatan Bulanan -->
    <div class="col-md-4 mb-3">
        <div class="card h-100"
             style="background: linear-gradient(120deg, #ff9966, #ff5e62); border:none; color:#fff;">
            <div class="card-body">
                <p class="mb-1">Pendapatan Bulanan</p>
                <h3 class="mb-3">
                    Rp <?= number_format($pendapatanBulanan ?? 0, 0, ',', '.'); ?>
                </h3>
                <p class="mb-0">
                    Total omzet penjualan bulan ini.
                </p>
            </div>
        </div>
    </div>

    <!-- Biru: Total Order 7 Hari Terakhir -->
    <div class="col-md-4 mb-3">
        <div class="card h-100"
             style="background: linear-gradient(120deg, #4facfe, #00f2fe); border:none; color:#fff;">
            <div class="card-body">
                <p class="mb-1">Total Order (7 Hari Terakhir)</p>
                <h3 class="mb-3">
                    <?= (int)($totalOrderMingguan ?? 0); ?> Order
                </h3>
            <p class="mb-0">
                Jumlah transaksi yang terjadi dalam 7 hari terakhir.
            </p>
            </div>
        </div>
    </div>

    <!-- Hijau: Barang Laku 7 Hari Terakhir -->
    <div class="col-md-4 mb-3">
        <div class="card h-100"
             style="background: linear-gradient(120deg, #11998e, #38ef7d); border:none; color:#fff;">
            <div class="card-body">
                <p class="mb-1">Barang Laku (7 Hari Terakhir)</p>
                <h3 class="mb-3">
                    <?= (int)($barangLakuMingguan ?? 0); ?> Item
                </h3>
                <p class="mb-0">
                    Total unit HP yang terjual dalam 7 hari terakhir.
                </p>
            </div>
        </div>
    </div>

</div>

<!-- ====================== TABEL LAPORAN TRANSAKSI ====================== -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <strong>Laporan Transaksi Terbaru</strong>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Tanggal</th>
                                <th>No. Nota</th>
                                <th>Kasir</th>
                                <th>Total</th>
                                <th>Metode Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $laporanTransaksi = $laporanTransaksi ?? []; ?>
                            <?php if (!empty($laporanTransaksi)): ?>
                                <?php $no = 1; foreach ($laporanTransaksi as $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td>
                                            <?php
                                                $tgl = $row['tanggal_order'] ?? null;
                                                echo $tgl ? date('d-m-Y H:i', strtotime($tgl)) : '-';
                                            ?>
                                        </td>
                                        <td><?= esc($row['no_penjualan'] ?? '-'); ?></td>
                                        <td><?= esc($row['username'] ?? '-'); ?></td>
                                        <td>
                                            Rp <?= number_format($row['total'] ?? 0, 0, ',', '.'); ?>
                                        </td>
                                        <td><?= esc($row['metode_pembayaran'] ?? '-'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Belum ada data transaksi yang ditampilkan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
