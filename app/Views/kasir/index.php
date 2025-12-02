<?= $this->extend('layout/index'); ?>
<?= $this->section('content'); ?>

<?php
// siapkan data produk untuk JS
$jsProduk = [];
if (!empty($produk)) {
    foreach ($produk as $p) {
        $jsProduk[] = [
            'id'    => (int) $p->id_produk,
            'nama'  => $p->nama_produk,
            'harga' => (int) $p->harga,
            'stok'  => (int) $p->stok,   // ✅ kirim stok ke JS
            'diskon' => (int) ($p->besaran_discount ?? 0) // ✅ kirim diskon aktif ke JS
        ];
    }
}

$session        = session();
$namaKasir      = $session->get('username') ?? 'Kasir';
$tanggalHariIni = date('d-m-Y');
?>

<!-- =================== BARIS ATAS: KODE / KASIR / TANGGAL + CARI PRODUK =================== -->
<div class="row mb-3">

    <!-- KODE / KASIR / TANGGAL -->
    <div class="col-lg-6 mb-3 mb-lg-0">
        <div class="card">
            <div class="card-body py-3">
                <div>
                    <strong>Kode Transaksi :</strong>
                    <span id="kode-transaksi" class="text-primary">
                        Belum ada (tambahkan produk)
                    </span>
                </div>
                <div><strong>Kasir :</strong> <?= esc($namaKasir); ?></div>
                <div><strong>Tanggal :</strong> <?= esc($tanggalHariIni); ?></div>
            </div>
        </div>
    </div>

    <!-- CARI PRODUK -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body py-3">
                <label class="form-label mb-1">
                    <i class="typcn typcn-zoom-outline"></i> Cari Produk :
                </label>
                <div class="input-group">
                    <input type="text"
                           id="input-cari"
                           class="form-control"
                           placeholder="Ketik nama produk di sini...  (auto-complete)"
                           list="listProduk"
                           autocomplete="off">
                    <button class="btn btn-primary" type="button" id="btnTambahDariCari">
                        Tambah
                    </button>
                </div>

                <datalist id="listProduk">
                    <?php if (!empty($produk)): ?>
                        <?php foreach ($produk as $p): ?>
                            <option value="<?= esc($p->nama_produk); ?>"></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </datalist>
            </div>
        </div>
    </div>

</div>

<!-- =================== TABEL KERANJANG =================== -->
<div class="card mb-3">
    <div class="card-body">
        <table class="table table-bordered table-hover mb-2">
            <thead class="table-dark text-center">
                <tr>
                    <th style="width:6%;">No</th>
                    <th>Produk</th>
                    <th style="width:10%;">Qty</th>
                    <th style="width:18%;">Harga</th>
                    <th style="width:12%;">Diskon (%)</th>
                    <th style="width:18%;">Subtotal</th>
                    <th style="width:10%;">Aksi</th>
                </tr>
            </thead>
            <tbody id="cart-body">
                <!-- baris item diisi oleh JS -->
            </tbody>
        </table>

        <!-- tombol manual kosong (tidak terikat stok) -->
        <button type="button" class="btn btn-outline-primary btn-sm" id="btnTambahManualKosong">
            + Tambah Produk
        </button>
    </div>
</div>

<!-- =================== RINGKASAN & TOMBOL BAYAR/BATAL =================== -->
<div class="card">
    <div class="card-body">
        <div class="row">

            <!-- RINGKASAN -->
            <div class="col-md-6 mb-3 mb-md-0">
                <h5 class="mb-3">RINGKASAN TRANSAKSI</h5>
                <div class="row mb-1">
                    <div class="col-6 col-sm-4">Subtotal</div>
                    <div class="col-6 col-sm-8 text-end" id="txt-subtotal">Rp 0</div>
                </div>
                <div class="row mb-1">
                    <div class="col-6 col-sm-4">Total Diskon</div>
                    <div class="col-6 col-sm-8 text-end" id="txt-total-diskon">Rp 0</div>
                </div>
                <div class="row mb-1">
                    <div class="col-6 col-sm-4 fw-bold">Total</div>
                    <div class="col-6 col-sm-8 text-end fw-bold text-success" id="txt-total">Rp 0</div>
                </div>
            </div>

            <!-- AKSI PEMBAYARAN: BAYAR & BATAL -->
            <div class="col-md-6 d-flex flex-column align-items-md-end">
                <div class="w-100" style="max-width: 260px;">
                    <button type="button" class="btn btn-success w-100 mb-2" id="btnBayar">
                        Bayar
                    </button>
                    <button type="button" class="btn btn-secondary w-100" id="btnBatal">
                        Batal
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- =================== FORM HIDDEN UNTUK KIRIM KE HALAMAN BAYAR =================== -->
<form id="formKasir" method="post" action="<?= base_url('kasir/bayar'); ?>">
    <?= csrf_field(); ?>
    <input type="hidden" name="no_penjualan"  id="input-no-penjualan">
    <input type="hidden" name="items"         id="input-items">
    <input type="hidden" name="subtotal"      id="input-subtotal">
    <input type="hidden" name="total_diskon"  id="input-total-diskon">
    <input type="hidden" name="grand_total"   id="input-grand-total">
</form>

<!-- =================== SCRIPT KASIR =================== -->
<script>
    // Data produk dari PHP -> JS
    const PRODUCTS = <?= json_encode($jsProduk); ?>;

    const cartBody          = document.getElementById('cart-body');
    const inputCari         = document.getElementById('input-cari');
    const btnTambahDariCari = document.getElementById('btnTambahDariCari');
    const btnTambahManual   = document.getElementById('btnTambahManualKosong');

    const txtSubtotal    = document.getElementById('txt-subtotal');
    const txtTotalDiskon = document.getElementById('txt-total-diskon');
    const txtTotal       = document.getElementById('txt-total');

    const formKasir        = document.getElementById('formKasir');
    const btnBayar         = document.getElementById('btnBayar');
    const btnBatal         = document.getElementById('btnBatal');

    const inputItemsHidden   = document.getElementById('input-items');
    const inputSubtotalH     = document.getElementById('input-subtotal');
    const inputTotalDiskonH  = document.getElementById('input-total-diskon');
    const inputGrandTotalH   = document.getElementById('input-grand-total');
    const kodeTransaksiSpan  = document.getElementById('kode-transaksi');
    const inputNoPenjualan   = document.getElementById('input-no-penjualan');

    let cart = []; // {id, nama, harga, qty, diskon, isManual, stokMax}

    let subtotalGlobal    = 0;
    let totalDiskonGlobal = 0;
    let totalGlobal       = 0;

    let kodeTransaksi = '';

    // ---------- UTIL ----------
    function formatRupiah(angka) {
        angka = Number(angka) || 0;
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    function findProductByName(name) {
        name = (name || '').toLowerCase();
        return PRODUCTS.find(p => p.nama.toLowerCase() === name);
    }

    function findProductById(id) {
        id = parseInt(id);
        return PRODUCTS.find(p => p.id === id);
    }

    function generateKodeTransaksi() {
        if (kodeTransaksi) return kodeTransaksi;

        const now = new Date();
        const y  = now.getFullYear();
        const m  = String(now.getMonth() + 1).padStart(2, '0');
        const d  = String(now.getDate()).padStart(2, '0');
        const h  = String(now.getHours()).padStart(2, '0');
        const i  = String(now.getMinutes()).padStart(2, '0');
        const s  = String(now.getSeconds()).padStart(2, '0');
        const r  = Math.floor(Math.random() * 900) + 100;

        kodeTransaksi = `INV-${y}${m}${d}-${h}${i}${s}-${r}`;

        if (kodeTransaksiSpan) {
            kodeTransaksiSpan.textContent = kodeTransaksi;
        }
        if (inputNoPenjualan) {
            inputNoPenjualan.value = kodeTransaksi;
        }
        return kodeTransaksi;
    }

    // ---------- CART ----------
    function hitungSubtotalItem(item) {
        const bruto    = item.harga * item.qty;
        const potongan = bruto * (item.diskon / 100);
        return bruto - potongan;
    }

    function hitungTotal() {
        let subtotal    = 0;
        let totalDiskon = 0;

        cart.forEach(item => {
            const bruto    = item.harga * item.qty;
            const potongan = bruto * (item.diskon / 100);
            subtotal    += bruto;
            totalDiskon += potongan;
        });

        const total = subtotal - totalDiskon;

        subtotalGlobal    = subtotal;
        totalDiskonGlobal = totalDiskon;
        totalGlobal       = total;

        txtSubtotal.textContent    = formatRupiah(subtotal);
        txtTotalDiskon.textContent = formatRupiah(totalDiskon);
        txtTotal.textContent       = formatRupiah(total);
    }

    function updateSubtotalCell(tr, item) {
        const cell = tr.querySelector('.subtotal-cell');
        if (cell) {
            cell.textContent = formatRupiah(hitungSubtotalItem(item));
        }
    }

    function renderCart() {
        cartBody.innerHTML = '';

        if (cart.length === 0) {
            const trEmpty = document.createElement('tr');
            const td      = document.createElement('td');
            td.colSpan    = 7;
            td.className  = 'text-center text-muted';
            td.textContent = 'Keranjang masih kosong.';
            trEmpty.appendChild(td);
            cartBody.appendChild(trEmpty);
            hitungTotal();

            // reset kode transaksi kalau keranjang kosong
            kodeTransaksi = '';
            if (kodeTransaksiSpan) {
                kodeTransaksiSpan.textContent = 'Belum ada (tambahkan produk)';
            }
            if (inputNoPenjualan) {
                inputNoPenjualan.value = '';
            }
            return;
        }

        cart.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.dataset.id = item.id;

            // No
            const tdNo = document.createElement('td');
            tdNo.className = 'text-center';
            tdNo.textContent = index + 1;
            tr.appendChild(tdNo);

            // Produk
            const tdNama = document.createElement('td');
            if (item.isManual) {
                const inputNama = document.createElement('input');
                inputNama.type = 'text';
                inputNama.className = 'form-control form-control-sm';
                inputNama.value = item.nama || '';
                inputNama.addEventListener('input', () => {
                    item.nama = inputNama.value;
                });
                tdNama.appendChild(inputNama);
            } else {
                tdNama.textContent = item.nama;
            }
            tr.appendChild(tdNama);

            // Qty
            const tdQty = document.createElement('td');
            tdQty.className = 'text-center';
            tdQty.innerHTML = `
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary btn-qty-minus">-</button>
                    <button type="button" class="btn btn-light disabled qty-text">${item.qty}</button>
                    <button type="button" class="btn btn-outline-secondary btn-qty-plus">+</button>
                </div>
            `;
            tr.appendChild(tdQty);

            // Harga
            const tdHarga = document.createElement('td');
            tdHarga.className = 'text-end';
            if (item.isManual) {
                const inputHarga = document.createElement('input');
                inputHarga.type  = 'number';
                inputHarga.min   = 0;
                inputHarga.className = 'form-control form-control-sm text-end';
                inputHarga.value = item.harga;
                inputHarga.addEventListener('input', () => {
                    item.harga = Number(inputHarga.value) || 0;
                    updateSubtotalCell(tr, item);
                    hitungTotal();
                });
                tdHarga.appendChild(inputHarga);
            } else {
                tdHarga.textContent = formatRupiah(item.harga);
            }
            tr.appendChild(tdHarga);

            // Diskon
            const tdDiskon = document.createElement('td');
            tdDiskon.className = 'text-center';
            const inputDiskon = document.createElement('input');
            inputDiskon.type      = 'number';
            inputDiskon.min       = 0;
            inputDiskon.max       = 100;
            inputDiskon.className = 'form-control form-control-sm text-center';
            inputDiskon.value     = item.diskon;
            inputDiskon.addEventListener('input', () => {
                item.diskon = Number(inputDiskon.value) || 0;
                updateSubtotalCell(tr, item);
                hitungTotal();
            });
            tdDiskon.appendChild(inputDiskon);
            tr.appendChild(tdDiskon);

            // Subtotal
            const tdSub = document.createElement('td');
            tdSub.className   = 'text-end subtotal-cell';
            tdSub.textContent = formatRupiah(hitungSubtotalItem(item));
            tr.appendChild(tdSub);

            // Aksi
            const tdAksi = document.createElement('td');
            tdAksi.className = 'text-center';
            const btnHapus = document.createElement('button');
            btnHapus.type      = 'button';
            btnHapus.className = 'btn btn-danger btn-sm btn-hapus';
            btnHapus.textContent = 'Hapus';
            tdAksi.appendChild(btnHapus);
            tr.appendChild(tdAksi);

            cartBody.appendChild(tr);
        });

        hitungTotal();
    }

    // ---------- EVENT: tambah dari cari + VALIDASI STOK ----------
    function tambahProdukDariCari() {
        const nama = (inputCari.value || '').trim();
        if (!nama) {
            alert('Ketik nama produk terlebih dahulu.');
            return;
        }

        const p = findProductByName(nama);
        if (!p) {
            alert('Produk "' + nama + '" tidak ditemukan.');
            return;
        }

        // ✅ stok dari database
        const stokTersedia = Number(p.stok) || 0;
        if (stokTersedia <= 0) {
            alert('Stok produk "' + p.nama + '" sudah habis.');
            return;
        }

        let item = cart.find(c => !c.isManual && c.id === p.id);
        if (item) {
            // cek apakah tambah 1 melebihi stok
            if (item.qty + 1 > stokTersedia) {
                alert('Stok produk "' + p.nama + '" tidak mencukupi.\nStok tersisa: ' + stokTersedia);
                return;
            }
            item.qty += 1;
        } else {
            item = {
                id: p.id,
                nama: p.nama,
                harga: p.harga,
                qty: 1,
                diskon: p.diskon ?? 0,  //pakai diskon default dari produk
                isManual: false,
                stokMax: stokTersedia   // ✅ simpan batas stok di item
            };
            cart.push(item);
        }

        // generate kode transaksi kalau baru pertama kali isi keranjang
        if (!kodeTransaksi) {
            generateKodeTransaksi();
        }

        inputCari.value = '';
        renderCart();
    }

    btnTambahDariCari.addEventListener('click', tambahProdukDariCari);
    inputCari.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            tambahProdukDariCari();
        }
    });

    // ---------- EVENT: tambah baris manual kosong ----------
    btnTambahManual.addEventListener('click', function () {
        const item = {
            id: Date.now(),
            nama: '',
            harga: 0,
            qty: 1,
            diskon: 0,
            isManual: true,
            // stokMax tidak dipakai untuk manual
        };
        cart.push(item);

        if (!kodeTransaksi) {
            generateKodeTransaksi();
        }

        renderCart();
    });

    // ---------- +, -, hapus (delegasi) DENGAN VALIDASI STOK ----------
    cartBody.addEventListener('click', function (e) {
        const tr = e.target.closest('tr');
        if (!tr) return;
        const id   = parseInt(tr.dataset.id);
        const item = cart.find(c => c.id === id);
        if (!item) return;

        if (e.target.classList.contains('btn-qty-minus')) {
            if (item.qty > 1) {
                item.qty -= 1;
                renderCart();
            }
        }

        if (e.target.classList.contains('btn-qty-plus')) {
            // kalau item dari database (bukan manual) cek stok
            if (!item.isManual) {
                const stokMax = Number(item.stokMax ?? 0);
                if (stokMax > 0 && item.qty + 1 > stokMax) {
                    alert('Tidak dapat menambah qty.\nStok tersedia: ' + stokMax);
                    return;
                }
            }
            item.qty += 1;
            renderCart();
        }

        if (e.target.classList.contains('btn-hapus')) {
            cart = cart.filter(c => c.id !== id);
            renderCart();
        }
    });

    // ---------- BAYAR (LANJUT KE HALAMAN BAYAR) ----------
    btnBayar.addEventListener('click', function () {
        if (cart.length === 0) {
            alert('Keranjang masih kosong.');
            return;
        }

        hitungTotal();

        if (!confirm('Lanjut ke halaman pembayaran?')) {
            return;
        }

        if (!kodeTransaksi) {
            generateKodeTransaksi();
        }

        inputItemsHidden.value  = JSON.stringify(cart);
        inputSubtotalH.value    = subtotalGlobal;
        inputTotalDiskonH.value = totalDiskonGlobal;
        inputGrandTotalH.value  = totalGlobal;

        formKasir.submit();
    });

    // ---------- BATAL ----------
    btnBatal.addEventListener('click', function () {
        if (!confirm('Batalkan transaksi dan kosongkan keranjang?')) {
            return;
        }
        cart = [];
        renderCart();
        inputCari.value = '';
    });

    // render awal
    renderCart();
</script>

<?= $this->endSection(); ?>
