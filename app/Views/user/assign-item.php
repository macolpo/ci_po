<title>Assign Item</title>
<?php include('layout/layout-top.php') ?>
<?php include('navbar.php')?>
<?php include('aside.php')?>

<main id="main" class="main">
    <div class="pagetitle container">
        <h1>Manage Assign Item</h1>
        <nav class="d-flex justify-content-between">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-info">Home</a></li>
                <li class="breadcrumb-item active">Assign Item</li>
            </ol>
        </nav>
    </div>

    <section>
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow container">
                    <div class="card-body py-5 ">
                        <div class="table-responsive">
                            <table id="myTable" class="table display w-100 nowrap">
                                <thead>
                                    <tr>
                                        <th scope="col">NO.</th>
                                        <th scope="col">ITEM NAME</th>
                                        <th scope="col">SERIAL NO.</th>
                                        <th scope="col">PRODUCT NO</th>
                                        <th scope="col">STATUS</th>
                                        <th scope="col">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
<?php include('layout/layout-bottom.php') ?>

<!-- Modal -->
<div class="modal fade" id="modalAssign" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">ASSIGN ITEM</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <form id="assignForm">
            <div class="modal-body">
                <div class="mb-3">
                    <input type="hidden" id="itemId">
                </div>
                <div class="mb-3">
                    <span>ITEM NAME:</span>
                    <span id="item-name"></span>
                </div>
                <div class="mb-3">
                <label for="employeeId" class="form-label">ASSIGN TO</label>
                    <select name="employeeId" id="employeeId" class="form-select">
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Assign</button>
            </div>
        </form>
    </div>
  </div>
</div>


<script>
function fetchData() {
    $('#myTable').DataTable({
        processing: true, 
        ajax: {
            url: '<?= base_url("user/assign-item"); ?>',
            type: 'POST',
            dataSrc: ''
        },
        columns: [
            { 
                data: null, 
                render: function(data, type, row, meta) {
                    return meta.row + 1; 
                }
            },
            { data: 'inventory_name' },
            { data: 'inventory_sn' },
            { data: 'inventory_pn' },
            {
                data: 'status',
                render: function(data, type, row) {
                    if (data === '1') {
                        return '<span class="bg-warning-subtle p-1 rounded-3 text-warning">Not Available</span>';
                    } else {
                        return '<span class="bg-success-subtle p-1 rounded-3 text-success">Available</span>';
                    }
                }
            },           
            {
                data: 'inventory_id',   
                render: function(data, type, row) {
                    return `
                        <div class="dropdown">
                            <a class="btn btn-secondary dropdown-toggle dropdown-toggle-split" href="#" data-bs-toggle="dropdown"></a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" type="button" id="assignModal" 
                                data-id="`+ data +`"
                                data-item="`+ row.inventory_name +`"
                                >
                                Assign</a></li>
                            </ul>
                        </div>
                    `;
                }
            },
        ],
    });
}

function deleteData(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This action can not be undone. Do you want to continue?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: '<?= base_url("user/item-delete"); ?>',
                data: {
                    id: id
                }, 
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'The item has been deleted.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        })
                        fetchData();
                    } else if (response.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'The item has been deleted.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        })
                        fetchData();
                    }  
                    
                    else {
                        console.error(result.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    });
}

$(document).ready(function() {
    fetchData();

    $(document).on("click", "#assignModal", function() {
        var modalAssign = new bootstrap.Modal(document.getElementById('modalAssign'));

        var itemId = $(this).data("id");
        var item = $(this).data("item");


        $("#itemId").val(itemId);
        $("#item-name").text(item);

        $.ajax({
            url: '<?= base_url("user/get-employees"); ?>',
            method: 'GET',
            success: function(response) {
                var selectOptions = '';
                if (response.length > 0) {
                    $.each(response, function(index, employee) {
                        selectOptions += '<option value="' + employee.employee_id + '">' + employee.emp_fname + '</option>';
                    });
                } else {
                    selectOptions += '<option value="">No employees available</option>';
                }
                $('#employeeId').html(selectOptions);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });

        modalAssign.show();
    });

    $('#assignForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var itemIdVal = $('#itemId').val(); 

        $.ajax({
            url: '<?= base_url("user/assign-item/") ?>' + itemIdVal,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response)
                if (response.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Item assigned successfully!',
                        showConfirmButton: false,
                        timer: 3000,
                    })
                    $('#modalAssign').modal('hide');
                    $('#myTable').DataTable().ajax.reload();
                } else if (response.status === "validation_error") {
                    $('.text-danger').remove();
                    $.each(response.errors, function(field, errorMessage) {
                        $('[name="' + field + '"]').after('<div class="text-danger">' + errorMessage + '</div>');
                    });
                } else if (response.status === "noid") {
                    $('#modalAssign').modal('hide');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

});
</script>

