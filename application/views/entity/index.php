<style>
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <button type="button" class="create-btn btn btn-light border shadow-sm">
                    <i class="fas fa-plus mr-2"></i> Add New Entity
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-sm">
                <table id="entityiesTable" class="table table-bordered" style="width:100%">
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
                <h5 class="modal-title" id="inputModalHeader">New Entity</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="entityForm">
                <div class="modal-body">
                    <input id="entityId" type="hidden" name="id">
                    <div class="form-group col-md-10">
                        <label for="entityName">Name</label>
                        <input id="entityName" type="text" class="form-control" name="name" placeholder="Name" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save mr-2"></i> Save Entity</button>
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
                <h5 class="modal-title d-flex"><i class="fas fa-trash mr-3 rounded px-2 py-2 text-danger bg-light-danger text-sm"></i> <span>Delete Entity</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="deleteEntityId">
                <p>Are you sure you want to delete this entity?</p>
                <p class="text-center text-sm border px-4 py-2 border-warning text-bold rounded bg-light-warning"><i class="fas fa-exclamation-triangle text-warning mr-2"></i> The data will be permanently deleted.</p>
            </div>
            <div class="modal-footer d-flex">
                <button type="button" style="flex: 1 1 auto;" class="btn btn-default bg-white" data-dismiss="modal">Cancel</button>
                <button type="button" style="flex: 1 1 auto;" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete Entity</button>
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
        get_list: "<?= site_url('entity/get_list') ?>",
        get: "<?= site_url('entity/get') ?>",
        create: "<?= site_url('entity/create') ?>",
        edit: "<?= site_url('entity/edit') ?>",
        delete: "<?= site_url('entity/delete') ?>",
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
        var table = $('#entityiesTable').DataTable({
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

        // Create Entity Button
        $(document).on('click', '.create-btn', function() {
            resetForm();
            $('#inputModalHeader').text('Add New Entity');
            $('#entityId').val('');
            $('#inputModal').modal('show');
        });

        // Edit Entity Button
        $(document).on('click', '.edit-btn', function() {
            resetForm();
            
            var entity = $(this).data('id');
            $('#entityId').val(entity);

            $.get(urls.get + '/' + entity, function(data) {
                data = JSON.parse(data);
                if (data.error) {
                    toastr.error(data.error);
                    return;
                }

                $('#entityName').val(data.name);
                $('#inputModalHeader').text('Edit Entity');
            });

            $('#inputModal').modal('show');
        });

        $('#entityForm').submit(function(e) {
            e.preventDefault();

            const id = $('#entityId').val();
            
            const formData = new FormData();
            formData.append('name', $('#entityName').val());
            
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
                    toastr.success('Entity updated successfully');
                },
                error: function() {
                    toastr.error("Failed to "+ mode +" product.");
                }
            });
        });

        // Delete Entity Button
        $(document).on('click', '.delete-btn', function() {
            var entity = $(this).data('id');
            $('#deleteEntityId').val(entity);
            $('#deleteModal').modal('show');
        });

        // Confirm Delete
        $('#confirmDeleteBtn').click(function() {
            var entity = $('#deleteEntityId').val();
            
            $.ajax({
                url: urls.delete + '/' + entity,
                method: 'DELETE',
                success: function() {
                    $('#deleteModal').modal('hide');
                    table.ajax.reload();
                    toastr.success('Entity deleted successfully');
                },
                error: function() {
                    toastr.error("Failed to delete entity.");
                }
            });
        });
    });

    function resetForm() {
        $('#entityForm')[0].reset();
        $('#entityId').val('');
    }
</script>