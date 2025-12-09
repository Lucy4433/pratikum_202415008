document.addEventListener('DOMContentLoaded', () => {
    /* ============================================================
     *  KONFIGURASI DARI GLOBAL WINDOW (DISET DI bayar.php)
     * ============================================================*/
    const totalHarusBayar = window.totalHarusBayar || 0;
    const baseUrlKasir    = window.baseUrlKasir    || '/kasir';

    // key localStorage (harus sama dengan yang dipakai di kasir.js)
    const STORAGE_KEY_CART = 'kasir_cart';
    const STORAGE_KEY_KODE = 'kasir_kode_transaksi';

    /* ============================================================
     *  AMBIL ELEMEN DOM YANG DIPAKAI
     * ============================================================*/
    // Metode pembayaran
    const metodeButtons = document.querySelectorAll('.metode-btn');
    const metodeHidden  = document.getElementById('metode');               // input hidden tampilan
    const metodeInputH  = document.getElementById('input-metode-hidden');  // input hidden untuk form

    // Nominal bayar & kembalian
    const inputBayar   = document.getElementById('input-bayar');
    const inputKembali = document.getElementById('input-kembalian');

    // Form simpan transaksi
    const formBayar     = document.getElementById('formBayar');
    const bayarHidden   = document.getElementById('input-bayar-hidden');
    const kembaliHidden = document.getElementById('input-kembali-hidden');

    // Tombol aksi
    const btnSimpan     = document.getElementById('btnSimpan');
    const btnCetak      = document.getElementById('btnCetak');
    const btnKembali    = document.getElementById('btnKembali');
    const btnBatalBayar = document.getElementById('btnBatalBayar');

    /* ============================================================
     *  FUNGSI UTILITAS: FORMAT & PARSE RUPIAH
     * ============================================================*/
    function formatRupiah(num) {
        num = Number(num) || 0;
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    function parseNumber(str) {
        if (!str) return 0;
        // ambil angka saja (hapus titik, Rp, dll)
        return Number(str.replace(/\D/g, '')) || 0;
    }

    /* ============================================================
     *  SET DEFAULT NILAI DI FORM PEMBAYARAN
     * ============================================================*/
    if (inputBayar) {
        inputBayar.value = formatRupiah(totalHarusBayar);
    }
    if (inputKembali) {
        inputKembali.value = formatRupiah(0);
    }

    /* ============================================================
     *  PILIH METODE PEMBAYARAN (CASH / TRANSFER)
     * ============================================================*/
    metodeButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            // hilangkan kelas active di semua tombol
            metodeButtons.forEach(b => b.classList.remove('active'));
            // beri active di tombol yang diklik
            this.classList.add('active');

            const m = this.getAttribute('data-metode') || 'CASH';
            if (metodeHidden)  metodeHidden.value  = m;
            if (metodeInputH)  metodeInputH.value  = m;
        });
    });

    /* ============================================================
     *  HITUNG KEMBALIAN SAAT NOMINAL BAYAR DIUBAH
     * ============================================================*/
    if (inputBayar) {
        inputBayar.addEventListener('input', function () {
            const nilai = parseNumber(this.value);
            this.value  = nilai ? formatRupiah(nilai) : '';

            const kembali = nilai - totalHarusBayar;
            if (inputKembali) {
                inputKembali.value = formatRupiah(kembali > 0 ? kembali : 0);
            }
        });
    }

    /* ============================================================
     *  SIMPAN TRANSAKSI (KIRIM KE KONTROLLER Kasir::simpan)
     * ============================================================*/
    if (btnSimpan) {
        btnSimpan.addEventListener('click', function () {
            const nilaiBayar = parseNumber(inputBayar ? inputBayar.value : '0');

            // validasi: bayar harus >= total
            if (nilaiBayar < totalHarusBayar) {
                alert('Nominal bayar kurang dari total yang harus dibayar.');
                return;
            }

            const kembali = nilaiBayar - totalHarusBayar;

            if (bayarHidden)   bayarHidden.value   = nilaiBayar;
            if (kembaliHidden) kembaliHidden.value = kembali > 0 ? kembali : 0;

            if (!confirm('Simpan transaksi ini?')) {
                return;
            }

            if (formBayar) {
                formBayar.submit();
            }
        });
    }

    /* ============================================================
     *  CETAK NOTA (PRINT HALAMAN BAYAR)
     * ============================================================*/
    if (btnCetak) {
        btnCetak.addEventListener('click', function () {
            window.print();
        });
    }


    /* ============================================================
     *  TOMBOL KEMBALI & BATAL (LOCALSTORAGE)
     * ============================================================*/

    // Tombol "Kembali" → hanya balik ke kasir, keranjang tetap ada di localStorage
    if (btnKembali) {
        btnKembali.addEventListener('click', function (e) {
            e.preventDefault(); // cegah default kalau pakai <a href="">
            window.location.href = baseUrlKasir;
        });
    }

    // Tombol "Batal" → hapus keranjang + kode transaksi, lalu balik ke kasir (keranjang kosong)
    if (btnBatalBayar) {
        btnBatalBayar.addEventListener('click', function () {
            if (!confirm('Batalkan transaksi ini dan kosongkan keranjang?')) {
                return;
            }

            localStorage.removeItem(STORAGE_KEY_CART);
            localStorage.removeItem(STORAGE_KEY_KODE);

            window.location.href = baseUrlKasir;
        });
    }
});
