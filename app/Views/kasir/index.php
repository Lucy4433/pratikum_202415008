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
        ];
    }
}
?>

<!-- ====== CSS KHUSUS CETAK STRUK ====== -->
<style>
  /* Saat print, hanya #print-area yang kelihatan */
  @media print {
    body * {
      visibility: hidden;
    }
    #print-area, #print-area * {
      visibility: visible;
    }
    #print-area {
      position: absolute;
      left: 50%;
      top: 0;
      transform: translateX(-50%);
      width: 80mm;           /* lebar kertas nota */
      padding: 0;
    }
  }

  /* Style kecil seperti struk thermal */
  #print-area {
    font-family: monospace;
    font-size: 12px;
    line-height: 1.3;
    width: 80mm;             /* biar di tengah juga saat preview */
    margin: 0 auto;
  }

  #print-area .center {
    text-align: center;
  }

  #print-area hr {
    margin: 4px 0;
    border-top: 1px dashed #000;
  }

  #print-area table {
    width: 100%;
  }

  #print-area td {
    padding: 0;
  }

  #print-area .text-right {
    text-align: right;
  }
</style>

<!-- ======================== CARI PRODUK (FULL WIDTH) ======================== -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-2 fw-bold">
                    <i class="typcn typcn-zoom-outline"></i> Cari Produk :
                </div>
                <div class="input-group">
                    <input type="text"
                           id="input-cari"
                           class="form-control"
                           list="listProduk"
                           placeholder="Ketik nama produk..."
                           autocomplete="off">
                    <!-- tombol ini kembali untuk tambah berdasarkan nama -->
                    <button class="btn btn-primary" type="button" id="btnTambahManual">
                        + Tambah Produk Manual
                    </button>
                </div>

                <!-- datalist untuk auto-complete -->
                <datalist id="listProduk">
                    <?php foreach ($produk as $p): ?>
                        <option value="<?= esc($p->nama_produk); ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>
        </div>
    </div>
</div>

<!-- ======================== DAFTAR BELANJA & PANEL PEMBAYARAN ======================== -->
<div class="row">
    <!-- ====== DAFTAR BELANJA (KIRI) ====== -->
    <div class="col-lg-8 col-md-7 mb-4">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover mb-2">
                    <thead class="table-dark text-center">
                        <tr>
                            <th style="width:5%;">No</th>
                            <th>Produk</th>
                            <th style="width:14%;">Qty</th>
                            <th style="width:17%;">Harga</th>
                            <th style="width:12%;">Diskon (%)</th>
                            <th style="width:17%;">Subtotal</th>
                            <th style="width:10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="cart-body">
                        <!-- baris produk akan di-generate JS -->
                    </tbody>
                </table>

                <!-- tombol ini sekarang buka modal produk cepat -->
                <button type="button"
                        class="btn btn-outline-primary btn-sm"
                        id="btnTambahKosong"
                        data-bs-toggle="modal"
                        data-bs-target="#produkCepatModal">
                    + Tambah Produk Manual (kosong)
                </button>
            </div>
        </div>
    </div>

    <!-- ====== PANEL PEMBAYARAN (KANAN) ====== -->
    <div class="col-lg-4 col-md-5 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">PANEL PEMBAYARAN</h5>

                <div class="row mb-2">
                    <div class="col-6 col-md-5">Subtotal :</div>
                    <div class="col-6 col-md-7 fw-bold text-end" id="txt-subtotal">Rp 0</div>
                </div>
                <div class="row mb-2">
                    <div class="col-6 col-md-5">Total Diskon :</div>
                    <div class="col-6 col-md-7 fw-bold text-end" id="txt-total-diskon">Rp 0</div>
                </div>
                <div class="row mb-3">
                    <div class="col-6 col-md-5">Grand Total :</div>
                    <div class="col-6 col-md-7 fw-bold text-success text-end" id="txt-grand-total">Rp 0</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Bayar :</label>
                    <input type="text" id="input-bayar" class="form-control" placeholder="Masukkan uang bayar">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kembalian :</label>
                    <input type="text" id="input-kembalian" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Metode Pembayaran :</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="metode" id="metode-cash" value="cash" checked>
                        <label class="form-check-label" for="metode-cash">Cash</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="metode" id="metode-transfer" value="transfer">
                        <label class="form-check-label" for="metode-transfer">Transfer</label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <!-- tambahkan id untuk tombol simpan -->
                    <button type="button" class="btn btn-success flex-fill" id="btnSimpan">SIMPAN</button>

                    <!-- CETAK NOTA -> buka modal -->
                    <button type="button"
                            class="btn btn-info text-white flex-fill"
                            id="btnCetakNota"
                            data-bs-toggle="modal"
                            data-bs-target="#modalCetakNota">
                        CETAK NOTA
                    </button>

                    <button type="button" class="btn btn-secondary flex-fill">BATAL</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======================== MODAL PRODUK CEPAT ======================== -->
<div class="modal fade" id="produkCepatModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Pilih Produk Cepat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p class="text-muted" style="font-size:.85rem;">
          Klik salah satu produk untuk menambahkannya ke daftar belanja.
        </p>

        <div id="produk-cepat" class="mt-2">
            <?php foreach ($produk as $p): ?>
                <button type="button"
                        class="btn btn-outline-primary btn-sm me-2 mb-2 btn-produk-cepat"
                        data-id="<?= (int) $p->id_produk; ?>">
                    <?= esc($p->nama_produk); ?>
                </button>
            <?php endforeach; ?>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
      </div>

    </div>
  </div>
</div>

<!-- =============== MODAL CETAK NOTA (POPUP RINGKASAN) ================= -->
<div class="modal fade" id="modalCetakNota" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cetak Nota</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p class="mb-3">Ringkasan pembayaran:</p>

        <div class="mb-2 d-flex justify-content-between">
          <span>Subtotal</span>
          <span id="nota-subtotal">Rp 0</span>
        </div>
        <div class="mb-2 d-flex justify-content-between">
          <span>Total Diskon</span>
          <span id="nota-total-diskon">Rp 0</span>
        </div>
        <div class="mb-3 d-flex justify-content-between fw-bold">
          <span>Grand Total</span>
          <span id="nota-grand-total">Rp 0</span>
        </div>

        <small class="text-muted">
          Saat menekan tombol <b>Cetak</b>, akan muncul jendela print dari browser dengan tampilan struk nota.
        </small>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary btn-sm" id="btnPrintNota">Cetak</button>
      </div>
    </div>
  </div>
</div>

<!-- ============= AREA STRUK YANG AKAN DICETAK ============== -->
<div id="print-area" style="display:none;">
  <div class="center">
    <strong>PHONE STORE</strong><br>
    Jl. Cenderawasih No. 10, Jayapura<br>
    Telp: 0812-3456-7890<br>
    Tanggal : <span id="receipt-date"></span>
  </div>
  <hr>
  <div>Item:</div>
  <table>
    <tbody id="print-items">
      <!-- Diisi via JS -->
    </tbody>
  </table>
  <hr>
  <table>
    <tr>
      <td>Subtotal</td>
      <td class="text-right" id="receipt-subtotal">Rp 0</td>
    </tr>
    <tr>
      <td>Total Diskon</td>
      <td class="text-right" id="receipt-total-diskon">Rp 0</td>
    </tr>
    <tr>
      <td><strong>Grand Total</strong></td>
      <td class="text-right" id="receipt-grand-total"><strong>Rp 0</strong></td>
    </tr>
    <tr><td colspan="2"><hr></td></tr>
    <tr>
      <td>Bayar</td>
      <td class="text-right" id="receipt-bayar">Rp 0</td>
    </tr>
    <tr>
      <td>Kembali</td>
      <td class="text-right" id="receipt-kembali">Rp 0</td>
    </tr>
    <tr>
      <td>Metode</td>
      <td class="text-right" id="receipt-metode">-</td>
    </tr>
  </table>
  <hr>
  <div class="center">
    TERIMA KASIH<br>
    Belanja di PHONE STORE
  </div>
</div>
<!-- ============= END AREA STRUK ============== -->

<!-- ============= FORM HIDDEN UNTUK SIMPAN TRANSAKSI ============== -->
<form id="formKasir" method="post" action="<?= base_url('kasir/simpan'); ?>">
    <?= csrf_field(); ?>
    <input type="hidden" name="items"        id="input-items">
    <input type="hidden" name="subtotal"     id="input-subtotal">
    <input type="hidden" name="total_diskon" id="input-total-diskon">
    <input type="hidden" name="grand_total"  id="input-grand-total">
    <input type="hidden" name="metode"       id="input-metode">
    <input type="hidden" name="bayar"        id="input-bayar-hidden">
    <input type="hidden" name="kembali"      id="input-kembali-hidden">
</form>
<!-- =============================================================== -->

<!-- ======================== SCRIPT KASIR ======================== -->
<script>
    // Data produk dari PHP -> JS
    const PRODUCTS = <?= json_encode($jsProduk); ?>;

    const cartBody        = document.getElementById('cart-body');
    const inputCari       = document.getElementById('input-cari');
    const btnTambahManual = document.getElementById('btnTambahManual');
    const btnTambahKosong = document.getElementById('btnTambahKosong');
    const produkCepatWrap = document.getElementById('produk-cepat');
    const inputBayar      = document.getElementById('input-bayar');
    const inputKembalian  = document.getElementById('input-kembalian');

    const txtSubtotal     = document.getElementById('txt-subtotal');
    const txtTotalDiskon  = document.getElementById('txt-total-diskon');
    const txtGrandTotal   = document.getElementById('txt-grand-total');

    // elemen cetak nota (modal ringkasan)
    const btnCetakNota    = document.getElementById('btnCetakNota');
    const notaSubtotal    = document.getElementById('nota-subtotal');
    const notaTotalDiskon = document.getElementById('nota-total-diskon');
    the_rect = ' '
    const notaGrandTotal  = document.getElementById('nota-grand-total');
    const btnPrintNota    = document.getElementById('btnPrintNota');
    const modalCetakEl    = document.getElementById('modalCetakNota');

    // elemen untuk struk print
    const printArea       = document.getElementById('print-area');
    const printItemsBody  = document.getElementById('print-items');
    const receiptDate     = document.getElementById('receipt-date');
    const receiptSubtotal = document.getElementById('receipt-subtotal');
    const receiptTotalDiskon = document.getElementById('receipt-total-diskon');
    const receiptGrandTotal  = document.getElementById('receipt-grand-total');
    const receiptBayar    = document.getElementById('receipt-bayar');
    const receiptKembali  = document.getElementById('receipt-kembali');
    const receiptMetode   = document.getElementById('receipt-metode');

    // elemen form hidden untuk simpan transaksi
    const formKasir        = document.getElementById('formKasir');
    const btnSimpan        = document.getElementById('btnSimpan');
    const inputItemsHidden = document.getElementById('input-items');
    const inputSubtotalH   = document.getElementById('input-subtotal');
    const inputTotalDiskonH= document.getElementById('input-total-diskon');
    const inputGrandTotalH = document.getElementById('input-grand-total');
    const inputMetodeH     = document.getElementById('input-metode');
    const inputBayarH      = document.getElementById('input-bayar-hidden');
    const inputKembaliH    = document.getElementById('input-kembali-hidden');

    let cart = []; // {id, nama, harga, qty, diskon}

    // total global untuk dipakai di struk & simpan
    let subtotalGlobal   = 0;
    let totalDiskonGlobal = 0;
    let grandTotalGlobal  = 0;

    // ------------------- UTIL FORMAT RUPIAH ------------------->
    function formatRupiah(angka) {
        angka = Number(angka) || 0;
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    // ------------------- FUNGSI CART ------------------->
    function findProductByName(name) {
        name = name.toLowerCase();
        return PRODUCTS.find(p => p.nama.toLowerCase() === name);
    }

    function findProductById(id) {
        id = parseInt(id);
        return PRODUCTS.find(p => p.id === id);
    }

    function addProductToCartById(id) {
        id = parseInt(id);
        let item = cart.find(c => c.id === id);

        if (item) {
            item.qty += 1;
        } else {
            const p = findProductById(id);
            if (!p) return;
            item = {
                id: p.id,
                nama: p.nama,
                harga: p.harga,
                qty: 1,
                diskon: 0 // persen
            };
            cart.push(item);
        }
        renderCart();
    }

    function addProductManualByName() {
        const kata = inputCari.value.trim();
        if (!kata) {
            alert('Isi nama produk dulu.');
            return;
        }

        const p = findProductByName(kata);
        if (!p) {
            alert('Produk "' + kata + '" tidak ditemukan.');
            return;
        }

        addProductToCartById(p.id);
        inputCari.value = '';
    }

    function tambahBarisKosong() {
        // baris kosong untuk input manual (tanpa database)
        const item = {
            id: Date.now(),   // id unik sementara
            nama: '',
            harga: 0,
            qty: 1,
            diskon: 0,
            isManual: true
        };
        cart.push(item);
        renderCart();
    }

    function renderCart() {
        cartBody.innerHTML = '';
        cart.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.dataset.id = item.id;

            // No
            const tdNo = document.createElement('td');
            tdNo.className = 'text-center';
            tdNo.textContent = index + 1;
            tr.appendChild(tdNo);

            // Nama Produk
            const tdNama = document.createElement('td');
            if (item.isManual) {
                const inputNama = document.createElement('input');
                inputNama.type = 'text';
                inputNama.className = 'form-control form-control-sm input-nama';
                inputNama.value = item.nama || '';
                inputNama.addEventListener('input', () => {
                    item.nama = inputNama.value;
                });
                tdNama.appendChild(inputNama);
            } else {
                tdNama.textContent = item.nama;
            }
            tr.appendChild(tdNama);

            // Qty + tombol - +
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
                inputHarga.type = 'number';
                inputHarga.min = 0;
                inputHarga.className = 'form-control form-control-sm input-harga text-end';
                inputHarga.value = item.harga;
                inputHarga.addEventListener('input', () => {
                    item.harga = Number(inputHarga.value) || 0;
                    hitungTotal();
                    updateSubtotalCell(tr, item);
                });
                tdHarga.appendChild(inputHarga);
            } else {
                tdHarga.textContent = formatRupiah(item.harga);
            }
            tr.appendChild(tdHarga);

            // Diskon (%)
            const tdDiskon = document.createElement('td');
            tdDiskon.className = 'text-center';
            const inputDiskon = document.createElement('input');
            inputDiskon.type = 'number';
            inputDiskon.min = 0;
            inputDiskon.max = 100;
            inputDiskon.className = 'form-control form-control-sm input-diskon text-center';
            inputDiskon.value = item.diskon;
            inputDiskon.addEventListener('input', () => {
                item.diskon = Number(inputDiskon.value) || 0;
                hitungTotal();
                updateSubtotalCell(tr, item);
            });
            tdDiskon.appendChild(inputDiskon);
            tr.appendChild(tdDiskon);

            // Subtotal
            const tdSubtotal = document.createElement('td');
            tdSubtotal.className = 'text-end subtotal-cell';
            tdSubtotal.textContent = formatRupiah(hitungSubtotalItem(item));
            tr.appendChild(tdSubtotal);

            // Aksi (hapus saja)
            const tdAksi = document.createElement('td');
            tdAksi.className = 'text-center';
            const btnHapus = document.createElement('button');
            btnHapus.type = 'button';
            btnHapus.className = 'btn btn-danger btn-sm btn-hapus';
            btnHapus.textContent = 'Hapus';
            tdAksi.appendChild(btnHapus);
            tr.appendChild(tdAksi);

            cartBody.appendChild(tr);
        });

        hitungTotal();
    }

    function updateSubtotalCell(tr, item) {
        const cell = tr.querySelector('.subtotal-cell');
        if (cell) {
            cell.textContent = formatRupiah(hitungSubtotalItem(item));
        }
    }

    function hitungSubtotalItem(item) {
        const bruto = item.harga * item.qty;
        const potongan = bruto * (item.diskon / 100);
        return bruto - potongan;
    }

    function hitungTotal() {
        let subtotal = 0;
        let totalDiskon = 0;

        cart.forEach(item => {
            const bruto = item.harga * item.qty;
            const potongan = bruto * (item.diskon / 100);
            subtotal += bruto;
            totalDiskon += potongan;
        });

        const grandTotal = subtotal - totalDiskon;

        txtSubtotal.textContent    = formatRupiah(subtotal);
        txtTotalDiskon.textContent = formatRupiah(totalDiskon);
        txtGrandTotal.textContent  = formatRupiah(grandTotal);

        // simpan ke global untuk struk & simpan
        subtotalGlobal    = subtotal;
        totalDiskonGlobal = totalDiskon;
        grandTotalGlobal  = grandTotal;

        // hitung kembalian kalau sudah ada nilai bayar
        const bayar = Number(inputBayar.value.replace(/\D/g, '')) || 0;
        const kembali = bayar - grandTotal;
        inputKembalian.value = kembali > 0 ? formatRupiah(kembali) : 'Rp 0';
    }

    // ------------------- EVENT LISTENER ------------------->

    // tombol tambah produk manual berdasarkan nama (search)
    btnTambahManual.addEventListener('click', addProductManualByName);
    inputCari.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addProductManualByName();
        }
    });

    // event delegasi untuk qty +/- dan hapus
    cartBody.addEventListener('click', function (e) {
        const tr = e.target.closest('tr');
        if (!tr) return;
        const id = parseInt(tr.dataset.id);
        const item = cart.find(c => c.id === id);
        if (!item) return;

        if (e.target.classList.contains('btn-qty-minus')) {
            if (item.qty > 1) {
                item.qty -= 1;
            }
            renderCart();
        }

        if (e.target.classList.contains('btn-qty-plus')) {
            item.qty += 1;
            renderCart();
        }

        if (e.target.classList.contains('btn-hapus')) {
            cart = cart.filter(c => c.id !== id);
            renderCart();
        }
    });

    // produk cepat: klik -> tambah ke cart + tutup modal
    produkCepatWrap.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-produk-cepat')) {
            const id = e.target.dataset.id;
            addProductToCartById(id);

            // tutup modal produk cepat
            const modalEl = document.getElementById('produkCepatModal');
            if (typeof bootstrap !== 'undefined' && modalEl) {
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        }
    });

    // hitung kembalian saat input bayar berubah
    inputBayar.addEventListener('input', function () {
        // ambil angka saja dari input lalu format rupiah
        const nilai = this.value.replace(/\D/g, '');
        this.value = nilai ? formatRupiah(nilai) : '';
        hitungTotal();
    });

    // ------------------- SIMPAN TRANSAKSI ------------------->
    if (btnSimpan) {
        btnSimpan.addEventListener('click', function () {
            if (cart.length === 0) {
                alert('Keranjang masih kosong.');
                return;
            }

            // pastikan total diupdate
            hitungTotal();

            const bayarNum   = Number(inputBayar.value.replace(/\D/g, '')) || 0;
            const kembaliNum = bayarNum - grandTotalGlobal;

            const metodeEl = document.querySelector('input[name="metode"]:checked');
            const metode   = metodeEl ? metodeEl.value : 'cash';

            // isi hidden input
            inputItemsHidden.value    = JSON.stringify(cart);
            inputSubtotalH.value      = subtotalGlobal;
            inputTotalDiskonH.value   = totalDiskonGlobal;
            inputGrandTotalH.value    = grandTotalGlobal;
            inputMetodeH.value        = metode;
            inputBayarH.value         = bayarNum;
            inputKembaliH.value       = kembaliNum > 0 ? kembaliNum : 0;

            formKasir.submit();
        });
    }

    // ------------------- CETAK NOTA (MODAL + STRUK) ------------------->
    if (btnCetakNota && btnPrintNota) {
        // saat tombol CETAK NOTA ditekan → copy nilai ke modal ringkasan
        btnCetakNota.addEventListener('click', function () {
            notaSubtotal.textContent    = txtSubtotal.textContent;
            notaTotalDiskon.textContent = txtTotalDiskon.textContent;
            notaGrandTotal.textContent  = txtGrandTotal.textContent;
        });

        // fungsi untuk menyiapkan isi struk
        function buildPrintNota() {
            // tanggal & jam
            const now   = new Date();
            const tgl   = now.toLocaleDateString('id-ID');
            const jam   = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            receiptDate.textContent = tgl + ' ' + jam;

            // daftar item
            printItemsBody.innerHTML = '';
            cart.forEach(item => {
                if (!item.nama || item.qty <= 0) return;
                const tr = document.createElement('tr');
                const tdNamaQty = document.createElement('td');
                const tdSub     = document.createElement('td');

                tdNamaQty.textContent = item.qty + 'x ' + item.nama;
                tdSub.className = 'text-right';
                const sub = hitungSubtotalItem(item);
                tdSub.textContent = sub.toLocaleString('id-ID');

                tr.appendChild(tdNamaQty);
                tr.appendChild(tdSub);
                printItemsBody.appendChild(tr);
            });

            // total-total
            receiptSubtotal.textContent    = formatRupiah(subtotalGlobal);
            receiptTotalDiskon.textContent = formatRupiah(totalDiskonGlobal);
            receiptGrandTotal.textContent  = formatRupiah(grandTotalGlobal);

            // bayar & kembali
            const bayarNum   = Number(inputBayar.value.replace(/\D/g, '')) || 0;
            const kembaliNum = bayarNum - grandTotalGlobal;

            receiptBayar.textContent   = formatRupiah(bayarNum);
            receiptKembali.textContent = formatRupiah(kembaliNum > 0 ? kembaliNum : 0);

            // metode
            const metodeEl = document.querySelector('input[name="metode"]:checked');
            receiptMetode.textContent = metodeEl ? metodeEl.value.toUpperCase() : '-';

            // tampilkan area struk sebelum print
            printArea.style.display = 'block';
        }

        // saat tombol CETAK di modal ditekan
        btnPrintNota.addEventListener('click', function () {
            // siapkan isi struk
            buildPrintNota();

            // panggil print browser
            window.print();

            // sembunyikan kembali area struk setelah keluar dari dialog print
            printArea.style.display = 'none';

            // tutup modal
            if (typeof bootstrap !== 'undefined' && modalCetakEl) {
                const modalInstance = bootstrap.Modal.getInstance(modalCetakEl);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        });
    }
</script>

<?= $this->endSection(); ?>
