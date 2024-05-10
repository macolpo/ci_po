<title>Employee</title>
<?php include('layout/layout-top.php') ?>
<?php include('navbar.php')?>
<?php include('aside.php')?>

<main id="main" class="main">
    <div class="pagetitle container">
        <h1>Manage Employee</h1>
        <nav class="d-flex justify-content-between">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-info">Home</a></li>
                <li class="breadcrumb-item active">Employee</li>
            </ol>

                <button type="button" class="btn btn-info text-light p-0 align-items-center"
                    style="width: 30px; height: 30px;"
                    onclick="window.location.href = '<?= base_url('user/employee-add') ?>' ">
                    <i class="bi bi-plus"></i>
                </button>
        </nav>
    </div>

    <section>
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow container">
                    <div class="card-body py-3">
                        <form id="filterTable">
                            <div class="col-sm-12 align-items-center">
                                <label>Select Date</label>
                                <input type="month" name="month" id="month" class="form-control">
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
                                        <th scope="col">EMPLOYEE ID</th>
                                        <th scope="col">NAME</th>
                                        <th scope="col">ADDRESS</th>
                                        <th scope="col">ITEM NAME</th>
                                        <th scope="col">DATE OF JOIN</th>
                                        <th scope="col">CREATED BY</th>
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
    $('#month').change(function() {
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
        url: '<?= base_url("user/employee"); ?>',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            dataTables(response);
            
        },
        error: function(xhr, status, error) {
            console.error(status + ': ' + error);
        }
    });

}

function dataTables(data) {
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
        data: data,
        columns: [
            { data: 'employee_id' },
            { data: 'name' },
            { data: 'emp_address' },
            {
                data: 'inventory_name',
                render: function(data, type, row) {
                    return data ? data : 'No item';
                }
            },
            { data: 'date_join' },
            { data: 'created_by' },
            {
                data: 'employee_id',
                render: function(data, type, row) {
                    return '<a class="btn mx-1 btn-sm btn-success" href="<?= base_url('user/employee-edit/') ?>' + data + '"><i class="bi bi-pencil-square" style="font-size:12px"></i></a>' +
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
                url: '<?= base_url("user/employee-delete"); ?>',
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
