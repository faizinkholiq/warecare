<div class="container-fluid my-container">
    <button type="button" class="create-btn btn btn-default border shadow-sm rounded-lg border-0 font-weight-bold">
        <i class="fas fa-plus text-success mr-2"></i> Tambah Kategori Baru
    </button>
    <div class="table-responsive text-sm bg-white shadow mt-3 rounded-lg">
        <table id="categoriesTable" class="table border-0" style="width:100%">
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

<!-- Create or Edit Modal -->
<div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="inputModalHeader" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="inputModalHeader"><i class="fas fa-plus mr-3 rounded px-2 py-2 text-primary bg-light-primary text-sm" id="inputModalIcon"></i> <span id="inputModalTitle">Tambah Kategori Baru</span></h5>
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
                    <button type="button" class="btn btn-default rounded-lg shadow border-0 mr-2" data-dismiss="modal"><i class="fas fa-times mr-2"></i> Batal</button>
                    <button type="submit" class="btn btn-success rounded-lg shadow border-0"><i class="fas fa-save mr-2"></i> Simpan Kategori</button>
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
                <h5 class="modal-title d-flex"><i class="fas fa-trash mr-3 rounded px-2 py-2 text-danger bg-light-danger text-sm"></i> <span>Hapus Kategori</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="deleteCategoryId">
                <p>Apakah kamu yakin ingin menghapus kategori ini?</p>
                <p class="text-center text-sm border px-4 py-2 border-warning text-bold rounded bg-light-warning"><i class="fas fa-exclamation-triangle text-warning mr-2"></i> Data akan dihapus secara permanen.</p>
            </div>
            <div class="modal-footer d-flex">
                <button type="button" style="flex: 1 1 auto;" class="btn btn-default rounded-lg border-0 shadow" data-dismiss="modal">Batal</button>
                <button type="button" style="flex: 1 1 auto;" class="btn btn-danger rounded-lg border-0 shadow" id="confirmDeleteBtn">Ya, Hapus Kategori</button>
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
            if (notifications.success) {
                toastr.success(notifications.success);
            } else if (notifications.error) {
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
            columns: [{
                    data: "id",
                    visible: false,
                    orderable: false,
                    targets: 0
                },
                {
                    data: null,
                    width: "3%",
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
                    width: "10%",
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

        // Create Category Button
        $(document).on('click', '.create-btn', function() {
            resetForm();
            $('#inputModalTitle').text('Tambah Kategori Baru');
            $('#inputModalIcon').removeClass('fa-edit').addClass('fa-plus');
            $('#inputModalIcon').removeClass('text-primary').addClass('text-success');
            $('#inputModalIcon').removeClass('bg-light-primary').addClass('bg-light-success');
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
                $('#inputModalIcon').removeClass('fa-plus').addClass('fa-edit');
                $('#inputModalIcon').removeClass('text-success').addClass('text-primary');
                $('#inputModalIcon').removeClass('bg-light-success').addClass('bg-light-primary');
                $('#inputModalTitle').text('Ubah Kategori');
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
                url: !(id) ? urls.create : urls.edit + '/' + id,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#inputModal').modal('hide');
                    table.ajax.reload();
                    toastr.success('Berhasil menyimpan kategori');
                },
                error: function() {
                    toastr.error("Gagal menyimpan kategori");
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
                    toastr.success('Berhasil menghapus kategori');
                },
                error: function() {
                    toastr.error("Gagal menghapus kategori");
                }
            });
        });
    });

    function resetForm() {
        $('#categoryForm')[0].reset();
        $('#categoryId').val('');
    }
</script>