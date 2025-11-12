<?= $this->extend('layout/index'); ?>
<?= $this->section('content') ?>
<div class="card-header d-flex justify-content-between mb-3">
    <h4>Daftar Merek</h4>
    <!-- <a href="/merek/tambah" class="btn btn-primary btn-sm">Tambah</a> -->
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <form action="/merek/tambah" method="post" style="margin: 15px;">
                <div class="form-group">
                    <label for="">Merek</label>
                    <input type="text" name="nama_merek" class="form-control form-control-sm" placeholder="" aria-describedby="helpId">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width=10%>No</th>
                        <th>Merek</th>
                        <th width="20%">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($model as $key => $value): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $value->nama_merek ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#model<?= $key ?>"><i class="fa fa-pencil"></i></button>
                                <a href="/merek/hapus/<?= $value->id_merek ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                <div class="modal fade" id="model<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel-2">Ubah Merek</h5>
                                            </div>
                                            <form action="/merek/ubah/<?= $value->id_merek ?>" method="post" style="margin: 6px;">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="">Merek</label>
                                                        <input type="hidden" name="id_merek" value="<?= $value->id_merek ?>" class="form-control form-control-sm" aria-describedby="helpId">
                                                        <input type="text" name="nama_merek" value="<?= $value->nama_merek ?>" class="form-control form-control-sm" aria-describedby="helpId">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>