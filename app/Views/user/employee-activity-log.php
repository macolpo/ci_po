<title>Employee Activity Log</title>
<?= $this->include('user/layout/layout-top.php') ?>
<?= $this->include('user/navbar') ?>
<?= $this->include('user/aside') ?>

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
<?= $this->include('user/layout/layout-bottom.php') ?>

<script>
$(document).ready(function() {
    $('#startdate, #enddate, #user').change(function() {
        fetchData();
    });
    $('#myTable').DataTable({
        processing: true,
    });
    $('#reload').click(function () { 
        $('#filterTable')[0].reset();
        fetchData();
    });

    fetchData();
});

function fetchData() {
    let formData = new FormData(document.getElementById('filterTable'));

    $('#myTable').DataTable().processing(true);

    $.ajax({
        url: '<?= base_url('user/employee-activity-log') ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#myTable').DataTable().clear().destroy();
            $('#myTable').DataTable({
                processing: true, 
                data: response,
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
                ]
            });
        },
        error: function(xhr, status, error) {
            console.error(status + ': ' + error);
        }
    });
}


</script>