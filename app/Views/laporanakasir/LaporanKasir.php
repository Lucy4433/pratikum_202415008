<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<div class="row mb-3">
    <div class="col-md-8">
        <h4>Laporan Penjualan (Kasir)</h4>
        <small>
            Kasir: <strong><?= esc($namaUser ?? 'Kasir'); ?></strong>
            (<?= esc($role ?? 'Kasir'); ?>) — <?= esc($tanggalHariIni ?? date('d-m-Y')); ?>
        </small>
    </div>
    <div class="col-md-4 text-md-end mt-2 mt-md-0">
        <a href="<?= base_url('laporan/kasir/pdf'); ?>" 
           class="btn btn-sm btn-danger" target="_blank">
            <i class="fa fa-file-pdf-o"></i> Export PDF
        </a>
    </div>
</div>

<!-- ====================== KARTU STATISTIK (3 KOLOM) ====================== -->
<div class="row mb-4">

    <!-- Pendapatan Bulanan (Kasir) -->
    <div class="col-md-4 mb-3">
        <div class="card h-100" 
             style="background: linear-gradient(120deg, #ff9966, #ff5e62); border:none; color:#fff;">
            <div class="card-body">
                <p class="mb-1">Pendapatan Bulanan</p>
                <h3 class="mb-3">
                    Rp <?= number_format($pendapatanBulanan ?? 0, 0, ',', '.'); ?>
                </h3>
                <p class="mb-0">Total omzet yang dicatat kasir ini di bulan ini.</p>
            </div>
        </div>
    </div>

    <!-- Total Order 7 Hari Terakhir -->
    <div class="col-md-4 mb-3">
        <div class="card h-100" 
             style="background: linear-gradient(120deg, #4facfe, #00f2fe); border:none; color:#fff;">
            <div class="card-body">
                <p class="mb-1">Total Order (7 Hari Terakhir)</p>
                <h3 class="mb-3">
                    <?= (int)($totalOrderMingguan ?? 0); ?> Order
                </h3>
                <p class="mb-0">Jumlah transaksi yang dilakukan kasir ini.</p>
            </div>
        </div>
    </div>

    <!-- Barang Laku 7 Hari Terakhir -->
    <div class="col-md-4 mb-3">
        <div class="card h-100" 
             style="background: linear-gradient(120deg, #11998e, #38ef7d); border:none; color:#fff;">
            <div class="card-body">
                <p class="mb-1">Barang Laku (7 Hari Terakhir)</p>
                <h3 class="mb-3">
                    <?= (int)($barangLakuMingguan ?? 0); ?> Item
                </h3>
                <p class="mb-0">Unit HP yang terjual oleh kasir ini.</p>
            </div>
        </div>
    </div>

</div>

<!-- ====================== TABEL TRANSAKSI KASIR ====================== -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <strong>Laporan Transaksi Terbaru (Kasir Ini)</strong>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Tanggal</th>
                                <th>No. Nota</th>
                                <th>Total</th>
                                <th>Metode Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($laporanTransaksi ?? [])): ?>
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
                                        <td>Rp <?= number_format($row['total'] ?? 0, 0, ',', '.'); ?></td>
                                        <td><?= esc($row['metode_pembayaran'] ?? '-'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Belum ada data transaksi yang ditampilkan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- tidak ada tombol edit / hapus -->
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
