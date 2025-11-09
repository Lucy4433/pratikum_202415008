<?= $this->extend('layout/index');?>
<?= $this->section('content') ?>
<div class="row">
        <div class="card-header d-flex justify-content-between mb-3">
            <h4>Daftar Merek</h4>
            <a href="/merek/tambah" class="btn btn-primary btn-sm">Tambah</a>
        </div>
        <div class="card body">
            <table class="table tambel-bordered">
                <tr>
                    <th>No</th>
                    <th>Merek</th>
                    <th>Aktion</th>
                </tr>
                <?php foreach($model as $key => $value):?>
                    <tr>
                        <td><?= $key+1?></td>
                        <td><?= $value->nama_merek?></td>
                        <td>
                            <a href="/merek/ubah/<?= $value->id_merek?>" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>
                            <a href="/merek/hapus/<?= $value->id_merek?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach;?>
            </table>
        </div>
</div>
<?= $this->endSection() ?>