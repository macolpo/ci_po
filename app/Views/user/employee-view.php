<title>Dashboard - Edit Employee</title>
<?php include('layout/layout-top.php') ?>
<?php include('navbar.php')?>
<?php include('aside.php')?>

<main id="main" class="main">
    <div class="pagetitle container">
        <h1>View Employee Information</h1>
        <nav class="d-flex justify-content-between">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-info">Home</a></li>
                <li class="breadcrumb-item active">View Employee</li>

            </ol>
        </nav>
    </div>

    <section>
        <div class="row">
                <div class="col-md-4">
                    <div class="card shadow container">
                        <div class="card-body">
                            <h5 class="card-title fw-bold py-4">Employee Image</h5>
                            <div class="col-sm-12" id="img-preview">
                                <img src="<?= base_url('images/' . $employee['emp_image']) ?>" class="img-fluid img-thumbnail"  alt="Employee Image">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow container">
                        <div class="card-body">
                            <h5 class="card-title fw-bold py-4">Employee Information</h5>
                                <div class="col-sm-12 mb-3">
                                    <span class="fw-bold">Fullname:</span>
                                    <?= ucwords($employee['emp_fname'] . ' '. substr($employee['emp_mname'],0, 1) .'. '.$employee['emp_sname']) ?>
                                </div>

                                <div class="col-sm-12 mb-3">
                                    <span class="fw-bold">Address:</span> <?= $employee['emp_address'] ?>
                                </div>

                            
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary text-light"
                        onclick="window.location.href = '<?= base_url('user/employee') ?>' ">
                        Back
                    </button>
                </div>

            </div>
        </div>
    </section>
</main>
<?php include('layout/layout-bottom.php') ?>

<script>
$(document).ready(function () {
    $('#employeeFormEdit').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: '<?= base_url("user/employee-update/{$employee['employee_id']}") ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Employee Edit successfully!',
                    }).then((result) => {
                        window.location.href = '<?= base_url('user/employee') ?>';
                    });
                } 
                else if (response.status == "validation_error") {
                    $('.text-danger').remove();
                    $.each(response.errors, function(field, errorMessage) {
                        $('input[name="' + field + '"]').after('<div class="text-danger">' + errorMessage + '</div>');
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});
</script>
