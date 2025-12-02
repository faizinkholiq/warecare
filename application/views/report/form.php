<style>
    .status-badge {
        font-size: 1.2rem;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
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

    .dropzone {
        border: 2px dashed #ced4da;
        border-radius: 5px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 15px;
    }

    .dropzone.readonly {
        cursor: default;
    }

    .dropzone:hover {
        border-color: #6c757d;
        background-color: #f8f9fa;
    }

    .dropzone.readonly:hover {
        border-color: #ced4da;
        background-color: #fff;
    }

    .dropzone.active {
        border-color: #007bff;
        background-color: #e7f1ff;
    }

    .preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        /* margin-top: 20px; */
        justify-content: center;
    }

    .preview-item {
        position: relative;
        width: 100px;
        height: 100px;
        overflow: visible;
    }

    .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15);
        border-radius: 15px;
        cursor: pointer;
    }

    .preview-item img:hover {
        transform: scale(1.05);
        transition: transform 0.3s;
    }

    .preview-item .remove-btn {
        position: absolute;
        top: -5px;
        right: -7px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        cursor: pointer;
        z-index: 1;
    }

    .preview-item .remove-btn:hover {
        background: #c82333;
    }

    .preview-item-full {
        position: relative;
        width: 30%;
        overflow: visible;
        height: 25rem;
    }

    .preview-item-full.split {
        width: 80%;
    }

    .preview-item-full img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15);
        border-radius: 15px;
        cursor: pointer;
    }

    .preview-item-full .remove-btn {
        position: absolute;
        top: -5px;
        right: -7px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        cursor: pointer;
        z-index: 1;
    }

    .preview-item-full .remove-btn:hover {
        background: #c82333;
    }

    .file-input {
        display: none;
    }

    .invalid-feedback {
        display: none;
        color: #dc3545;
    }

    /* Modal for zoomed image */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 1500;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        overflow: auto;
        text-align: center;
        padding: 3rem;
    }

    .image-modal .close-modal {
        font-size: 2.5rem;
        color: white;
        cursor: pointer;
        margin: 0;
        padding: 0;
        top: 0.5rem;
        right: 2rem;
        position: fixed;
    }

    .image-modal img {
        width: 100%;
        height: 100%;
        max-width: fit-content;
        max-height: fit-content;
    }

    /* Detail Table */

    #reportDetailTable tr th {
        text-align: center;
    }

    #reportDetailTable tr td,
    #reportDetailTable tr th {
        vertical-align: middle;
        border-right: 1px solid #dee2e6;
    }

    #reportDetailTable tr td:last-child,
    #reportDetailTable tr th:last-child {
        border-right: none;
    }

    #reportDetailTable tbody tr.child-row td {
        padding-left: 40px;
    }

    .detail-status-ok {
        background-color: #d1e7dd;
        color: #0f5132;
    }

    .detail-status-not-ok {
        background-color: #f8d7da;
        color: #842029;
    }

    .status-radio-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 0.3rem 0;
        margin-bottom: 12px;
        margin-right: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        width: 6rem;
        text-align: center;
    }

    .status-radio-card[for="reportDetailStatusOK"]:hover {
        border: 1px solid #d1e7dd;
        background-color: #d1e7dd;
        color: #0f5132;
    }

    .status-radio-card[for="reportDetailStatusNotOK"]:hover {
        border: 1px solid #f8d7da;
        background-color: #f8d7da;
        color: #842029;
    }

    .status-radio-card[for="reportDetailStatusOK"].selected {
        border: 1px solid #d1e7dd;
        background-color: #d1e7dd;
        color: #0f5132;
    }

    .status-radio-card[for="reportDetailStatusNotOK"].selected {
        border: 1px solid #f8d7da;
        background-color: #f8d7da;
        color: #842029;
    }

    .status-radio-input {
        display: none;
    }

    .status-radio-card .status-label {
        font-weight: 600;
        display: block;
    }
</style>

<div class="container-fluid my-container">
    <div class="card rounded-lg shadow border-0">
        <form id="reportForm">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6" style="padding-left: 15px;">
                        <label for="reportEntity">Entity</label>
                        <select
                            class="form-control"
                            id="reportEntity"
                            required
                            <?= $mode === 'detail' || ($mode === 'edit' && isset($report) && ($report['status'] !== 'Pending' || ($report['status'] === 'Pending' && $this->auth_lib->role() === 'kontraktor'))) ? 'disabled' : '' ?>>
                            <option value="">- Pilih Entity -</option>
                            <?php foreach ($list_data['entity'] as $key => $value): ?>
                                <option value="<?= $value['id'] ?>" <?= isset($report) && $report['entity_id'] == $value['id'] ? 'selected' : '' ?>><?= $value['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if ($mode !== 'create'): ?>
                        <div class="col-md-6 text-right">
                            <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $report["status"])) ?>">
                                <i class="fas fa-<?php
                                                    switch ($report["status"]) {
                                                        case 'Pending':
                                                            echo 'clock';
                                                            break;
                                                        case 'On Process':
                                                            echo 'spinner';
                                                            break;
                                                        case 'Approved':
                                                            echo 'check-circle';
                                                            break;
                                                        case 'Rejected':
                                                            echo 'times-circle';
                                                            break;
                                                        case 'Completed':
                                                            echo 'check-double';
                                                            break;
                                                        default:
                                                            echo 'circle';
                                                    }
                                                    ?> mr-2"></i>
                                <?= $report["status"] ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="reportProject">Project</label>
                    <select
                        class="form-control"
                        id="reportProject"
                        required
                        <?= $mode === 'detail' || ($mode === 'edit' && isset($report) && ($report['status'] !== 'Pending' || ($report['status'] === 'Pending' && $this->auth_lib->role() === 'kontraktor'))) ? 'disabled' : '' ?>>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="reportCompany">Nama Perusahaan</label>
                    <div class="d-flex">
                        <input type="hidden" id="reportCompanyID">
                        <input
                            type="text"
                            class="form-control <?= $mode === 'create' || ($mode === 'edit' && isset($report) && ($report['status'] === 'Pending' && $this->auth_lib->role() === 'pelapor')) ? '' : '' ?>"
                            id="reportCompany"
                            required
                            readonly>
                        <?php if ($mode === 'create' || ($mode === 'edit' && isset($report) && ($report['status'] === 'Pending' && $this->auth_lib->role() === 'pelapor'))): ?>
                            <button
                                type="button"
                                onclick="showWarehouses()"
                                class="btn btn-default border-0 shadow-sm rounded-lg font-weight-bold text-navy ml-2"
                                style="width:200px;">
                                <i class="fas fa-caret-down mr-1"></i> Pilih Perusahaan
                            </button>
                        <?php endif ?>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="reportWarehouse">Nomor Gudang</label>
                    <div class="d-flex">
                        <input type="hidden" id="reportWarehouseID">
                        <input
                            type="text"
                            class="form-control <?= $mode === 'create' || ($mode === 'edit' && isset($report) && ($report['status'] === 'Pending' && $this->auth_lib->role() === 'pelapor')) ? '' : '' ?>"
                            id="reportWarehouse"
                            required
                            readonly>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="reportCategory">Kategori Pengaduan</label>
                    <select
                        class="form-control"
                        id="reportCategory"
                        required
                        <?= $mode === 'detail' || ($mode === 'edit' && isset($report) && ($report['status'] !== 'Pending' || ($report['status'] === 'Pending' && $this->auth_lib->role() === 'kontraktor'))) ? 'disabled' : '' ?>>
                        <option value="">- Pilih Kategori -</option>
                        <?php foreach ($list_data['category'] as $key => $value): ?>
                            <option
                                value="<?= $value['id'] ?>"
                                <?= isset($report) && $report['category_id'] == $value['id'] ? 'selected' : '' ?>>
                                <?= $value['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="reportDetailContainer" class="col-md-12" style="display: <?= isset($report) && in_array($report['category_id'], $category_with_detail) ? '' : 'none'; ?>;">
                    <div style="margin: 2rem 0 3rem;">
                        <?php if ($mode === 'create' || ($mode === 'edit' && $report['status'] === 'Pending' && $this->auth_lib->role() === 'pelapor')): ?>
                            <div class="d-flex justify-content-between">
                                <button type="button" id="reportDetailAddBtn" class="btn btn-default rounded-lg shadow border-0">
                                    <i class="fas fa-plus-circle mr-1"></i> Tambah Uraian
                                </button>
                            </div>
                        <?php endif; ?>
                        <div class="table-responsive shadow rounded-lg" style="margin-top: 1.5rem;">
                            <table id="reportDetailTable" class="table text-sm" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th width="3%">No</th>
                                        <th width="25%">Uraian</th>
                                        <th width="7%">Status</th>
                                        <th width="12%">Kondisi</th>
                                        <th>Keterangan</th>
                                        <th width="5%">Show</th>
                                        <th width="8%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="" style="margin-top: 2.5rem;">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="mb-0">Pekerjaan</label>
                            <?php if ($mode === 'create'): ?>
                                <button type="button" id="addWorkRowBtn" class="btn btn-sm btn-default text-navy font-weight-bold rounded-lg shadow-sm border-0">
                                    <i class="fas fa-plus-circle mr-1"></i> Tambah
                                </button>
                            <?php endif; ?>
                        </div>
                        <div id="workRowsContainer">
                        </div>
                    </div>
                </div>
                <?php if ($mode !== 'create' && in_array($this->auth_lib->role(), ['kontraktor', 'rab', 'manager'])): ?>
                    <div class="form-group col-md-6">
                        <label for="reportRAB">RAB</label>
                        <select
                            class="form-control"
                            id="reportRAB"
                            required
                            <?= $mode === 'detail' || ($mode === 'edit' && $this->auth_lib->role() !== 'kontraktor') ? 'disabled' : '' ?>>
                            <option value="0" <?= isset($report) && $report['is_rab'] == 0 ? 'selected' : '' ?>>Tanpa RAB</option>
                            <option value="1" <?= isset($report) && $report['is_rab'] == 1 ? 'selected' : '' ?>>Dengan RAB</option>
                        </select>
                    </div>

                    <?php if ($mode === 'edit' || ($mode === 'detail' && isset($report) && (bool)$report['is_rab'])): ?>
                        <div id="reportRABContainer">
                            <div class="form-group col-md-6">
                                <div class="d-flex align-items-center justify-content-between border rounded-lg py-2 px-2">
                                    <?php if ($mode !== 'detail'): ?>
                                        <input type="file" class="form-control" id="reportRABFile" hidden>
                                    <?php endif; ?>
                                    <div>
                                        <?php if (isset($report['rab']['file']) && !empty($report['rab']['file'])): ?>
                                            <a
                                                id="reportRABDownloadFile"
                                                href="<?= site_url('file/download/' . $report['rab']['file']) ?>"
                                                class="btn btn-sm bg-navy rounded-lg">
                                                <i class="fas fa-download mr-1"></i> Download File
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($mode !== 'detail'): ?>
                                            <button
                                                id="reportRABSelectFile"
                                                type="button"
                                                class="btn btn-sm bg-navy rounded-lg"
                                                onclick="document.getElementById('reportRABFile').click()"
                                                style="display:<?= isset($report['rab']['file']) && !empty($report['rab']['file']) ? 'none' : '' ?>">
                                                <i class="fas fa-folder-open mr-1"></i> Pilih File
                                            </button>
                                        <?php endif; ?>
                                        <span id="reportRABFileName" class="ml-2 font-weight-bold text-gray text-sm"><?= isset($report['rab']['file']) && !empty($report['rab']['file']) ? $report['rab']['file'] : ($mode === 'detail' ? 'Belum ada file yang diupload' : 'Belum ada file yang dipilih') ?></span>
                                    </div>
                                    <?php if ($mode !== 'detail' && $this->auth_lib->role() === 'kontraktor'): ?>
                                        <button
                                            id="reportRABRemoveFile"
                                            type="button"
                                            class="btn btn-sm text-danger"
                                            style="display:<?= isset($report['rab']['file']) && !empty($report['rab']['file']) ? '' : 'none' ?>">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="reportRABNo">Nomor RAB</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="reportRABNo"
                                    value="<?= isset($report['rab']['no']) ? $report['rab']['no'] : ''; ?>"
                                    <?= $mode === 'detail' || ($mode === 'edit' && $this->auth_lib->role() !== 'kontraktor') ? 'disabled' : '' ?>>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="reportRABName">Nama RAB</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="reportRABName"
                                    value="<?= isset($report['rab']['name']) ? $report['rab']['name'] : ''; ?>"
                                    <?= $mode === 'detail' || ($mode === 'edit' && $this->auth_lib->role() !== 'kontraktor') ? 'disabled' : '' ?>>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="reportRABBudget">Nominal RAB</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white font-weight-bold">Rp</span>
                                    </div>
                                    <input
                                        type="text"
                                        class="form-control input-currency"
                                        id="reportRABBudget"
                                        value="<?= isset($report['rab']['budget']) ? number_format($report['rab']['budget'], 0, ',', '.') : ''; ?>"
                                        <?= $mode === 'detail' || ($mode === 'edit' && $this->auth_lib->role() !== 'kontraktor') ? 'disabled' : '' ?> />
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="reportRABDescription">Keterangan</label>
                                <textarea
                                    class="form-control"
                                    id="reportRABDescription"
                                    <?= $mode === 'detail' || ($mode === 'edit' && $this->auth_lib->role() !== 'kontraktor') ? 'disabled' : '' ?>><?= isset($report['rab']['description']) ? $report['rab']['description'] : ''; ?></textarea>
                            </div>
                        </div>
                        <?php if (
                            in_array($this->auth_lib->role(), ['rab', 'manager'])
                            && ($mode === 'detail' || $mode === 'edit')
                            && isset($report['rab']['file']) && !empty($report['rab']['file'])
                        ): ?>
                            <div class="form-group col-md-6">
                                <label for="reportRABFinalFile">RAB Final</label>
                                <div class="d-flex align-items-center justify-content-between border rounded-lg py-2 px-2">
                                    <?php if ($mode !== 'detail'): ?>
                                        <input type="file" class="form-control" id="reportRABFinalFile" hidden>
                                    <?php endif; ?>
                                    <div>
                                        <?php if (isset($report['rab']['final_file']) && !empty($report['rab']['final_file'])): ?>
                                            <a
                                                id="reportRABFinalDownloadFile"
                                                href="<?= site_url('file/download/' . $report['rab']['final_file']) ?>"
                                                class="btn btn-sm bg-navy rounded-lg">
                                                <i class="fas fa-download mr-1"></i> Download File
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($mode !== 'detail'): ?>
                                            <button
                                                id="reportRABFinalSelectFile"
                                                type="button" class="btn btn-sm bg-navy rounded-lg"
                                                onclick="document.getElementById('reportRABFinalFile').click()"
                                                style="display:<?= isset($report) && $report['rab']['final_file'] ? 'none' : '' ?>">
                                                <i class="fas fa-folder-open mr-1"></i> Pilih File
                                            </button>
                                        <?php endif; ?>
                                        <span
                                            id="reportRABFinalFileName"
                                            class="ml-2 font-weight-bold text-gray text-sm">
                                            <?= isset($report['rab']['final_file']) && !empty($report['rab']['final_file']) ? $report['rab']['final_file'] : ($mode === 'detail' ? 'Belum ada file yang diupload' : 'Belum ada file yang dipilih') ?>
                                        </span>
                                    </div>
                                    <?php if ($mode !== 'detail' && $this->auth_lib->role() === 'rab'): ?>
                                        <button
                                            id="reportRABFinalRemoveFile"
                                            type="button"
                                            class="btn btn-sm text-danger"
                                            style="display:<?= isset($report['rab']['final_file']) && !empty($report['rab']['final_file']) ? '' : 'none' ?>">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="reportRABFinalBudget">Nominal RAB Final</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white font-weight-bold">Rp</span>
                                    </div>
                                    <input
                                        type="text"
                                        class="form-control input-currency"
                                        id="reportRABFinalBudget"
                                        value="<?= isset($report['rab']['final_budget']) ? number_format($report['rab']['final_budget'], 0, ',', '.') : ''; ?>"
                                        <?= $mode === 'detail' || ($mode === 'edit' && $this->auth_lib->role() !== 'rab') ? 'disabled' : '' ?> />
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($mode !== 'create' && $this->auth_lib->role() === 'manager'): ?>
                    <div class="border-top pt-4" style="margin-top: 2.5rem;">
                        <div class="form-group col-md-6">
                            <label for="reportRABNo">Bukti Pembayaran</label>
                            <div class="d-flex align-items-center justify-content-between border rounded-lg py-2 px-2">
                                <?php if ($mode !== 'detail'): ?>
                                    <input type="file" class="form-control" id="reportManagerPaymentFile" hidden>
                                <?php endif; ?>
                                <div>
                                    <?php if (isset($report['manager']['payment_file']) && !empty($report['manager']['payment_file'])): ?>
                                        <a
                                            id="reportManagerDownloadPaymentFile"
                                            href="<?= site_url('file/download/' . $report['manager']['payment_file']) ?>"
                                            class="btn btn-sm bg-navy rounded-lg">
                                            <i class="fas fa-download mr-1"></i> Download File
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($mode !== 'detail'): ?>
                                        <button
                                            id="reportManagerSelectPaymentFile"
                                            type="button"
                                            class="btn btn-sm bg-navy rounded-lg"
                                            onclick="document.getElementById('reportManagerPaymentFile').click()"
                                            style="display:<?= isset($report['manager']['payment_file']) && !empty($report['manager']['payment_file']) ? 'none' : '' ?>">
                                            <i class="fas fa-folder-open mr-1"></i> Pilih File
                                        </button>
                                    <?php endif; ?>
                                    <span
                                        id="reportManagerPaymentFileName"
                                        class="ml-2 font-weight-bold text-gray text-sm">
                                        <?= isset($report['manager']['payment_file']) && !empty($report['manager']['payment_file']) ? $report['manager']['payment_file'] : ($mode === 'detail' ? 'Belum ada file yang diupload' : 'Belum ada file yang dipilih') ?>
                                    </span>
                                </div>
                                <?php if ($mode !== 'detail' && $this->auth_lib->role() === 'manager'): ?>
                                    <button
                                        id="reportManagerRemovePaymentFile"
                                        type="button"
                                        class="btn btn-sm text-danger"
                                        style="display:<?= isset($report['manager']['payment_file']) && !empty($report['manager']['payment_file']) ? '' : 'none' ?>">
                                        <i class="fa fa-times"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="reportManagerPaidBy">Dibayar Oleh</label>
                            <select
                                name="condition"
                                class="form-control"
                                id="reportManagerPaidBy"
                                <?= $mode === 'detail' || ($mode === 'edit' && $this->auth_lib->role() !== 'manager') ? 'disabled' : '' ?>>
                                <option value="Customer" <?= isset($report['manager']['paid_by']) && $report['manager']['paid_by'] === 'Customer' ? 'selected' : ''; ?>>Customer</option>
                                <option value="Waringin" <?= isset($report['manager']['paid_by']) && $report['manager']['paid_by'] === 'Waringin' ? 'selected' : ''; ?>>Waringin</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="reportManagerBill">Nominal Tagihan <?= isset($report['manager']['paid_by']) && $report['manager']['paid_by'] ? $report['manager']['paid_by'] : 'Customer' ?></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white font-weight-bold">Rp</span>
                                </div>
                                <input type="text"
                                    class="form-control input-currency"
                                    id="reportManagerBill"
                                    value="<?= isset($report['manager']['bill']) ? number_format($report['manager']['bill'], 0, ',', '.') : ''; ?>"
                                    <?= $mode === 'detail' || ($mode === 'edit' && $this->auth_lib->role() !== 'manager') ? 'disabled' : '' ?>>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="reportManagerName">Atas Nama</label>
                            <input type="text"
                                class="form-control"
                                id="reportManagerName"
                                value="<?= isset($report['manager']['name']) ? $report['manager']['name'] : ''; ?>"
                                <?= $mode === 'detail' || ($mode === 'edit' && $this->auth_lib->role() !== 'manager') ? 'disabled' : '' ?>>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="reportManagerDate">Tanggal Bayar</label>
                            <input type="date"
                                class="form-control"
                                id="reportManagerDate"
                                value="<?= isset($report['manager']['date']) ? $report['manager']['date'] : date('Y-m-d'); ?>"
                                <?= $mode === 'detail' || ($mode === 'edit' && $this->auth_lib->role() !== 'manager') ? 'disabled' : '' ?>>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="reportManagerTaxReport">Lapor Pajak</label>
                            <select name="condition"
                                class="form-control"
                                id="reportManagerTaxReport"
                                <?= $mode === 'detail' || ($mode === 'edit' && $this->auth_lib->role() !== 'manager') ? 'disabled' : '' ?>>
                                <option value="1" <?= isset($report['manager']['paid_by']) && $report['manager']['paid_by'] == 1 ? 'selected' : ''; ?>>Iya</option>
                                <option value="0" <?= isset($report['manager']['paid_by']) && $report['manager']['paid_by'] == 0 ? 'selected' : ''; ?>>Tidak</option>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-white border-top rounded">
                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('report') ?>" class="btn btn-default border-0 shadow-sm rounded-lg">
                        <i class="fas fa-chevron-left mr-2"></i> Batal
                    </a>
                    <div>
                        <?php if ($mode === 'edit'): ?>
                            <?php if (in_array($this->auth_lib->role(), ['manager'])): ?>
                                <button
                                    type="button"
                                    id="rejectReportBtn"
                                    onclick="rejectReport()"
                                    class="btn rounded-lg border-0 shadow-sm btn-danger ml-2">
                                    <i class="fas fa-times mr-2"></i> Ditolak
                                </button>
                            <?php endif; ?>

                            <?php if (in_array($this->auth_lib->role(), ['kontraktor', 'rab'])): ?>
                                <button
                                    type="submit"
                                    class="btn rounded-lg border-0 shadow-sm bg-navy ml-2">
                                    <i class="fas fa-arrow-right mr-2"></i> Ajukan Pengaduan
                                </button>
                            <?php endif; ?>

                            <?php if ($this->auth_lib->role() === 'manager'): ?>
                                <button
                                    type="submit"
                                    id="approveReportBtn"
                                    class="btn rounded-lg border-0 shadow-sm btn-success ml-2">
                                    <i class="fas fa-check mr-2"></i> Setujui Pengaduan
                                </button>
                            <?php endif; ?>

                            <?php if ($this->auth_lib->role() === 'pelapor'): ?>
                                <?php if (isset($report) && $report['status'] === 'Approved'): ?>
                                    <button
                                        type="submit"
                                        class="btn rounded-lg border-0 shadow-sm btn-success ml-2">
                                        <i class="fas fa-check-circle mr-2"></i> Selesaikan Pekerjaan
                                    </button>
                                <?php else: ?>
                                    <button
                                        type="submit"
                                        class="btn rounded-lg border-0 shadow-sm btn-success ml-2">
                                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($this->auth_lib->role() === 'manager'): ?>
                            <a
                                href="<?= site_url('report/memo/' . $report['id']) ?>"
                                target="_blank"
                                id="printMemoBtn"
                                class="btn rounded-lg border-0 shadow-sm btn-white font-weight-bold ml-2"
                                style="display: <?= $report['status'] === 'Approved' || $report['status'] === 'Completed' ? '' : 'none' ?>;">
                                <i class="fas fa-print mr-2"></i> Cetak Memo
                            </a>
                        <?php endif; ?>
                        <?php if ($mode === 'create'): ?>
                            <button
                                type="button"
                                onclick="resetForm()"
                                class="btn rounded-lg border-0 shadow-sm btn-danger ml-2">
                                <i class="fas fa-trash mr-2"></i> Reset
                            </button>
                            <button
                                type="submit"
                                class="btn rounded-lg border-0 shadow-sm bg-navy ml-2">
                                <i class="fas fa-bullhorn mr-2"></i> Ajukan Pengaduan
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Report Warehouse Modal -->
<div class="modal fade" id="reportWarehouseModal" tabindex="-1" aria-labelledby="reportDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="pt-2 pr-3">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive text-sm bg-white shadow mt-3 rounded-lg p-3">
                    <table id="reportWarehouseTable" class="table" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th class="dt-center">No</th>
                                <th class="dt-center">No. Gudang</th>
                                <th class="dt-center">Penyewa/Pembeli</th>
                                <th class="dt-center">Status</th>
                                <th class="dt-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-white shadow-sm border-0 font-weight-bold" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- Report Details Modal -->
<div class="modal fade" id="reportDetailModal" tabindex="-1" aria-labelledby="reportDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportDetailModalLabel">Tambah Uraian</h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="reportDetailForm">
                    <input type="hidden" id="reportDetailID">
                    <div class="row">
                        <div class="form-group col-md-11">
                            <label for="reportDetailLevel">Level</label>
                            <select
                                name="level"
                                class="form-control"
                                id="reportDetailLevel"
                                required>
                                <option value="1">Level 1 (Main Category)</option>
                                <option value="2">Level 2 (Sub Item)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" id="reportDetailParentItemContainer">
                        <div class="form-group col-md-11">
                            <label for="reportDetailParent">Parent Item</label>
                            <select
                                id="reportDetailParent"
                                class="form-control">
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-11">
                            <label for="reportDetailDescription">Uraian</label>
                            <input
                                id="reportDetailDescription"
                                type="text"
                                name="description"
                                class="form-control"
                                required>
                        </div>
                    </div>
                    <div class="row child-detail-container">
                        <div class="form-group col-md-11">
                            <label for="status">Status</label>
                            <div class="radio-group">
                                <label class="status-radio-card" for="reportDetailStatusOK">
                                    <input
                                        id="reportDetailStatusOK"
                                        type="radio"
                                        name="status"
                                        class="status-radio-input"
                                        value="OK"
                                        checked>
                                    <span class="status-label">OK</span>
                                </label>
                                <label class="status-radio-card" for="reportDetailStatusNotOK">
                                    <input
                                        id="reportDetailStatusNotOK"
                                        type="radio"
                                        name="status"
                                        class="status-radio-input"
                                        value="Not OK">
                                    <span class="status-label">Not OK</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row child-detail-container">
                        <div class="form-group col-md-11">
                            <label for="reportDetailCondition">Kondisi</label>
                            <select
                                id="reportDetailCondition"
                                name="condition"
                                class="form-control">
                                <option value="Tidak Butuh Perbaikan">Tidak Butuh Perbaikan</option>
                                <option value="Butuh Perbaikan">Butuh Perbaikan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row child-detail-container">
                        <div class="form-group col-md-11">
                            <label for="reportDetailInformation">Keterangan</label>
                            <textarea
                                id="reportDetailInformation"
                                name="information"
                                class="form-control"
                                style="height: 7rem;"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal">Cancel</button>
                <button
                    type="button"
                    class="btn btn-primary"
                    id="reportDetailSaveBtn">Save Row</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Report Detail Modal -->
<div class="modal fade" id="deleteReportDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex"><i class="fas fa-trash mr-3 rounded px-2 py-2 text-danger bg-light-danger text-sm"></i> <span>Hapus Detail</span></h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="deleteReportDetailID">
                <p>Apakah kamu yakin ingin menghapus data ini?</p>
                <p class="warning-text text-center border px-4 py-2 border-warning text-bold rounded bg-light-warning" style="font-size:13px"></p>
            </div>
            <div class="modal-footer d-flex">
                <button
                    type="button"
                    style="flex: 1 1 auto;"
                    class="btn btn-default rounded-lg border-0 shadow"
                    data-dismiss="modal">Batal</button>
                <button
                    id="reportDetailDeleteBtn"
                    type="button"
                    style="flex: 1 1 auto;"
                    class="btn btn-danger rounded-lg border-0 shadow">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Reason Modal -->
<div class="modal fade" id="reportReasonModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex"><i class="fas fa-times mr-3 rounded px-2 py-2 text-danger bg-light-danger text-sm"></i> <span>Tolak Pengaduan</span></h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah kamu yakin ingin menolak pengaduan ini?</p>
                <label>Silahkan tulis alasannya dibawah ini:</label>
                <textarea
                    id="reportRejectedReason"
                    name="reportRejectedReason"
                    class="form-control"
                    style="height: 7rem;"></textarea>
            </div>
            <div class="modal-footer d-flex">
                <button
                    type="button"
                    style="flex: 1 1 auto;"
                    class="btn btn-default rounded-lg border-0 shadow"
                    data-dismiss="modal">Batal</button>
                <button
                    type="button"
                    onclick="submitRejectReport()"
                    style="flex: 1 1 auto;"
                    class="btn btn-danger rounded-lg border-0 shadow">Ya, Tolak Pengaduan</button>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="image-modal" id="imageModal">
    <span class="close-modal"><i class="fa fa-times"></i></span>
    <img id="zoomedImage">
</div>

<script>
    const URLS = {
        default: "<?= site_url('report') ?>",
        create: "<?= site_url('report/create') ?>",
        edit: "<?= site_url('report/edit') ?>",
        reject: "<?= site_url('report/reject') ?>",
        get_warehouses: "<?= site_url('warehouse/get_list') ?>",
    };

    const domCache = {
        form: {
            name: document.getElementById('reportForm'),
            item: {
                entity: document.getElementById('reportEntity'),
                project: document.getElementById('reportProject'),
                company: {
                    id: document.getElementById('reportCompanyID'),
                    name: document.getElementById('reportCompany'),
                },
                warehouse: {
                    id: document.getElementById('reportWarehouseID'),
                    name: document.getElementById('reportWarehouse'),
                },
                category: document.getElementById('reportCategory'),
                title: document.getElementById('reportTitle'),
                description: {
                    input: document.getElementById('reportDescription'),
                    container: document.getElementById('reportDescriptionContainer'),
                },
                detail: {
                    container: document.getElementById('reportDetailContainer'),
                },
                rab: {
                    input: document.getElementById('reportRAB'),
                    container: document.getElementById('reportRABContainer'),
                },
                rabNo: document.getElementById('reportRABNo'),
                rabName: document.getElementById('reportRABName'),
                rabBudget: document.getElementById('reportRABBudget'),
                rabDescription: document.getElementById('reportRABDescription'),
                rabFile: {
                    input: document.getElementById('reportRABFile'),
                    content: document.getElementById('reportRABFileName'),
                    select: document.getElementById('reportRABSelectFile'),
                    remove: document.getElementById('reportRABRemoveFile'),
                    download: document.getElementById('reportRABDownloadFile'),
                },
                rabFinalFile: {
                    input: document.getElementById('reportRABFinalFile'),
                    content: document.getElementById('reportRABFinalFileName'),
                    select: document.getElementById('reportRABFinalSelectFile'),
                    remove: document.getElementById('reportRABFinalRemoveFile'),
                    download: document.getElementById('reportRABFinalDownloadFile')
                },
                rabFinalBudget: document.getElementById('reportRABFinalBudget'),
                managerPaidBy: document.getElementById('reportManagerPaidBy'),
                managerBill: {
                    input: document.getElementById('reportManagerBill'),
                    label: document.querySelector('label[for="reportManagerBill"]')
                },
                managerName: document.getElementById('reportManagerName'),
                managerDate: document.getElementById('reportManagerDate'),
                managerTaxReport: document.getElementById('reportManagerTaxReport'),
                managerPaymentFile: {
                    input: document.getElementById('reportManagerPaymentFile'),
                    content: document.getElementById('reportManagerPaymentFileName'),
                    select: document.getElementById('reportManagerSelectPaymentFile'),
                    remove: document.getElementById('reportManagerRemovePaymentFile'),
                    download: document.getElementById('reportManagerDownloadPaymentFile'),
                },
                work: {
                    input: document.getElementById('reportWorkFiles'),
                    dropzone: document.getElementById('reportWorkDropzone'),
                    preview: document.getElementById('reportWorkPreview'),
                    error: document.getElementById('reportWorkError')
                },
            }
        },
        modal: {
            image: {
                container: document.getElementById('imageModal'),
                content: document.getElementById('zoomedImage'),
                close: document.getElementById('imageModal').querySelector('.close-modal')
            }
        },
        buttons: {
            approveReport: document.getElementById('approveReportBtn'),
            rejectReport: document.getElementById('rejectReportBtn'),
            printMemo: document.getElementById('printMemoBtn'),
            addWorkRow: document.getElementById('addWorkRowBtn'),
        }
    };

    const appState = {
        mode: "<?= $mode ?>",
        userRole: "<?= $this->auth_lib->role() ?>",
        categoryWithDetail: <?= json_encode($category_with_detail) ?>,
        reportData: <?= !empty($report) ? json_encode($report) : '{}' ?>,
        details: [],
        rab: {
            deleted: false
        },
        rabFinal: {
            deleted: false
        },
        manager: {
            deleted: false
        },
        work: {
            files: [],
            deletedIds: []
        },
        listData: {
            project: <?= json_encode($list_data['project']) ?>,
            company: <?= json_encode($list_data['company']) ?>,
        }
    };

    const statusRadioCards = document.querySelectorAll('.status-radio-card');

    const reportDetailModal = $('#reportDetailModal');

    const deleteReportDetailModal = $('#deleteReportDetailModal');

    const reportReasonModal = $('#reportReasonModal');

    const reportDetailTable = $('#reportDetailTable').DataTable({
        paging: false,
        searching: false,
        ordering: false,
        info: false,
        responsive: true,
        autoWidth: false,
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
                    return row.level == 1 ? row.no : '';
                },
                orderable: false,
                targets: 1
            },
            {
                data: "description",
                render: function(data, type, row) {
                    if (row.level == 2) {
                        return `<span class="ml-3" style="display: inline-flex; align-items: center;"><i class="fa fa-circle mr-2" style="font-size: 0.45rem;"></i><span>${data}</span></span>`;
                    }
                    return data;
                },
                orderable: false,
                targets: 2
            },
            {
                data: "status",
                orderable: false,
                className: "dt-center",
                render: function(data, type, row) {
                    return row.level == 2 ? (`<span class="font-weight-bold rounded-lg px-2 py-1 ${data === 'OK' ? 'detail-status-ok' : 'detail-status-not-ok'}">${data}</span>`) : '';
                },
                targets: 3
            },
            {
                data: "condition",
                orderable: false,
                render: (data, type, row) => row.level == 2 ? data : '',
                targets: 4
            },
            {
                data: "information",
                orderable: false,
                render: (data, type, row) => row.level == 2 ? data : '',
                targets: 5
            },
            {
                data: "is_show",
                orderable: false,
                className: "dt-center",
                visible: appState.userRole === 'manager',
                render: function(data, type, row) {
                    const checked = data === '1' ? 'checked' : '';
                    const disabled = appState.mode === 'detail' ? 'disabled' : '';
                    return `<input type="checkbox" class="show-checkbox" data-id="${row.id}" ${checked} ${disabled}>`;
                },
                targets: 6
            },
            {
                data: null,
                orderable: false,
                className: "dt-center",
                visible: appState.mode === 'create' || (appState.mode === 'edit' && appState.reportData.status === 'Pending' && appState.userRole === 'pelapor'),
                render: function(data, type, row) {
                    return `
                        <button type="button" class="btn btn-sm rounded-lg shadow-sm border-0 btn-primary edit-row mr-2" data-id="${row.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm rounded-lg shadow-sm border-0 btn-danger remove-row" data-id="${row.id}">
                            <i class="fas fa-trash"></i>
                        </button>`
                },
                targets: 7
            }
        ],
        data: appState.details,
    });

    const reportWarehouseTable = $('#reportWarehouseTable').DataTable({
        serverSide: true,
        ajax: {
            url: URLS.get_warehouses,
            type: 'POST',
            data: function(d) {
                d.project = domCache.form.item.project.value
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
                className: "align-middle",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                searchable: false,
                orderable: false,
                targets: 1
            },
            {
                data: "name",
                className: "align-middle",
                targets: 2
            },
            {
                data: "company",
                className: "align-middle",
                targets: 3
            },
            {
                data: "status",
                className: "align-middle",
                targets: 4
            },
            {
                data: null,
                width: "8%",
                className: "dt-center align-middle",
                render: function(data, type, row) {
                    const buttons = [
                        `<button onclick="selectWarehouse(${row.company_id}, '${row.company}', ${row.id}, '${row.name}')" type="button" class="btn select-btn btn-sm btn-success shadow rounded-lg border-0 mr-1" data-tippy-content="Pilih Gudang">
                            <i class="text-xs fa fa-check-circle"></i>
                        </button>`
                    ];

                    return buttons.join('');
                },
                orderable: false,
                targets: 5
            }
        ],
        scrollResize: false,
        scrollCollapse: false,
        paging: false,
        info: false,
        responsive: true,
        lengthChange: false,
        autoWidth: true,
        searching: true,
        select: false,
        dom: 'lftr',
    });

    function init() {
        setupEventListeners();
        if (appState.mode === 'create') {
            initializeDefaultData();
        } else {
            initializeExistingData();
        }
    }

    function setupEventListeners() {
        domCache.modal.image.close.addEventListener('click', (e) => {
            closeImageModal();
        });

        domCache.modal.image.container.addEventListener('click', (e) => {
            if (e.target === domCache.modal.image.container) closeImageModal();
        });

        document.addEventListener('keydown', (e) => {
            if (e.target === domCache.modal.image.container && e.key === "Escape") closeImageModal();
        });

        if (appState.mode !== 'detail') {
            domCache.form.name.addEventListener('submit', handleFormSubmit);

            if (domCache.form.item.entity) {
                domCache.form.item.entity.addEventListener('change', (e) => {
                    const value = e.target.value;
                    const projectSelect = domCache.form.item.project;

                    if (projectSelect) {
                        projectSelect.value = null;
                        projectSelect.innerHTML = '';

                        domCache.form.item.company.id.value = '';
                        domCache.form.item.company.name.value = '';
                        domCache.form.item.warehouse.id.value = '';
                        domCache.form.item.warehouse.name.value = '';

                        if (appState.listData.project.length > 0) {
                            const filteredProjects = appState.listData.project.filter(v => v.entity_id == value);

                            if (filteredProjects.length > 0) {
                                filteredProjects.unshift({
                                    id: '',
                                    name: '- Pilih Project -'
                                });

                                filteredProjects.forEach(project => {
                                    const option = document.createElement('option');
                                    option.value = project.id;
                                    option.textContent = project.name;
                                    projectSelect.appendChild(option);
                                });

                                projectSelect.value = filteredProjects[0].id;
                                projectSelect.dispatchEvent(new Event('change'));
                            } else {
                                const option = document.createElement('option');
                                option.value = '';
                                option.textContent = 'No projects available';
                                option.disabled = true;
                                projectSelect.appendChild(option);
                            }
                        }
                    }
                });
            }

            if (domCache.form.item.project) {
                domCache.form.item.project.addEventListener('change', (e) => {
                    domCache.form.item.company.id.value = '';
                    domCache.form.item.company.name.value = '';
                    domCache.form.item.warehouse.id.value = '';
                    domCache.form.item.warehouse.name.value = '';
                });
            }

            if (domCache.form.item.category) {
                const categoryWithDetail = appState.categoryWithDetail;

                if (appState.reportData.category_id) {
                    domCache.form.item.detail.container.style.display = categoryWithDetail.includes(parseInt(appState.reportData.category_id)) ? '' : 'none';
                }

                domCache.form.item.category.addEventListener('change', (e) => {
                    const value = e.target.value;
                    domCache.form.item.detail.container.style.display = categoryWithDetail.includes(parseInt(value)) ? '' : 'none';
                });
            }

            // RAB
            if (domCache.form.item.rab && domCache.form.item.rab.container) {
                domCache.form.item.rab.container.style.display = (appState.reportData.is_rab === '1') ? '' : 'none';
                domCache.form.item.rab.input.addEventListener('change', (e) => {
                    domCache.form.item.rab.container.style.display = (e.target.value === '1') ? '' : 'none';
                });
            }

            if (domCache.form.item.rabFile.input) {
                domCache.form.item.rabFile.input.addEventListener('change', function() {
                    const file = this.files[0];

                    if (file) {
                        // Validate file type
                        const allowedTypes = ['image/png', 'image/jpg', 'image/jpeg', 'application/pdf',
                            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ];
                        const allowedExtensions = ['png', 'jpg', 'jpeg', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
                        const fileExtension = file.name.split('.').pop().toLowerCase();

                        if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
                            toastr.error('Format file tidak valid. Hanya diperbolehkan: PNG, JPG, JPEG, PDF, DOC, DOCX, XLS, XLSX');
                            this.value = '';
                            return;
                        }

                        // Validate file size (20MB)
                        const maxSize = 20 * 1024 * 1024; // 20MB in bytes
                        if (file.size > maxSize) {
                            toastr.error('Ukuran file maksimal 20MB');
                            this.value = '';
                            return;
                        }

                        updateFileUI(domCache.form.item.rabFile.content, domCache.form.item.rabFile.remove, domCache.form.item.rabFile.select, file);
                    }
                });
            }

            if (domCache.form.item.rabFile.remove) {
                domCache.form.item.rabFile.remove.addEventListener('click', function() {
                    resetFileUI(domCache.form.item.rabFile.input, domCache.form.item.rabFile.content, domCache.form.item.rabFile.remove, domCache.form.item.rabFile.select);

                    if (domCache.form.item.rabFile.download) {
                        domCache.form.item.rabFile.download.remove();
                        appState.rab.deleted = true;
                    }
                });
            }

            if (domCache.form.item.rabFinalFile.input) {
                domCache.form.item.rabFinalFile.input.addEventListener('change', function() {
                    const file = this.files[0];

                    if (file) {
                        // Validate file type
                        const allowedTypes = ['image/png', 'image/jpg', 'image/jpeg', 'application/pdf',
                            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ];
                        const allowedExtensions = ['png', 'jpg', 'jpeg', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
                        const fileExtension = file.name.split('.').pop().toLowerCase();

                        if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
                            toastr.error('Format file tidak valid. Hanya diperbolehkan: PNG, JPG, JPEG, PDF, DOC, DOCX, XLS, XLSX');
                            this.value = '';
                            return;
                        }

                        // Validate file size (20MB)
                        const maxSize = 20 * 1024 * 1024; // 20MB in bytes
                        if (file.size > maxSize) {
                            toastr.error('Ukuran file maksimal 20MB');
                            this.value = '';
                            return;
                        }

                        updateFileUI(domCache.form.item.rabFinalFile.content, domCache.form.item.rabFinalFile.remove, domCache.form.item.rabFinalFile.select, file);
                    }
                });
            }

            if (domCache.form.item.rabFinalFile.remove) {
                domCache.form.item.rabFinalFile.remove.addEventListener('click', function() {
                    resetFileUI(domCache.form.item.rabFinalFile.input, domCache.form.item.rabFinalFile.content, domCache.form.item.rabFinalFile.remove, domCache.form.item.rabFinalFile.select);

                    if (domCache.form.item.rabFinalFile.download) {
                        domCache.form.item.rabFinalFile.download.remove();
                        appState.rabFinal.deleted = true;
                    }
                });
            }

            if (domCache.form.item.managerPaymentFile.input) {
                domCache.form.item.managerPaymentFile.input.addEventListener('change', function() {
                    const file = this.files[0];

                    if (file) {
                        // Validate file type
                        const allowedTypes = ['image/png', 'image/jpg', 'image/jpeg', 'application/pdf',
                            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ];
                        const allowedExtensions = ['png', 'jpg', 'jpeg', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
                        const fileExtension = file.name.split('.').pop().toLowerCase();

                        if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
                            toastr.error('Format file tidak valid. Hanya diperbolehkan: PNG, JPG, JPEG, PDF, DOC, DOCX, XLS, XLSX');
                            this.value = '';
                            return;
                        }

                        // Validate file size (20MB)
                        const maxSize = 20 * 1024 * 1024; // 20MB in bytes
                        if (file.size > maxSize) {
                            toastr.error('Ukuran file maksimal 20MB');
                            this.value = '';
                            return;
                        }

                        updateFileUI(domCache.form.item.managerPaymentFile.content, domCache.form.item.managerPaymentFile.remove, domCache.form.item.managerPaymentFile.select, file);
                    }
                });
            }

            if (domCache.form.item.managerPaymentFile.remove) {
                domCache.form.item.managerPaymentFile.remove.addEventListener('click', function() {
                    resetFileUI(domCache.form.item.managerPaymentFile.input, domCache.form.item.managerPaymentFile.content, domCache.form.item.managerPaymentFile.remove, domCache.form.item.managerPaymentFile.select);

                    if (domCache.form.item.managerPaymentFile.download) {
                        domCache.form.item.managerPaymentFile.download.remove();
                        appState.manager.deleted = true;
                    }
                });
            }

            if (domCache.form.item.managerPaidBy) {
                domCache.form.item.managerPaidBy.addEventListener('change', function() {
                    domCache.form.item.managerBill.label.textContent = 'Nominal Tagihan ' + this.value;
                    if (this.value === 'Waringin') {
                        domCache.form.item.managerName.value = 'Waringin';
                    } else {
                        domCache.form.item.managerName.value = '';
                    }
                });
            }

            // Report Detail Modal Events

            statusRadioCards.forEach(card => {
                const radioInput = card.querySelector('.status-radio-input');

                if (radioInput.checked) {
                    card.classList.add('selected');
                }

                card.addEventListener('click', () => {
                    statusRadioCards.forEach(c => c.classList.remove('selected'));

                    card.classList.add('selected');

                    radioInput.checked = true;
                });
            });

            $('#reportDetailLevel').on('change', updateParentDropdown);

            $('#reportDetailAddBtn').on('click', function() {
                $('#reportDetailForm')[0].reset();
                $('#reportDetailID').val('');
                document.querySelector('.status-radio-input:checked').value = 'OK';
                statusRadioCards.forEach(c => c.classList.remove('selected'));
                statusRadioCards[0].classList.add('selected');
                $('#reportDetailModalLabel').text('Tambah Uraian');
                $('#reportDetailParentItemContainer').hide();
                $('.child-detail-container').hide();
                reportDetailModal.modal('show');
            });

            $('#reportDetailDeleteBtn').on('click', function() {
                const id = $('#deleteReportDetailID').val()
                const index = appState.details.findIndex(item => item.id == id);

                if (index != -1) {
                    const item = appState.details[index];

                    const hasChildren = appState.details.some(i => i.parent_id == id);

                    if (hasChildren) {
                        appState.details = appState.details.filter(i => i.parent_id != id);
                    }

                    appState.details.splice(index, 1);

                    renumberDetails();

                    reloadDetailTable();

                    toastr.success("Row removed successfully");
                    deleteReportDetailModal.modal('hide');
                }

            });

            $('#reportDetailTable').on('click', '.edit-row', function() {
                const id = $(this).data('id');
                const item = appState.details.find(item => item.id == id);

                if (item) {
                    $('#reportDetailID').val(item.id);
                    $('#reportDetailLevel').val(item.level);
                    $('#reportDetailDescription').val(item.description);
                    $('#reportDetailStatus').val(item.status);
                    $('#reportDetailCondition').val(item.condition);
                    $('#reportDetailInformation').val(item.information);

                    // Update parent dropdown and set value if level 2
                    updateParentDropdown();
                    if (item.level == 2) {
                        $('#reportDetailParent').val(item.parent_id);
                    }

                    $('#reportDetailModalLabel').text('Edit Row');
                    reportDetailModal.modal('show');
                }
            });

            $('#reportDetailTable').on('change', '.show-checkbox', function() {
                const id = parseInt($(this).data('id'));
                const isChecked = $(this).is(':checked');
                const index = appState.details.findIndex(item => item.id == id);

                console.log('Toggled is_show for ID:', id, 'to', isChecked);
                if (index !== -1) {
                    appState.details[index].is_show = isChecked ? '1' : '0';
                }
            });

            $('#reportDetailSaveBtn').on('click', function() {
                const id = $('#reportDetailID').val();
                const level = parseInt($('#reportDetailLevel').val());
                const description = $('#reportDetailDescription').val();
                const status = document.querySelector('.status-radio-input:checked').value;
                const condition = $('#reportDetailCondition').val();
                const information = $('#reportDetailInformation').val();

                if (!description) {
                    toastr.error("Please enter Uraian");
                    return;
                }

                if (level == 2) {
                    const parentId = parseInt($('#reportDetailParent').val());
                    if (!parentId) {
                        toastr.error("Please select a parent item for Level 2");
                        return;
                    }
                }

                if (id) {
                    // Editing existing row
                    const index = appState.details.findIndex(item => item.id == parseInt(id));
                    if (index != -1) {
                        appState.details[index].description = description;
                        appState.details[index].level = level;
                        appState.details[index].status = status;
                        appState.details[index].condition = condition;
                        appState.details[index].information = information;
                        appState.details[index].is_show = 0;

                        if (level == 2) {
                            appState.details[index].parent_id = parseInt($('#reportDetailParent').val());
                            // Update the numbering
                            const parent = appState.details.find(item => item.id == appState.details[index].parent_id);
                            if (parent) {
                                const siblings = appState.details.filter(i => i.parent_id == appState.details[index].parent_id && i.id != appState.details[index].id);
                                appState.details[index].no = `${parent.no}.${siblings.length + 1}`;
                            }
                        } else {
                            // If changing from level 2 to level 1, remove parentId
                            delete appState.details[index].parent_id;
                            // Renumber level 1 items
                            const level1Items = appState.details.filter(item => item.level == 1);
                            appState.details[index].no = (level1Items.length).toString();
                        }

                        toastr.success("Row updated successfully");
                    }
                } else {
                    // Adding new row
                    const newId = appState.details.length > 0 ? Math.max(...appState.details.map(item => item.id)) + 1 : 1;
                    const newItem = {
                        id: newId,
                        description: description,
                        level: level,
                        status: status,
                        condition: condition,
                        information: information,
                        is_show: 0,
                    };

                    if (level == 1) {
                        // For level 1, generate the next number
                        const level1Items = appState.details.filter(item => item.level == 1);
                        newItem.no = (level1Items.length + 1).toString();
                    } else {
                        // For level 2, get the parent and generate the number
                        const parentId = parseInt($('#reportDetailParent').val());
                        const parent = appState.details.find(item => item.id == parentId);

                        if (!parent) {
                            toastr.error("Please select a valid parent");
                            return;
                        }

                        newItem.parent_id = parentId;
                        const parentChildren = appState.details.filter(item => item.parent_id == parentId);
                        newItem.no = `${parent.no}.${parentChildren.length + 1}`;
                    }

                    appState.details.push(newItem);
                    toastr.success("New row added successfully");
                }

                renumberDetails();

                reloadDetailTable();

                reportDetailModal.modal('hide');
            });

            $('#reportDetailTable').on('click', '.remove-row', function() {
                const id = parseInt($(this).data('id'));
                const index = appState.details.findIndex(item => item.id == id);

                deleteReportDetailModal.find('.warning-text').hide()
                if (index != -1) {
                    const hasChildren = appState.details.some(i => i.parent_id == id);

                    if (hasChildren) {
                        deleteReportDetailModal.find('.warning-text').html('<i class="fa fa-exclamation-triangle text-warning mr-2"></i>Menghapus data ini akan menghapus seluruh sub item dibawahnya.');
                        deleteReportDetailModal.find('.warning-text').show()
                    }

                    $('#deleteReportDetailID').val(id)
                    deleteReportDetailModal.modal('show');
                }
            });

            // Work Events

            if (domCache.buttons.addWorkRow) {
                domCache.buttons.addWorkRow.addEventListener('click', function() {
                    const container = document.getElementById('workRowsContainer');
                    const index = container.children.length;

                    const workRow = document.createElement('div');
                    workRow.className = 'work-row border rounded-lg p-3 mb-3';
                    workRow.dataset.index = index;

                    workRow.innerHTML = `
                        <div class="row">
                            <div class="col-md-${appState.mode === 'create' ? '12' : '6'}">
                                <div class="form-group">
                                    <label style="display: ${appState.mode === 'create' ? 'none' : ''}">Sebelum</label>
                                    <div class="dropzone work-dropzone-before" data-type="before" data-index="${index}">
                                        <p class="font-weight-bold text-gray"><i class="fa fa-upload mr-1"></i> Drag & drop atau klik</p>
                                        <input type="file" class="file-input work-file-before" accept="image/*">
                                        <div class="preview-container work-preview-before"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control work-desc-before" placeholder="Keterangan sebelum">
                                </div>
                            </div>
                            ${appState.mode !== 'create' ? `
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sesudah</label>
                                    <div class="dropzone work-dropzone-after" data-type="after" data-index="${index}">
                                        <p class="font-weight-bold text-gray"><i class="fa fa-upload mr-1"></i> Drag & drop atau klik</p>
                                        <input type="file" class="file-input work-file-after" accept="image/*">
                                        <div class="preview-container work-preview-after"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control work-desc-after" placeholder="Keterangan sesudah">
                                </div>
                            </div>
                            ` : ''}
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-sm btn-danger rounded-lg shadow-sm border-0 remove-work-row">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </div>
                        <input type="hidden" class="work-id" value="">
                    `;

                    container.appendChild(workRow);

                    // Setup event listeners for the new row
                    setupWorkRowListeners(workRow);
                });
            }

            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-work-row')) {
                    const workRow = e.target.closest('.work-row');
                    const workId = workRow.querySelector('.work-id').value;

                    if (workId) {
                        appState.work.deletedIds.push(parseInt(workId));
                    }

                    workRow.remove();
                }

                if (e.target.closest('.remove-work-image')) {
                    const btn = e.target.closest('.remove-work-image');
                    const type = btn.dataset.type;
                    const workRow = btn.closest('.work-row');
                    const dropzone = workRow.querySelector(`.work-dropzone-${type}`);
                    const previewContainer = dropzone.querySelector(`.work-preview-${type}`);
                    const dropzoneText = dropzone.querySelector('p');

                    previewContainer.innerHTML = '';
                    dropzoneText.style.display = 'block';

                    // Clear file reference
                    delete workRow[`file${capitalizeFirst(type)}`];
                }
            });
        }
    }

    function initializeDefaultData() {
        // Work
        appState.work.files = [];
        appState.work.deletedIds = [];

        // Add one default work row if no existing data
        const container = document.getElementById('workRowsContainer');
        if (container && container.children.length === 0) {
            const index = 0;
            const workRow = document.createElement('div');
            workRow.className = 'work-row border rounded-lg p-3 mb-3';
            workRow.dataset.index = index;

            workRow.innerHTML = `
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="dropzone work-dropzone-before" data-type="before" data-index="${index}">
                                <p class="font-weight-bold text-gray"><i class="fa fa-upload mr-1"></i> Drag & drop atau klik</p>
                                <p class="small text-muted">Format: JPG, PNG, GIF. Max 2MB</p>
                                <input type="file" class="file-input work-file-before" accept="image/*">
                                <div class="preview-container work-preview-before"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control work-desc-before" placeholder="Keterangan sebelum">
                        </div>
                    </div>
                </div>
                <input type="hidden" class="work-id" value="">
            `;

            container.appendChild(workRow);
            setupWorkRowListeners(workRow);
        }
    }

    function initializeExistingData() {
        if (appState.reportData.works.length > 0) {
            const container = document.getElementById('workRowsContainer');
            if (container && container.children.length === 0) {
                appState.reportData.works.forEach((work, idx) => {
                    const index = idx;
                    const workRow = document.createElement('div');
                    workRow.className = 'work-row border rounded-lg p-3 mb-3';
                    workRow.dataset.index = index;

                    const hasImageAfter = work.image_name_after && work.image_name_after.trim() !== '';
                    const showAfterSection = (appState.mode === 'edit' && appState.reportData.status === 'Approved') || (appState.mode === 'detail' && hasImageAfter);

                    workRow.innerHTML = `
                        <div class="row">
                            <div class="col-md-${showAfterSection ? '6' : '12'}">
                                <div class="form-group">
                                    <label style="display: ${appState.mode === 'create' ? 'none' : ''}">Sebelum</label>
                                    <div class="dropzone work-dropzone-before ${appState.mode === 'detail' ? 'readonly' : ''}" data-type="before" data-index="${index}">
                                        <input type="file" class="file-input work-file-before" accept="image/*">
                                        <div class="preview-container work-preview-before">
                                            <div class="preview-item-full ${showAfterSection ? 'split' : ''}">
                                                <img src="${BASE_URL+'uploads/'+work.image_name_before}" alt="Before Image" onclick="zoomImage('${BASE_URL+'uploads/'+work.image_name_before}')">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control work-desc-before" placeholder="Keterangan sebelum" value="${work.description_before}" ${appState.mode === 'create' ? '' : 'readonly'}>
                                </div>
                            </div>
                            ${showAfterSection ? `
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sesudah</label>
                                    <div class="dropzone work-dropzone-after ${appState.mode === 'detail' ? 'readonly' : ''}" data-type="after" data-index="${index}">
                                        ${appState.mode !== 'detail' ? '<p class="font-weight-bold text-gray"><i class="fa fa-upload mr-1"></i> Drag & drop atau klik</p>' : ''}
                                        <input type="file" class="file-input work-file-after" accept="image/*">
                                        <div class="preview-container work-preview-after">
                                            ${hasImageAfter ? `
                                            <div class="preview-item-full split">
                                                <img src="${BASE_URL+'uploads/'+work.image_name_after}" alt="After Image" onclick="zoomImage('${BASE_URL+'uploads/'+work.image_name_after}')">
                                            </div>
                                            ` : ''}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control work-desc-after" placeholder="Keterangan sesudah" value="${work.description_after || ''}" ${appState.mode === 'detail' ? 'readonly' : ''}>
                                </div>
                            </div>
                            ` : ''}
                        </div>
                        ${appState.mode === 'create' ? `
                        <div class="text-right">
                            <button type="button" class="btn btn-sm btn-danger rounded-lg shadow-sm border-0 remove-work-row">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </div>
                        ` : ''}
                        <input type="hidden" class="work-id" value="${work.id}">
                    `;

                    container.appendChild(workRow);

                    if (appState.mode !== 'detail') {
                        setupWorkRowListeners(workRow);
                    }
                });

            }
        } else {
            const container = document.getElementById('workRowsContainer');
            if (appState.mode === 'detail') {
                if (container) {
                    container.innerHTML = `<div class="text-red font-weight-bold">* Belum ada bukti yang diupload</div>`;
                    return;
                }
            }

            // Add one default work row if no existing data
            if (container && container.children.length === 0) {
                const index = 0;
                const workRow = document.createElement('div');
                workRow.className = 'work-row border rounded-lg p-3 mb-3';
                workRow.dataset.index = index;

                workRow.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sebelum</label>
                            <div class="dropzone work-dropzone-before" data-type="before" data-index="${index}">
                                <p class="font-weight-bold text-gray"><i class="fa fa-upload mr-1"></i> Drag & drop atau klik</p>
                                <p class="small text-muted">Format: JPG, PNG, GIF. Max 2MB</p>
                                <input type="file" class="file-input work-file-before" accept="image/*">
                                <div class="preview-container work-preview-before"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control work-desc-before" placeholder="Keterangan sebelum">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sesudah</label>
                            <div class="dropzone work-dropzone-after" data-type="after" data-index="${index}">
                                <p class="font-weight-bold text-gray"><i class="fa fa-upload mr-1"></i> Drag & drop atau klik</p>
                                <p class="small text-muted">Format: JPG, PNG, GIF. Max 2MB</p>
                                <input type="file" class="file-input work-file-after" accept="image/*">
                                <div class="preview-container work-preview-after"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control work-desc-after" placeholder="Keterangan sesudah">
                        </div>
                    </div>
                </div>
                <input type="hidden" class="work-id" value="">
            `;

                container.appendChild(workRow);
                setupWorkRowListeners(workRow);
            }
        }

        if (appState.reportData.details) {
            appState.details = appState.reportData.details;
            reloadDetailTable();
        }

        if (appState.listData.project.length > 0) {
            if (domCache.form.item.project) {
                const projectSelect = domCache.form.item.project;
                const filteredProjects = appState.listData.project.filter(v => v.entity_id == appState.reportData.entity_id);

                projectSelect.value = null;
                projectSelect.innerHTML = '';

                if (filteredProjects.length > 0) {
                    filteredProjects.unshift({
                        id: '',
                        name: '- Pilih Project -'
                    });

                    filteredProjects.forEach(project => {
                        const option = document.createElement('option');
                        option.value = project.id;
                        option.textContent = project.name;
                        projectSelect.appendChild(option);
                    });

                    projectSelect.value = appState.reportData.project_id;
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'No projects available';
                    option.disabled = true;
                    projectSelect.appendChild(option);
                }
            }
        }

        domCache.form.item.company.id.value = appState.reportData.company_id;
        domCache.form.item.company.name.value = appState.reportData.company;
        domCache.form.item.warehouse.id.value = appState.reportData.warehouse_id;
        domCache.form.item.warehouse.name.value = appState.reportData.warehouse;
    }

    function showWarehouses() {
        reportWarehouseTable.draw();

        $('#reportWarehouseModal').modal('show');
    }

    function selectWarehouse(company_id, company_name, warehouse_id, warehouse_name) {
        domCache.form.item.company.id.value = company_id;
        domCache.form.item.company.name.value = company_name;
        domCache.form.item.warehouse.id.value = warehouse_id;
        domCache.form.item.warehouse.name.value = warehouse_name;
        $('#reportWarehouseModal').modal('hide');
    }

    function handleFileInputChange(field, e) {
        processFiles(field, e.target.files);
        e.target.value = '';
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.target.classList.add('active');
    }

    function handleDragLeave(e) {
        e.target.classList.remove('active');
    }

    function handleDrop(field, e) {
        e.preventDefault();
        e.target.classList.remove('active');

        if (e.dataTransfer.files.length) {
            processFiles(field, e.dataTransfer.files);
        }
    }

    function processFiles(field, fileList) {
        domCache.form.item[field].error.style.display = 'none';

        const files = Array.from(fileList);
        const MAX_FILE_COUNT = 10;
        const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB

        for (const file of files) {
            if (appState[field].files.length >= MAX_FILE_COUNT) {
                showError(`Maksimum upload file hanya ${MAX_FILE_COUNT} gambar.`);
                break;
            }

            if (!file.type.match('image.*')) {
                showError('Hanya file gambar (JPG, PNG, GIF) yang diperbolehkan.');
                continue;
            }

            if (file.size > MAX_FILE_SIZE) {
                showError('Ukuran file maksimal 2MB.');
                continue;
            }

            appState[field].files.push(file);

            const reader = new FileReader();
            reader.onload = (e) => {
                createPreviewElement(field, `temp-${Date.now()}`, e.target.result, true, file);
            };
            reader.readAsDataURL(file);
        }
    }

    function showError(field, message) {
        domCache.form.item[field].error.textContent = message;
        domCache.form.item[field].error.style.display = 'block';
    }

    function createPreviewElement(field, id, src, isNewFile = false, file = null) {
        const previewItem = document.createElement('div');
        previewItem.className = 'preview-item';
        previewItem.id = `${field}Item${id}`;

        const img = document.createElement('img');
        img.src = src;
        img.dataset.src = src;
        img.alt = `${capitalizeFirst(field)} Image`;

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove-btn';
        removeBtn.innerHTML = '<i class="fa fa-times"></i>';
        removeBtn.onclick = () => removeFile(field, id, file);

        img.onclick = () => zoomImage(src);

        previewItem.appendChild(img);
        previewItem.appendChild(removeBtn);
        domCache.form.item[field].preview.appendChild(previewItem);
    }

    function zoomImage(src) {
        domCache.modal.image.content.src = src;
        domCache.modal.image.container.style.display = "block";
    }

    function closeImageModal() {
        domCache.modal.image.container.style.display = "none";
    }

    function removeFile(field, id, file) {
        if (file instanceof File) {
            appState[field].files = appState[field].files.filter(f => f !== file);
        } else {
            const fileIndex = appState[field].files.findIndex(f => f.id == id);
            if (fileIndex > -1) {
                appState[field].deletedIds.push(id);
                appState[field].files.splice(fileIndex, 1);
            }
        }

        const previewElement = document.getElementById(`${field}Item${id}`);
        if (previewElement) {
            previewElement.remove();
        }
    }

    function handleFormSubmit(e) {
        e.preventDefault();

        const formData = new FormData();

        formData.append('entity_id', domCache.form.item.entity.value);
        formData.append('project_id', domCache.form.item.project.value);
        formData.append('company_id', domCache.form.item.company.id.value);
        formData.append('warehouse_id', domCache.form.item.warehouse.id.value);
        formData.append('category_id', domCache.form.item.category.value);

        if (appState.categoryWithDetail.includes(parseInt(domCache.form.item.category.value))) {
            if (appState.details.length === 0) {
                toastr.error("Uraian tidak boleh kosong. setidaknya tambahkan satu uraian.");
                return;
            }

            formData.append('details', JSON.stringify(appState.details));
        } else {
            formData.append('title', domCache.form.item.title.value);
            formData.append('description', domCache.form.item.description.input.value);
        }

        if (appState.mode === 'create' || (appState.mode === 'edit' && appState.reportData.status === 'Approved')) {
            // Collect work data from DOM
            const workRows = document.querySelectorAll('.work-row');
            const workData = [];

            workRows.forEach((row, index) => {
                let workId = row.querySelector('.work-id').value;
                let workItem = {
                    id: workId || null,
                }

                if (appState.mode === 'create' || (appState.mode === 'edit' && appState.reportData.status === 'Pending')) {
                    if (!row.fileBefore || row.fileBefore instanceof File === false) {
                        return;
                    }

                    let descBefore = row.querySelector('.work-desc-before').value;
                    let fileBefore = row.fileBefore;

                    workItem.description_before = descBefore;

                    if (fileBefore instanceof File) {
                        formData.append(`work_image_before[${index}]`, fileBefore);
                    }
                } else if (appState.mode === 'edit') {
                    if (!row.fileAfter || row.fileAfter instanceof File === false) {
                        return;
                    }

                    let descAfter = row.querySelector('.work-desc-after').value;
                    let fileAfter = row.fileAfter;

                    workItem.description_after = descAfter;

                    if (fileAfter instanceof File) {
                        formData.append(`work_image_after[${index}]`, fileAfter);
                    }
                }

                workData.push(workItem);
            });

            if (workData.length === 0) {
                toastr.error("Mohon tambahkan minimal satu bukti pekerjaan.");
                return;
            }

            // Append work data as JSON
            formData.append('work_data', JSON.stringify(workData));
        }

        if (appState.mode === 'edit') {
            if (appState.userRole === 'pelapor' && appState.reportData.status === 'Pending') {
                submitFormData(formData);
                return;
            }

            const statusMap = {
                'kontraktor': 'On Process',
                'rab': 'On Process',
                'manager': 'Approved',
                'pelapor': 'Completed'
            };

            if (statusMap[appState.userRole]) {
                formData.append('status', statusMap[appState.userRole]);
            }

            const rabRole = ['kontraktor', 'rab'];
            if (rabRole.includes(appState.userRole)) {
                if (appState.userRole === 'kontraktor') {
                    formData.append('is_rab', domCache.form.item.rab.input.value);
                }

                if (domCache.form.item.rab.input.value === '1') {
                    formData.append('rab_no', domCache.form.item.rabNo.value);
                    formData.append('rab_name', domCache.form.item.rabName.value);
                    formData.append('rab_budget', domCache.form.item.rabBudget.value);
                    formData.append('rab_description', domCache.form.item.rabDescription.value);

                    if (domCache.form.item.rabFile.input && domCache.form.item.rabFile.input.files[0]) {
                        formData.append('rab_file', domCache.form.item.rabFile.input.files[0]);
                    }

                    formData.append('delete_rab_file', appState.rab.deleted);

                    if (domCache.form.item.rabFinalFile.input && domCache.form.item.rabFinalFile.input.files[0]) {
                        formData.append('rab_final_file', domCache.form.item.rabFinalFile.input.files[0]);
                    }

                    formData.append('delete_rab_final_file', appState.rabFinal.deleted);

                    if (appState.userRole === 'rab') {
                        formData.append('rab_final_budget', domCache.form.item.rabFinalBudget.value);
                    }
                }
            }

            if (appState.userRole === 'manager') {
                formData.append('manager_paid_by', domCache.form.item.managerPaidBy.value);
                formData.append('manager_bill', domCache.form.item.managerBill.input.value);
                formData.append('manager_name', domCache.form.item.managerName.value);
                formData.append('manager_date', domCache.form.item.managerDate.value);
                formData.append('manager_tax_report', domCache.form.item.managerTaxReport.value);

                if (domCache.form.item.managerPaymentFile.input && domCache.form.item.managerPaymentFile.input.files[0]) {
                    formData.append('manager_payment_file', domCache.form.item.managerPaymentFile.input.files[0]);
                }

                formData.append('delete_manager_payment_file', appState.manager.deleted);
            }
        }

        submitFormData(formData);
    }

    function submitFormData(formData) {
        const url = appState.mode === 'create' ? URLS.create : `${URLS.edit}/${appState.reportData.id}`;

        showLoading(true);
        updateProgress(10);

        const progressInterval = simulateProgress();

        fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json()
            })
            .then(data => {
                clearInterval(progressInterval);
                updateProgress(100);

                setTimeout(() => {
                    showLoading(false);
                    if (data.success) {
                        if (appState.userRole === 'manager') {
                            setTimeout(() => {
                                toastr.success("Report has been approved successfully.");
                                domCache.buttons.approveReport.style.display = 'none';
                                domCache.buttons.rejectReport.style.display = 'none';
                                domCache.buttons.printMemo.style.display = '';
                            }, 500);
                        } else {
                            window.location.href = URLS.default;
                        }
                    } else {
                        throw new Error('Operation failed');
                    }
                }, 500);
            })
            .catch(error => {
                clearInterval(progressInterval);
                showLoading(false);
                toastr.error("Failed to " + appState.mode + " report.");
            });
    }

    function resetForm() {
        domCache.form.name.reset();
        appState.work.files = [];
        appState.work.deletedIds = [];
    }

    function updateFileUI(fileNameElement, removeBtn, selectBtn, file) {
        if (fileNameElement) fileNameElement.textContent = file ? file.name : 'Belum ada file yang dipilih';
        if (removeBtn) removeBtn.style.display = file ? '' : 'none';
    }

    function resetFileUI(fileInput, fileNameElement, removeBtn, selectBtn) {
        if (fileInput) fileInput.value = "";
        if (fileNameElement) fileNameElement.textContent = "Belum ada file yang dipilih";
        if (removeBtn) removeBtn.style.display = 'none';
        if (selectBtn) selectBtn.style.display = '';
    }

    function rejectReport() {
        $('#reportReasonModal').modal('show');
    }

    function submitRejectReport() {
        const formData = new FormData();
        formData.append('reason', $('#reportRejectedReason').val());

        fetch(`${URLS.reject}/${appState.reportData.id}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = URLS.default;
                } else {
                    throw new Error('Operation failed');
                }
            })
            .catch(error => {
                toastr.error("Gagal menolak pengaduan.");
            });
    }

    function reloadDetailTable() {
        reportDetailTable.clear();
        reportDetailTable.rows.add(appState.details);
        reportDetailTable.draw();
    }

    // Function to update parent dropdown based on level selection
    function updateParentDropdown() {
        const level = parseInt($('#reportDetailLevel').val());
        const parentDropdown = $('#reportDetailParent');

        parentDropdown.empty();

        if (level === 1) {
            $('#reportDetailParentItemContainer').hide();
            $('.child-detail-container').hide();
        } else {
            $('#reportDetailParentItemContainer').show();
            $('.child-detail-container').show();

            const parentItems = appState.details.filter(item => item.level == 1);
            if (parentItems.length === 0) {
                parentDropdown.append('<option value="">No parent items available</option>');
            } else {
                parentItems.forEach(item => {
                    parentDropdown.append(`<option value="${item.id}">${item.description}</option>`);
                });
            }
        }
    }

    function renumberDetails() {
        // First, ensure all items have a 'no' property
        appState.details.forEach(item => {
            if (!item.no) {
                return;
            }
        });

        // Create a copy and sort by 'no'
        const sortedArray = appState.details.slice().sort((a, b) => {
            if (!a.no && !b.no) return 0;
            if (!a.no) return 1;
            if (!b.no) return -1;

            const aParts = a.no.split('.').map(Number);
            const bParts = b.no.split('.').map(Number);

            const maxLength = Math.max(aParts.length, bParts.length);

            for (let i = 0; i < maxLength; i++) {
                const aVal = aParts[i] || 0;
                const bVal = bParts[i] || 0;

                if (aVal != bVal) {
                    return aVal - bVal;
                }
            }

            return 0;
        });

        // Fix parent numbers first (level 1 items)
        fixNumberGroup(sortedArray.filter(item => item.level == 1), 1);

        // Then fix child numbers for each parent group
        const parents = sortedArray.filter(item => item.level === 1);
        parents.forEach(parent => {
            const children = sortedArray.filter(item =>
                item.level > 1 && item.no.startsWith(`${parent.no}.`)
            );
            fixNumberGroup(children, 2, parent.no);
        });

        // Fix deeper levels if they exist
        fixDeeperLevels(sortedArray);

        appState.details = sortedArray;

        // Helper function to fix numbering for a group of items
        function fixNumberGroup(group, level, parentPrefix = '') {
            if (group.length == 0) return;

            // Extract and sort the numbers
            const numbers = group.map(item => {
                const parts = item.no.split('.');
                return parseInt(parts[level - 1]);
            }).sort((a, b) => a - b);

            // Check for gaps and fix them
            let expectedNumber = 1;
            const fixedItems = [];

            numbers.forEach((currentNumber, index) => {
                const item = group.find(it => {
                    const parts = it.no.split('.');
                    return parseInt(parts[level - 1]) == currentNumber;
                });

                if (item && currentNumber != expectedNumber) {
                    const oldNo = item.no;
                    const parts = item.no.split('.');
                    parts[level - 1] = expectedNumber.toString();

                    // Update the prefix for children if this is a parent
                    if (level == 1) {
                        const newPrefix = parts.join('.');
                        updateChildrenPrefix(sortedArray, oldNo, newPrefix);
                    }

                    item.no = parts.join('.');
                }

                fixedItems.push(item);
                expectedNumber++;
            });

            return fixedItems;
        }

        // Helper function to update children's prefix when parent number changes
        function updateChildrenPrefix(array, oldParentNo, newParentNo) {
            array.forEach(item => {
                if (item.no && item.no.startsWith(`${oldParentNo}.`)) {
                    const oldNo = item.no;
                    item.no = item.no.replace(`${oldParentNo}.`, `${newParentNo}.`);
                }
            });
        }

        // Helper function to fix deeper levels recursively
        function fixDeeperLevels(array) {
            const maxLevel = Math.max(...array.map(item => item.level || 1));

            for (let level = 3; level <= maxLevel; level++) {
                const itemsByParent = {};

                // Group items by their parent's number
                array.filter(item => item.level == level).forEach(item => {
                    const parentNo = item.no.split('.').slice(0, -1).join('.');
                    if (!itemsByParent[parentNo]) {
                        itemsByParent[parentNo] = [];
                    }
                    itemsByParent[parentNo].push(item);
                });

                // Fix numbering for each parent group
                Object.keys(itemsByParent).forEach(parentNo => {
                    fixNumberGroup(itemsByParent[parentNo], level, parentNo);
                });
            }
        }
    }

    function setupWorkRowListeners(workRow) {
        const index = workRow.dataset.index;

        // Setup dropzone for before image
        const dropzoneBefore = workRow.querySelector('.work-dropzone-before');
        const fileInputBefore = workRow.querySelector('.work-file-before');

        dropzoneBefore.addEventListener('click', (e) => {
            if (e.target === dropzoneBefore || e.target.tagName === 'P') {
                fileInputBefore.click();
            }
        });
        dropzoneBefore.addEventListener('dragover', handleDragOver);
        dropzoneBefore.addEventListener('dragleave', handleDragLeave);
        dropzoneBefore.addEventListener('drop', (e) => handleWorkImageDrop(e, workRow, 'before'));
        fileInputBefore.addEventListener('change', (e) => handleWorkImageChange(e, workRow, 'before'));

        // Setup dropzone for after image
        const dropzoneAfter = workRow.querySelector('.work-dropzone-after');
        const fileInputAfter = workRow.querySelector('.work-file-after');

        if (!dropzoneAfter || !fileInputAfter) {
            return;
        }

        dropzoneAfter.addEventListener('click', (e) => {
            if (e.target === dropzoneAfter || e.target.tagName === 'P') {
                fileInputAfter.click();
            }
        });
        dropzoneAfter.addEventListener('dragover', handleDragOver);
        dropzoneAfter.addEventListener('dragleave', handleDragLeave);
        dropzoneAfter.addEventListener('drop', (e) => handleWorkImageDrop(e, workRow, 'after'));
        fileInputAfter.addEventListener('change', (e) => handleWorkImageChange(e, workRow, 'after'));
    }

    function handleWorkImageDrop(e, workRow, type) {
        e.preventDefault();
        e.target.classList.remove('active');

        if (e.dataTransfer.files.length) {
            processWorkImage(workRow, type, e.dataTransfer.files[0]);
        }
    }

    function handleWorkImageChange(e, workRow, type) {
        if (e.target.files.length) {
            processWorkImage(workRow, type, e.target.files[0]);
        }
        e.target.value = '';
    }

    function processWorkImage(workRow, type, file) {
        const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB

        if (!file.type.match('image.*')) {
            toastr.error('Hanya file gambar (JPG, PNG, GIF) yang diperbolehkan.');
            return;
        }

        if (file.size > MAX_FILE_SIZE) {
            toastr.error('Ukuran file maksimal 2MB.');
            return;
        }

        const dropzone = workRow.querySelector(`.work-dropzone-${type}`);
        const previewContainer = dropzone.querySelector(`.work-preview-${type}`);
        const dropzoneText = dropzone.querySelector('p');

        previewContainer.innerHTML = '';

        const reader = new FileReader();
        reader.onload = (e) => {
            const previewItem = document.createElement('div');
            previewItem.className = 'preview-item-full split';

            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = `${type} Image`;
            img.onclick = () => zoomImage(e.target.result);

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove-btn remove-work-image';
            removeBtn.dataset.type = type;
            removeBtn.innerHTML = '<i class="fa fa-times"></i>';

            previewItem.appendChild(img);
            previewItem.appendChild(removeBtn);
            previewContainer.appendChild(previewItem);

            // Hide the upload text when image is shown
            dropzoneText.style.display = 'none';

            // Store file reference
            workRow.dataset[`file${capitalizeFirst(type)}`] = file.name;
            workRow[`file${capitalizeFirst(type)}`] = file;
        };
        reader.readAsDataURL(file);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
</script>