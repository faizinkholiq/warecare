<style>
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <button type="button" class="create-btn btn btn-light border shadow-sm">
                    <i class="fas fa-plus mr-2"></i> Add New Warehouse
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-sm">
                <table id="warehousesTable" class="table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th class="dt-center">No</th>
                            <th class="dt-center">Perusahaan</th>
                            <th class="dt-center">Name</th>
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
                <h5 class="modal-title" id="inputModalHeader">New Warehouse</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="warehouseForm">
                <div class="modal-body">
                    <input id="warehouseId" type="hidden" name="id">
                    <div class="form-group col-md-10">
                        <label for="warehouseCompany">Perusahaan</label>
                        <select id="warehouseCompany" class="form-control" name="company_id" required>
                            <option value="">- Pilih Perusahaan -</option>
                            <?php foreach($list_data['company'] as $key => $value): ?>    
                            <option value="<?=$value['id']  ?>"><?= $value['name']  ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-10">
                        <label for="warehouseName">Name</label>
                        <input id="warehouseName" type="text" class="form-control" name="name" placeholder="Name" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save mr-2"></i> Save Warehouse</button>
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
                <h5 class="modal-title d-flex"><i class="fas fa-trash mr-3 rounded px-2 py-2 text-danger bg-light-danger text-sm"></i> <span>Delete Warehouse</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="deleteWarehouseId">
                <p>Are you sure you want to delete this warehouse?</p>
                <p class="text-center text-sm border px-4 py-2 border-warning text-bold rounded bg-light-warning"><i class="fas fa-exclamation-triangle text-warning mr-2"></i> The data will be permanently deleted.</p>
            </div>
            <div class="modal-footer d-flex">
                <button type="button" style="flex: 1 1 auto;" class="btn btn-default bg-white" data-dismiss="modal">Cancel</button>
                <button type="button" style="flex: 1 1 auto;" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete Warehouse</button>
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
        get_list: "<?= site_url('warehouse/get_list') ?>",
        get: "<?= site_url('warehouse/get') ?>",
        create: "<?= site_url('warehouse/create') ?>",
        edit: "<?= site_url('warehouse/edit') ?>",
        delete: "<?= site_url('warehouse/delete') ?>",
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
        var table = $('#warehousesTable').DataTable({
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
                    data: "company",
                    width: "30%",
                    targets: 2
                },
                { 
                    data: "name",
                    targets: 3
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
                    targets: 4
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

        // Create Warehouse Button
        $(document).on('click', '.create-btn', function() {
            resetForm();
            $('#inputModalHeader').text('Add New Warehouse');
            $('#warehouseId').val('');
            $('#inputModal').modal('show');
        });

        // Edit Warehouse Button
        $(document).on('click', '.edit-btn', function() {
            resetForm();
            
            var warehouse = $(this).data('id');
            $('#warehouseId').val(warehouse);

            $.get(urls.get + '/' + warehouse, function(data) {
                data = JSON.parse(data);
                if (data.error) {
                    toastr.error(data.error);
                    return;
                }

                $('#warehouseCompany').val(data.company_id).trigger('change');
                $('#warehouseName').val(data.name);
                $('#inputModalHeader').text('Edit Warehouse');
            });

            $('#inputModal').modal('show');
        });

        $('#warehouseForm').submit(function(e) {
            e.preventDefault();

            const id = $('#warehouseId').val();
            
            const formData = new FormData();
            formData.append('company_id', $('#warehouseCompany').val());
            formData.append('name', $('#warehouseName').val());
            
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
                    toastr.success('Warehouse updated successfully');
                },
                error: function() {
                    toastr.error("Failed to "+ mode +" product.");
                }
            });
        });

        // Delete Warehouse Button
        $(document).on('click', '.delete-btn', function() {
            var warehouse = $(this).data('id');
            $('#deleteWarehouseId').val(warehouse);
            $('#deleteModal').modal('show');
        });

        // Confirm Delete
        $('#confirmDeleteBtn').click(function() {
            var warehouse = $('#deleteWarehouseId').val();
            
            $.ajax({
                url: urls.delete + '/' + warehouse,
                method: 'DELETE',
                success: function() {
                    $('#deleteModal').modal('hide');
                    table.ajax.reload();
                    toastr.success('Warehouse deleted successfully');
                },
                error: function() {
                    toastr.error("Failed to delete warehouse.");
                }
            });
        });
    });

    function resetForm() {
        $('#warehouseForm')[0].reset();
        $('#warehouseId').val('');
        $('#warehouseCompany').val('').trigger('change');
    }
</script>