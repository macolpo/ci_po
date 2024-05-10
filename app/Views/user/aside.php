<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <div>
            <li class="nav-heading">Sample crud</li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="<?= base_url('user/employee') ?>">
                    <i class="bi bi-person"></i>
                    <span>Employee</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#payroll-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-credit-card"></i>
                    <span>Inventory</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="payroll-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="<?= base_url('user/item-category'); ?>">
                            <i class="bi bi-circle"></i><span>Item Category</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('user/item-list') ?>">
                            <i class="bi bi-circle"></i><span>Item List</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('user/assign-item') ?>">
                            <i class="bi bi-circle"></i><span>Assign Item</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('user/return-item') ?>">
                            <i class="bi bi-circle"></i><span>Return Item</span>
                        </a>
                    </li>
                    
                </ul>
            </li>
        </div>
        <!-- Human Resouce Management -->
    </ul>
</aside>
<!-- End Sidebar-->