<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        <li class="nav-item">
            <a class="nav-link" href="/dashboard">
                <i class="typcn typcn-home menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
            </li>

        <?php if (session()->get('role') == 'admin'): ?>
            <li class="nav-item">
                <a class="nav-link" href="/merek">
                    <i class="typcn typcn-tags menu-icon"></i>
                    <span class="menu-title">Merek</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/produk">
                    <i class="typcn typcn-shopping-cart menu-icon"></i>
                    <span class="menu-title">Produk</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/discount">
                    <i class="typcn typcn-ticket menu-icon"></i>
                    <span class="menu-title">Discount</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/supplier">
                    <i class="typcn typcn-briefcase menu-icon"></i>
                    <span class="menu-title">Supplier</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/LaporanAdmin">
                    <i class="typcn typcn-briefcase menu-icon"></i>
                    <span class="menu-title">Laporan</span>
                </a>
            </li>

             <li class="nav-item">
                <a class="nav-link" href="/UserAdmin">
                    <i class="typcn typcn-briefcase menu-icon"></i>
                    <span class="menu-title">User</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/KelolaUser">
                    <i class="typcn typcn-tag menu-icon"></i>
                    <span class="menu-title">Kelola User</span>
                </a>
            </li>

        <?php endif; ?>
        <?php if (session()->get('role') == 'kasir'): ?>
             <li class="nav-item">
                <a class="nav-link" href="/kasir">
                    <i class="typcn typcn-calculator menu-icon"></i>
                    <span class="menu-title">Kasir</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/RiwayatTransaksi">
                    <i class="typcn typcn-time menu-icon"></i>
                    <span class="menu-title">Riwayat Transaksi</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/LaporanKasir">
                    <i class="typcn typcn-chart-bar menu-icon"></i>
                    <span class="menu-title">Laporan</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/ProdukKasir">
                    <i class="typcn typcn-tag menu-icon"></i>
                    <span class="menu-title">Produk</span>
                </a>
            </li>


        <?php endif; ?>
    </ul>
</nav>