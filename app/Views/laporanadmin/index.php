<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<div class="row mb-3">
    <div class="col-md-8">
        <h4>Laporan Penjualan (Admin)</h4>
        <small>
            Selamat datang, <strong><?= esc($namaUser ?? 'Admin'); ?></strong>
            (<?= esc($role ?? 'Admin'); ?>) — <?= esc($tanggalHariIni ?? date('d-m-Y')); ?>
        </small>
    </div>
    <div class="col-md-4 text-md-end mt-2 mt-md-0">
        <a href="<?= base_url('laporan/admin/pdf'); ?>" 
           class="btn btn-sm btn-danger" target="_blank">
            <i class="fa fa-file-pdf-o"></i> Export PDF
        </a>
    </div>
</div>

<!-- ====================== KARTU STATISTIK (ADMIN) ====================== -->
<div class="row mb-4">

    <!-- Pendapatan Bulanan -->
    <div class="col-md-4 mb-3">
        <div class="card h-100" 
             style="background: linear-gradient(120deg, #ff9966, #ff5e62); border:none; color:#fff;">
            <div class="card-body">
                <p class="mb-1">Pendapatan Bulanan (Semua Kasir)</p>
                <h3 class="mb-3">
                    Rp <?= number_format($pendapatanBulanan ?? 0, 0, ',', '.'); ?>
                </h3>
                <p class="mb-0">Total omzet penjualan bulan ini.</p>
            </div>
        </div>
    </div>

    <!-- Total Order Mingguan -->
    <div class="col-md-4 mb-3">
        <div class="card h-100" 
             style="background: linear-gradient(120deg, #4facfe, #00f2fe); border:none; color:#fff;">
            <div class="card-body">
                <p class="mb-1">Total Order (7 Hari Terakhir)</p>
                <h3 class="mb-3">
                    <?= esc($totalOrderMingguan ?? 0); ?> Order
                </h3>
                <p class="mb-0">Jumlah transaksi dari seluruh kasir.</p>
            </div>
        </div>
    </div>

    <!-- Barang Laku Mingguan -->
    <div class="col-md-4 mb-3">
        <div class="card h-100" 
             style="background: linear-gradient(120deg, #11998e, #38ef7d); border:none; color:#fff;">
            <div class="card-body">
                <p class="mb-1">Barang Laku (7 Hari Terakhir)</p>
                <h3 class="mb-3">
                    <?= esc($barangLakuMingguan ?? 0); ?> Item
                </h3>
                <p class="mb-0">Total unit HP terjual semua kasir.</p>
            </div>
        </div>
    </div>

</div>

<!-- ====================== TABEL LAPORAN TRANSAKSI (ADMIN) ====================== -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <strong>Laporan Transaksi Terbaru (Semua Kasir)</strong>
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
                            <?php if (!empty($laporanTransaksi ?? [])): ?>  
                                <?php $no = 1; foreach ($laporanTransaksi as $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td>
                                            <?php
                                                $tgl = $row['tanggal'] ?? null;
                                                echo $tgl ? date('d-m-Y H:i', strtotime($tgl)) : '-';
                                            ?>
                                        </td>
                                        <td><?= esc($row['no_nota'] ?? '-'); ?></td>
                                        <td><?= esc($row['kasir'] ?? '-'); ?></td>
                                        <td>Rp <?= number_format($row['total'] ?? 0, 0, ',', '.'); ?></td>
                                        <td><?= esc($row['metode'] ?? '-'); ?></td>
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
