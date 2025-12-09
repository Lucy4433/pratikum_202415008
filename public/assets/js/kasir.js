document.addEventListener('DOMContentLoaded', () => {
    // Data produk dari PHP -> JS global (diisi dari view)
    const PRODUCTS = window.PRODUCTS || [];

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

    // struktur item: {id, nama, harga, qty, diskon, isManual, stokMax}
    let cart = [];

    let subtotalGlobal    = 0;
    let totalDiskonGlobal = 0;
    let totalGlobal       = 0;
    let kodeTransaksi     = '';

    // ========== LOCAL STORAGE ==========
    const STORAGE_KEY_CART = 'kasir_cart';
    const STORAGE_KEY_KODE = 'kasir_kode_transaksi';

    function saveCartToStorage() {
        try {
            localStorage.setItem(STORAGE_KEY_CART, JSON.stringify(cart));
            localStorage.setItem(STORAGE_KEY_KODE, kodeTransaksi || '');
        } catch (e) {
            console.error('Gagal menyimpan cart ke storage', e);
        }
    }

    function loadCartFromStorage() {
        try {
            const cartStr = localStorage.getItem(STORAGE_KEY_CART);
            const kode    = localStorage.getItem(STORAGE_KEY_KODE);

            if (cartStr) {
                const parsed = JSON.parse(cartStr);
                if (Array.isArray(parsed)) {
                    cart = parsed;
                }
            }

            if (kode) {
                kodeTransaksi = kode;
                if (kodeTransaksiSpan) {
                    kodeTransaksiSpan.textContent = kodeTransaksi;
                }
                if (inputNoPenjualan) {
                    inputNoPenjualan.value = kodeTransaksi;
                }
            }
        } catch (e) {
            console.error('Gagal load cart dari storage', e);
            cart = [];
            kodeTransaksi = '';
        }
    }

    // ========== UTIL ==========
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

    /* ============================================================
     *  RENDER KERANJANG (TABEL ITEM)
     * ============================================================*/
    function renderCart() {
        cartBody.innerHTML = '';

        // keranjang kosong
        if (cart.length === 0) {
            const trEmpty = document.createElement('tr');
            const td      = document.createElement('td');
            td.colSpan    = 7;
            td.className  = 'text-center text-muted';
            td.textContent = 'Keranjang masih kosong.';
            trEmpty.appendChild(td);
            cartBody.appendChild(trEmpty);

            hitungTotal();

            kodeTransaksi = '';
            if (kodeTransaksiSpan) {
                kodeTransaksiSpan.textContent = 'Belum ada (tambahkan produk)';
            }
            if (inputNoPenjualan) {
                inputNoPenjualan.value = '';
            }

            saveCartToStorage();
            return;
        }

        cart.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.dataset.index = index; // pakai index, aman utk manual & produk

            // 1. No
            const tdNo = document.createElement('td');
            tdNo.className = 'text-center align-middle';
            tdNo.textContent = index + 1;
            tr.appendChild(tdNo);

            // 2. Produk
            const tdNama = document.createElement('td');
            tdNama.className = 'align-middle';
            if (item.isManual) {
                const inputNama = document.createElement('input');
                inputNama.type = 'text';
                inputNama.className = 'form-control form-control-sm';
                inputNama.value = item.nama || '';

                inputNama.addEventListener('change', () => {
                    const namaBaru = (inputNama.value || '').trim();
                    item.nama = namaBaru;

                    if (!namaBaru) {
                        updateSubtotalCell(tr, item);
                        hitungTotal();
                        saveCartToStorage();
                        return;
                    }

                    const p = findProductByName(namaBaru);
                    if (p) {
                        // auto isi dari produk DB
                        item.id       = p.id;
                        item.nama     = p.nama;
                        item.harga    = p.harga;
                        item.diskon   = p.diskon ?? 0;
                        item.isManual = false;
                        item.stokMax  = Number(p.stok) || 0;

                        renderCart();
                    } else {
                        updateSubtotalCell(tr, item);
                        hitungTotal();
                        saveCartToStorage();
                    }
                });

                tdNama.appendChild(inputNama);
            } else {
                tdNama.textContent = item.nama;
            }
            tr.appendChild(tdNama);

            // 3. Qty
            const tdQty = document.createElement('td');
            tdQty.className = 'text-center align-middle';
            tdQty.innerHTML = `
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary btn-qty-minus">-</button>
                    <button type="button" class="btn btn-light disabled qty-text">${item.qty}</button>
                    <button type="button" class="btn btn-outline-secondary btn-qty-plus">+</button>
                </div>
            `;
            tr.appendChild(tdQty);

            // 4. Harga
            const tdHarga = document.createElement('td');
            tdHarga.className = 'text-end align-middle';
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
                    saveCartToStorage();
                });
                tdHarga.appendChild(inputHarga);
            } else {
                tdHarga.textContent = formatRupiah(item.harga);
            }
            tr.appendChild(tdHarga);

            // 5. Diskon
            const tdDiskon = document.createElement('td');
            tdDiskon.className = 'text-center align-middle';
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
                saveCartToStorage();
            });
            tdDiskon.appendChild(inputDiskon);
            tr.appendChild(tdDiskon);

            // 6. Subtotal
            const tdSub = document.createElement('td');
            tdSub.className   = 'text-end align-middle subtotal-cell';
            tdSub.textContent = formatRupiah(hitungSubtotalItem(item));
            tr.appendChild(tdSub);

            // 7. Aksi
            const tdAksi = document.createElement('td');
            tdAksi.className = 'text-center align-middle';
            const btnHapus = document.createElement('button');
            btnHapus.type      = 'button';
            btnHapus.className = 'btn btn-danger btn-sm btn-hapus';
            btnHapus.textContent = 'Hapus';
            tdAksi.appendChild(btnHapus);
            tr.appendChild(tdAksi);

            cartBody.appendChild(tr);
        });

        hitungTotal();
        saveCartToStorage();
    }

    // ========== TAMBAH DARI CARI ==========
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

        const stokTersedia = Number(p.stok) || 0;
        if (stokTersedia <= 0) {
            alert('Stok produk "' + p.nama + '" sudah habis.');
            return;
        }

        let item = cart.find(c => !c.isManual && c.id === p.id);
        if (item) {
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
                diskon: p.diskon ?? 0,
                isManual: false,
                stokMax: stokTersedia
            };
            cart.push(item);
        }

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

    // ========== TAMBAH PRODUK MANUAL ==========
    function tambahProdukManual() {
        const item = {
            id: null,
            nama: '',
            harga: 0,
            qty: 1,
            diskon: 0,
            isManual: true,
            stokMax: 0
        };

        cart.push(item);

        if (!kodeTransaksi) {
            generateKodeTransaksi();
        }

        renderCart();
    }

    btnTambahManual.addEventListener('click', tambahProdukManual);

    // ========== EVENT QTY +/- & HAPUS ==========
    cartBody.addEventListener('click', function (e) {
        const tr = e.target.closest('tr');
        if (!tr) return;

        const idx = parseInt(tr.dataset.index);
        if (Number.isNaN(idx)) return;

        const item = cart[idx];
        if (!item) return;

        if (e.target.classList.contains('btn-qty-minus')) {
            if (item.qty > 1) {
                item.qty -= 1;
                renderCart();
            }
        }

        if (e.target.classList.contains('btn-qty-plus')) {
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
            cart.splice(idx, 1);
            renderCart();
        }
    });

    // ========== BAYAR ==========
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

    // ========== BATAL ==========
    btnBatal.addEventListener('click', function () {
        if (!confirm('Batalkan transaksi dan kosongkan keranjang?')) {
            return;
        }
        cart = [];
        renderCart();
        inputCari.value = '';

        localStorage.removeItem(STORAGE_KEY_CART);
        localStorage.removeItem(STORAGE_KEY_KODE);
    });

    // ========== LOAD AWAL ==========
    loadCartFromStorage();
    renderCart();
});
