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

    .dropzone:hover {
        border-color: #6c757d;
        background-color: #f8f9fa;
    }

    .dropzone.active {
        border-color: #007bff;
        background-color: #e7f1ff;
    }

    .preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 20px;
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

<div class="container-fluid">
    <div class="card rounded-lg shadow border-0">
        <form id="reportForm">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6" style="padding-left: 15px;">
                        <label for="reportEntity">Entity</label>
                        <select class="form-control" id="reportEntity" required <?= $mode === 'detail' || ($mode === 'edit' && isset($report) && ($report['status'] !== 'Pending' || ($report['status'] === 'Pending' && $this->auth_lib->role() === 'kontraktor'))) ? 'disabled' : '' ?>>
                            <option value="">- Pilih Entity -</option>
                            <?php foreach ($list_data['entity'] as $key => $value): ?>
                                <option value="<?= $value['id'] ?>" <?= isset($report) && $report['entity_id'] == $value['id'] ? 'selected' : '' ?>><?= $value['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if ($mode !== 'create'): ?>
                        <div class="col-md-6 text-right">
                            <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $report["status"])) ?>"><?= $report["status"] ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="reportProject">Project</label>
                    <select class="form-control" id="reportProject" required <?= $mode === 'detail' || ($mode === 'edit' && isset($report) && ($report['status'] !== 'Pending' || ($report['status'] === 'Pending' && $this->auth_lib->role() === 'kontraktor'))) ? 'disabled' : '' ?>>
                        <option value="">- Pilih Project -</option>
                        <?php foreach ($list_data['project'] as $key => $value): ?>
                            <option value="<?= $value['id'] ?>" <?= isset($report) && $report['project_id'] == $value['id'] ? 'selected' : '' ?>><?= $value['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="reportCompany">Nama Perusahaan</label>
                    <select class="form-control" id="reportCompany" required <?= $mode === 'detail' || ($mode === 'edit' && isset($report) && ($report['status'] !== 'Pending' || ($report['status'] === 'Pending' && $this->auth_lib->role() === 'kontraktor'))) ? 'disabled' : '' ?>>
                        <option value="">- Pilih Perusahaan -</option>
                        <?php foreach ($list_data['company'] as $key => $value): ?>
                            <option value="<?= $value['id'] ?>" <?= isset($report) && $report['company_id'] == $value['id'] ? 'selected' : '' ?>><?= $value['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="reportWarehouse">Nomor Gudang</label>
                    <select class="form-control" id="reportWarehouse" required <?= $mode === 'detail' || ($mode === 'edit' && isset($report) && ($report['status'] !== 'Pending' || ($report['status'] === 'Pending' && $this->auth_lib->role() === 'kontraktor'))) ? 'disabled' : '' ?>>
                        <option value="">- Pilih Gudang -</option>
                        <?php foreach ($list_data['warehouse'] as $key => $value): ?>
                            <option value="<?= $value['id'] ?>" <?= isset($report) && $report['warehouse_id'] == $value['id'] ? 'selected' : '' ?>><?= $value['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="reportCategory">Kategori Pengaduan</label>
                    <select class="form-control" id="reportCategory" required <?= $mode === 'detail' || ($mode === 'edit' && isset($report) && ($report['status'] !== 'Pending' || ($report['status'] === 'Pending' && $this->auth_lib->role() === 'kontraktor'))) ? 'disabled' : '' ?>>
                        <option value="">- Pilih Kategori -</option>
                        <?php foreach ($list_data['category'] as $key => $value): ?>
                            <option value="<?= $value['id'] ?>" <?= isset($report) && $report['category_id'] == $value['id'] ? 'selected' : '' ?>><?= $value['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="reportDescriptionContainer" class="col-md-12 row" style="display: <?= isset($report) && $report['category_id'] != 2 ? '' : 'none'; ?>;">
                    <div class="form-group col-md-6">
                        <label for="reportTitle">Judul</label>
                        <input type="text" class="form-control" id="reportTitle" value="<?= isset($report) ? $report['title'] : ''; ?>" <?= $mode === 'detail' || ($mode === 'edit' && isset($report) && ($report['status'] !== 'Pending' || ($report['status'] === 'Pending' && $this->auth_lib->role() === 'kontraktor'))) ? 'disabled' : '' ?>>
                    </div>
                    <div class="form-group col-md-7 pr-0">
                        <label for="reportDescription">Deskripsi</label>
                        <textarea class="form-control" id="reportDescription" name="description" style="height: 7rem;" <?= $mode === 'detail' || ($mode === 'edit' && isset($report) && ($report['status'] !== 'Pending' || ($report['status'] === 'Pending' && $this->auth_lib->role() === 'kontraktor'))) ? 'disabled' : '' ?>><?= isset($report) ? $report['description'] : ''; ?></textarea>
                    </div>
                </div>
                <div id="reportDetailContainer" class="col-md-12" style="display: <?= isset($report) && $report['category_id'] == 2 ? '' : 'none'; ?>;">
                    <div style="margin: 2rem 0 3rem;">
                        <div class="d-flex justify-content-between">
                            <button type="button" id="reportDetailAddButton" class="btn btn-default rounded-lg shadow border-0">
                                <i class="fas fa-plus-circle mr-1"></i> Add New Row
                            </button>
                        </div>
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
                                        <th width="8%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-7">
                    <label>Lampiran Bukti</label>
                    <?php if ($mode === 'create' || ($mode === 'edit' && isset($report) && ($report['status'] === 'Pending' && $this->auth_lib->role() === 'pelapor'))): ?>
                        <div class="dropzone" id="reportEvidenceDropzone">
                            <p class="font-weight-bold text-gray"><i class="fa fa-upload mr-1"></i> Drag & drop gambar di sini atau klik untuk memilih</p>
                            <p class="small text-muted">Format yang didukung: JPG, PNG, GIF. Maksimal 2MB per file.</p>
                            <input type="file" id="reportEvidenceFiles" class="file-input" accept="image/*" multiple>
                        </div>
                        <div class="invalid-feedback" id="reportEvidenceError"></div>
                    <?php endif; ?>
                    <div class="preview-container" id="reportEvidencePreview">
                        <?php if (isset($report) && !empty($report['evidences'])): ?>
                            <?php foreach ($report['evidences'] as $evidence):
                                $file_path = base_url('/uploads/' . $evidence['image_name']);
                            ?>
                                <div id="evidenceItem<?= $evidence['id']; ?>" class="preview-item">
                                    <img src="<?= $file_path ?>" alt="Evidence Image" data-src="<?= $file_path ?>" onclick="zoomImage(this.dataset.src)">
                                    <?php if ($mode === 'create' || ($mode === 'edit' && isset($report) && ($report['status'] === 'Pending' && $this->auth_lib->role() === 'pelapor'))): ?>
                                        <button type="button" class="remove-btn" onclick="removeFile('evidence', <?= $evidence['id'] ?>)"><i class="fa fa-times"></i></button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php if ($mode === 'detail'): ?>
                                <div class="text-red font-weight-bold">* Belum ada bukti pengerjaan yang diupload</div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (($mode === 'detail' || $mode === 'edit') && isset($report) && ($report['status'] !== 'Pending' || ($report['status'] === 'Pending' && $this->auth_lib->role() === 'kontraktor'))): ?>
                    <div class="form-group col-md-6">
                        <label for="reportRAB">RAB</label>
                        <select class="form-control" id="reportRAB" required <?= $mode === 'detail' || ($mode === 'edit' && $this->auth_lib->role() !== 'kontraktor') ? 'disabled' : '' ?>>
                            <option value="0" <?= isset($report) && $report['is_rab'] == 0 ? 'selected' : '' ?>>Tanpa RAB</option>
                            <option value="1" <?= isset($report) && $report['is_rab'] == 1 ? 'selected' : '' ?>>Dengan RAB</option>
                        </select>
                    </div>

                    <?php if ($mode === 'edit' || ($mode === 'detail' && isset($report) && (bool)$report['is_rab'] === true)): ?>
                        <div id="reportRABContainer" class="form-group col-md-6">
                            <div class="d-flex align-items-center justify-content-between border rounded-lg py-2 px-2">
                                <?php if ($mode !== 'detail'): ?>
                                    <input type="file" class="form-control" id="reportRABFile" hidden>
                                <?php endif; ?>
                                <div>
                                    <?php if (isset($report) && $report['rab_file']): ?>
                                        <a id="reportRABDownloadFile" href="<?= site_url('file/download/' . $report['rab_file']) ?>" class="btn btn-sm bg-navy rounded-lg">
                                            <i class="fas fa-download mr-1"></i> Download File
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($mode !== 'detail'): ?>
                                        <button id="reportRABSelectFile" type="button" class="btn btn-sm bg-navy rounded-lg" onclick="document.getElementById('reportRABFile').click()" style="display:<?= isset($report) && $report['rab_file'] ? 'none' : '' ?>">
                                            <i class="fas fa-folder-open mr-1"></i> Pilih File
                                        </button>
                                    <?php endif; ?>
                                    <span id="reportRABFileName" class="ml-2 font-weight-bold text-gray text-sm"><?= isset($report) && $report['rab_file'] ? $report['rab_file'] : ($mode === 'detail' ? 'Belum ada file yang diupload' : 'Belum ada file yang dipilih') ?></span>
                                </div>
                                <?php if ($mode !== 'detail' && $this->auth_lib->role() === 'kontraktor'): ?>
                                    <button id="reportRABRemoveFile" type="button" class="btn btn-sm text-danger" style="display:<?= isset($report) && $report['rab_file'] ? '' : 'none' ?>">
                                        <i class="fa fa-times"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (isset($report) && $report['rab_file'] && ($mode === 'detail' || ($mode === 'edit' && $this->auth_lib->role() === 'rab') || ($mode === 'edit' && in_array($report['status'], ['On Process', 'Approved'], true)))): ?>
                            <div class="form-group col-md-6">
                                <label for="reportRABFinalFile">RAB Final</label>
                                <div class="d-flex align-items-center justify-content-between border rounded-lg py-2 px-2">
                                    <?php if ($mode !== 'detail'): ?>
                                        <input type="file" class="form-control" id="reportRABFinalFile" hidden>
                                    <?php endif; ?>
                                    <div>
                                        <?php if (isset($report) && $report['rab_final_file']): ?>
                                            <a id="reportRABFinalDownloadFile" href="<?= site_url('file/download/' . $report['rab_final_file']) ?>" class="btn btn-sm bg-navy rounded-lg">
                                                <i class="fas fa-download mr-1"></i> Download File
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($mode !== 'detail'): ?>
                                            <button id="reportRABFinalSelectFile" type="button" class="btn btn-sm bg-navy rounded-lg" onclick="document.getElementById('reportRABFinalFile').click()" style="display:<?= isset($report) && $report['rab_final_file'] ? 'none' : '' ?>">
                                                <i class="fas fa-folder-open mr-1"></i> Pilih File
                                            </button>
                                        <?php endif; ?>
                                        <span id="reportRABFinalFileName" class="ml-2 font-weight-bold text-gray text-sm"><?= isset($report) && $report['rab_final_file'] ? $report['rab_final_file'] : ($mode === 'detail' ? 'Belum ada file yang diupload' : 'Belum ada file yang dipilih') ?></span>
                                    </div>
                                    <?php if ($mode !== 'detail' && $this->auth_lib->role() === 'rab'): ?>
                                        <button id="reportRABFinalRemoveFile" type="button" class="btn btn-sm text-danger" style="display:<?= isset($report) && $report['rab_final_file'] ? '' : 'none' ?>">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (isset($report) && ($report['status'] === 'Approved' || $report['status'] === 'Completed') && ($mode === 'detail' || ($mode === 'edit' && $this->auth_lib->role() === 'pelapor'))): ?>
                        <div class="form-group col-md-7">
                            <label>Pengerjaan</label>
                            <?php if ($mode !== 'detail'): ?>
                                <div class="dropzone" id="reportWorkDropzone">
                                    <p class="font-weight-bold text-gray"><i class="fa fa-upload mr-1"></i> Drag & drop gambar di sini atau klik untuk memilih</p>
                                    <p class="small text-muted">Format yang didukung: JPG, PNG, GIF. Maksimal 2MB per file.</p>
                                    <input type="file" id="reportWorkFiles" class="file-input" accept="image/*" multiple>
                                </div>
                                <div class="invalid-feedback" id="reportWorkError"></div>
                            <?php endif; ?>
                            <div class="preview-container" id="reportWorkPreview">
                                <?php if (isset($report) && !empty($report['works'])): ?>
                                    <?php foreach ($report['works'] as $work):
                                        $file_path = base_url('/uploads/' . $work['image_name']);
                                    ?>
                                        <div id="workItem<?= $work['id']; ?>" class="preview-item">
                                            <img src="<?= $file_path ?>" alt="Work Image" data-src="<?= $file_path ?>" onclick="zoomImage(this.dataset.src)">
                                            <?php if ($mode !== 'detail'): ?>
                                                <button type="button" class="remove-btn" onclick="removeFile('work', <?= $work['id'] ?>)"><i class="fa fa-times"></i></button>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <?php if ($mode === 'detail'): ?>
                                        <div class="text-red font-weight-bold">* Belum ada bukti pengerjaan yang diupload</div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-white border-top rounded">
                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('report') ?>" class="btn btn-default border-0 shadow-sm rounded-lg">
                        <i class="fas fa-chevron-left mr-2"></i> Batal
                    </a>
                    <div>
                        <?php if ($mode === 'edit'): ?>
                            <?php if (in_array($this->auth_lib->role(), ['kontraktor', 'rab'])): ?>
                                <button onclick="declineReport()" type="button" class="btn rounded-lg border-0 shadow-sm btn-danger ml-2">
                                    <i class="fas fa-times mr-2"></i> Ditolak
                                </button>
                                <button type="submit" class="btn rounded-lg border-0 shadow-sm bg-navy ml-2">
                                    <i class="fas fa-arrow-right mr-2"></i> Ajukan Pengaduan
                                </button>
                            <?php endif; ?>

                            <?php if ($this->auth_lib->role() === 'manager'): ?>
                                <button onclick="declineReport()" type="button" class="btn rounded-lg border-0 shadow-sm btn-danger ml-2">
                                    <i class="fas fa-times mr-2"></i> Ditolak
                                </button>
                                <a href="<?= site_url('report/memo/' . $report['id']) ?>" class="btn rounded-lg border-0 shadow-sm btn-white font-weight-bold ml-2">
                                    <i class="fas fa-print mr-2"></i> Cetak Memo
                                </a>
                                <button type="submit" class="btn rounded-lg border-0 shadow-sm btn-success ml-2">
                                    <i class="fas fa-check mr-2"></i> Setujui Pengaduan
                                </button>
                            <?php endif; ?>

                            <?php if ($this->auth_lib->role() === 'pelapor'): ?>
                                <?php if (isset($report) && $report['status'] === 'Approved'): ?>
                                    <a href="<?= site_url('report/memo/' . $report['id']) ?>" class="btn rounded-lg border-0 shadow-sm btn-white font-weight-bold ml-2">
                                        <i class="fas fa-print mr-2"></i> Cetak Memo
                                    </a>
                                    <button type="submit" class="btn rounded-lg border-0 shadow-sm btn-success ml-2">
                                        <i class="fas fa-tasks mr-2"></i> Selesaikan Pengerjaan
                                    </button>
                                <?php else: ?>
                                    <button type="submit" class="btn rounded-lg border-0 shadow-sm btn-success ml-2">
                                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($mode === 'create'): ?>
                            <button onclick="resetForm()" type="button" class="btn rounded-lg border-0 shadow-sm btn-danger ml-2">
                                <i class="fas fa-trash mr-2"></i> Reset
                            </button>
                            <button type="submit" class="btn rounded-lg border-0 shadow-sm bg-navy ml-2">
                                <i class="fas fa-bullhorn mr-2"></i> Ajukan Pengaduan
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="reportDetailModal" tabindex="-1" aria-labelledby="reportDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportDetailModalLabel">Add New Row</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="reportDetailForm">
                    <input type="hidden" id="reportDetailID">
                    <div class="row">
                        <div class="form-group col-md-11">
                            <label for="reportDetailLevel">Level</label>
                            <select name="level" class="form-control" id="reportDetailLevel" required>
                                <option value="1">Level 1 (Main Category)</option>
                                <option value="2">Level 2 (Sub Item)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" id="reportDetailParentItemContainer">
                        <div class="form-group col-md-11">
                            <label for="reportDetailParent">Parent Item</label>
                            <select class="form-control" id="reportDetailParent">
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-11">
                            <label for="reportDetailDescription">Uraian</label>
                            <input name="description" type="text" class="form-control" id="reportDetailDescription" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-11">
                            <label for="status">Status</label>
                            <div class="radio-group">
                                <label class="status-radio-card" for="reportDetailStatusOK">
                                    <input name="status" class="status-radio-input" type="radio" id="reportDetailStatusOK" value="OK" required checked>
                                    <span class="status-label">OK</span>
                                </label>
                                <label class="status-radio-card" for="reportDetailStatusNotOK">
                                    <input name="status" class="status-radio-input" type="radio" id="reportDetailStatusNotOK" value="Not OK" required>
                                    <span class="status-label">Not OK</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-11">
                            <label for="reportDetailCondition">Kondisi</label>
                            <select name="condition" class="form-control" id="reportDetailCondition" required>
                                <option value="Tidak Butuh Perbaikan">Tidak Butuh Perbaikan</option>
                                <option value="Butuh Perbaikan">Butuh Perbaikan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-11">
                            <label for="reportDetailInformation">Keterangan</label>
                            <textarea name="information" class="form-control" id="reportDetailInformation" style="height: 7rem;"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="reportDetailSaveBtn">Save Row</button>
            </div>
        </div>
    </div>
</div>

<div class="image-modal" id="imageModal">
    <span class="close-modal">&times;</span>
    <img class="modal-content" id="zoomedImage">
</div>

<script>
    const URLS = {
        default: "<?= site_url('report') ?>",
        create: "<?= site_url('report/create') ?>",
        edit: "<?= site_url('report/edit') ?>",
    };

    const domCache = {
        form: {
            name: document.getElementById('reportForm'),
            item: {
                entity: document.getElementById('reportEntity'),
                project: document.getElementById('reportProject'),
                company: document.getElementById('reportCompany'),
                warehouse: document.getElementById('reportWarehouse'),
                category: document.getElementById('reportCategory'),
                title: document.getElementById('reportTitle'),
                description: {
                    input: document.getElementById('reportDescription'),
                    container: document.getElementById('reportDescriptionContainer'),
                },
                detail: {
                    container: document.getElementById('reportDetailContainer'),
                },
                rab: document.getElementById('reportRAB'),
                evidence: {
                    input: document.getElementById('reportEvidenceFiles'),
                    dropzone: document.getElementById('reportEvidenceDropzone'),
                    preview: document.getElementById('reportEvidencePreview'),
                    error: document.getElementById('reportEvidenceError')
                },
                rabFile: {
                    input: document.getElementById('reportRABFile'),
                    container: document.getElementById('reportRABContainer'),
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
        buttons: {}
    };

    const appState = {
        mode: "<?= $mode ?>",
        userRole: "<?= $this->auth_lib->role() ?>",
        reportData: <?= !empty($report) ? json_encode($report) : '{}' ?>,
        details: [],
        evidence: {
            files: [],
            deletedIds: []
        },
        rab: {
            deleted: false
        },
        rabFinal: {
            deleted: false
        },
        work: {
            files: [],
            deletedIds: []
        },
    };

    const statusRadioCards = document.querySelectorAll('.status-radio-card');

    const reportDetailModal = $('#reportDetailModal');

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
                render: function(data, type, row) {
                    return (row.level === 1) ? row.no : '';
                },
                orderable: false,
                targets: 1
            },
            {
                data: "description",
                render: function(data, type, row) {
                    if (row.level === 2) {
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
                    return `<span class="font-weight-bold rounded-lg px-2 py-1 ${data === 'OK' ? 'detail-status-ok' : 'detail-status-not-ok'}">${data}</span>`;
                },
                targets: 3
            },
            {
                data: "condition",
                orderable: false,
                targets: 4
            },
            {
                data: "information",
                orderable: false,
                targets: 5
            },
            {
                data: null,
                orderable: false,
                className: "dt-center",
                render: function(data, type, row) {
                    return `
                        <button type="button" class="btn btn-sm btn-primary edit-row mr-2" data-id="${row.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger remove-row" data-id="${row.id}">
                            <i class="fas fa-trash"></i>
                        </button>`
                },
                targets: 6
            }
        ],
        data: appState.details,
    });

    // Initialize the DataTable
    $(document).ready(function() {
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
            } else {
                $('#reportDetailParentItemContainer').show();
                // Add only level 1 items as parents
                const parentItems = appState.details.filter(item => item.level === 1);
                if (parentItems.length === 0) {
                    parentDropdown.append('<option value="">No parent items available</option>');
                } else {
                    parentItems.forEach(item => {
                        parentDropdown.append(`<option value="${item.id}">${item.description}</option>`);
                    });
                }
            }
        }

        // Update parent dropdown when level changes
        $('#reportDetailLevel').on('change', updateParentDropdown);

        // Add new row button
        $('#reportDetailAddButton').on('click', function() {
            $('#reportDetailForm')[0].reset();
            $('#reportDetailID').val('');
            document.querySelector('.status-radio-input:checked').value = 'OK';
            statusRadioCards.forEach(c => c.classList.remove('selected'));
            statusRadioCards[0].classList.add('selected');
            $('#reportDetailModalLabel').text('Add New Row');
            $('#reportDetailParentItemContainer').hide();
            reportDetailModal.modal('show');
        });

        // Edit row
        $('#reportDetailTable').on('click', '.edit-row', function() {
            const id = parseInt($(this).data('id'));
            const item = appState.details.find(item => item.id === id);

            if (item) {
                $('#reportDetailID').val(item.id);
                $('#reportDetailLevel').val(item.level);
                $('#reportDetailDescription').val(item.description);
                $('#reportDetailStatus').val(item.status);
                $('#reportDetailCondition').val(item.condition);
                $('#reportDetailInformation').val(item.information);

                // Update parent dropdown and set value if level 2
                updateParentDropdown();
                if (item.level === 2) {
                    $('#reportDetailParent').val(item.parent_id);
                }

                $('#reportDetailModalLabel').text('Edit Row');
                reportDetailModal.modal('show');
            }
        });

        // Save row
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

            if (level === 2) {
                const parentId = parseInt($('#reportDetailParent').val());
                if (!parentId) {
                    toastr.error("Please select a parent item for Level 2");
                    return;
                }
            }

            if (id) {
                // Editing existing row
                const index = appState.details.findIndex(item => item.id === parseInt(id));
                if (index !== -1) {
                    appState.details[index].description = description;
                    appState.details[index].level = level;
                    appState.details[index].status = status;
                    appState.details[index].condition = condition;
                    appState.details[index].information = information;

                    if (level === 2) {
                        appState.details[index].parent_id = parseInt($('#reportDetailParent').val());
                        // Update the numbering
                        const parent = appState.details.find(item => item.id === appState.details[index].parent_id);
                        if (parent) {
                            const siblings = appState.details.filter(i => i.parent_id === appState.details[index].parent_id && i.id !== appState.details[index].id);
                            appState.details[index].no = `${parent.no}.${siblings.length + 1}`;
                        }
                    } else {
                        // If changing from level 2 to level 1, remove parentId
                        delete appState.details[index].parent_id;
                        // Renumber level 1 items
                        const level1Items = appState.details.filter(item => item.level === 1);
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
                    information: information
                };

                if (level === 1) {
                    // For level 1, generate the next number
                    const level1Items = appState.details.filter(item => item.level === 1);
                    newItem.no = (level1Items.length + 1).toString();
                } else {
                    // For level 2, get the parent and generate the number
                    const parentId = parseInt($('#reportDetailParent').val());
                    const parent = appState.details.find(item => item.id === parentId);

                    if (!parent) {
                        toastr.error("Please select a valid parent");
                        return;
                    }

                    newItem.parent_id = parentId;
                    const parentChildren = appState.details.filter(item => item.parent_id === parentId);
                    newItem.no = `${parent.no}.${parentChildren.length + 1}`;
                }

                appState.details.push(newItem);
                toastr.success("New row added successfully");
            }

            renumberDetails();

            reloadDetailTable();

            reportDetailModal.modal('hide');
        });

        // Remove row
        $('#reportDetailTable').on('click', '.remove-row', function() {
            const id = parseInt($(this).data('id'));
            const index = appState.details.findIndex(item => item.id === id);

            if (index !== -1) {
                const item = appState.details[index];

                // Check if this is a parent with children
                const hasChildren = appState.details.some(i => i.parent_id === id);

                if (hasChildren) {
                    if (!confirm("This item has child rows. Delete all children as well?")) {
                        return;
                    }
                    // Remove children
                    appState.details = appState.details.filter(i => i.parent_id !== id);
                }

                // Remove the item
                appState.details.splice(index, 1);

                renumberDetails();

                reloadDetailTable();

                toastr.success("Row removed successfully");
            }
        });

        function renumberDetails() {
            // First, ensure all items have a 'no' property
            appState.details.forEach(item => {
                if (!item.no) {
                    console.warn(`Item with id ${item.id} is missing 'no' property`);
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

                    if (aVal !== bVal) {
                        return aVal - bVal;
                    }
                }

                return 0;
            });

            // Fix parent numbers first (level 1 items)
            fixNumberGroup(sortedArray.filter(item => item.level === 1), 1);

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
                if (group.length === 0) return;

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
                        return parseInt(parts[level - 1]) === currentNumber;
                    });

                    if (item && currentNumber !== expectedNumber) {
                        const oldNo = item.no;
                        const parts = item.no.split('.');
                        parts[level - 1] = expectedNumber.toString();

                        // Update the prefix for children if this is a parent
                        if (level === 1) {
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
                    array.filter(item => item.level === level).forEach(item => {
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
    });

    function init() {
        setupEventListeners();

        if (appState.mode === 'edit' && appState.reportData) {
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

            if (domCache.form.item.evidence.dropzone && domCache.form.item.evidence.input) {
                domCache.form.item.evidence.dropzone.addEventListener('click', () => {
                    domCache.form.item.evidence.input.click();
                });
                domCache.form.item.evidence.dropzone.addEventListener('dragover', handleDragOver);
                domCache.form.item.evidence.dropzone.addEventListener('dragleave', handleDragLeave);
                domCache.form.item.evidence.dropzone.addEventListener('drop', (event) => {
                    handleDrop('evidence', event);
                });

                domCache.form.item.evidence.input.addEventListener('change', (event) => {
                    handleFileInputChange('evidence', event);
                });
            }

            if (domCache.form.item.work.dropzone && domCache.form.item.work.input) {
                domCache.form.item.work.dropzone.addEventListener('click', () => {
                    domCache.form.item.work.input.click();
                });
                domCache.form.item.work.dropzone.addEventListener('dragover', handleDragOver);
                domCache.form.item.work.dropzone.addEventListener('dragleave', handleDragLeave);
                domCache.form.item.work.dropzone.addEventListener('drop', (event) => {
                    handleDrop('work', event);
                });
                domCache.form.item.work.input.addEventListener('change', (event) => {
                    handleFileInputChange('work', event);
                });
            }

            if (domCache.form.item.category) {
                if (appState.reportData.category_id) {
                    domCache.form.item.description.container.style.display = (appState.reportData.category_id != 2) ? '' : 'none';
                    domCache.form.item.detail.container.style.display = (appState.reportData.category_id == 2) ? '' : 'none';
                }

                domCache.form.item.category.addEventListener('change', (e) => {
                    domCache.form.item.description.container.style.display = (e.target.value != 2) ? '' : 'none';
                    domCache.form.item.detail.container.style.display = (e.target.value == 2) ? '' : 'none';
                });
            }


            if (domCache.form.item.rab && domCache.form.item.rabFile.container) {
                domCache.form.item.rabFile.container.style.display = (appState.reportData.is_rab === '1') ? '' : 'none';
                domCache.form.item.rab.addEventListener('change', (e) => {
                    domCache.form.item.rabFile.container.style.display = (e.target.value === '1') ? '' : 'none';
                });
            }

            if (domCache.form.item.rabFile.input) {
                domCache.form.item.rabFile.input.addEventListener('change', function() {
                    const file = this.files[0];
                    updateFileUI(domCache.form.item.rabFile.content, domCache.form.item.rabFile.remove, domCache.form.item.rabFile.select, file);
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
                    updateFileUI(domCache.form.item.rabFinalFile.content, domCache.form.item.rabFinalFile.remove, domCache.form.item.rabFinalFile.select, file);
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
        }

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
    }

    function initializeExistingData() {
        if (appState.reportData.evidences && appState.reportData.evidences.length > 0) {
            appState.evidence.files = appState.reportData.evidences.map(evidence => ({
                id: evidence.id,
                fileName: evidence.image_name,
                filePath: evidence.image_path,
                isExisting: true
            }));
        }

        if (appState.reportData.works && appState.reportData.works.length > 0) {
            appState.work.files = appState.reportData.works.map(work => ({
                id: work.id,
                fileName: work.image_name,
                filePath: work.image_path,
                isExisting: true
            }));
        }
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

        // report.entity.evidence.dom.imageError.style.display = report.entity.evidence.data.length > 5 ? 'block' : 'none';
    }

    function handleFormSubmit(e) {
        e.preventDefault();

        if (appState.evidence.files.length === 0) {
            showError('Harap unggah setidaknya satu gambar bukti.');
            return;
        }

        const formData = new FormData();

        formData.append('entity_id', domCache.form.item.entity.value);
        formData.append('project_id', domCache.form.item.project.value);
        formData.append('company_id', domCache.form.item.company.value);
        formData.append('warehouse_id', domCache.form.item.warehouse.value);
        formData.append('category_id', domCache.form.item.category.value);

        if (domCache.form.item.category.value != 2) {
            formData.append('title', domCache.form.item.title.value);
            formData.append('description', domCache.form.item.description.input.value);
        } else {
            if (appState.details.length === 0) {
                toastr.error("Uraian tidak boleh kosong. setidaknya tambahkan satu uraian.");
                return;
            }

            formData.append('detail', JSON.stringify(appState.details));
        }

        appState.evidence.files.forEach((file, index) => {
            if (file instanceof File) {
                formData.append(`evidence_files[${index}]`, file);
            }
        });

        formData.append('deleted_evidence_files', JSON.stringify(appState.evidence.deletedIds));

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

            formData.append('is_rab', domCache.form.item.rab.value);

            if (domCache.form.item.rabFile.input && domCache.form.item.rabFile.input.files[0]) {
                formData.append('rab_file', domCache.form.item.rabFile.input.files[0]);
            }

            formData.append('delete_rab_file', appState.rab.deleted);

            if (domCache.form.item.rabFinalFile.input && domCache.form.item.rabFinalFile.input.files[0]) {
                formData.append('rab_final_file', domCache.form.item.rabFinalFile.input.files[0]);
            }

            formData.append('delete_rab_final_file', appState.rabFinal.deleted);

            appState.work.files.forEach((file, index) => {
                if (file instanceof File) {
                    formData.append(`work_files[${index}]`, file);
                }
            });

            formData.append('deleted_work_files', JSON.stringify(appState.work.deletedIds));
        }

        submitFormData(formData);
    }

    function submitFormData(formData) {
        const url = appState.mode === 'create' ? URLS.create : `${URLS.edit}/${appState.reportData.id}`;

        fetch(url, {
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
                console.error('Error:', error);
                toastr.error("Failed to " + appState.mode + " report.");
            });
    }

    function resetForm() {
        domCache.form.name.reset();
        appState.evidence.files = [];
        appState.evidence.deletedIds = [];
        domCache.form.item.evidence.preview.innerHTML = '';
        domCache.form.item.evidence.error.style.display = 'none';
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

    function declineReport() {
        const formData = new FormData();
        formData.append('status', 'Rejected');

        fetch(`${URLS.edit}/${appState.reportData.id}`, {
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
                console.error('Error:', error);
                toastr.error("Failed to decline report.");
            });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
</script>