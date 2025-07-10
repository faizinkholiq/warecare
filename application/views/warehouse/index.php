<div class="d-flex flex-column" style="height: 100%;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-4 d-flex" style="gap: 0.5rem;">
                <button type="button" class="btn btn-success" style="flex: 1;" onclick="createAction('IDR')">Create</button>
                <button type="button" class="btn btn-warning" style="flex: 1;" onclick="editAction()">Edit</button>
                <button type="button" class="btn btn-danger" style="flex: 1;" onclick="deleteAction()">Delete</button>
            </div>
        </div>
    </div>
    <div id="warehouse_table_container" class="container-fluid mt-4" style="flex: 1;">
        <table id="warehouse_table" class="table table-bordered table-striped" style="table-layout: fixed;">
            <thead>
                <tr>
                    <th width="30px">No.</th>
                    <th width="30px">#ID</th>
                    <th width="80px">Date</th>
                    <th width="50px">Currency</th>
                    <th width="80px">Code</th>
                    <th width="100px">Category</th>
                    <th width="100px">Invoice To</th>
                    <th width="100px">Address</th>
                    <th width="100px">Phone</th>
                    <th width="100px">Email</th>
                    <th width="100px">Description</th>
                    <th width="100px">Quantity</th>
                    <th width="100px">Price</th>
                    <th width="100px">Sub Total</th>
                    <th width="100px">Tax</th>
                    <th width="100px">Other</th>
                    <th width="100px">Deduction</th>
                    <th width="100px">Grand Total</th>
                    <th width="100px">Paid By</th>
                    <th width="100px">Status</th>
                    <th width="100px">Received</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div class="modal fade" id="warehouseModal" tabindex="-1" aria-labelledby="warehouseModalHeader" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="warehouseModalHeader">New Warehouse</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="warehouse_form">
                    <input id="warehouse_id" type="hidden" name="id">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="warehouse_date">Date</label>
                            <div class="input-group date-input" id="warehouse_date" name="date" data-target-input="nearest">
                                <input type="text" name="date" class="form-control datetimepicker-input" data-target="#warehouse_date" />
                                <div class="input-group-append" data-target="#warehouse_date" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="warehouse_currency">Currency</label>
                            <input type="text" class="form-control" id="warehouse_currency" name="currency" readonly />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="warehouse_code">Code</label>
                            <input type="text" class="form-control" id="warehouse_code" name="code" placeholder="Code" />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="warehouse_category">Category</label>
                            <select class="form-control select2 select2-danger" id="warehouse_category" name="category" data-placeholder="Category" placeholder="Category" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                <?php foreach($list['category'] as $key => $value): ?>    
                                <option value="<?=$value['id']  ?>"><?= $value['name']  ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="warehouse_to">Invoice To</label>
                        <input type="text" class="form-control" id="warehouse_to" name="warehouse_to" placeholder="Invoice To" />
                    </div>
                    <div class="form-group">
                        <label for="warehouse_address">Address</label>
                        <input type="text" class="form-control" id="warehouse_address" name="address" placeholder="Address" />
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="warehouse_phone">Phone</label>
                            <input type="number" class="form-control" id="warehouse_phone" name="phone" placeholder="Phone" />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="warehouse_email">Email</label>
                            <input type="email" class="form-control" id="warehouse_email" name="email" placeholder="Email" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="warehouse_description">Description</label>
                        <textarea id="warehouse_description" name="description" class="form-control" rows="3" placeholder="Description"></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="warehouse_qty">Quantity</label>
                            <input type="number" class="form-control" id="warehouse_qty" name="qty" placeholder="Quantity" onchange="calculateSubTotal()" />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="warehouse_price">Price</label>
                            <input type="text" class="form-control" id="warehouse_price" name="price" placeholder="Price" onchange="calculateSubTotal()" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="warehouse_sub_total">Sub Total</label>
                            <input type="text" class="form-control" id="warehouse_sub_total" name="sub_total" placeholder="Total" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="warehouse_tax">Tax</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="warehouse_tax" name="tax" min="0" max="100" placeholder="Tax" onchange="calculateTotal()" />
                                <div class="input-group-append">
                                    <div class="input-group-text"><i class="fa fa-percent"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="warehouse_other">Other</label>
                            <input type="text" class="form-control" id="warehouse_other" name="other" placeholder="Other" onchange="calculateTotal()" />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="warehouse_deduction">Deduction</label>
                            <input type="text" class="form-control" id="warehouse_deduction" name="deduction" placeholder="Deduction" onchange="calculateTotal()" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="warehouse_total">Grand Total</label>
                            <input type="text" class="form-control" id="warehouse_total" name="total" placeholder="Grand Total" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="warehouse_paid_by">Paid By</label>
                            <select class="form-control select2 select2-danger" id="warehouse_paid_by" name="paid_by" data-placeholder="Paid By" placeholder="Paid By" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                <option value="cash">Cash</option>
                                <option value="cheque">Cheque</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="warehouse_status">Status</label>
                            <select class="form-control select2 select2-danger" id="warehouse_status" name="status" data-placeholder="Status" placeholder="Status" data-dropdown-css-class="select2-danger" style="width: 100%;">
                                <?php foreach($list['status'] as $key => $value): ?>    
                                <option value="<?=$value['id']  ?>"><?= $value['name']  ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="warehouse_received">Received</label>
                            <input type="text" class="form-control" id="warehouse_received" name="received" placeholder="Received" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="submitAction()">Submit</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    const warehouseTable = $("#warehouse_table");
    let warehouseTableHeight = 420;

    const warehouseModal = $("#warehouseModal");
    const warehouseModalHeader = $("#warehouseModalHeader");
    const warehouseForm = {
        group: $("#warehouse_form"),
        controls: {
            id: $("#warehouse_id"),
            company: $("#warehouse_company"),
            name: $("#warehouse_name"),
        }
    };
    const summary = {
        total_transaction: $('#summary-total-transaction'),
    }

    const urls = {
        site: "<?= site_url("/warehouse") ?>",
        datatables: "<?= site_url("/warehouse/datatables") ?>",
        create: "<?= site_url("/warehouse/create") ?>",
        detail: "<?= site_url("/warehouse/detail") ?>",
        edit: "<?= site_url("/warehouse/edit") ?>",
        delete: "<?= site_url("/warehouse/delete") ?>",
    };

    $(function() {
        setTimeout(() => {
            warehouseTableHeight = $("#warehouse_table_container").height() - 105;
            initDatatables();
        }, 300);

        initDatatablesFilter();
        initFormValidation();
    });
</script>