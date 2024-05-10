<title>Item List</title>
<?php include('layout/layout-top.php') ?>
<?php include('navbar.php')?>
<?php include('aside.php')?>

<main id="main" class="main">
    <div class="pagetitle container">
        <h1>Manage Return Item</h1>
        <nav class="d-flex justify-content-between">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-info">Home</a></li>
                <li class="breadcrumb-item active">Return Item</li>
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
                                        <th scope="col">INVENTORY ID</th>
                                        <th scope="col">ITEM NAME</th>
                                        <th scope="col">SERIAL NO.</th>
                                        <th scope="col">PRODUCT NO</th>
                                        <th scope="col">ASSIGN TO</th>
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
<div class="modal fade" id="modalReturn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">RETURN ITEM</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <form id="returnForm">
            <div class="modal-body">
                <div class="mb-3">
                    <span>Item ID:</span>
                    <span id="itemId"></span>
                </div>
                <div class="mb-3">
                    <span>Item Name:</span>
                    <span id="itemname"></span>
                </div>
                <div class="mb-3">
                    <span>Name:</span>
                    <span id="employeeName"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Return</button>
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
            url: '<?= base_url("user/return-item/"); ?>',
            type: 'POST',
            dataSrc: ''
        },
        columns: [
            { data: 'inventory_id' },
            { data: 'inventory_name' },
            { data: 'inventory_sn' },
            { data: 'inventory_pn' },
            { data: 'name' },           
            {
                data: 'inventory_id',   
                render: function(data, type, row) {
                    return `
                        <div class="dropdown">
                            <a class="btn btn-secondary dropdown-toggle dropdown-toggle-split" href="#" data-bs-toggle="dropdown"></a>
                            <ul class="dropdown-menu">
                                <li>
                                <a class="dropdown-item" type="button" id="returnModal" 
                                    data-id="`+ data +`"
                                    data-item="`+ row.inventory_name +`"
                                    data-employeeid="`+ row.employee_id +`"
                                    data-name="`+ row.name +`
                                    ">
                                    Return
                                </a>
                                </li>
                            </ul>
                        </div>
                    `;
                }
            },
        ]
    });
}

function dataTables(info) {
    var table = $('#myTable').DataTable();

    if ($.fn.DataTable.isDataTable('#myTable')) {
        table.clear().destroy();
    }

   
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
                    } else {
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

    $(document).on("click", "#returnModal", function() {
        var modalReturn = new bootstrap.Modal(document.getElementById('modalReturn'));

        var itemIdVal = $(this).data("id");

        var itemId = $(this).data("id");
        var item = $(this).data("item");
        var employeeName = $(this).data("name");
        var employeeId = $(this).data("employeeid");


        $("#itemId").text(itemId);
        $("#itemname").text(item);
        $("#employeeName").text(employeeName);
        $("#employeeId").val(employeeId);
        modalReturn.show();
    });

    $('#returnForm').submit(function (e) { 
            e.preventDefault();
            var formData = new FormData(this); 
            var itemIdVal = $('#itemId').text(); 

            $.ajax({
                url: '<?= base_url("user/return-item/") ?>'+ itemIdVal,
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
                            text: 'Item returned successfully!',
                            showConfirmButton: false,
                            timer: 3000,
                        })
                        $('#modalReturn').modal('hide');
                        $('#returnForm')[0].reset();
                        $('#myTable').DataTable().ajax.reload();
                    } else if (response.status === "error") {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops!',
                            text: 'Employee ID does not exist!',
                            showConfirmButton: false,
                            timer: 2000,
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

