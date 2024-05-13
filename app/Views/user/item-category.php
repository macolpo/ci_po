<title>Category</title>
<?php include('layout/layout-top.php') ?>
<?php include('navbar.php')?>
<?php include('aside.php')?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Manage Category</h1>
        <nav class="d-flex justify-content-between align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-info">Home</a></li>
                <li class="breadcrumb-item active">Category</li>
            </ol>

            <button type="button" class="btn btn-info text-light p-0 p-1 align-items-center"
            data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Add Category"
            id="modalForAdd">
                <i class="bi bi-plus"></i>
            </button>

        </nav>
    </div>

    <section>
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow">
                    <div class="card-body py-5 ">
                        <div class="table-responsive">
                            <table id="myTable" class="table display w-100 nowrap">
                                <thead>
                                    <tr>
                                        <th scope="col">NO.</th>
                                        <th scope="col">CATEGORY NAME</th>
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
<!-- modal -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">ADD CATEGORY</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="$('.text-danger').remove();"></button>
      </div>
      <form id="categoryForm">
        <div class="modal-body">
            <label for="category">Category Name</label>
            <input type="text" class="form-control" name="category" id="category">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="$('.text-danger').remove();">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- edit modal -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 fw-bold" id="staticBackdropLabel">EDIT CATEGORY</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="$('.text-danger').remove();"></button>
      </div>
      <form id="categoryEditForm">
        <div class="modal-body">
            <label for="editCategory">Category Name</label>
            <input type="text" class="form-control" id="editCategory" name="editCategory">
        </div>
        <div class="modal-footer">
            <input type="hidden" class="form-control" id="categoryId" name="categoryId">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="$('.text-danger').remove();">Close</button>
            <button type="submit" class="btn btn-primary">Update</button>
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
            url: '<?= base_url('user/item-category') ?>',
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
            { data: 'category_name' },
            {
                data: 'category_id',
                render: function(data, type, row) {
                    return `
                    <a class="btn btn-sm btn-success" id="editBtn" data-id="`+ data +`" data-category="` + row.category_name + `">
                        <i class="bi bi-pencil-fill" style="font-size:9px"></i>
                    </a>`
;
                }
            },
        ],
    });

}

$(document).ready(function() {
    fetchData();
    $('#categoryForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this); 
        
        $.ajax({
            url: '<?= base_url('user/item-category/insert') ?>',
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
                        text: 'Category inserted successfully!',
                        showConfirmButton: false,
                        timer: 3000,
                    })
                    $('#addModal').modal('hide');
                    $('#categoryForm')[0].reset();
                    $('#myTable').DataTable().ajax.reload();
                } else if (response.status === "error") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Opps!',
                        text: 'Category name is already exist!',
                    })
                } else if (response.status === "validation_error") {
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
    $(document).on("click", "#modalForAdd", function() {
        $('#addModal').modal('show');
    });

// ----------------- edit modal
    $(document).on("click", "#editBtn", function() {
        $('#editModal').modal('show');

        var categoryId = $(this).data("id");
        var category = $(this).data("category");

        $("#editCategory").val(category);
        $("#categoryId").val(categoryId);
    });

    $('#categoryEditForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this); 

        $('.text-danger').remove();
        
        $.ajax({
            url: '<?= base_url('user/item-category/update') ?>',
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
                        text: 'Category updated successfully!',
                        showConfirmButton: false,
                        timer: 3000,
                    })
                    $('#editModal').modal('hide');
                    $('#categoryEditForm')[0].reset();
                    $('#myTable').DataTable().ajax.reload();
                } else if (response.status === "error") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Opps!',
                        text: 'No changes were made!',
                        showConfirmButton: false,
                        timer: 2500,
                    })
                } else if (response.status === "validation_error") {
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
</script>