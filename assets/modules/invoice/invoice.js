function initDatatables() {
    invoiceTable.DataTable({
        serverSide: true,
        ajax: {
            url: urls.datatables,
            type: 'POST'
        },
        initComplete: function (settings, json) {
            summary.total_transaction.text(json.recordsTotal);
        },
        "rowId": 'id',
        "columnDefs": [
            {
                "data": null,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                "searchable": false,
                "orderable": false,
                "targets": 0
            },
            { "data": "id", "targets": 1, "visible": false },
            { "data": "date", "targets": 2 },
            { "data": "currency", "targets": 3 },
            { "data": "code", "targets": 4 },
            { "data": "category", "targets": 5 },
            { "data": "invoice_to", "targets": 6 },
            { "data": "address", "targets": 7 },
            { "data": "phone", "targets": 8 },
            { "data": "email", "targets": 9 },
            { "data": "description", "targets": 10 },
            { "data": "qty", "targets": 11 },
            { "data": "price", "targets": 12, render: priceRenderer },
            { "data": "sub_total", "targets": 13, render: priceRenderer },
            {
                "data": "tax",
                "targets": 14,
                render: function (data, type, row, meta) {
                    return data ? (parseInt(data) + "%") : null;
                }
            },
            { "data": "other", "targets": 15, render: priceRenderer },
            { "data": "deduction", "targets": 16, render: priceRenderer },
            { "data": "total", "targets": 17, render: priceRenderer },
            { "data": "paid_by", "targets": 18 },
            { "data": "status", "targets": 19 },
            { "data": "received", "targets": 20 },
        ],
        "drawCallback": dataTableFitPageLength,
        "scrollResize": true,
        "scrollY": invoiceTableHeight,
        "scrollX": "100%",
        "scrollCollapse": true,
        "paging": true,
        "responsive": false,
        "lengthChange": false,
        "autoWidth": false,
        "searching": true,
        "select": true,
        "dom": 'lrt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
    });
}

function priceRenderer(data, type, row, meta) {
    if (data) {
        switch (row.currency) {
            case "IDR":
                return 'Rp' + parseInt(data).toLocaleString('id-ID');
            case "USD":
            default:
                return '$' + parseInt(data).toLocaleString('en-US');
        }
    } else {
        return null;
    }

}

function initDatatablesFilter() {
    let startingYear = 2001;
    let endYear = new Date().getFullYear() + 1;
    let yearList = [];

    for (let year = startingYear; year <= endYear; year++) {
        yearList.push({ id: year, text: year });
    }

    $('#year_filter').select2({
        theme: 'bootstrap4',
        data: yearList
    });

    $("#year_filter").val(null).trigger("change");
    $("#month_filter").val(null).trigger("change");
    $("#day_filter").val(null).trigger("change");
}

function onYearFilterChange() {
    loadDayFilterList();
}

function onMonthFilterChange() {
    loadDayFilterList();
}

function loadDayFilterList() {
    const year = $("#year_filter").val();
    const month = $("#month_filter").val() - 1;

    if (year && month) {
        const lastDay = new Date(year, month + 1, -1).getDate();
        const dayList = [];

        for (let index = 1; index <= lastDay; index++) {
            let label = index;
            if (index < 10) label = "0" + label;

            dayList.push({
                id: index,
                text: label
            });

            $('#day_filter').select2({
                theme: 'bootstrap4',
                data: dayList
            });
            $("#day_filter").val(null).trigger("change");
        }
    }
}

function onDayFilterChange() {

}

function searchAction() {
    const value = $('#invoice_search').val();
    invoiceTable.DataTable().search(value, false, true).draw();
}

function initFormValidation() {
    invoiceForm.group.validate({
        rules: {
            date: { required: true },
            currency: { required: true },
            code: { required: true },
            category: { required: true },
            invoice_to: { required: true },
            qty: { required: true },
            price: { required: true },
            paid_by: { required: true },
            status: { required: true },
        },
        messages: {
            date: { required: "Please select a date" },
            currency: { required: "Please select a currency" },
            code: { required: "Please select a code" },
            category: { required: "Please select a category" },
            invoice_to: { required: "Please enter an invoice recipient" },
            qty: { required: "Please enter quantity" },
            price: { required: "Please enter price" },
            paid_by: { required: "Please select paid by" },
            status: { required: "Please select a status" },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });

    invoiceForm.controls.price[0].addEventListener('input', function (e) {
        let input = e.target.value;
        e.target.value = disableAlphabetic(input);
        input = input.replace(/,/g, ''); 
        if (!isNaN(input) && input !== "") {
            input = formatNumber(input);
            e.target.value = input;
        }
    });
    
    invoiceForm.controls.price[0].addEventListener('input', function (e) {
        let input = e.target.value;
        e.target.value = disableAlphabetic(input);
        input = input.replace(/,/g, ''); 
        if (!isNaN(input) && input !== "") {
            input = formatNumber(input);
            e.target.value = input;
        }
    });

    invoiceForm.controls.sub_total[0].addEventListener('input', function (e) {
        let input = e.target.value;
        e.target.value = disableAlphabetic(input);
        input = input.replace(/,/g, ''); 
        if (!isNaN(input) && input !== "") {
            input = formatNumber(input);
            e.target.value = input;
        }
    });

    invoiceForm.controls.other[0].addEventListener('input', function (e) {
        let input = e.target.value;
        e.target.value = disableAlphabetic(input);
        input = input.replace(/,/g, ''); 
        if (!isNaN(input) && input !== "") {
            input = formatNumber(input);
            e.target.value = input;
        }
    });

    invoiceForm.controls.deduction[0].addEventListener('input', function (e) {
        let input = e.target.value;
        e.target.value = disableAlphabetic(input);
        input = input.replace(/,/g, ''); 
        if (!isNaN(input) && input !== "") {
            input = formatNumber(input);
            e.target.value = input;
        }
    });

    invoiceForm.controls.total[0].addEventListener('input', function (e) {
        let input = e.target.value;
        e.target.value = disableAlphabetic(input);
        input = input.replace(/,/g, ''); 
        if (!isNaN(input) && input !== "") {
            input = formatNumber(input);
            e.target.value = input;
        }
    });
}

function formatNumber(num) {
    if (num.includes('.')) {
        num = num.split('.')[0];
    }

    return num.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function disableAlphabetic(num) {
    return num.replace(/[^0-9.,]/g, '');
}

function resetForm() {
    const today = new Date();
    invoiceForm.group.trigger("reset");
    invoiceForm.controls.date.val(today.toISOString().split("T").shift());
    invoiceForm.group.find(".select2").val(null).trigger("change");
}

function loadForm(data) {
    invoiceForm.controls.id.val(data.id);
    invoiceForm.controls.date.val(data.date);
    invoiceForm.controls.currency.val(data.currency);
    invoiceForm.controls.code.val(data.code).trigger("change");
    invoiceForm.controls.category.val(data.category).trigger("change");
    invoiceForm.controls.invoice_to.val(data.invoice_to);
    invoiceForm.controls.address.val(data.address);
    invoiceForm.controls.phone.val(data.phone);
    invoiceForm.controls.email.val(data.email);
    invoiceForm.controls.description.val(data.description);
    invoiceForm.controls.qty.val(data.qty);
    invoiceForm.controls.price.val(formatNumber(data.price));
    invoiceForm.controls.sub_total.val(data.sub_total);
    invoiceForm.controls.tax.val(data.tax);
    invoiceForm.controls.other.val(formatNumber(data.other));
    invoiceForm.controls.deduction.val(formatNumber(data.deduction));
    invoiceForm.controls.total.val(data.total);
    invoiceForm.controls.paid_by.val(data.paid_by).trigger("change");
    invoiceForm.controls.status.val(data.status).trigger("change");
    invoiceForm.controls.received.val(data.received);

    calculateSubTotal();
}

function parseToNumeric(val) {
    return val.replace(/,/g, '')
}

function calculateSubTotal() {
    let qty = invoiceForm.controls.qty.val();
    qty = !isNaN(parseInt(qty)) ? parseInt(qty) : 0;

    let price = parseToNumeric(invoiceForm.controls.price.val());
    
    price = !isNaN(parseInt(price)) ? parseInt(price) : 0;

    invoiceForm.controls.sub_total.val(formatNumber((price * qty).toString()));

    calculateTotal();
}

function calculateTotal() {
    let subTotal = parseToNumeric(invoiceForm.controls.sub_total.val());
    subTotal = !isNaN(parseInt(subTotal)) ? parseInt(subTotal) : 0;

    let tax = invoiceForm.controls.tax.val();
    tax = !isNaN(parseInt(tax)) ? parseFloat(tax) : 0;

    let other = parseToNumeric(invoiceForm.controls.other.val());
    other = !isNaN(parseInt(other)) ? parseInt(other) : 0;

    let deduction = parseToNumeric(invoiceForm.controls.deduction.val());
    deduction = !isNaN(parseInt(deduction)) ? parseInt(deduction) : 0;
    
    invoiceForm.controls.total.val(formatNumber(((subTotal + (subTotal * tax / 100)) + other - deduction).toString()));
}

function createAction(currency) {
    resetForm();
    invoiceForm.controls.currency.val(currency);
    invoiceForm.group.attr("url", urls.create);
    invoiceModalHeader.text("New Invoice");
    invoiceModal.modal("show");
}

function editAction() {
    const selected = invoiceTable.DataTable().row({ selected: true }).data();

    if (selected) {
        $.ajax({
            url: urls.detail + "/" + selected.id,
            dataType: "json",
            success: function (data) {
                if (data) {
                    resetForm();
                    loadForm(data);
                    invoiceForm.group.attr("url", urls.edit + "/" + data.id);
                    invoiceModalHeader.text("Edit Invoice");
                    invoiceModal.modal("show");
                }
            }
        });
    } else {
        $(document).Toasts("create", {
            title: "Info",
            class: "bg-info",
            icon: "fa fa-info-circle",
            close: false,
            autohide: true,
            delay: 1500,
            body: "Please select a row first!"
        });
    }
}



function submitAction() {
    if (!invoiceForm.group.valid()) return;

    $.ajax({
        url: invoiceForm.group.attr("url"),
        data: invoiceForm.group.serialize(),
        method: "POST",
        dataType: "json",
        success: function (data) {
            if (data.success) {
                invoiceTable.DataTable().ajax.reload();
                invoiceModal.modal("hide");

                $(document).Toasts("create", {
                    title: "Success",
                    class: "bg-success",
                    icon: "fa fa-check",
                    close: false,
                    autohide: true,
                    delay: 1500,
                    body: data.message
                });
            } else {
                $(document).Toasts("create", {
                    title: "Error",
                    class: "bg-error",
                    icon: "fa fa-times",
                    close: false,
                    autohide: true,
                    delay: 1500,
                    body: data.message
                });
            }
        }
    })
}

function deleteAction() {
    const selected = invoiceTable.DataTable().row({ selected: true }).data();

    if (selected) {
        showPrompt({
            title: "Delete Invoice",
            text: "Are you sure to delete this invoice?",
            submit: "Delete",
            submitClass: "btn-danger",
            action: function () {
                $.ajax({
                    url: urls.delete + "/" + selected.id,
                    dataType: "json",
                    success: function (data) {
                        if (data.success) {
                            invoiceTable.DataTable().ajax.reload();

                            $(document).Toasts("create", {
                                title: "Success",
                                class: "bg-success",
                                icon: "fa fa-check",
                                close: false,
                                autohide: true,
                                delay: 1500,
                                body: data.message
                            });
                        } else {
                            $(document).Toasts("create", {
                                title: "Error",
                                class: "bg-error",
                                icon: "fa fa-times",
                                close: false,
                                autohide: true,
                                delay: 1500,
                                body: data.message
                            });
                        }
                    }
                });

                hidePrompt();
            }
        });
    } else {
        $(document).Toasts("create", {
            title: "Info",
            class: "bg-info",
            icon: "fa fa-info-circle",
            close: false,
            autohide: true,
            delay: 1500,
            body: "Please select a row first!"
        });
    }
}

function printAction(type) {
    let params = { mode: "print", type: type };
    window.open(urls.site + '?' + $.param(params), '_blank');
}

function exportAction() {
    let params = { mode: "export" };
    let dataTableParams = invoiceTable.DataTable().ajax.params();

    params["draw"] = dataTableParams["draw"];
    params["length"] = dataTableParams["length"];
    params["order"] = dataTableParams["order"];
    params["search"] = dataTableParams["search"];
    params["start"] = dataTableParams["start"];

    window.open(urls.site + '?' + $.param(params), '_blank');
}