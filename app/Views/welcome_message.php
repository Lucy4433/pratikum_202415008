<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Toko HP - Beranda</title>

  <!-- ======= HEADER (CSS) ======= -->
  <link rel="stylesheet" href="<?= base_url('public/css/bootstrap.min.css') ?>">

  <style>
    /* Styling sederhana tema toko HP */
    body { background:#f8f5f9; font-family: 'Helvetica Neue', Arial, sans-serif; }
    .hero { background: linear-gradient(90deg, #fff 60%, #ffeef4 100%); border-radius: 12px; padding: 40px; }
    .product-card { border-radius: 14px; border:1px solid #eee; background:white; padding:18px; }
    .price { font-weight:700; color:#e91e63; }
    .badge-discount { background:#ffe4ef; color:#e91e63; font-weight:700; border-radius:10px; padding:4px 8px; }
    footer { background:#ffffff; border-top:1px solid #eee; padding:20px 0; margin-top:30px; }
  </style>
</head>
<body>

  <!-- ===========================
       HEADER
       (navbar / brand / search)
       =========================== -->
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
      <div class="container">
        <a class="navbar-brand font-weight-bold" href="<?= base_url() ?>">HpStore</a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navMain"
          aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
          <ul class="navbar-nav ml-auto align-items-lg-center">
            <li class="nav-item"><a class="nav-link" href="<?= base_url() ?>">Beranda</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('page/produk') ?>">Produk</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('page/promo') ?>">Promo</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('page/kontak') ?>">Kontak</a></li>
            <li class="nav-item ml-lg-3">
              <form class="form-inline" action="<?= base_url('search') ?>" method="get">
                <input class="form-control form-control-sm" name="q" type="search" placeholder="Cari HP..." aria-label="Search">
                <button class="btn btn-sm btn-outline-primary ml-2">Cari</button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <!-- ===========================
       BODY
       (hero, kategori, daftar produk)
       =========================== -->
  <main class="container my-4">

    <!-- Hero -->
    <section class="hero mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h1 class="display-4 font-weight-bold">HP Terbaru & Terbaik</h1>
          <p class="lead text-muted">Koleksi smartphone resmi — garansi resmi, cicilan ringan, dan pengiriman cepat.</p>
          <a