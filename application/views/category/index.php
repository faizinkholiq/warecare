<style>
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <button type="button" class="create-btn btn btn-light border shadow-sm">
                    <i class="fas fa-plus mr-2"></i> Add New Category
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-sm">
                <table id="categoriesTable" class="table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th class="dt-center">No</th>
                            <th class="dt-center">Nama</th>
                            <th class="dt-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create or Edit Modal -->
<div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="inputModalHeader" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inputModalHeader">New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="categoryForm">
                <div class="modal-body">
                    <input id="categoryId" type="hidden" name="id">
                    <div class="form-group col-md-10">
                        <label for="categoryName">Name</label>
                        <input id="categoryName" type="text" class="form-control" name="name" placeholder="Name" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save mr-2"></i> Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex"><i class="fas fa-trash mr-3 rounded px-2 py-2 text-danger bg-light-danger text-sm"></i> <span>Delete Category</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="deleteCategoryId">
                <p>Are you sure you want to delete this category?</p>
                <p class="text-center text-sm border px-4 py-2 border-warning text-bold rounded bg-light-warning"><i class="fas fa-exclamation-triangle text-warning mr-2"></i> The data will be permanently deleted.</p>
            </div>
            <div class="modal-footer d-flex">
                <button type="button" style="flex: 1 1 auto;" class="btn btn-default bg-white" data-dismiss="modal">Cancel</button>
                <button type="button" style="flex: 1 1 auto;" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete Category</button>
            </div>
        </div>
    </div>
</div>

<script>
    let notifications = {
        success: '<?= $this->session->flashdata('success') ?>',
        error: '<?= $this->session->flashdata('error') ?>'
    };

    const urls = {
        get_list: "<?= site_url('category/get_list') ?>",
        get: "<?= site_url('category/get') ?>",
        create: "<?= site_url('category/create') ?>",
        edit: "<?= site_url('category/edit') ?>",
        delete: "<?= site_url('category/delete') ?>",
    }

    $(document).ready(function() {
        setTimeout(() => {
            if(notifications.success) {
                toastr.success(notifications.success);
            }else if (notifications.error) {
                toastr.error(notifications.error);
            }
        }, 500)

        // Initialize DataTable
        var table = $('#categoriesTable').DataTable({
            serverSide: true,
            ajax: {
                url: urls.get_list,
                type: 'POST'
            },
            rowId: 'id',
            columns: [
                { 
                    data: "id", 
                    visible: false,
                    orderable: false,
                    targets: 0 
                },
                {
                    data: null,
                    width: "3%",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    searchable: false,
                    orderable: false,
                    targets: 1
                },
                { 
                    data: "name",
                    targets: 2
                },
                {
                    data: null,
                    width: "10%",
                    className: "dt-center",
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-sm btn-primary border-0 edit-btn" data-id="${row.id}">
                                <i class="text-xs fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger border-0 delete-btn" data-id="${row.id}">
                                <i class="text-xs fas fa-trash"></i>
                            </button>
                        `;
                    },
                    orderable: false,
                    targets: 3
                }
            ],
            scrollResize: true,
            scrollX: "100%",
            scrollCollapse: true,
            paging: false,
            responsive: false,
            lengthChange: false,
            autoWidth: false,
            searching: false,
            select: false,
            info: false,
        });

        // Create Category Button
        $(document).on('click', '.create-btn', function() {
            resetForm();
            $('#inputModalHeader').text('Add New Category');
            $('#categoryId').val('');
            $('#inputModal').modal('show');
        });

        // Edit Category Button
        $(document).on('click', '.edit-btn', function() {
            resetForm();
            
            var category = $(this).data('id');
            $('#categoryId').val(category);

            $.get(urls.get + '/' + category, function(data) {
                data = JSON.parse(data);
                if (data.error) {
                    toastr.error(data.error);
                    return;
                }

                $('#categoryName').val(data.name);
                $('#inputModalHeader').text('Edit Category');
            });

            $('#inputModal').modal('show');
        });

        $('#categoryForm').submit(function(e) {
            e.preventDefault();

            const id = $('#categoryId').val();
            
            const formData = new FormData();
            formData.append('name', $('#categoryName').val());
            
            // Submit form
            $.ajax({
                url: !(id)? urls.create : urls.edit + '/' + id,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#inputModal').modal('hide');
                    table.ajax.reload();
                    toastr.success('Category updated successfully');
                },
                error: function() {
                    toastr.error("Failed to "+ mode +" product.");
                }
            });
        });

        // Delete Category Button
        $(document).on('click', '.delete-btn', function() {
            var category = $(this).data('id');
            $('#deleteCategoryId').val(category);
            $('#deleteModal').modal('show');
        });

        // Confirm Delete
        $('#confirmDeleteBtn').click(function() {
            var category = $('#deleteCategoryId').val();
            
            $.ajax({
                url: urls.delete + '/' + category,
                method: 'DELETE',
                success: function() {
                    $('#deleteModal').modal('hide');
                    table.ajax.reload();
                    toastr.success('Category deleted successfully');
                },
                error: function() {
                    toastr.error("Failed to delete category.");
                }
            });
        });
    });

    function resetForm() {
        $('#categoryForm')[0].reset();
        $('#categoryId').val('');
    }
</script>