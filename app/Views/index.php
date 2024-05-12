<title>Login</title>
<?php include('layout/layout-top.php') ?>

<section class="py-5">
    <div class="container py-5">
        <div class="row">
            <div class="d-flex justify-content-center">
                <div class="card" style="width: 20rem;">
                    <div class="card-body">
                        <h5 class="card-title text-center fw-bold">LOGIN</h5>
                        <?php if(session()->getFlashdata('msg')): ?>
                            <div class="alert alert-danger text-center"><?= session()->getFlashdata('msg') ?></div>
                        <?php endif; ?>
                        <form id="loginForm" action="<?= base_url('userlogin') ?>" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" name="email" id="email" class="form-control" placeholder="Enter Email" />
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" />
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="form-control btn btn-info">Submit</button>
                            </div>
                        </form>
                        <h6><a href="<?= base_url('register'); ?>" class="text-underline">Don't have an account?</a></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<?php include('layout/layout-bottom.php') ?>