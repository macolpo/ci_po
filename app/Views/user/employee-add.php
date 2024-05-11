<title>Add Employee</title>
<?php include('layout/layout-top.php') ?>
<?php include('navbar.php')?>
<?php include('aside.php')?>    

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Add Employee</h1>
        <nav class="d-flex justify-content-between">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('user/') ?>" class="text-info">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('user/employee') ?>">Employee</a></li>
                <li class="breadcrumb-item active">Add Employee</li>
            </ol>
        </nav>
    </div>

    <section>
        <div class="row">
            <form id="employeeForm" class="row" enctype="multipart/form-data"> 
                <div class="col-md-8">
                    <div class="card shadow px-3 py-1">
                        <div class="card-body row">
                            <h5 class="card-title fw-bold">Employee Information</h5>
                            <div class="form-group mb-3 col-md-6">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name">
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="middle_name">Middle Name<span class="text-secondary">(Optional)</span></label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name">
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="surname">Surname</label>
                                <input type="text" class="form-control" id="surname" name="surname">
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address">
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
                                <div class="col-sm-12">
                                    <div id="img-preview">
                                    </div>
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
                    <button type="submit" class="btn btn-info fw-semi">Submit</button>
                </div>
            </form>
        </div>
    </section>
</main>
<?php include('layout/layout-bottom.php') ?>

<script>
$(document).ready(function () {
    $('#employeeForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this); 

            $.ajax({
                url: '<?= base_url('user/employee-insert') ?>',
                method: 'POST',
                data: formData,
                processData: false, 
                contentType: false, 
                success: function(response) {
                    if (response.status === "success") {
                        Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Employee created successfully!',
                                showConfirmButton: false,
                                timer: 3000,
                            }).then((result) => {
                                window.location.href = '<?= base_url('user/employee') ?>';
                            });
                        }
                    else if (response.status === "error") {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops!',
                            text: 'Employee already exist!',
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
        
    });
    
});

// image
$('#picture').on('change', function(e) {
    var files = e.target.files;
    $('#img-preview').empty();

    for (var i = 0; i < files.length; i++) {
        var file = files[i];

        // Check if the file is an image
        if (file.type.match('image.*')) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var imgSrc = e.target.result;

                // Create image element and add to col-sm-4
                var imgElement = $('<div class="col-sm-12"><img src="' + imgSrc +
                    '" class="img-fluid img-thumbnail w-100"  alt="Preview"></div>');
                $('#img-preview').append(imgElement);
            };

            // Read the image file as a data URL
            reader.readAsDataURL(file);
        }
    }
});
</script>