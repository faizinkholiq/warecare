<div class="container-fluid my-container">
    <div class="d-flex align-items-center" style="justify-content: space-between;">
        <div class="d-flex" style="gap: 0.5rem;">
            <button type="button" class="create-btn btn btn-default border shadow-sm rounded-lg border-0 font-weight-bold">
                <i class="fas fa-plus text-success mr-2"></i> Tambah Gudang Baru
            </button>
            <button id="clearFilters" class="btn btn-sm btn-default shadow-sm rounded-lg border-0 font-weight-bold">
                <i class="fas fa-undo mr-1"></i>
            </button>
        </div>
        <div class="d-flex" style="gap: 0.5rem">
            <div class="form-group m-0 d-flex">
                <select id="searchCompany" class="form-control" style="width: 200px">
                    <option value="">- All Company -</option>
                    <?php foreach ($list_data['company'] as $key => $value): ?>
                        <option value="<?= $value['id']  ?>"><?= $value['name']  ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group m-0">
                <input id="searchName" type="text" class="form-control" placeholder="Name" style="width: 200px" />
            </div>
        </div>
    </div>
    <div class="table-responsive text-sm bg-white shadow mt-3 rounded-lg">
        <table id="warehousesTable" class="table border-0" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="dt-center" width="3%">No</th>
                    <th class="dt-center" width="20%">Perusahaan</th>
                    <th class="dt-center">Nama</th>
                    <th class="dt-center" width="10%">Status</th>
                    <th class="dt-center" width="15%">Tgl. Sewa/Beli</th>
                    <th class="dt-center" width="15%">Tgl. Serah Terima</th>
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
                <h5 class="modal-title" id="inputModalHeader"><i class="fas fa-plus mr-3 rounded px-2 py-2 text-primary bg-light-primary text-sm" id="inputModalIcon"></i> <span id="inputModalTitle">Tambah Gudang Baru</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="warehouseForm">
                <div class="modal-body">
                    <input id="warehouseId" type="hidden" name="id">
                    <div class="form-group col-md-10">
                        <label for="warehouseEntity">Entity</label>
                        <select id="warehouseEntity" class="form-control" name="entity_id" required>
                            <option value="">- Pilih Entity -</option>
                            <?php foreach ($list_data['entity'] as $key => $value): ?>
                                <option value="<?= $value['id']  ?>"><?= $value['name']  ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-10">
                        <label for="warehouseProject">Project</label>
                        <select id="warehouseProject" class="form-control" name="project_id" required>
                            <option value="">- Pilih Project -</option>
                        </select>
                    </div>
                    <div class="form-group col-md-10">
                        <label for="warehouseCompany">Perusahaan</label>
                        <select id="warehouseCompany" class="form-control" name="company_id" required>
                            <option value="">- Pilih Perusahaan -</option>
                        </select>
                    </div>
                    <div class="form-group col-md-10">
                        <label for="warehouseName">Name</label>
                        <input id="warehouseName" type="text" class="form-control" name="name" placeholder="Name" required />
                    </div>
                    <div class="form-group col-md-10">
                        <label for="warehouseStatus">Status</label>
                        <select id="warehouseStatus" class="form-control" name="status">
                            <option value="Jual">Jual</option>
                            <option value="Sewa">Sewa</option>
                        </select>
                    </div>
                    <div class="form-group col-md-10">
                        <label for="warehouseOwnedAt">Tgl. Sewa/Beli</label>
                        <input id="warehouseOwnedAt" type="date" class="form-control" name="owned_at" placeholder="" />
                    </div>
                    <div class="form-group col-md-10">
                        <label for="warehouseHandoveredAt">Tgl. Serah Terima</label>
                        <input id="warehouseHandoveredAt" type="date" class="form-control" name="handovered_at" placeholder="" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default rounded-lg shadow border-0 mr-2" data-dismiss="modal"><i class="fas fa-times mr-2"></i> Batal</button>
                    <button type="submit" class="btn btn-success rounded-lg shadow border-0"><i class="fas fa-save mr-2"></i> Simpan Gudang</button>
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
                <h5 class="modal-title d-flex"><i class="fas fa-trash mr-3 rounded px-2 py-2 text-danger bg-light-danger text-sm"></i> <span>Hapus Gudang</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="deleteWarehouseId">
                <p>Apakah kamu yakin ingin menghapus gudang ini?</p>
                <p class="text-center text-sm border px-4 py-2 border-warning text-bold rounded bg-light-warning"><i class="fas fa-exclamation-triangle text-warning mr-2"></i> Data akan dihapus secara permanen.</p>
            </div>
            <div class="modal-footer d-flex">
                <button type="button" style="flex: 1 1 auto;" class="btn btn-default rounded-lg border-0 shadow" data-dismiss="modal">Batal</button>
                <button type="button" style="flex: 1 1 auto;" class="btn btn-danger rounded-lg border-0 shadow" id="confirmDeleteBtn">Ya, Hapus Gudang</button>
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
        get_projects: "<?= site_url('project/get_list') ?>",
        get_companies: "<?= site_url('company/get_list') ?>",
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
        var table = $('#warehousesTable').DataTable({
            serverSide: true,
            ajax: {
                url: urls.get_list,
                type: 'POST',
                data: function(d) {
                    d.company = $('#searchCompany').val();
                    d.name = $('#searchName').val();
                    return d;
                }
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
                    data: "company",
                    targets: 2
                },
                {
                    data: "name",
                    targets: 3
                },
                {
                    data: "status",
                    className: "dt-center",
                    targets: 4
                },
                {
                    data: "owned_at",
                    className: "dt-center",
                    targets: 5
                },
                {
                    data: "handovered_at",
                    className: "dt-center",
                    targets: 6
                },
                {
                    data: null,
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
                    targets: 7
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
            $('#inputModalTitle').text('Tambah Gudang Baru');
            $('#inputModalIcon').removeClass('fa-edit').addClass('fa-plus');
            $('#inputModalIcon').removeClass('text-primary').addClass('text-success');
            $('#inputModalIcon').removeClass('bg-light-primary').addClass('bg-light-success');
            $('#warehouseId').val('');
            $('#inputModal').modal('show');

            loadProjectSelect();
            loadCompanySelect();
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

                loadProjectSelect(data.entity_id);
                loadCompanySelect(data.project_id);
                $('#warehouseEntity').val(data.entity_id).trigger('change');
                setTimeout(function() {
                    $('#warehouseProject').val(data.project_id).trigger('change');
                    $('#warehouseCompany').val(data.company_id).trigger('change');
                }, 100);
                $('#warehouseCompany').val(data.company_id).trigger('change');
                $('#warehouseName').val(data.name);
                $('#warehouseStatus').val(data.status);
                $('#warehouseOwnedAt').val(data.owned_at);
                $('#warehouseHandovered').val(data.handovered_at);
                $('#inputModalIcon').removeClass('fa-plus').addClass('fa-edit');
                $('#inputModalIcon').removeClass('text-success').addClass('text-primary');
                $('#inputModalIcon').removeClass('bg-light-success').addClass('bg-light-primary');
                $('#inputModalTitle').text('Ubah Gudang');
            });

            $('#inputModal').modal('show');
        });

        $('#warehouseForm').submit(function(e) {
            e.preventDefault();

            const id = $('#warehouseId').val();

            const formData = new FormData();
            formData.append('name', $('#warehouseName').val());
            formData.append('company_id', $('#warehouseCompany').val());
            formData.append('status', $('#warehouseStatus').val());
            formData.append('owned_at', $('#warehouseOwnedAt').val());
            formData.append('handovered_at', $('#warehouseHandoveredAt').val());

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
                    toastr.success('Berhasil menyimpan gudang');
                },
                error: function() {
                    toastr.error("Gagal menyimpan gudang");
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
                    toastr.success('Berhasil menghapus gudang');
                },
                error: function() {
                    toastr.error("Gagal menghapus gudang");
                }
            });
        });

        document.getElementById('warehouseEntity').addEventListener('change', (e) => {
            const value = e.target.value;
            loadProjectSelect(value);
        });

        document.getElementById('warehouseProject').addEventListener('change', (e) => {
            const value = e.target.value;
            loadCompanySelect(value);
        });

        $('#searchCompany').change(function(e) {
            const value = e.target.value;
            table.draw();
        });

        $('#searchName').on('input', function(e) {
            const value = e.target.value;
            table.draw();
        });

        $('#searchName').on('change', function(e) {
            const value = e.target.value;
            table.draw();
        });

        $('#clearFilters').on('click', function() {
            $('#searchCompany, #searchName').val('');
            table.draw();
        });
    });

    function loadProjectSelect(entityID) {
        const projectSelect = document.getElementById('warehouseProject');
        projectSelect.innerHTML = '';

        if (!entityID) {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'No projects available';
            option.disabled = true;
            projectSelect.appendChild(option);
            return;
        }

        let params = {
            entity: entityID
        };

        const queryString = new URLSearchParams(params).toString();

        fetch(urls.get_projects + '?' + queryString, {
                method: 'GET',
            })
            .then(response => response.json())
            .then(res => {
                if (res.data.length > 0) {
                    res.data.forEach(project => {
                        const option = document.createElement('option');
                        option.value = project.id;
                        option.textContent = project.name;
                        projectSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'No projects available';
                    option.disabled = true;
                    projectSelect.appendChild(option);
                }

                $('#warehouseProject').val('').trigger('change');
                $('#warehouseCompany').val('').trigger('change');
                document.getElementById('warehouseProject').dispatchEvent(new Event('change'));
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error("Failed to load project.");
            });
    }

    function loadCompanySelect(projectID) {
        const companySelect = document.getElementById('warehouseCompany');
        companySelect.innerHTML = '';
        if (!projectID) {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'No companies available';
            option.disabled = true;
            companySelect.appendChild(option);
            return;
        }

        let params = {
            project: projectID
        };

        const queryString = new URLSearchParams(params).toString();

        fetch(urls.get_companies + '?' + queryString, {
                method: 'GET',
            })
            .then(response => response.json())
            .then(res => {
                if (res.data.length > 0) {
                    res.data.forEach(project => {
                        const option = document.createElement('option');
                        option.value = project.id;
                        option.textContent = project.name;
                        companySelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'No companies available';
                    option.disabled = true;
                    companySelect.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error("Failed to load project.");
            });
    }

    function resetForm() {
        $('#warehouseForm')[0].reset();
        $('#warehouseId').val('');
    }
</script>