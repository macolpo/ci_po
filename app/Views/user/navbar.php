<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <a href="/" class="logo d-flex align-items-center lh-1">
            <img src="" class="align-self-start" alt="">
            <div class="ms-2 d-none d-lg-block fs-6 text-uppercase text-logo">
              
                <span class="fs-6">
                    LOGOO
                </span>
            </div>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>
    <!-- End Logo -->

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <div id="image">
                      <img src="<?= base_url('img/user.png') ?>" alt="Profile" class="rounded-circle">
                    </div>
                    <span class="d-none d-md-block dropdown-toggle ps-2">
                      <?= ucwords($first_name)?>
                    </span>
                </a>
                <!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>
                        <?= ucwords($first_name)?>
                        </h6>
                        <span>
                        <?= ucwords($user_id)?>
                        </span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="<?= base_url('logout') ?>">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sign Out</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

</header>