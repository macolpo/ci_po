<title>Add Item</title>
<?= $this->include('user/layout/layout-top.php') ?>
<?= $this->include('user/navbar') ?>
<?= $this->include('user/aside') ?>
  
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit Item</h1>
        <nav class="d-flex justify-content-between">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('user/') ?>" class="text-info">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('user/item-list') ?>">Item</a></li>
                <li class="breadcrumb-item active">Edit Item</li>

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
                                <input type="text" class="form-control" id="inventory_name" name="inventory_name" value="<?= $inventory['inventory_name'] ?>">
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-select">
                                    <?php if (!empty($categories)) {
                                        foreach ($categories as $category) { ?>
                                            <option value="<?= $category['category_id']; ?>" <?= $category['category_id'] == $inventory['category_id'] ? 'selected' : '' ?>>
                                                <?= $category['category_name']; ?>
                                            </option>
                                        <?php }
                                    } else { ?>
                                        <option value="">No categories found</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="serial_no">Serial No.</label>
                                <input type="text" class="form-control" id="serial_no" name="serial_no" value="<?= $inventory['inventory_sn'] ?>">
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="product_no">Product No.</label>
                                <input type="text" class="form-control" id="product_no" name="product_no" value="<?= $inventory['inventory_pn'] ?>">
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-secondary text-light"
                                    onclick="window.location.href = '<?= base_url('user/item-list') ?>' ">
                                    Back
                                </button>
                                <button type="submit" class="btn btn-info fw-semi">Update</button>
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

        if (this.checkValidity() === true) {
            $.ajax({
                url: '<?= base_url("user/item-update/{$inventory['inventory_id']}") ?>',
                method: 'POST',
                data: formData,
                processData: false, 
                contentType: false, 
                success: function(response) {
                    if (response.status === "success") {
                        Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Item updated successfully!',
                                showConfirmButton: false,
                                timer: 3000,
                            }).then((result) => {
                                window.location.href = '<?= base_url('user/item-list') ?>';
                            });
                        }
                    else if (response.status === "error") {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops!',
                            text: 'No changes were made!',
                            showConfirmButton: false,
                                timer: 3000,
                        })
                    }
                    else if (response.status === "validation_error") {
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
        } else {
            $(this).addClass('was-validated');
        }
    });
    
});
</script>