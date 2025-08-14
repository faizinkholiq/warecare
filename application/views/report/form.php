 <style>

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

<script>
    const mode = "<?= $mode ?>";
    const urls = {
        default: "<?= site_url('report/report') ?>",
        create: "<?= site_url('report/create') ?>",
        edit: "<?= site_url('report/edit') ?>",
    };

    let report = <?= !empty($report)? json_encode($report) : 'null' ?>;

    $(document).ready(function() {
        // Form submission
        $('#reportForm').submit(function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('name', $('#reportName').val());
            formData.append('sku', $('#reportSku').val());
            formData.append('price', $('#reportPrice').val());
            formData.append('compare_price', $('#reportComparePrice').val());
            formData.append('stock', $('#reportStock').val());
            formData.append('description', $('#reportDescription').val());
            formData.append('category_id', $('#reportCategory').val());
            formData.append('status', $('#reportStatus').val());
            
            // Process variants
            const variants = [];
            $('.variant-row').each(function() {
                const variant = {
                    option1_name: $(this).find('input[placeholder="Size"]').val(),
                    option1_value: $(this).find('input[placeholder="Small"]').val(),
                    option2_name: $(this).find('input[placeholder="Color"]').val(),
                    option2_value: $(this).find('input[placeholder="Red"]').val(),
                    price_adjustment: $(this).find('input[placeholder="0.00"]').val(),
                    stock: $(this).find('input[placeholder="Quantity"]').val(),
                    sku: $(this).find('input[placeholder="SKU"]').val(),
                    barcode: $(this).find('input[placeholder="Barcode"]').val()
                };
            });
            
            // Submit form
            $.ajax({
                url: mode == 'create'? urls.create : urls.edit + '/' + report.id,
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
    }
</script>