<title>Employee</title>
<?php include('layout/layout-top.php') ?>
<?php include('navbar.php')?>
<?php include('aside.php')?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Manage Employee</h1>
        <nav class="d-flex justify-content-between align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-info">Home</a></li>
                <li class="breadcrumb-item active">Employee</li>
            </ol>

            <div class="buttons">
                <a type="button" href="<?= base_url('user/employee-activity-log') ?>" class="btn btn-info text-light p-0 p-1 align-items-center"
                data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Activity Log">
                    <i class="bi bi-clock-history"></i>
                </a>
                <button type="button" class="btn btn-info text-light p-0 p-1 align-items-center"
                data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Add Employee"
                    onclick="window.location.href = '<?= base_url('user/employee-add') ?>' ">
                    <i class="bi bi-plus"></i>
                </button>
            </div>
           

        </nav>
    </div>

    <section>
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow">
                    <div class="card-body py-3">
                        <form id="filterTable" class="row">
                            <div class="col-sm-4">
                                <label>Start Date</label>
                                <input type="date" name="startdate" id="startdate" class="form-control">
                            </div>
                            <div class="col-sm-4">
                                <label>End Date</label>
                                <input type="date" name="enddate" id="enddate" class="form-control">
                            </div>
                            <div class="col-sm-3">
                                <label>Action By</label>
                                <select name="user" id="user" class="form-select">
                                    <option value="" selected>All</option>
                                    <?php if (!empty($user)) : ?>
                                        <?php foreach ($user as $user) : ?>
                                            <option value="<?= $user['user_id']; ?>"><?= $user['first_name']; ?></option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="" selected disabled>No user found</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-sm-1 mt-4">
                                <button class="btn bg-success-subtle py-2" type="button" id="reload">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="card shadow">
                    <div class="card-body py-5 ">
                        <div class="table-responsive">
                            <table id="myTable" class="table display w-100 nowrap">
                                <thead>
                                    <tr>
                                        <th scope="col">NO.</th>
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
    $('#startdate, #enddate, #user').change(function() {
        fetchData();
    });

    $('#myTable').DataTable({
        serverside: true, 
        processing: true,
    });
    $('#reload').click(function () { 
        $('#filterTable')[0].reset();
        fetchData();
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
            $('#myTable').DataTable().clear().destroy();
            $('#myTable').DataTable({
                processing: true,
                layout: {
                top: {
                    buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print',]
                }
                },
                data: response,
                columns: [
                    { 
                        data: null, 
                        render: function(data, type, row, meta) {
                            return meta.row + 1; 
                        }
                    },
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
                            return '<a class="btn mx-1 btn-sm btn-primary" href="<?= base_url('user/employee-view/') ?>' + data + '"><i class="bi bi-eye" style="font-size:9px"></i></a>' +
                            '<a class="btn mx-1 btn-sm btn-success" href="<?= base_url('user/employee-edit/') ?>' + data + '"><i class="bi bi-pencil-square" style="font-size:9px"></i></a>' +
                            '<a class="btn btn-sm btn-danger" onclick="deleteData(' + data + ')"><i class="bi bi-trash3-fill" style="font-size:9px"></i></a>';
                        }
                    },
                ],
            });
        },
        error: function(xhr, status, error) {
            console.error(status + ': ' + error);
        }
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
                url: '<?= base_url("user/employee-delete/"); ?>'+ id,
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
