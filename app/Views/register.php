<title>Register</title>
<?php include('layout/layout-top.php') ?>


<section class="py-5">
    <div class="container py-5">
        <div class="row">
            <div class="d-flex justify-content-center">
                <div class="card" style="width: 26rem;">
                    <div class="card-body">
                        <h5 class="card-title text-center fw-bold my-3">REGISTER</h5>
                        <form class="row" id="registerForm">
                            <div class="col-sm-6 mb-3">
                                <label for="firstname" class="form-label">Firstname</label>
                                <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Enter Firstname" />
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="surname" class="form-label">Surname</label>
                                <input type="text" name="surname" id="surname" class="form-control" placeholder="Enter Surname" />
                            </div>

                            <div class="col-sm-12 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" name="address" id="address" class="form-control" placeholder="Enter Address" />
                            </div>

                            <div class="col-sm-12 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" name="email" id="email" class="form-control" placeholder="Enter Email" />
                            </div>

                            <div class="col-sm-12 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" />
                            </div>

                            <div class="col-sm-12 mb-3">
                                <label for="cpassword" class="form-label">Confirm Password</label>
                                <input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Confirm Password" />
                            </div>

                            <div class="col-sm-12 mb-3">
                                <button type="submit" class="form-control btn btn-info">Submit</button>
                            </div>
                        </form>
                        <h6><a href="<?= base_url('/'); ?>" class="text-underline">I already have an accout?</a></h6>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>


<?php include('layout/layout-bottom.php') ?>


<script>
$(document).ready(function () {
    $('#registerForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this); 

        if (this.checkValidity() === true) {
            $.ajax({
                url: '<?= base_url('register') ?>',
                method: 'POST',
                data: formData,
                processData: false, 
                contentType: false, 
                success: function(response) {
                    if (response.status === "success") {
                        Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Register successfully!',
                            }).then((result) => {
                                window.location.href = '<?= base_url('/') ?>';
                            });
                        }
                    else if (response.status === "error") {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops!',
                            text: 'Email already exist!',
                        })
                    }
                    else if (response.status === "validation_error") {
                        $('.text-danger').remove();
                        $.each(response.errors, function(field, errorMessage) {
                            $('[name="' + field + '"]').after('<div class="text-danger">' + errorMessage + '</div>');
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            $(this).addClass('was-validated');
        }
    });
});
</script>