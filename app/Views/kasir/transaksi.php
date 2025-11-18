
<div class="page-header">
    <h3 class="page-title">Transaksi Kasir</h3>
</div>

<div class="row">
    <!-- Form input produk -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Input Produk</h5>
            </div>
            <div class="card-body">
                <form id="formTambahItem" onsubmit="return false;">
                    <div class="mb-3">
                        <label class="form-label">Kode Produk / Barcode</label>
                        <input type="text" name="kode_produk" id="kode_produk"
                               class="form-control form-control-sm"
                               placeholder="Scan barcode atau ketik kode produk">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="nama_produk" id="nama_produk"
                               class="form-control form-control-sm"
                               placeholder="(Opsional) otomatis jika pakai kode">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga Satuan (Rp)</label>
                        <input type="number" name="harga" id="harga"
                               class="form-control form-control-sm" min="0" value="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Qty</label>
                        <input type="number" name="qty" id="qty"
                               class="form-control form-control-sm" min="1" value="1">
                    </div>

                    <div class="d-grid">
                        <button type="button" class="btn btn-primary btn-sm" onclick="tambahKeKeranjang()">
                            Tambah ke Keranjang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel keranjang + pembayaran -->
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Keranjang Belanja</h5>
            </div>
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0" id="tabelKeranjang">
                        <thead class="table-light text-center">
                            <tr>
                                <th width="5%">No</th>
                                <th>Produk</th>
                                <th width="15%">Harga</th>
                                <th width="10%">Qty</th>
                                <th width="18%">Subtotal</th>
                                <th width="8%">X</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- baris dinamis via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Panel pembayaran -->
        <div class="card">
            <div class="card-body">

                <div class="row mb-2">
                    <div class="col-6">
                        <h5 class="mb-0">Total</h5>
                    </div>
                    <div class="col-6 text-end">
                        <h4 class="mb-0" id="labelTotal">Rp 0</h4>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <label class="form-label">Bayar (Rp)</label>
                    <input type="number" id="bayar" class="form-control form-control-sm" min="0" value="0"
                           oninput="hitungKembalian()">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kembalian (Rp)</label>
                    <input type="text" id="kembalian" class="form-control form-control-sm" readonly value="0">
                </div>

                <div class="d-flex justify-content-between">
                    <button class="btn btn-secondary btn-sm" type="button" onclick="resetTransaksi()">
                        Batal / Reset
                    </button>
                    <button class="btn btn-success btn-sm" type="button">
                        Simpan & Cetak Nota
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- JS sederhana hanya di halaman ini -->
<script>
let keranjang = [];

function formatRupiah(angka) {
    angka = Number(angka) || 0;
    return new Intl.NumberFormat('id-ID').format(angka);
}

function renderKeranjang() {
    const tbody = document.querySelector('#tabelKeranjang tbody');
    tbody.innerHTML = '';
    let total = 0;

    keranjang.forEach((item, index) => {
        const subtotal = item.harga * item.qty;
        total += subtotal;

        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td class="text-center">${index + 1}</td>
            <td>${item.nama}</td>
            <td class="text-end">Rp ${formatRupiah(item.harga)}</td>
            <td class="text-center">${item.qty}</td>
            <td class="text-end">Rp ${formatRupiah(subtotal)}</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger" onclick="hapusItem(${index})">x</button>
            </td>
        `;

        tbody.appendChild(tr);
    });

    document.getElementById('labelTotal').innerText = 'Rp ' + formatRupiah(total);
    document.getElementById('labelTotal').dataset.total = total;
    hitungKembalian();
}

function tambahKeKeranjang() {
    const nama  = document.getElementById('nama_produk').value || document.getElementById('kode_produk').value;
    const harga = Number(document.getElementById('harga').value || 0);
    const qty   = Number(document.getElementById('qty').value || 1);

    if (!nama || harga <= 0 || qty <= 0) {
        alert('Nama, harga, dan qty harus diisi dengan benar.');
        return;
    }

    keranjang.push({ nama, harga, qty });
    renderKeranjang();

    // reset input qty saja
    document.getElementById('qty').value = 1;
}

function hapusItem(index) {
    keranjang.splice(index, 1);
    renderKeranjang();
}

function hitungKembalian() {
    const total = Number(document.getElementById('labelTotal').dataset.total || 0);
    const bayar = Number(document.getElementById('bayar').value || 0);
    const kembali = bayar - total;
    document.getElementById('kembalian').value = (kembali < 0 ? 0 : kembali);
}

function resetTransaksi() {
    if (!confirm('Reset transaksi?')) return;
    keranjang = [];
    renderKeranjang();
    document.getElementById('bayar').value = 0;
    document.getElementById('kembalian').value = 0;
    document.getElementById('kode_produk').value = '';
    document.getElementById('nama_produk').value = '';
    document.getElementById('harga').value = 0;
    document.getElementById('qty').value = 1;
}

// Inisialisasi awal
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('labelTotal').dataset.total = 0;
});
</script>
