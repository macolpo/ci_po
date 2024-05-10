<title>Item List</title>
<?php include('layout/layout-top.php') ?>
<?php include('navbar.php')?>
<?php include('aside.php')?>

<main id="main" class="main">
    <div class="pagetitle container">
        <h1>Manage Item</h1>
        <nav class="d-flex justify-content-between">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('user/') ?>" class="text-info">Home</a></li>
                <li class="breadcrumb-item active">Item</li>
            </ol>

            <div class="buttons">
                <button type="button" class="btn btn-info text-light p-0 align-items-center"
                    style="width: 30px; height: 30px;"
                    onclick="window.location.href = '<?= base_url('user/item-add') ?>' ">
                    <i class="bi bi-plus"></i>
                </button>
            </div>
        </nav>
    </div>

    <section>
        <div class="row">

            <div class="col-sm-12">
                <div class="card shadow container">
                    <div class="card-body py-3">
                        <form id="filterTable">
                            <div class="col-sm-12 align-items-center">
                                <label>Select Category</label>
                                <select name="category" id="category" class="form-select">
                                    <option value="" selected>All</option>
                                    <?php if (!empty($categories)) : ?>
                                        <?php foreach ($categories as $category) : ?>
                                            <option value="<?= $category['category_id']; ?>"><?= $category['category_name']; ?></option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="">No categories found</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="card shadow container">
                    <div class="card-body py-5 ">
                        <div class="table-responsive">
                            <table id="myTable" class="table display w-100 nowrap">
                                <thead>
                                    <tr>
                                        <th scope="col">INVENTORY ID</th>
                                        <th scope="col">ITEM NAME</th>
                                        <th scope="col">CATEGORY</th>
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
<script>
$(document).ready(function() {
    $('#category').change(function() {
        fetchData();
    });

    $('#myTable').DataTable({
        processing: true
    });
    fetchData();
});

function fetchData() {
    $('#myTable').DataTable().processing(true);

    let formData = new FormData(document.getElementById('filterTable'));

    $.ajax({
        type: 'POST',
        url: '<?= base_url("user/item-list"); ?>',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log(response)
            dataTables(response);
        },
        error: function(xhr, status, error) {
            console.error(status + ': ' + error);
        }
    });
}

function dataTables(info) {
    $('#myTable').DataTable().clear().destroy();
    $('#myTable').DataTable({
        processing: true,
        layout: {
            top: {
                buttons: [
                    'copy',
                    'csv',
                    'excel',
                    'pdf',
                    'print',
                ]
            }
        },
        data: info,
        columns: [
            { data: 'inventory_id' },
            { data: 'inventory_name' },
            { data: 'category_name' },
            { data: 'inventory_sn' },
            {
                data: 'inventory_pn',
                render: function(data, type, row) {
                    return data ? data : 'No item';
                }
            },
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
                    return '<a class="btn mx-1 btn-sm btn-success" href="<?= base_url('user/item-edit/') ?>' + data + '"><i class="bi bi-pencil-square" style="font-size:12px"></i></a>' +
                    '<a class="btn btn-sm btn-danger" onclick="deleteData(' + data + ')"><i class="bi bi-trash3-fill" style="font-size:12px"></i></a>';
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

</script>

<?php include('layout/layout-bottom.php') ?>
