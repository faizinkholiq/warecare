<div class="container-fluid">
    <div class="card rounded-lg shadow border-0">
        <form id="userForm">
            <div class="card-body">
                <div class="form-group">
                    <label for="userUsername">Username</label>
                    <input type="text" class="form-control col-md-6" id="userUserName" value="<?= isset($user) ? $user['username'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="userPassword">Password</label>
                    <input type="password" class="form-control col-md-6" id="userPassword" value="" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3 mr-1">
                        <label for="userFirstName">Nama Depan</label>
                        <input type="text" class="form-control" id="userFirstName" value="<?= isset($user) ? $user['first_name'] : ''; ?>" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="userLastName">Nama Belakang</label>
                        <input type="text" class="form-control" id="userLastName" value="<?= isset($user) ? $user['last_name'] : ''; ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="userRole">Role</label>
                    <select class="form-control col-md-6" id="userRole" required>
                        <option value="">- Pilih Role -</option>
                        <option <?= isset($user) && $user['role'] === 'administrator'? 'selected' : '' ?> value="administrator">Administrator</option>
                        <option <?= isset($user) && $user['role'] === 'manager'? 'selected' : '' ?> value="manager">Manager</option>
                        <option <?= isset($user) && $user['role'] === 'rab'? 'selected' : '' ?> value="rab">RAB</option>
                        <option <?= isset($user) && $user['role'] === 'kontraktor'? 'selected' : '' ?> value="kontraktor">Kontraktor</option>
                        <option <?= isset($user) && $user['role'] === 'pelapor'? 'selected' : '' ?> value="pelapor">Pelapor</option>
                    </select>
                </div>
            </div>
            <div class="card-footer bg-white border-top rounded">
                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('user') ?>" class="btn btn-default border-0 shadow-sm rounded-lg">
                        <i class="fas fa-chevron-left mr-2"></i> Cancel
                    </a>
                    <div>
                        <button onclick="resetForm()" type="button" class="btn rounded-lg border-0 shadow-sm btn-danger ml-2">
                            <i class="fas fa-trash mr-2"></i> Clear
                        </button>
                        <button type="submit" class="btn btn-success rounded-lg border-0 shadow-sm ml-2">
                            <i class="fas fa-save mr-2"></i> Simpan User 
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
        default: "<?= site_url('user') ?>",
        create: "<?= site_url('user/create') ?>",
        edit: "<?= site_url('user/edit') ?>",
    };

    let user = <?= !empty($user)? json_encode($user) : 'null' ?>;

    $(document).ready(function() {
        if (mode === 'edit' && user) {
            $('#userPassword').removeAttr('required');
        }

        // Form submission
        $('#userForm').submit(function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('username', $('#userUserName').val());
            formData.append('password', $('#userPassword').val());
            formData.append('first_name', $('#userFirstName').val());
            formData.append('last_name', $('#userLastName').val());
            formData.append('role', $('#userRole').val());
            
            // Submit form
            $.ajax({
                url: mode == 'create'? urls.create : urls.edit + '/' + user.id,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    window.location.href = urls.default;
                },
                error: function() {
                    toastr.error("Failed to "+ mode +" user.");
                }
            });
        });
    });

    
    function resetForm() {
        $('#userForm')[0].reset();
    }
</script>