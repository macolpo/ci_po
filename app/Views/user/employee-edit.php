<title>Edit Employee</title>
<?php include('layout/layout-top.php') ?>
<?php include('navbar.php')?>
<?php include('aside.php')?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit Employee</h1>
        <nav class="d-flex justify-content-between">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('user/') ?>" class="text-info">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('user/employee') ?>">Employee</a></li>
                <li class="breadcrumb-item active">Edit Employee</li>
            </ol>
        </nav>
    </div>

    <section>
        <div class="row">
            <form id="employeeFormEdit" class="row" enctype="multipart/form-data"> 
                <div class="col-md-8">
                    <div class="card shadow px-3 py-1">
                        <div class="card-body row">
                            <h5 class="card-title fw-bold">Employee Information</h5>
                            <div class="form-group mb-3 col-md-6">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?= $employee['emp_fname'] ?>">
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?= $employee['emp_mname'] ?>">
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="surname">Surname</label>
                                <input type="text" class="form-control" id="surname" name="surname" value="<?= $employee['emp_sname'] ?>">
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?= $employee['emp_address'] ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow px-3 py-1">
                        <h5 class="card-title fw-bold">Upload Image</h5>
                        <div class="card-body">
                            <div class="col-sm-12 mb-3">
                                <input type="file" class="form-control" id="picture" name="picture"
                                    accept="image/*">
                            </div>

                            <div class="row">
                                <div class="col-sm-12" id="img-preview">
                                    <img src="<?= base_url('images/' . $employee['emp_image']) ?>" class="img-thumbnail img-fluid w-100" alt="Employee Image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary text-light"
                        onclick="window.location.href = '<?= base_url('user/employee') ?>' ">
                        Back
                    </button>
                    <button type="submit" class="btn btn-info fw-semi">Update</button>
                </div>
            </form>
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
                        text: 'Employee update successfully!',
                        showConfirmButton: false,
                        timer: 2000,
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
// image
$('#picture').on('change', function(e) {
    var files = e.target.files;
    $('#img-preview').empty();

    for (var i = 0; i < files.length; i++) {
        var file = files[i];

        if (file.type.match('image.*')) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var imgSrc = e.target.result;

                var imgElement = $('<div class="col-lg-12"><img src="' + imgSrc +
                    '" class="img-thumbnail img-fluid w-100" alt="Preview"></div>');
                $('#img-preview').append(imgElement);
            };

            reader.readAsDataURL(file);
        }
    }
});
// image
validateImageType(document.getElementById('picture'));
function validateImageType(input) {
    input.addEventListener('change', function() {
        const file = this.files[0];
        if (file && !file.type.startsWith('image/')) {
            Swal.fire({
                icon: 'warning',
                title: 'File Type Error',
                text: 'Please select a JPEG or PNG file',
            });
            this.value = '';
        }
    });
}
</script>
