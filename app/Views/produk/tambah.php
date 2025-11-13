<?= $this->extend('layout/index'); ?>
<?= $this->section('content') ?>

<div class="card-header d-flex justify-content-between mb-3">
    <h4>Tambah Produk HP</h4>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <form action="/produk/tambah" method="post" style="margin: 15px;">
                <?= csrf_field() ?>

                <div class="form-group mb-2">
                    <label for="nama_produk">Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control form-control-sm" placeholder="Masukkan nama produk" required>
                </div>

                <div class="form-group mb-2">
                    <label for="id_merek">Merek</label>
                    <select name="id_merek" class="form-select form-select-sm" required>
                        <option value="">-- Pilih Merek --</option>
                        <?php foreach ($merek as $mr): ?>
                            <option value="<?= $mr->id_merek ?>"><?= $mr->nama_merek ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group mb-2">
                    <label for="harga">Harga</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="text" id="harga_tampil" class="form-control" placeholder="0" required>
                    </div>
                    <input type="hidden" name="harga" id="harga">
                </div>

                <div class="form-group mb-2">
                    <label for="stok">Stok</label>
                    <input type="number" name="stok" class="form-control form-control-sm" min="0" value="0" required>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    <a href="/produk" class="btn btn-outline-secondary btn-sm">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script format harga -->
<script>
document.addEventListener('DOMContentLoaded', function(){
    const tampil = document.getElementById('harga_tampil');
    const hidden = document.getElementById('harga');

    function onlyDigits(s){ return (s||'').replace(/\D/g,''); }
    function formatID(s){ return new Intl.NumberFormat('id-ID').format(s); }

    tampil.addEventListener('input', function(){
        let val = onlyDigits(this.value);
        this.value = val ? formatID(val) : '';
        hidden.value = val;
    });
});
</script>

<?= $this->endSection() ?>
