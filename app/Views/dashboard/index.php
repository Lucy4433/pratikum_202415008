<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<div class="row mb-3">
    <div class="col-12"> <!--style header-->
        <h4>Dashboard Toko HP</h4>
        <small>
            Tampilan untuk Admin & Kasir<br>  <!--paragraf -->
            Selamat datang, 
            <strong><?= esc($namaUser ?? 'Pengguna'); ?></strong> <!--memaggil nama user-->
            (<?= esc($role ?? '-'); ?>) — <?= esc($tanggalHariIni ?? date('d-m-Y')); ?> <!--memaggil role user dan penentuan tgl-->
        </small>
    </div>
</div>

<!-- ====================== KARTU STATISTIK (3 KOLOM) ====================== -->
<div class="row mb-4">

    <!-- Merah: Pendapatan Bulanan -->
    <div class="col-md-4 mb-3">
        <div class="card h-100"
             style="background: linear-gradient(120deg, #ff9966, #ff5e62); border:none; color:#fff;"> <!---style kartu merah-->
            <div class="card-body"
                <p class="mb-1">Pendapatan Bulanan</p> 
                <h3 class="mb-3">
                    Rp <?= number_format($pendapatanBulanan ?? 0, 0, ',', '.'); ?> <!--Menampilkan angka pendapatan bulan-->
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
             style="background: linear-gradient(120deg, #4facfe, #00f2fe); border:none; color:#fff;">  <!---style kartu biru-->
            <div class="card-body">
                <p class="mb-1">Total Order (7 Hari Terakhir)</p> <!---paragraf atas--->
                <h3 class="mb-3">
                    <?= (int)($totalOrderMingguan ?? 0); ?> Order  <!--Menampilkan angka pendapatan mingguan-->
                </h3>
            <p class="mb-0">
                Jumlah transaksi yang terjadi dalam 7 hari terakhir.
            </p> <!--paragraf bawah-->
            </div>
        </div>
    </div>

    <!-- Hijau: Barang Laku 7 Hari Terakhir -->
    <div class="col-md-4 mb-3">
        <div class="card h-100"
             style="background: linear-gradient(120deg, #11998e, #38ef7d); border:none; color:#fff;">  <!---style kartu hijau-->
            <div class="card-body">
                <p class="mb-1">Barang Laku (7 Hari Terakhir)</p> <!---paragraf atas--->
                <h3 class="mb-3">
                    <?= (int)($barangLakuMingguan ?? 0); ?> Item <!--Menampilkan item barang mingguan--> 
                </h3>
                <p class="mb-0">
                    Total unit HP yang terjual dalam 7 hari terakhir.
                </p> <!--paragraf bawah-->
            </div>
        </div>
    </div>

</div>

<!-- ====================== TABEL LAPORAN TRANSAKSI ====================== -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"> <!---style header-->
                <strong>Laporan Transaksi Terbaru</strong> <!--Judul card/header-->
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle"> <!--style laporan transaksi-->
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Tanggal</th>
                                <th>No. Nota</th>
                                <th>Kasir</th>
                                <th>Total</th>
                                <th>Metode Bayar</th> <!--membuat judul kolom pada tabel-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php $laporanTransaksi = $laporanTransaksi ?? []; ?>
                            <?php if (!empty($laporanTransaksi)): ?> <!--cek data transaksi-->
                                <?php $no = 1; foreach ($laporanTransaksi as $row): ?>  <!--mulai memproses semua data transaksi untuk ditampilkan-->
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td> <!--tampilan nomor urut otomatis transaksi baru pertama-->
                                        <td>
                                            <?php
                                                $tgl = $row['tanggal_order'] ?? null; //tgl dari data bases
                                                echo $tgl ? date('d-m-Y H:i', strtotime($tgl)) : '-'; //tampilan format tgl
                                            ?>
                                        </td>
                                        <td><?= esc($row['no_penjualan'] ?? '-'); ?></td> <!--menampilkan nomor nota-->
                                        <td><?= esc($row['username'] ?? '-'); ?></td> <!--menampilkan user kasir-->
                                        <td>
                                            Rp <?= number_format($row['total'] ?? 0, 0, ',', '.'); ?> <!--tampilan format rupiah-->
                                        </td>
                                        <td><?= esc($row['metode_pembayaran'] ?? '-'); ?></td> <!--menampilkan metode pembayaran-->
                                    </tr>
                                <?php endforeach; ?> //tutup looping transaksi
                            <?php else: ?> <!--jika tidak ada data-->
                                <tr>
                                    <td colspan="6" class="text-center text-muted"> //pesan jika tabel kosong
                                        Belum ada data transaksi yang ditampilkan.  <!---pesan ini yang akan tampil jika data kosmg-->
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
