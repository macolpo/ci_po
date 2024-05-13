<title>View Employee</title>
<?php include('layout/layout-top.php') ?>
<?php include('navbar.php')?>
<?php include('aside.php')?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>View Employee</h1>
        <nav class="d-flex justify-content-between align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('user/') ?>" class="text-info">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('user/employee') ?>">Employee</a></li>
                <li class="breadcrumb-item active">View Employee</li>
            </ol>
            <div>
                <a href="<?= base_url('images/' . $employee['emp_image']) ?>" class="btn btn-info text-light p-0 p-1 align-items-center"
                download data-bs-toggle="tooltip" data-bs-placement="left"
                data-bs-title="Download Image">
                    <i class="bi bi-images"></i>
                </a>

                <button type="button" id="saveAsPDF" class="btn btn-info text-light p-0 p-1 align-items-center"
                    data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Download">
                    <i class="bi bi-download"></i>
                </button>
            </div>
          
        </nav>
    </div>

    <section>
        <div class="row">
            <div class="container">
                <div class="col-sm-12">
                    <div id="printableArea">
                        <div class="card shadow px-4 py-2"> 
                            <div class="card-header text-primary-emphasis fw-bold">
                                Employee Information
                            </div>
                            <div class="row p-3">
                                <div class="col-sm-4">
                                    <a type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <img src="<?= base_url('images/' . $employee['emp_image']) ?>" class="img-fluid img-thumbnail w-100"  alt="Employee Image">
                                    </a>
                                </div>
                                <div class="col-sm-8">
                                     <div>
                                        <span class="fw-bold">Employee ID:</span>
                                        <?= ucwords($employee['employee_id']) ?>
                                    </div>
                                    <div>
                                        <span class="fw-bold">Fullname:</span>
                                        <?= ucwords($employee['emp_fname'].' '. substr($employee['emp_mname'],0,1).'. '. $employee['emp_sname']) ?>
                                    </div>
                                    <div>
                                        <span class="fw-bold">Address:</span>
                                        <?= ucwords($employee['emp_address']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <a href="<?= base_url('user/employee') ?>" class="btn btn-sm bg-secondary text-light">Back</a>
                </div>
            </div>
        </div>
    </section>
</main>
<?php include('layout/layout-bottom.php') ?>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
        <img src="<?= base_url('images/' . $employee['emp_image']) ?>"></img>
    </div>
  </div>
</div>
<script>
$(document).ready(function () {
    $('#employeeFormEdit').submit(function(e) {
        e.preventDefault();
        var forsmata = new Forsmata(this);

        $.ajax({
            url: '<?= base_url("user/employee-update/{$employee['employee_id']}") ?>',
            method: 'POST',
            data: forsmata,
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
// download pdf
document.getElementById('saveAsPDF').addEventListener('click', function() {
    // Select the div to be converted to PDF
    const element = document.getElementById('printableArea');

    // Set options for the PDF conversion
    const options = {
        margin: 10,
        filename: '<?= $employee['emp_fname'] ?> information.pdf',
        image: {
            type: 'jpeg',
            quality: 0.98
        },
        html2canvas: {
            scale: 2
        },
        jsPDF: {
            unit: 'mm',
            format: 'a4',
            orientation: 'portrait'
        }
    };

    html2pdf().from(element).set(options).save();
});
</script>
