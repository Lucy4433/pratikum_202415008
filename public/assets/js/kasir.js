document.addEventListener("DOMContentLoaded", () => {
  const PRODUCTS = Array.isArray(window.PRODUCTS) ? window.PRODUCTS : [];

  const cartBody = document.getElementById("cart-body");
  const inputCari = document.getElementById("input-cari");

  const btnTambahDariCari = document.getElementById("btnTambahDariCari");
  const btnTambahManual = document.getElementById("btnTambahManualKosong");

  const txtSubtotal = document.getElementById("txt-subtotal");
  const txtTotalDiskon = document.getElementById("txt-total-diskon");
  const txtTotal = document.getElementById("txt-total");

  const formKasir = document.getElementById("formKasir");
  const btnBayar = document.getElementById("btnBayar");
  const btnBatal = document.getElementById("btnBatal");

  const inputItemsHidden = document.getElementById("input-items");
  const inputSubtotalH = document.getElementById("input-subtotal");
  const inputTotalDiskonH = document.getElementById("input-total-diskon");
  const inputGrandTotalH = document.getElementById("input-grand-total");

  const kodeTransaksiSpan = document.getElementById("kode-transaksi");
  const inputNoPenjualan = document.getElementById("input-no-penjualan");

  let cart = [];
  let subtotalGlobal = 0;
  let totalDiskonGlobal = 0;
  let totalGlobal = 0;
  let kodeTransaksi = "";

  const STORAGE_CART = "kasir_cart";
  const STORAGE_KODE = "kasir_kode";

  /* ================= UTIL ================= */

  const num = (v) => Number(v) || 0;

  const rupiah = (n) => "Rp " + num(n).toLocaleString("id-ID");

  const findProductByName = (name) =>
    PRODUCTS.find((p) => p.nama.toLowerCase() === name.toLowerCase());

  function generateKode() {
    if (kodeTransaksi) return kodeTransaksi;

    const d = new Date();
    kodeTransaksi = `INV-${d.getFullYear()}${(d.getMonth() + 1)
      .toString()
      .padStart(2, "0")}${d.getDate().toString().padStart(2, "0")}-${Math.floor(
      Math.random() * 900 + 100
    )}`;

    kodeTransaksiSpan.textContent = kodeTransaksi;
    if (inputNoPenjualan) inputNoPenjualan.value = kodeTransaksi;

    return kodeTransaksi;
  }

  function hitungSubtotalItem(item) {
    const bruto = num(item.harga) * num(item.qty);
    const disk = bruto * (num(item.diskon) / 100);
    return bruto - disk;
  }

  function hitungTotal() {
    let sub = 0;
    let dis = 0;

    cart.forEach((i) => {
      const bruto = num(i.harga) * num(i.qty);
      const pot = bruto * (num(i.diskon) / 100);
      sub += bruto;
      dis += pot;
    });

    subtotalGlobal = sub;
    totalDiskonGlobal = dis;
    totalGlobal = sub - dis;

    txtSubtotal.textContent = rupiah(sub);
    txtTotalDiskon.textContent = rupiah(dis);
    txtTotal.textContent = rupiah(totalGlobal);
  }

  /* ================= RENDER ================= */

  function renderCart() {
    cartBody.innerHTML = "";

    if (cart.length === 0) {
      cartBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        Keranjang masih kosong
                    </td>
                </tr>
            `;
      kodeTransaksiSpan.textContent = "-";
      hitungTotal();
      save();
      return;
    }

    cart.forEach((item, i) => {
      item.qty = Math.max(1, num(item.qty));

      const tr = document.createElement("tr");
      tr.dataset.index = i;

      tr.innerHTML = `
                <td class="text-center">${i + 1}</td>
                <td>${
                  item.isManual
                    ? `<input class="form-control form-control-sm nama-manual" value="${
                        item.nama || ""
                      }">`
                    : item.nama
                }
                </td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-secondary btn-minus">-</button>
                    <span class="mx-2">${item.qty}</span>
                    <button class="btn btn-sm btn-outline-secondary btn-plus">+</button>
                </td>
                <td class="text-end">
                    ${
                      item.isManual
                        ? `<input type="number" class="form-control form-control-sm harga-manual text-end" value="${item.harga}">`
                        : rupiah(item.harga)
                    }
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm diskon-input text-center"
                           min="0" max="100" value="${num(item.diskon)}">
                </td>
                <td class="text-end subtotal-cell">
                    ${rupiah(hitungSubtotalItem(item))}
                </td>
                <td class="text-center">
                    <button class="btn btn-danger btn-sm btn-hapus">Hapus</button>
                </td>
            `;
      cartBody.appendChild(tr);
    });

    hitungTotal();
    save();
  }

  /* ================= STORAGE ================= */

  function save() {
    localStorage.setItem(STORAGE_CART, JSON.stringify(cart));
    localStorage.setItem(STORAGE_KODE, kodeTransaksi || "");
  }

  function load() {
    try {
      cart = JSON.parse(localStorage.getItem(STORAGE_CART)) || [];
      kodeTransaksi = localStorage.getItem(STORAGE_KODE) || "";
      if (kodeTransaksi) kodeTransaksiSpan.textContent = kodeTransaksi;
    } catch {
      cart = [];
    }
  }

  /* ================= EVENTS ================= */

  btnTambahDariCari.onclick = () => {
    const nama = inputCari.value.trim();
    if (!nama) return;

    const p = findProductByName(nama);
    if (!p) return alert("Produk tidak ditemukan");

    let item = cart.find((i) => i.id === p.id && !i.isManual);
    if (item) {
      if (item.qty + 1 > p.stok) return alert("Stok tidak cukup");
      item.qty++;
    } else {
      cart.push({
        id: p.id,
        nama: p.nama,
        harga: num(p.harga),
        qty: 1,
        diskon: num(p.diskon),
        isManual: false,
        stokMax: num(p.stok),
      });
    }

    generateKode();
    inputCari.value = "";
    renderCart();
  };

  btnTambahManual.onclick = () => {
    cart.push({
      id: null,
      nama: "",
      harga: 0,
      qty: 1,
      diskon: 0,
      isManual: true,
      stokMax: 0,
    });
    generateKode();
    renderCart();
  };

  cartBody.onclick = (e) => {
    const tr = e.target.closest("tr");
    if (!tr) return;
    const i = tr.dataset.index;
    const item = cart[i];

    if (e.target.classList.contains("btn-minus") && item.qty > 1) {
      item.qty--;
    }
    if (e.target.classList.contains("btn-plus")) {
      if (!item.isManual && item.qty + 1 > item.stokMax) {
        return alert("Stok tidak mencukupi");
      }
      item.qty++;
    }
    if (e.target.classList.contains("btn-hapus")) {
      cart.splice(i, 1);
    }
    renderCart();
  };

  btnBayar.onclick = () => {
    if (!cart.length) return alert("Keranjang kosong");
    inputItemsHidden.value = JSON.stringify(cart);
    inputSubtotalH.value = subtotalGlobal;
    inputTotalDiskonH.value = totalDiskonGlobal;
    inputGrandTotalH.value = totalGlobal;
    formKasir.submit();
  };

  btnBatal.onclick = () => {
    if (!confirm("Batalkan transaksi?")) return;
    cart = [];
    localStorage.clear();
    renderCart();
  };

  load();
  renderCart();
});
