<title>Add Item</title>
<?= $this->include('user/layout/layout-top.php') ?>
<?= $this->include('user/navbar') ?>
<?= $this->include('user/aside') ?>   
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Add Item</h1>
        <nav class="d-flex justify-content-between">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('user/') ?>" class="text-info">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('user/item-list') ?>">Item</a></li>
                <li class="breadcrumb-item active">Add Item</li>
            </ol>

        </nav>
    </div>

    <section>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Item Information</h5>
                        <form id="itemForm" class="row" enctype="multipart/form-data"> 
                            <div class="form-group mb-3 col-md-6">
                                <label for="inventory_name">Item Name</label>
                                <input type="text" class="form-control" id="inventory_name" name="inventory_name">
                            </div>
                            <div class="col-sm-6 align-items-center">
                                <label>Select Category</label>
                                    <select name="category" id="category" class="form-select">
                                        <?php if (!empty($categories)) : ?>
                                            <option value="" selected>Select Category</option>
                                            <?php foreach ($categories as $category) : ?>
                                                <option value="<?= $category['category_id']; ?>"><?= $category['category_name']; ?></option>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <option value="" selected disabled>No categories found</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="serial_no">Serial No.</label>
                                <input type="text" class="form-control" id="serial_no" name="serial_no">
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="product_no">Product No.</label>
                                <input type="text" class="form-control" id="product_no" name="product_no">
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-secondary text-light"
                                    onclick="window.location.href = '<?= base_url('user/item-list') ?>' ">
                                    Back
                                </button>
                                <button type="submit" class="btn btn-info fw-semi">
                                    Submit 
                                    <div class="spinner-border spinner-border-sm d-none" role="status"></div>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
<?= $this->include('user/layout/layout-bottom.php') ?>

<script>
$(document).ready(function () {
    $('#itemForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this); 
        $('.spinner-border').removeClass('d-none');

        $.ajax({
            url: '<?= base_url('user/item-add') ?>',
            method: 'POST',
            data: formData,
            processData: false, 
            contentType: false, 
            success: function(response) {
                if (response.status === "success") {
                    Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Item created successfully!',
                            showConfirmButton: false,
                            timer: 3000,
                        }).then((result) => {
                            window.location.href = '<?= base_url('user/item-list') ?>';
                        });
                        $('.spinner-border').addClass('d-none');
                    } 
                else if (response.status === "error") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops!',
                        text: 'Item already exist!',
                    })
                } else if (response.status === "validation_error") {
                        $('.text-danger').remove();
                        $('.spinner-border').addClass('d-none');
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