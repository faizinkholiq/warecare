<style>
    .status-badge {
        font-size: 0.8rem;
        padding: 8px 10px;
        border-radius: 10px;
    }

    .status-pending {
        background-color: #ffc10721;
        color: #f0b400;
        font-weight: bold;
    }

    .status-on-process {
        background-color: #007bff1a;
        color: #007bff;
        font-weight: bold;
    }

    .status-approved {
        background-color: #d4edda;
        color: #155724;
        font-weight: bold;
    }

    .status-rejected {
        background-color: #dc354521;
        color: #dc3545;
        font-weight: bold;
    }

    .status-completed {
        background-color: #d4edda;
        color: #155724;
        font-weight: bold;
    }

    .action-btn {
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .img-thumbnail {
        max-width: 60px;
        max-height: 60px;
    }

    .dropzone {
        border: 2px dashed #ccc;
        border-radius: 5px;
        padding: 25px;
        text-align: center;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 20px;
    }

    .dropzone:hover,
    .dropzone.dragover {
        background: #e9ecef;
        border-color: #999;
    }

    .dropzone i {
        font-size: 48px;
        color: #6c757d;
        margin-bottom: 10px;
    }

    .image-preview-container {
        display: flex;
        flex-wrap: wrap;
        margin-top: 15px;
    }

    .image-preview-item {
        position: relative;
        margin-right: 15px;
        margin-bottom: 15px;
    }

    .image-preview-item img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 5px;
        border: 1px solid #ddd;
    }

    .btn-remove-image {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .upload-progress {
        width: 100%;
        margin-top: 10px;
        display: none;
    }
</style>

<div class="container-fluid">
    <?php if ($this->auth_lib->role() === 'pelapor'): ?>
        <a href="<?= site_url('/report/create') ?>" class="btn btn-default border-0 shadow-sm rounded-lg text-navy font-weight-bold create-btn">
            <i class="fas fa-bullhorn mr-2"></i> Ajukan Pengaduan Baru
        </a>
    <?php endif; ?>
    <div class="table-responsive text-sm bg-white shadow mt-3 rounded-lg p-3">
        <table id="reportsTable" class="table table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="dt-center">No</th>
                    <th class="dt-center">No Pengaduan</th>
                    <th class="dt-center">Entity</th>
                    <th class="dt-center">Project</th>
                    <th class="dt-center">Tgl. Pengaduan</th>
                    <th class="dt-center">No. Gudang</th>
                    <th class="dt-center">Nama Perusahaan</th>
                    <th class="dt-center">Kategori Pengaduan</th>
                    <th class="dt-center">Status Pengajuan</th>
                    <th class="dt-center">Pelapor</th>
                    <th class="dt-center">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex"><i class="fas fa-trash mr-3 rounded px-2 py-2 text-danger bg-light-danger text-sm"></i> <span>Delete Report</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="deleteReportId">
                <p>Are you sure you want to delete this report?</p>
                <p class="text-center text-sm border px-4 py-2 border-warning text-bold rounded bg-light-warning"><i class="fas fa-exclamation-triangle text-warning mr-2"></i> The data will be permanently deleted.</p>
            </div>
            <div class="modal-footer d-flex">
                <button type="button" style="flex: 1 1 auto;" class="btn btn-default bg-white" data-dismiss="modal">Cancel</button>
                <button type="button" style="flex: 1 1 auto;" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete Report</button>
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
        get_list_datatables: "<?= site_url('report/get_list_datatables') ?>",
        detail: "<?= site_url('report/detail') ?>",
        edit: "<?= site_url('report/edit') ?>",
        delete: "<?= site_url('report/delete') ?>",
    }

    const appState = {
        userRole: "<?= $this->auth_lib->role() ?>"
    };

    $(document).ready(function() {
        setTimeout(() => {
            if (notifications.success) {
                toastr.success(notifications.success);
            } else if (notifications.error) {
                toastr.error(notifications.error);
            }
        }, 500)

        // Initialize DataTable
        var table = $('#reportsTable').DataTable({
            serverSide: true,
            ajax: {
                url: urls.get_list_datatables,
                type: 'POST'
            },
            drawCallback: function() {
                tippy('[data-tippy-content]', {
                    placement: 'bottom',
                    arrow: true,
                    animation: 'fade'
                });
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
                    className: "align-middle",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    searchable: false,
                    orderable: false,
                    targets: 1
                },
                {
                    data: "no",
                    width: "12%",
                    className: "align-middle",
                    targets: 2
                },
                {
                    data: "entity",
                    width: "8%",
                    className: "align-middle",
                    targets: 3
                },
                {
                    data: "project",
                    width: "8%",
                    className: "align-middle",
                    targets: 4
                },
                {
                    data: "created_at",
                    width: "10%",
                    className: "align-middle",
                    targets: 5
                },
                {
                    data: "warehouse",
                    width: "10%",
                    className: "align-middle",
                    targets: 6
                },
                {
                    data: "company",
                    width: "15%",
                    className: "align-middle",
                    targets: 7
                },

                {
                    data: "category",
                    width: "8%",
                    className: "align-middle",
                    targets: 8
                },
                {
                    data: null,
                    width: "7%",
                    className: "dt-center align-middle",
                    render: function(data, type, row) {
                        let action_by = "";

                        if (row.status === 'On Process') {
                            action_by = row.processed_by;
                        } else if (row.status === 'Approved') {
                            action_by = row.approved_by;
                        }

                        if (action_by !== '') {
                            action_by = ` (${action_by})`
                        }

                        return row.status ? `
                            <div class="status-badge status-${row.status.toLowerCase().replaceAll(' ', '-')}">
                                ${row.status}
                                <span class="text-xs">${action_by}</span>
                            </div>` : '-';
                    },
                    targets: 9
                },
                {
                    data: "created_by",
                    visible: (appState.userRole === 'pelapor') ? false : true,
                    width: "7%",
                    className: "align-middle",
                    targets: 10
                },
                {
                    data: null,
                    width: "8%",
                    className: "dt-center align-middle",
                    render: function(data, type, row) {
                        const buttons = [
                            `<a href="${urls.detail}/${row.id}" class="btn btn-sm btn-default shadow rounded-lg border-0 mr-1" data-tippy-content="View Details">
                                <i class="text-xs fa fa-info-circle"></i>
                            </a>`
                        ];

                        if (appState.userRole === 'administrator') {
                            buttons.push(`
                                <button class="btn btn-sm btn-danger rounded-lg border-0 delete-btn mr-1" data-id="${row.id}" data-tippy-content="Delete Report">
                                    <i class="text-xs fas fa-trash"></i>
                                </button>
                            `);
                        } else if (appState.userRole === 'pelapor') {
                            if (row.status === 'Pending') {
                                buttons.push(`
                                    <a href="${urls.edit}/${row.id}" class="btn btn-sm btn-primary rounded-lg border-0 mr-1" data-tippy-content="Edit Report">
                                        <i class="text-xs fa fa-edit"></i>
                                    </a>
                                `);
                                buttons.push(`
                                    <button class="btn btn-sm btn-danger rounded-lg border-0 delete-btn mr-1" data-id="${row.id}" data-tippy-content="Delete Report">
                                        <i class="text-xs fas fa-trash"></i>
                                    </button>
                                `);
                            } else if (row.status === 'Approved') {
                                buttons.push(`
                                    <a href="${urls.edit}/${row.id}" class="btn btn-sm btn-success rounded-lg border-0 mr-1" data-tippy-content="Complete Report">
                                        <i class="text-xs fa fa-check-circle"></i>
                                    </a>
                                `);
                            }
                        } else if ((appState.userRole === 'kontraktor' && row.status === 'Pending') || (appState.userRole === 'rab' && row.status === 'On Process' && !row.rab_final_file)) {
                            buttons.push(`
                                <a href="${urls.edit}/${row.id}" class="btn btn-sm btn-default shadow rounded-lg border-0 mr-1" data-tippy-content="Process Report">
                                    <i class="text-xs fa fa-play"></i>
                                </a>
                            `);
                        } else if (appState.userRole === 'manager' && row.status === 'On Process') {
                            buttons.push(`
                                <a href="${urls.edit}/${row.id}" class="btn btn-sm btn-success rounded-lg border-0 mr-1" data-tippy-content="Approve Report">
                                    <i class="text-xs fa fa-check"></i>
                                </a>
                            `);
                        }

                        return buttons.join('');
                    },
                    orderable: false,
                    targets: 11
                }
            ],
            scrollResize: true,
            scrollX: "100%",
            scrollCollapse: true,
            paging: true,
            responsive: false,
            lengthChange: false,
            autoWidth: false,
            searching: true,
            select: false,
            dom: 'lftipr'
        });

        // Delete Report Button
        $(document).on('click', '.delete-btn', function() {
            var reportId = $(this).data('id');
            $('#deletereportId').val(reportId);
            $('#deleteModal').modal('show');
        });

        // Confirm Delete
        $('#confirmDeleteBtn').click(function() {
            var reportId = $('#deletereportId').val();

            $.ajax({
                url: urls.delete + '/' + reportId,
                method: 'DELETE',
                success: function() {
                    $('#deleteModal').modal('hide');
                    table.ajax.reload();
                    toastr.success('Report deleted successfully');
                },
                error: function() {
                    toastr.error("Failed to delete report.");
                }
            });
        });

    });
</script>