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
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
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
        background-color: rgba(0,0,0,0.8);
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
                    <select class="form-control" id="reportEntity" required>
                        <option value="">- Pilih Entity -</option>
                        <?php foreach ($list_data['entity'] as $key => $value): ?>
                            <option value="<?= $value['id'] ?>" <?= isset($report) && $report['entity_id'] == $value['id'] ? 'selected' : '' ?>><?= $value['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="reportProject">Project</label>
                    <select class="form-control" id="reportProject" required>
                        <option value="">- Pilih Project -</option>
                        <?php foreach ($list_data['project'] as $key => $value): ?>
                            <option value="<?= $value['id'] ?>" <?= isset($report) && $report['project_id'] == $value['id'] ? 'selected' : '' ?>><?= $value['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="reportCompany">Nama Perusahaan</label>
                    <select class="form-control" id="reportCompany" required>
                        <option value="">- Pilih Perusahaan -</option>
                        <?php foreach ($list_data['company'] as $key => $value): ?>
                            <option value="<?= $value['id'] ?>" <?= isset($report) && $report['company_id'] == $value['id'] ? 'selected' : '' ?>><?= $value['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="reportWarehouse">Nomor Gudang</label>
                    <select class="form-control" id="reportWarehouse" required>
                        <option value="">- Pilih Gudang -</option>
                        <?php foreach ($list_data['warehouse'] as $key => $value): ?>
                            <option value="<?= $value['id'] ?>" <?= isset($report) && $report['warehouse_id'] == $value['id'] ? 'selected' : '' ?>><?= $value['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="reportCategory">Kategori Pengaduan</label>
                    <select class="form-control" id="reportCategory" required>
                        <option value="">- Pilih Kategori -</option>
                        <?php foreach ($list_data['category'] as $key => $value): ?>
                            <option value="<?= $value['id'] ?>" <?= isset($report) && $report['category_id'] == $value['id'] ? 'selected' : '' ?>><?= $value['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="reportTitle">Judul</label>
                    <input type="text" class="form-control" id="reportTitle" value="<?= isset($report) ? $report['title'] : ''; ?>" required>
                </div>
                <div class="form-group col-md-7">
                    <label for="reportDescription">Deskripsi</label>
                    <textarea class="form-control" id="reportDescription" name="description" style="height: 7rem;"></textarea>
                </div>
                <div class="form-group col-md-7">
                    <label for="reportImages">Lampiran Gambar</label>
                    <div class="dropzone" id="imageDropzone">
                        <p class="font-weight-bold text-gray"><i class="fa fa-upload mr-1"></i> Drag & drop gambar di sini atau klik untuk memilih</p>
                        <p class="small text-muted">Format yang didukung: JPG, PNG, GIF. Maksimal 10MB per file.</p>
                        <input type="file" id="fileInput" class="file-input" accept="image/*" multiple>
                    </div>
                    <div class="invalid-feedback" id="imageError">
                        Hanya file gambar (JPG, PNG, GIF) yang diperbolehkan dengan ukuran maksimal 10MB.
                    </div>
                    <div class="preview-container" id="imagePreview"></div>
                </div>
            </div>
            <div class="card-footer bg-white border-top rounded">
                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('report') ?>" class="btn btn-default border-0 shadow-sm rounded-lg">
                        <i class="fas fa-chevron-left mr-2"></i> Cancel
                    </a>
                    <div>
                        <button onclick="resetForm()" type="button" class="btn rounded-lg border-0 shadow-sm btn-danger ml-2">
                            <i class="fas fa-trash mr-2"></i> Clear
                        </button>
                        <button type="submit" class="btn rounded-lg border-0 shadow-sm bg-navy ml-2">
                            <i class="fas fa-bullhorn mr-2"></i> Ajukan Pengaduan 
                        </button>
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
    const urls = {
        default: "<?= site_url('report/report') ?>",
        create: "<?= site_url('report/create') ?>",
        edit: "<?= site_url('report/edit') ?>",
    };

    let report = <?= !empty($report)? json_encode($report) : 'null' ?>;
    let uploadedFiles = [];

    $(document).ready(function() {
        // Initialize dropzone
        const dropzone = document.getElementById('imageDropzone');
        const fileInput = document.getElementById('fileInput');
        const previewContainer = document.getElementById('imagePreview');
        const imageError = document.getElementById('imageError');

        // Handle click on dropzone
        dropzone.addEventListener('click', () => {
            fileInput.click();
        });

        // Handle file selection
        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
            fileInput.value = ''; // Reset input to allow selecting same files again
        });

        // Handle drag over
        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('active');
        });

        // Handle drag leave
        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('active');
        });

        // Handle drop
        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('active');
            
            if (e.dataTransfer.files.length) {
                handleFiles(e.dataTransfer.files);
            }
        });

        // Function to handle uploaded files
        function handleFiles(files) {
            imageError.style.display = 'none';
            let hasError = false;
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                
                // Validate file type
                if (!file.type.match('image.*')) {
                    hasError = true;
                    continue;
                }
                
                // Validate file size (10MB max)
                if (file.size > 10 * 1024 * 1024) {
                    hasError = true;
                    continue;
                }
                
                // Add to uploaded files array
                uploadedFiles.push(file);
                
                // Create preview
                const reader = new FileReader();
                reader.onload = (e) => {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.dataset.src = e.target.result;

                    const removeBtn = document.createElement('button');
                    removeBtn.className = 'remove-btn';
                    removeBtn.innerHTML = '<i class="fa fa-times"></i>';
                    removeBtn.onclick = (e) => {
                        e.stopPropagation();
                        removeFile(file, previewItem);
                    };

                    img.onclick = (e) => {
                        e.stopPropagation();
                        zoomImage(e.target.dataset.src);
                    };
                    
                    previewItem.appendChild(img);
                    previewItem.appendChild(removeBtn);
                    previewContainer.appendChild(previewItem);
                };
                reader.readAsDataURL(file);
            }
            
            if (hasError) {
                imageError.style.display = 'block';
            }
        }

        // Function to zoom image
        function zoomImage(src) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('zoomedImage');
            modal.style.display = "block";
            modalImg.src = src;
            
            // Close when clicking X
            document.querySelector('.close-modal').onclick = function() {
                modal.style.display = "none";
            }
            
            // Close when clicking outside image
            modal.onclick = function(e) {
                if (e.target === modal) {
                    modal.style.display = "none";
                }
            }
            
            // Close with ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === "Escape") {
                    modal.style.display = "none";
                }
            });
        }
        
        // Function to remove a file
        function removeFile(file, previewElement) {
            uploadedFiles = uploadedFiles.filter(f => f !== file);
            previewContainer.removeChild(previewElement);
        }

        // Form submission
        $('#reportForm').submit(function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('entity_id', $('#reportEntity').val());
            formData.append('project_id', $('#reportProject').val());
            formData.append('company_id', $('#reportCompany').val());
            formData.append('warehouse_id', $('#reportWarehouse').val());
            formData.append('category_id', $('#reportCategory').val());
            formData.append('title', $('#reportTitle').val());
            formData.append('description', $('#reportDescription').val());
            
            // Append all uploaded files
            uploadedFiles.forEach((file, index) => {
                formData.append(`images[${index}]`, file);
            });
            
            // Submit form
            $.ajax({
                url: urls.create,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    window.location.href = urls.default;
                },
                error: function() {
                    toastr.error("Failed to "+ mode +" report.");
                }
            });
        });
    });

    function resetForm() {
        $('#reportForm')[0].reset();
        uploadedFiles = [];
        document.getElementById('imagePreview').innerHTML = '';
        document.getElementById('imageError').style.display = 'none';
    }
</script>