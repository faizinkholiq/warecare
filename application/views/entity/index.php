<div class="container-fluid my-container">
    <button type="button" class="create-btn btn btn-default border shadow-sm rounded-lg border-0 font-weight-bold">
        <i class="fas fa-plus text-success mr-2"></i> Tambah Entity Baru
    </button>
    <div class="table-responsive text-sm bg-white shadow mt-3 rounded-lg">
        <table id="entitiesTable" class="table border-0" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="dt-center" width="3%">No</th>
                    <th class="dt-center">Nama</th>
                    <th class="dt-center" width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Create or Edit Modal -->
<div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="inputModalHeader" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="inputModalHeader"><i class="fas fa-plus mr-3 rounded px-2 py-2 text-primary bg-light-primary text-sm" id="inputModalIcon"></i> <span id="inputModalTitle">Tambah Entity Baru</span></h5>
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
                    <button type="button" class="btn btn-default rounded-lg shadow border-0 mr-2" data-dismiss="modal"><i class="fas fa-times mr-2"></i> Batal</button>
                    <button type="submit" class="btn btn-success rounded-lg shadow border-0"><i class="fas fa-save mr-2"></i> Simpan Entity</button>
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
                <h5 class="modal-title d-flex"><i class="fas fa-trash mr-3 rounded px-2 py-2 text-danger bg-light-danger text-sm"></i> <span>Hapus Entity</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="deleteEntityId">
                <p>Apakah kamu yakin ingin menghapus entity ini?</p>
                <p class="text-center text-sm border px-4 py-2 border-warning text-bold rounded bg-light-warning"><i class="fas fa-exclamation-triangle text-warning mr-2"></i> Data akan dihapus secara permanen.</p>
            </div>
            <div class="modal-footer d-flex">
                <button type="button" style="flex: 1 1 auto;" class="btn btn-default rounded-lg border-0 shadow" data-dismiss="modal">Batal</button>
                <button type="button" style="flex: 1 1 auto;" class="btn btn-danger rounded-lg border-0 shadow" id="confirmDeleteBtn">Ya, Hapus Entity</button>
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
            if (notifications.success) {
                toastr.success(notifications.success);
            } else if (notifications.error) {
                toastr.error(notifications.error);
            }
        }, 500)

        // Initialize DataTable
        var table = $('#entitiesTable').DataTable({
            serverSide: true,
            ajax: {
                url: urls.get_list,
                type: 'POST'
            },
            rowId: 'id',
            columns: [{
                    data: "id",
                    visible: false,
                    orderable: false,
                    targets: 0
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
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
                    className: "dt-center",
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-sm rounded-lg shadow btn-primary border-0 edit-btn" data-id="${row.id}">
                                <i class="text-xs fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm rounded-lg shadow btn-danger border-0 delete-btn" data-id="${row.id}">
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
            $('#inputModalTitle').text('Tambah Entity Baru');
            $('#inputModalIcon').removeClass('fa-edit').addClass('fa-plus');
            $('#inputModalIcon').removeClass('text-primary').addClass('text-success');
            $('#inputModalIcon').removeClass('bg-light-primary').addClass('bg-light-success');
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
                $('#inputModalIcon').removeClass('fa-plus').addClass('fa-edit');
                $('#inputModalIcon').removeClass('text-success').addClass('text-primary');
                $('#inputModalIcon').removeClass('bg-light-success').addClass('bg-light-primary');
                $('#inputModalTitle').text('Ubah Entity');
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
                url: !(id) ? urls.create : urls.edit + '/' + id,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#inputModal').modal('hide');
                    table.ajax.reload();
                    toastr.success('Berhasil menyimpan entity');
                },
                error: function() {
                    toastr.error("Gagal menyimpan entity");
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
                    toastr.success('Berhasil menghapus entity');
                },
                error: function() {
                    toastr.error("Gagal menghapus entity");
                }
            });
        });
    });

    function resetForm() {
        $('#entityForm')[0].reset();
        $('#entityId').val('');
    }
</script>