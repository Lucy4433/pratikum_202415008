<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<?php
// supaya bisa baca nilai GET tanpa $this->request
$req           = service('request');
$tanggalFilter = $tanggal ?? $req->getGet('tanggal'); // dikirim dari controller
$qFilter       = $q       ?? $req->getGet('q');
?>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Riwayat Transaksi</h4>
    </div>

    <div class="card-body">

        <!-- =================== FORM PENCARIAN =================== -->
        <form class="row g-3 mb-3" method="get" action="<?= base_url('riwayattransaksi'); ?>">
            <div class="col-md-3">
                <label class="form-label mb-1">Tanggal</label>
                <input type="date"
                       name="tanggal"
                       class="form-control form-control-sm"
                       value="<?= esc($tanggalFilter) ?>">
            </div>
            <div class="col-md-5">
                <label class="form-label mb-1">Pencarian</label>
                <input type="text"
                       name="q"
                       class="form-control form-control-sm"
                       placeholder="Ketik nomor transaksi / nama kasir..."
                       value="<?= esc($qFilter) ?>">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary btn-sm me-2">
                    <i class="typcn typcn-zoom-outline"></i> Cari
                </button>
                <a href="<?= base_url('riwayattransaksi'); ?>" class="btn btn-outline-secondary btn-sm">
                    Reset
                </a>
            </div>
        </form>

        <!-- =================== TABEL RIWAYAT =================== -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-dark text-center">
                    <tr>
                        <th style="width:6%;">No</th>
                        <th style="width:20%;">Nomor Transaksi</th>
                        <th style="width:20%;">Tanggal &amp; Jam</th>
                        <th style="width:18%;">Total Belanja</th>
                        <th style="width:13%;">Metode Bayar</th>
                        <th style="width:15%;">Kasir</th>
                        <th style="width:8%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($riwayat)): ?>
                        <?php foreach ($riwayat as $i => $row):
                            $rawTgl = $row->tanggal_order ?? null;
                            if ($rawTgl) {
                                $tglJam = date('d-m-Y H:i', strtotime($rawTgl));
                            } else {
                                $tglJam = '-';
                            }

                            $noJual = $row->no_penjualan ?? '-';
                            $total  = $row->total        ?? 0;
                            $kasir  = $row->username     ?? '-';

                            $metode = $row->metode_pembayaran
                                    ?? $row->metode
                                    ?? null;
                        ?>
                        <tr>
                            <td class="text-center"><?= $i + 1; ?></td>
                            <td><?= esc($noJual); ?></td>
                            <td><?= esc($tglJam); ?></td>
                            <td class="text-end">
                                Rp <?= number_format((float) $total, 0, ',', '.'); ?>
                            </td>
                            <td class="text-center">
                                <?= esc($metode ? strtoupper($metode) : '-'); ?>
                            </td>
                            <td class="text-center"><?= esc($kasir); ?></td>
                            <td class="text-center">
                                <!-- tombol untuk memunculkan pop-up detail -->
                                <button type="button"
                                        class="btn btn-sm btn-info text-white btn-detail-transaksi"
                                        data-id="<?= (int)$row->id_order; ?>">
                                    Lihat Detail
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                Belum ada data riwayat transaksi.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- =================== MODAL DETAIL TRANSAKSI =================== -->
<div class="modal fade" id="detailTransaksiModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <div>
            <h5 class="modal-title mb-1">Detail Transaksi</h5>
            <small class="text-muted">
                No. Transaksi: <span id="dt-no-transaksi">-</span>
            </small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div class="row mb-3">
            <div class="col-md-6">
                <div><strong>Tanggal &amp; Jam:</strong> <span id="dt-tanggal-jam">-</span></div>
                <div><strong>Kasir:</strong> <span id="dt-kasir">-</span></div>
                <div><strong>Metode Bayar:</strong> <span id="dt-metode">-</span></div>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <div><strong>Subtotal:</strong> <span id="dt-subtotal">Rp 0</span></div>
                <div><strong>Total Diskon:</strong> <span id="dt-diskon">Rp 0</span></div>
                <div class="fw-bold">
                    <strong>Grand Total:</strong> <span id="dt-grandtotal">Rp 0</span>
                </div>
            </div>
        </div>

        <hr>

        <h6 class="mb-2">Daftar Item</h6>
        <div class="table-responsive" style="max-height:260px; overflow-y:auto;">
            <table class="table table-sm table-bordered mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width:5%;">No</th>
                        <th>Produk</th>
                        <th style="width:15%;">Qty</th>
                        <th style="width:20%;">Harga</th>
                        <th style="width:20%;">Subtotal</th>
                    </tr>
                </thead>
                <tbody id="dt-item-body">
                    <!-- diisi via JS -->
                </tbody>
            </table>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
      </div>

    </div>
  </div>
</div>

<!-- =================== SCRIPT DETAIL MODAL =================== -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnsDetail = document.querySelectorAll('.btn-detail-transaksi');
    const modalEl    = document.getElementById('detailTransaksiModal');

    if (!modalEl || btnsDetail.length === 0) return;

    const noTransEl   = document.getElementById('dt-no-transaksi');
    const tglJamEl    = document.getElementById('dt-tanggal-jam');
    const kasirEl     = document.getElementById('dt-kasir');
    const metodeEl    = document.getElementById('dt-metode');
    const subtotalEl  = document.getElementById('dt-subtotal');
    const diskonEl    = document.getElementById('dt-diskon');
    const grandEl     = document.getElementById('dt-grandtotal');
    const bodyItemsEl = document.getElementById('dt-item-body');

    function formatRupiah(num) {
        num = Number(num) || 0;
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    btnsDetail.forEach(btn => {
        btn.addEventListener('click', function () {
            const idOrder = this.getAttribute('data-id');
            if (!idOrder) return;

            // kosongkan dulu isi modal
            noTransEl.textContent  = '-';
            tglJamEl.textContent   = '-';
            kasirEl.textContent    = '-';
            metodeEl.textContent   = '-';
            subtotalEl.textContent = 'Rp 0';
            diskonEl.textContent   = 'Rp 0';
            grandEl.textContent    = 'Rp 0';
            bodyItemsEl.innerHTML  =
                '<tr><td colspan="5" class="text-center">Memuat data...</td></tr>';

            // panggil endpoint detail (AJAX)
            fetch('<?= site_url('riwayattransaksi/detail'); ?>/' + idOrder, {
                headers: {
                    // supaya $this->request->isAJAX() = true kalau kamu pakai di controller
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.json())
                .then(data => {
                    const h     = data.header || {};
                    const items = data.items  || [];

                    // header
                    noTransEl.textContent = h.no_penjualan || '-';

                    if (h.tanggal_order) {
                        // tampilkan apa adanya saja (format dari PHP)
                        tglJamEl.textContent = h.tanggal_order;
                    } else {
                        tglJamEl.textContent = '-';
                    }

                    kasirEl.textContent  = h.username || '-';
                    metodeEl.textContent = h.metode_pembayaran || '-';

                    // total dari header (grand total)
                    grandEl.textContent = formatRupiah(h.total || 0);

                    // hitung subtotal
                    let subtotal    = 0;
                    let totalDiskon = 0; // kalau nanti ada diskon per item, bisa dihitung di sini

                    bodyItemsEl.innerHTML = '';

                    if (items.length === 0) {
                        bodyItemsEl.innerHTML =
                            '<tr><td colspan="5" class="text-center">Tidak ada item.</td></tr>';
                    } else {
                        items.forEach((it, idx) => {
                            const qty   = Number(it.jumlah_beli  || 0);
                            const harga = Number(it.harga_satuan || 0);
                            const sub   = qty * harga;

                            subtotal += sub;

                            const namaProduk = (it.nama_produk || 'Produk manual');
                            const namaMerek  = it.nama_merek ? ' (' + it.nama_merek + ')' : '';

                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td class="text-center">${idx + 1}</td>
                                <td>${namaProduk}${namaMerek}</td>
                                <td class="text-center">${qty}</td>
                                <td class="text-end">${formatRupiah(harga)}</td>
                                <td class="text-end">${formatRupiah(sub)}</td>
                            `;
                            bodyItemsEl.appendChild(tr);
                        });
                    }

                    subtotalEl.textContent = formatRupiah(subtotal);
                    diskonEl.textContent   = formatRupiah(totalDiskon);
                })
                .catch(err => {
                    console.error(err);
                    bodyItemsEl.innerHTML =
                        '<tr><td colspan="5" class="text-center text-danger">Gagal memuat data.</td></tr>';
                });

            // tampilkan modal
            if (typeof bootstrap !== 'undefined') {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        });
    });
});
</script>

<?= $this->endSection(); ?>
