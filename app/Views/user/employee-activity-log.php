<title>Employee Activity Log</title>
<?php include('layout/layout-top.php') ?>
<?php include('navbar.php')?>
<?php include('aside.php')?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>View Employee Activity Log</h1>
        <nav class="d-flex justify-content-between">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('user/') ?>" class="text-info">Home</a></li>
                <li class="breadcrumb-item "><a href="<?= base_url('user/employee') ?>">Employee</a></li>
                <li class="breadcrumb-item active">Employee Activity Log</li>
            </ol>
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
                                        <th scope="col">EMPLOYEE ID</th>
                                        <th scope="col">EMPLOYEE NAME</th>
                                        <th scope="col">STATUS</th>
                                        <th scope="col">ACTION BY</th>
                                        <th scope="col">DATE</th>
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
<script>
function fetchData() {
    $('#myTable').DataTable({
        processing: true, 

        ajax: {
            url: '<?= base_url('user/employee-activity-log') ?>',
            type: 'POST',
            dataSrc: '', 
        },
        columns: [
            { 
                data: null, 
                render: function(data, type, row, meta) {
                    return meta.row + 1; 
                }
            },
            { data: 'employee_id' },
            { data: 'employee_name' },
            {
                data: 'status',
                render: function(data, type, row) {
                    if (data === null) {
                        return 'Unknown';
                    } else if (data === '0') {
                        return 'Added';
                    } else if (data === '1') {
                        return 'Updated';
                    } else {
                        return 'Deleted';
                    }
                }
            },
            { data: 'created_by' },
            { data: 'created_at' },
        ],
    });

}

$(document).ready(function() {
    fetchData();
});
</script>