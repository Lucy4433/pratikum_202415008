<div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content"> <!-- modal pop up tambah dsikon-->

            <div class="modal-header">
                <h5 class="modal-title">Tambah Diskon Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> <!--header modal-->

            <form action="/discount/tambah" method="post" id="formTambahDiskon">
                <?= csrf_field() ?> <!--form menambah diskon-->

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Produk</label>
                        <select name="id_produk" class="form-select form-select-sm" required> <!--dropdown daftarpilihan-->
                            <option value="">-- Pilih Produk --</option> <!-- Jika user tidak memilih produk, form tidak bisa disimpan.-->
                            <?php foreach ($produk as $p): ?> <!-- Looping (perulangan) untuk menampilkan semua produk dari database-->
                                <option value="<?= esc($p->id_produk) ?>"><?= esc($p->nama_produk) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Besaran Diskon (%)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">%</span> <!-- megisih angka dsikon-->
                            <input type="number" name="besaran" class="form-control form-control-sm" min="0" max="100" required>
                        </div>
                    </div> <!--input besaran dsikon-->

                    <div class="mb-3">
                        <label class="form-label">Periode Diskon</label>
                        <div class="row g-2">
                            <div class="col">
                                <label class="form-label small text-muted">Mulai</label>
                                <input type="date" name="dari_date" class="form-control form-control-sm" required>
                            </div>
                            <div class="col">
                                <label class="form-label small text-muted">Selesai</label>
                                <input type="date" name="sampai_date" class="form-control form-control-sm" required>
                            </div>
                        </div> <!-- input periode dsikon mulai-selesai-->
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>
