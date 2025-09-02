<style>
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

    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        max-height: 80vh;
        object-fit: contain;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .close-modal {
        position: absolute;
        top: 10px;
        right: 35px;
        color: #f1f1f1;
        font-size: 50px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
    }

    .close-modal:hover {
        color: #bbb;
    }
</style>

<div class="container-fluid">
    <div class="card rounded-lg shadow border-0">
        <form id="reportForm">
            <div class="card-body">
                <div class="form-group col-md-6">
                    <label for="reportEntity">Entity</label>
                    <select class="form-control" id="reportEntity" required <?= $mode === 'detail' ? 'disabled' : '' ?>>
                        <option value="">- Pilih Entity -</option>
                        <?php foreach ($list_data['entity'] as $key => $value): ?>
                            <option value="<?= $value['id'] ?>" <?= isset($report) && $report['entity_id'] == $value['id'] ? 'selected' : '' ?>><?= $value['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="reportProject">Project</label>
                    <select class="form-control" id="reportProject" required <?= $mode === 'detail' ? 'disabled' : '' ?>>
                        <option value="">- Pilih Project -</option>
                        <?php foreach ($list_data['project'] as $key => $value): ?>
                            <option value="<?= $value['id'] ?>" <?= isset($report) && $report['project_id'] == $value['id'] ? 'selected' : '' ?>><?= $value['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="reportCompany">Nama Perusahaan</label>
                    <select class="form-control" id="reportCompany" required <?= $mode === 'detail' ? 'disabled' : '' ?>>
                        <option value="">- Pilih Perusahaan -</option>
                        <?php foreach ($list_data['company'] as $key => $value): ?>
                            <option value="<?= $value['id'] ?>" <?= isset($report) && $report['company_id'] == $value['id'] ? 'selected' : '' ?>><?= $value['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="reportWarehouse">Nomor Gudang</label>
                    <select class="form-control" id="reportWarehouse" required <?= $mode === 'detail' ? 'disabled' : '' ?>>
                        <option value="">- Pilih Gudang -</option>
                        <?php foreach ($list_data['warehouse'] as $key => $value): ?>
                            <option value="<?= $value['id'] ?>" <?= isset($report) && $report['warehouse_id'] == $value['id'] ? 'selected' : '' ?>><?= $value['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="reportCategory">Kategori Pengaduan</label>
                    <select class="form-control" id="reportCategory" required <?= $mode === 'detail' ? 'disabled' : '' ?>>
                        <option value="">- Pilih Kategori -</option>
                        <?php foreach ($list_data['category'] as $key => $value): ?>
                            <option value="<?= $value['id'] ?>" <?= isset($report) && $report['category_id'] == $value['id'] ? 'selected' : '' ?>><?= $value['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="reportTitle">Judul</label>
                    <input type="text" class="form-control" id="reportTitle" value="<?= isset($report) ? $report['title'] : ''; ?>" required <?= $mode === 'detail' ? 'disabled' : '' ?>>
                </div>
                <div class="form-group col-md-7">
                    <label for="reportDescription">Deskripsi</label>
                    <textarea class="form-control" id="reportDescription" name="description" style="height: 7rem;" <?= $mode === 'detail' ? 'disabled' : '' ?>><?= isset($report) ? $report['description'] : ''; ?></textarea>
                </div>
                <div class="form-group col-md-7">
                    <label>Lampiran Bukti</label>
                    <?php if ($mode !== 'detail'): ?>
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
                                    <?php if ($mode !== 'detail'): ?>
                                        <button type="button" class="remove-btn" onclick="removeFile('evidence', <?= $evidence['id'] ?>)"><i class="fa fa-times"></i></button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($mode !== 'create'): ?>
                    <div class="form-group col-md-6">
                        <label for="reportRAB">RAB</label>
                        <select class="form-control" id="reportRAB" required <?= $mode === 'detail' ? 'disabled' : '' ?>>
                            <option value="0" <?= isset($report) && $report['is_rab'] == 0 ? 'selected' : '' ?>>Tanpa RAB</option>
                            <option value="1" <?= isset($report) && $report['is_rab'] == 1 ? 'selected' : '' ?>>Dengan RAB</option>
                        </select>
                    </div>
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
                            <?php if ($mode !== 'detail'): ?>
                                <button id="reportRABRemoveFile" type="button" class="btn btn-sm text-danger" style="display:<?= isset($report) && $report['rab_file'] ? '' : 'none' ?>">
                                    <i class="fa fa-times"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
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
                            <button id="reportRABFinalRemoveFile" type="button" class="btn btn-sm text-danger" style="display:<?= isset($report) && $report['rab_final_file'] ? '' : 'none' ?>">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
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
                            <?php endif; ?>
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
                            <button onclick="resetForm()" type="button" class="btn rounded-lg border-0 shadow-sm btn-danger ml-2">
                                <i class="fas fa-times mr-2"></i> Ditolak
                            </button>
                            <button type="button" class="btn rounded-lg border-0 shadow-sm btn-white font-weight-bold ml-2">
                                <i class="fas fa-print mr-2"></i> Cetak Memo
                            </button>
                            <button type="submit" class="btn rounded-lg border-0 shadow-sm btn-success ml-2">
                                <i class="fas fa-check mr-2"></i> Setujui Pengaduan
                            </button>
                            <button type="submit" class="btn rounded-lg border-0 shadow-sm btn-success ml-2">
                                <i class="far fa-check-circle mr-2"></i> Selesai Pengerjaan
                            </button>
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
                description: document.getElementById('reportDescription'),
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
        reportData: <?= !empty($report) ? json_encode($report) : '{}' ?>,
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

            domCache.form.name.addEventListener('submit', handleFormSubmit);

            if (appState.mode === 'edit') {
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

                if (domCache.form.item.rab && domCache.form.item.rabContainer) {
                    domCache.form.item.rabContainer.style.display = (appState.reportData.is_rab === '1') ? '' : 'none';
                    domCache.form.item.rab.addEventListener('change', (e) => {
                        domCache.form.item.rabContainer.style.display = (e.target.value === '1') ? '' : 'none';
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
        }
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
        formData.append('title', domCache.form.item.title.value);
        formData.append('description', domCache.form.item.description.value);

        appState.evidence.files.forEach((file, index) => {
            if (file instanceof File) {
                formData.append(`evidence_files[${index}]`, file);
            }
        });

        formData.append('deleted_evidence_files', JSON.stringify(appState.evidence.deletedIds));

        if (appState.mode === 'edit') {
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
                    // window.location.href = URLS.default;
                } else {
                    throw new Error('Operation failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error("Failed to " + mode + " report.");
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

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
</script>