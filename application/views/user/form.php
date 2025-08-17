<div class="container-fluid">
    <div class="card rounded-lg shadow border-0">
        <form id="userForm">
            <div class="card-body">
                <div class="form-group">
                    <label for="userUsername">Username</label>
                    <input type="text" class="form-control col-md-6 <?= form_error('username') ? 'is-invalid' : '' ?>" id="userUserName" name="username" value="<?= isset($user) ? $user['username'] : set_value('username'); ?>" required>
                    <div class="invalid-feedback"><?= form_error('username') ?></div>
                </div>
               <div class="form-group">
                    <label for="userPassword">Password</label>
                    <input type="password" class="form-control col-md-6" id="userPassword" value="" required>
                    <div class="invalid-feedback" id="userPasswordError"></div>
                </div>
                <div class="form-group">
                    <label for="userConfirmPassword">Konfirmasi Password</label>
                    <input type="password" class="form-control col-md-6" id="userConfirmPassword" value="" required>
                    <div class="invalid-feedback" id="userConfirmPasswordError">Password tidak sama</div>
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
                        <?php if ($mode === 'create'): ?>
                        <button onclick="resetForm()" type="button" class="btn rounded-lg border-0 shadow-sm btn-danger ml-2">
                            <i class="fas fa-trash mr-2"></i> Clear
                        </button>
                        <?php endif; ?>
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
            $('#userConfirmPassword').removeAttr('required');
        }

        // Password validation
        function validatePassword() {
            const password = $('#userPassword').val();
            const userConfirmPassword = $('#userConfirmPassword').val();
            let isValid = true;

            // Clear previous errors
            $('#userPassword').removeClass('is-invalid');
            $('#userConfirmPassword').removeClass('is-invalid');

            // Only validate if in create mode or password field is not empty
            if (mode === 'create' || password.length > 0) {
                // Check password strength
                const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
                if (!strongRegex.test(password)) {
                    $('#userPassword').addClass('is-invalid');
                    $('#userPasswordError').text('Password setidaknya harus berisi 8 karakter, mengandung huruf besar, huruf kecil, & angka');
                    isValid = false;
                }

                // Check password match
                if (password !== userConfirmPassword) {
                    $('#userConfirmPassword').addClass('is-invalid');
                    isValid = false;
                }
            }

            return isValid;
        }

        // Form submission
        $('#userForm').submit(function(e) {
            e.preventDefault();

            if (!validatePassword()) {
                return false;
            }
            
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
                dataType: 'json',
                success: function(response) {
                    window.location.href = urls.default;
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            const input = $(`[name="${field}"]`);
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').html(errors[field]);
                        }
                    } else {
                        toastr.error("Failed to "+ mode +" user.");
                    }
                }
            });
        });

        $('#userPassword, #userConfirmPassword').on('keyup', function() {
            validatePassword();
        });
    });

    
    function resetForm() {
        $('#userForm')[0].reset();
        $('#userPassword, #userConfirmPassword').removeClass('is-invalid');
    }
</script>