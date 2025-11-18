<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row"
     style="background-color:#212842 !important;">
  
  <div class="navbar-brand-wrapper d-flex justify-content-center"
       style="background-color:#212842 !important;">
    
    <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100"
         style="background-color:#212842 !important;">

      <!-- LOGO BESAR -->
      <a class="navbar-brand brand-logo" href="<?= base_url('/') ?>">
        <img src="<?= base_url('assets/images/Logo_hp.png') ?>" 
             alt="logo" 
             style="height:120px; object-fit:contain;">
      </a>

      <!-- LOGO MINI -->
      <a class="navbar-brand brand-logo-mini" href="<?= base_url('/') ?>">
        <img src="<?= base_url('assets/images/Logo_kecil.png') ?>" 
             alt="logo-mini" 
             style="height:60px; object-fit:contain;">
      </a>

      <!-- TOMBOL MENU -->
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <span class="typcn typcn-th-menu" style="color:white;"></span>
      </button>

    </div>
  </div>

  <!-- BAGIAN KANAN NAVBAR -->
  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end"
       style="background-color:#212842 !important; color:white !important;">

    <ul class="navbar-nav me-lg-2">

      <!-- DROPDOWN PROFIL / PROFIL TOKO -->
      <li class="nav-item nav-profile dropdown">
        <a class="nav-link" href="#" data-bs-toggle="dropdown" id="profileDropdown" style="color:white !important;">
          <img src="<?= base_url('assets/images/faces/face5.jpg') ?>" alt="profile"/>
          <!-- tampilkan nama toko jika ada, kalau tidak pakai default "SHOP STORE" -->
          <span class="nav-profile-name" style="color:white !important;">
            <?= esc($profil->nama_toko ?? 'SHOP STORE') ?>
          </span>
        </a>

        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
          <!-- PROFIL TOKO → BUKA MODAL -->
          <a class="dropdown-item"
             href="#"
             data-bs-toggle="modal"
             data-bs-target="#modalProfilToko">
            <i class="typcn typcn-cog-outline text-primary"></i>
            Profil Toko
          </a>

          <!-- LOGOUT -->
          <a class="dropdown-item" href="<?= base_url('logout') ?>">
            <i class="typcn typcn-eject text-primary"></i>
            Logout
          </a>
        </div>
      </li>

      <li class="nav-item nav-user-status dropdown">
        <p class="mb-0" style="color:white !important;">Last login was 23 hours ago.</p>
      </li>

    </ul>

    <!-- BAGIAN NAV KANAN -->
    <ul class="navbar-nav navbar-nav-right">

      <!-- TANGGAL -->
      <li class="nav-item nav-date dropdown">
        <a class="nav-link d-flex justify-content-center align-items-center" href="javascript:;" style="color:white !important;">
          <h6 class="date mb-0" style="color:white !important;">Today : Mar 23</h6>
          <i class="typcn typcn-calendar" style="color:white !important;"></i>
        </a>
      </li>

      <!-- PESAN -->
      <li class="nav-item dropdown">
        <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center"
           id="messageDropdown"
           href="#"
           data-bs-toggle="dropdown"
           style="color:white !important;">
          <i class="typcn typcn-mail mx-0" style="color:white !important;"></i>
          <span class="count"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
        </div>
      </li>

      <!-- NOTIF -->
      <li class="nav-item dropdown me-0">
        <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center"
           id="notificationDropdown"
           href="#"
           data-bs-toggle="dropdown"
           style="color:white !important;">
          <i class="typcn typcn-bell mx-0" style="color:white !important;"></i>
          <span class="count"></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
        </div>
      </li>

    </ul>

    <!-- MENU MOBILE -->
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="typcn typcn-th-menu" style="color:white !important;"></span>
    </button>

  </div>
</nav>

<!-- =============== MODAL PROFIL TOKO ================= -->
<div class="modal fade" id="modalProfilToko" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Profil Toko</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="<?= base_url('profiltoko/simpan') ?>" method="post">
        <?= csrf_field() ?>

        <div class="modal-body">
          <!-- id_profil (hidden) kalau sudah ada di database -->
          <input type="hidden" name="id_profil" value="<?= esc($profil->id_profil ?? '') ?>">

          <div class="mb-3">
            <label class="form-label">Nama Toko</label>
            <input type="text"
                   name="nama_toko"
                   class="form-control form-control-sm"
                   value="<?= esc($profil->nama_toko ?? '') ?>"
                   required>
          </div>

          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat"
                      class="form-control form-control-sm"
                      rows="3"
                      required><?= esc($profil->alamat ?? '') ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">No. Telepon</label>
            <input type="text"
                   name="no_telp"
                   class="form-control form-control-sm"
                   value="<?= esc($profil->no_telp ?? '') ?>">
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
<!-- ============= END MODAL PROFIL TOKO ============== -->
