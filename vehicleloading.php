<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_vehicle_loading` WHERE `status` IN (1,0,2)";
$result =$conn-> query($sql); 

$sqlcommonnames="SELECT DISTINCT `common_name` FROM `tbl_product` WHERE `status`=1";
$resultcommonnames =$conn-> query($sqlcommonnames); 

include "include/topnavbar.php"; 
?>
<style>
    .tableprint {
        table-layout: fixed;
    }

    .vehicleloading-modal {
        max-width: 1000px;
    }
    .table-responsive {
    position: relative;
    max-height: 300px;
    overflow-y: auto;
    }
    .table thead {
        position: sticky;
        top: 0;
        background: white;
        z-index: 100;
    }
    table.dataTable {
    overflow: visible !important;
    }
    .dataTables_wrapper {
        overflow: visible !important;
    }


</style>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="list"></i></div>
                            <span>Vehicle Loading</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12" style="padding-bottom: 70px;" >
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right"
                                            id="btnvehicleloadcreate"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalcreatevehicleloading">
                                            <i class="fas fa-plus"></i>&nbsp;Create Vehicle Loading
                                        </button>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-bordered table-striped table-sm nowrap" id="vehicleLoadingTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Vehicle</th>
                                            <th>Driver</th>
                                            <th>Loading Date</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<!-- Modal Create Vehicle Loading -->
<div class="modal fade" id="modalcreatevehicleloading" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Vehicle Loading</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>

            <div class="modal-body">
                <form id="vehicleLoadingForm">
                    <div class="row mb-2">
                        <div class="col">
                            <label>Vehicle</label>
                            <select class="form-control form-control-sm select2" style="width: 100%;"
                                name="tbl_vehicle_idtbl_vehicle" id="vehicleSelect" required>
                                <option value="">Select Vehicle</option>
                            </select>
                        </div>
                        <div class="col">
                            <label>Driver/User</label>
                            <select class="form-control form-control-sm select2" style="width: 100%;"
                                name="tbl_user_idtbl_user" id="userSelect" required>
                                <option value="">Select Driver/User</option>
                            </select>
                        </div>
                        <div class="col">
                        </div>
                    </div>

                    <hr>
                    <h6>Loading Details</h6>
                    <div id="loadingDetailsContainer"></div>
                    <button type="button" class="btn btn-sm btn-outline-primary my-2" id="addProductBtn">Add Product</button>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" id="saveVehicleLoadingBtn">Save Loading</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal loading view -->
<div class="modal fade" id="vehicleViewModal" tabindex="-1" role="dialog" aria-labelledby="vehicleViewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="vehicleViewModalLabel">Vehicle Loading Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="vehicleViewContent">
        <!-- AJAX content loads here -->
        <p class="text-center text-muted">Loading...</p>
      </div>
    </div>
  </div>
</div>



<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function () {

        $('body').tooltip({
            selector: '[data-toggle="tooltip"]'
        });
        $('[data-toggle="tooltip"]').tooltip({
            trigger: 'hover'
        });

       // Vehicle Select2
        $('#vehicleSelect').select2({
            ajax: {
                url: 'process/getvehicleoptions.php',
                type: 'POST',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            dropdownParent: $('#modalcreatevehicleloading'),
            placeholder: 'Select a vehicle',
            allowClear: true
        });

        // User Select2
        $('#userSelect').select2({
            ajax: {
                url: 'process/getuseroptions.php',
                type: 'POST',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            dropdownParent: $('#modalcreatevehicleloading'),
            placeholder: 'Select a driver/user',
            allowClear: true
        });


        var addcheck = '<?php echo $addcheck; ?>';
        var editcheck = '<?php echo $editcheck; ?>';
        var statuscheck = '<?php echo $statuscheck; ?>';
        var deletecheck = '<?php echo $deletecheck; ?>';

        $('#vehicleLoadingTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/vehicleloadinglist.php",
                type: "POST", // you can use GET
            },
            "order": [
                [0, "desc"]
            ],
            "columns": [{
                    "data": "idtbl_vehicle_loading"
                },
                {
                    "data": "vehicleno"
                },
                {
                    "data": "name"
                },
                {
                    "data": "loadingdate"
                },
                {
                    "data": "status",
                    "render": function (data) {
                        let label = "";
                        let color = "";

                        switch (parseInt(data)) {
                            case 0: label = "Pending"; color = "warning"; break;
                            case 1: label = "Loaded"; color = "primary"; break;
                            case 2: label = "In Transit"; color = "info"; break;
                            case 3: label = "Completed"; color = "success"; break;
                            case 4: label = "Deleted"; color = "danger"; break;
                        }

                        return `<span class="badge bg-${color}">${label}</span>`;
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {
                        const id = full['idtbl_vehicle_loading'];
                        const status = parseInt(full['status']);
                        let buttons = '';

                        //  View
                        buttons += `
                            <button class="btn btn-outline-dark btn-sm mr-1 btnView" data-id="${id}" title="View">
                                <i class="fa fa-eye"></i>
                            </button>
                        `;

                        // Status Change Dropdown
                        if (status !== 4) {
                            buttons += `
                                <div class="btn-group mr-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-exchange-alt"></i> <!-- Status icon -->
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item changeStatus" data-id="${id}" data-status="0">Pending</a>
                                        <a class="dropdown-item changeStatus" data-id="${id}" data-status="1">Loaded</a>
                                        <a class="dropdown-item changeStatus" data-id="${id}" data-status="2">In Transit</a>
                                        <a class="dropdown-item changeStatus" data-id="${id}" data-status="3">Completed</a>
                                    </div>
                                </div>
                            `;
                        }

                        // Delete
                        if (status !== 4) {
                            buttons += `
                                <button class="btn btn-outline-danger btn-sm btndelete" data-id="${id}" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            `;
                        }

                        return buttons;
                    }
                }
            ]
        });
        document.getElementById('btnorderprint').addEventListener("click", print);
    });


    $(document).ready(function(){
        $('#btnvehicleloadcreate').click(function(){
            console.log("Modal button clicked");
            $('#modalcreatevehicleloading').modal('show');
        });
    });

    $('#modalcreatevehicleloading').on('hidden.bs.modal', function () {
        $('#vehicleLoadingForm')[0].reset();
        $('#loadingDetailsContainer').html('');
        $('#vehicleSelect').val(null).trigger('change');
        $('#userSelect').val(null).trigger('change');
        productIndex = 0;
        previousProductId = {};
    });


    let productIndex = 0;
    let previousProductId = {};


    function getProductDetailRow(index) {
        return `
        <div class="card p-2 mb-2" id="productCard_${index}">
            <div class="row">
                <div class="col">
                    <label>Product</label>
                    <select class="form-control form-control-sm select2" style="width: 100%;"
                        name="vehicle_loading_details[${index}][tbl_product_idtbl_product]" required>
                        <option value="">Select Product</option>
                    </select>
                </div>
                <div class="col">
                    <label>Qty</label>
                     <input type="number" class="form-control form-control-sm total-product-qty" 
                    name="vehicle_loading_details[${index}][qty]" 
                    value="0" readonly 
                    style="background:#f3f3f3; cursor:not-allowed;">
                </div>
                <div class="col">
                    <label>Sales Price</label>
                    <input type="number" class="form-control form-control-sm" step="0.01" name="vehicle_loading_details[${index}][salesprice]" required>
                </div>
                <div class="col">
                    <label>Unit Price</label>
                    <input type="number" class="form-control form-control-sm" step="0.01" name="vehicle_loading_details[${index}][unitprice]" required>
                </div>
                <div class="col-auto d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm removeProductBtn" data-index="${index}">Remove</button>
                </div>
            </div>

            <div class="mt-2">
                <h6>Batches</h6>
                <div id="batchContainer_${index}"></div>
                <button type="button" class="btn btn-sm btn-outline-secondary addBatchBtn" data-index="${index}">Add Batch</button>
            </div>
        </div>`;
    }

    function getBatchDetailRow(productIndex, batchIndex) {
        return `
        <div class="row mb-1" id="batchRow_${productIndex}_${batchIndex}">
            <div class="col">
                <label>Batch</label>
                <select class="form-control form-control-sm select2" style="width: 100%;"
                    name="vehicle_loading_details[${productIndex}][batches][${batchIndex}][tbl_batch_idtbl_batch]" required>
                    <option value="">Select Batch</option>
                </select>
            </div>
            <div class="col">
                <label>Qty From Batch</label>
                <input type="number" class="form-control form-control-sm" name="vehicle_loading_details[${productIndex}][batches][${batchIndex}][qty_from_batch]" required>
            </div>
            <div class="col-auto d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm removeBatchBtn" data-product-index="${productIndex}" data-batch-index="${batchIndex}">Remove</button>
            </div>
        </div>`;
    }

    $('#addProductBtn').click(function(){
        $('#loadingDetailsContainer').append(getProductDetailRow(productIndex));

        // Initialize Select2 for Product dynamically
        let productSelect = $(`#productCard_${productIndex} select[name="vehicle_loading_details[${productIndex}][tbl_product_idtbl_product]"]`);
        productSelect.select2({
            ajax: {
                url: 'process/getproductoptions.php',
                type: 'POST',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { searchTerm: params.term };
                },
                processResults: function (data) {
                    return { results: data };
                },
                cache: true
            },
            placeholder: 'Select Product',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#modalcreatevehicleloading')
        });

        productSelect.on('select2:select', function (e) {
            const data = e.params.data;
            const selectedId = data.id;
            
            const qtyInput = $(`#productCard_${productIndex} input[name="vehicle_loading_details[${productIndex}][qty]"]`);
            const maxQty = parseFloat(data.available_qty || 0);

            // Clear batches if product changed
            if (previousProductId[productIndex] !== selectedId) {
                $(`#batchContainer_${productIndex}`).html('');
                qtyInput.val(0);
                previousProductId[productIndex] = selectedId;
            }

            qtyInput.attr('max', maxQty);
            qtyInput.attr('placeholder', `Max: ${maxQty}`);

            // Prevent multiple event bindings
            qtyInput.off('input').on('input', function () {
                let val = parseFloat($(this).val());
                if (val > maxQty) {
                    $(this).val(maxQty);
                    Swal.fire('Limit Exceeded', `Max available quantity for this product is ${maxQty}.`, 'warning');
                }
            });
        });

        productIndex++;
    });

    $(document).on('click', '.removeProductBtn', function(){
        let idx = $(this).data('index');
        $('#productCard_' + idx).remove();
    });

    $(document).on('click', '.addBatchBtn', function(){
        let pIdx = $(this).data('index');
        let bContainer = $(`#batchContainer_${pIdx}`);
        let bIdx = bContainer.children().length;
        bContainer.append(getBatchDetailRow(pIdx, bIdx));

        let batchSelect = $(`#batchRow_${pIdx}_${bIdx} select[name="vehicle_loading_details[${pIdx}][batches][${bIdx}][tbl_batch_idtbl_batch]"]`);

        // Get the selected product ID for this product card
        let productId = $(`#productCard_${pIdx} select[name="vehicle_loading_details[${pIdx}][tbl_product_idtbl_product]"]`).val();

        batchSelect.select2({
            ajax: {
                url: 'process/getbatchoptions.php',
                type: 'POST',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { 
                        searchTerm: params.term,
                        product_id: productId 
                    };
                },
                processResults: function (data) {
                    return { results: data };
                },
                cache: true
            },
            placeholder: 'Select Batch',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#modalcreatevehicleloading')
        });

        batchSelect.on('select2:select', function (e) {
            const data = e.params.data;
            const maxBatchQty = parseFloat(data.available_qty || 0);
            const qtyInput = $(`#batchRow_${pIdx}_${bIdx} input[name="vehicle_loading_details[${pIdx}][batches][${bIdx}][qty_from_batch]"]`);

            qtyInput.attr('max', maxBatchQty);
            qtyInput.attr('placeholder', `Max: ${maxBatchQty}`);

            qtyInput.on('input', function () {
                let val = parseFloat($(this).val());
                if (val > maxBatchQty) {
                    $(this).val(maxBatchQty);
                    Swal.fire('Limit Exceeded', `Batch ${data.text} has only ${maxBatchQty} available.`, 'warning');
                }

                // --- Auto-update total product qty ---
                let total = 0;
                $(`#batchContainer_${pIdx} input[name$="[qty_from_batch]"]`).each(function () {
                    total += parseFloat($(this).val()) || 0;
                });
                $(`#productCard_${pIdx} input[name="vehicle_loading_details[${pIdx}][qty]"]`).val(total);
            });

        });

    });

    $(document).on('click', '.removeBatchBtn', function(){
        let pIdx = $(this).data('product-index');
        $(this).closest('.row').remove();

        // Recalculate total qty after removing batch
        let total = 0;
        $(`#batchContainer_${pIdx} input[name$="[qty_from_batch]"]`).each(function () {
            total += parseFloat($(this).val()) || 0;
        });
        $(`#productCard_${pIdx} input[name="vehicle_loading_details[${pIdx}][qty]"]`).val(total);
    });


    function setDeep(obj, path, value) {
        let keys = path.replace(/\]/g, '').split('[');
        let lastKey = keys.pop();
        let pointer = obj;
        keys.forEach(key => {
            if (!pointer[key]) pointer[key] = {};
            pointer = pointer[key];
        });
        pointer[lastKey] = value;
    }

    $('#saveVehicleLoadingBtn').click(function(){
        const vehicle = $('#vehicleSelect').val();
        const driver = $('#userSelect').val();

        if (!vehicle) {
            Swal.fire('Missing Vehicle', 'Please select a vehicle.', 'warning');
            return;
        }

        if (!driver) {
            Swal.fire('Missing Driver/User', 'Please select a driver or user.', 'warning');
            return;
        }

        const productCards = $('#loadingDetailsContainer .card');
        if (productCards.length === 0) {
            Swal.fire('No Products', 'Please add at least one product.', 'warning');
            return;
        }

        let isValid = true;
        let messages = [];

        productCards.each(function(){
            const productIndex = $(this).attr('id').split('_')[1];
            const batchContainer = $(`#batchContainer_${productIndex}`);
            const qtyField = $(`#productCard_${productIndex} input[name="vehicle_loading_details[${productIndex}][qty]"]`);
            const salesPrice = $(`#productCard_${productIndex} input[name="vehicle_loading_details[${productIndex}][salesprice]"]`);
            const unitPrice = $(`#productCard_${productIndex} input[name="vehicle_loading_details[${productIndex}][unitprice]"]`);
            const qty = parseFloat(qtyField.val()) || 0;

            // --- Reset invalid highlights ---
            qtyField.removeClass('is-invalid');
            salesPrice.removeClass('is-invalid');
            unitPrice.removeClass('is-invalid');

            // --- Validate batch presence ---
            if (batchContainer.children().length === 0) {
                isValid = false;
                messages.push(`Product #${parseInt(productIndex) + 1}: no batch selected.`);
            }

            // --- Validate sales price ---
            if (!salesPrice.val() || parseFloat(salesPrice.val()) <= 0) {
                isValid = false;
                messages.push(`Product #${parseInt(productIndex) + 1}: invalid Sales Price.`);
                salesPrice.addClass('is-invalid');
            }

            // --- Validate unit price ---
            if (!unitPrice.val() || parseFloat(unitPrice.val()) <= 0) {
                isValid = false;
                messages.push(`Product #${parseInt(productIndex) + 1}: invalid Unit Price.`);
                unitPrice.addClass('is-invalid');
            }

            // --- Validate product quantity ---
            if (qty <= 0) {
                isValid = false;
                messages.push(`Product #${parseInt(productIndex)+1}: quantity must be greater than 0.`);
                qtyField.addClass('is-invalid');
            }

            // --- Validate batch quantities ---
            let batchTotal = 0;
            let batchInvalid = false;
            batchContainer.find('input[name$="[qty_from_batch]"]').each(function(){
                let batchQty = parseFloat($(this).val()) || 0;
                if (batchQty <= 0) batchInvalid = true;
                batchTotal += batchQty;
            });

            if (batchInvalid) {
                isValid = false;
                messages.push(`Product #${parseInt(productIndex)+1}: all batch quantities must be greater than 0.`);
            }

            if (batchTotal !== qty) {
                isValid = false;
                messages.push(`Product #${parseInt(productIndex)+1}: total batch qty (${batchTotal}) must equal product qty (${qty}).`);
                qtyField.addClass('is-invalid');
            }

        });

        if (!isValid) {
            Swal.fire({
                title: 'Validation Errors Found',
                html: messages.join('<br>'),
                icon: 'warning',
                width: 600
            });
            return;
        }

        // --- Proceed to submit if all valid ---
        let formData = $('#vehicleLoadingForm').serializeArray();
        let structuredData = {};

        formData.forEach(field => {
            if (field.name.includes('[')) {
                setDeep(structuredData, field.name, field.value);
            } else {
                structuredData[field.name] = field.value;
            }
        });

        fetch('process/uploadVehicleLoadingDetails.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(structuredData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                $('#modalcreatevehicleloading').modal('hide');
                Swal.fire('Success', 'Vehicle Loading Saved', 'success');
                $('#vehicleLoadingTable').DataTable().ajax.reload(null, false);
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error', 'An unexpected error occurred', 'error');
            console.error(error);
        });
    });


    $(document).on("click", ".btnView", function () {
        const id = $(this).data("id"); 

        $.ajax({
            url: "mobile_api/getVehicleLoadingDetails.php",
            type: "GET",
            data: { id: id }, // ✅ pass the id here
            dataType: "json",
            success: function (response) {
                console.log("Response:", response);
                // since now API returns one record object, not an array
                const data = response;

                if (data.status === "ok") {
                    const details = data.details || [];
                    let totalQty = 0;
                    let totalSales = 0;

                    let html = `
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="vehicleLoadingDetailsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Batches (Batch No : Qty)</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                    details.forEach((detail, index) => {
                        totalQty += Number(detail.qty);

                        const batches = detail.batches.length
                            ? detail.batches.map(b => `${b.batchno} : ${b.qty_from_batch}`).join("<br>")
                            : "No batches";

                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${detail.productname}</td>
                                <td>${detail.qty}</td>
                                <td>${batches}</td>
                            </tr>`;
                    });

                    html += `
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="2">Total</th>
                                        <th>${totalQty}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    `;

                    $("#vehicleViewContent").html(html);
                    $("#vehicleViewModal").modal("show");
                } else {
                    Swal.fire("Error", data.message, "error");
                }

            },
            error: function () {
                alert("Error occurred while fetching vehicle loading details.");
            }
        });
    });


    $(document).on("click", ".btnedit", function () {
        const id = $(this).attr("id");

        // Fetch existing data
        $.ajax({
            url: "mobile_api/getVehicleLoadingDetails.php",
            type: "GET",
            data: { id: id },
            dataType: "json",
            success: function (data) {
                if (data && data.idtbl_vehicle_loading) {

                    // Clear old form
                    $("#vehicleLoadingForm")[0].reset();
                    $("#loadingDetailsContainer").html('');
                    productIndex = 0;

                    // Set vehicle and driver (reinitialize Select2)
                    $('#vehicleSelect').append(new Option(data.vehicleno, data.tbl_vehicle_idtbl_vehicle, true, true)).trigger('change');
                    $('#userSelect').append(new Option(data.drivername, data.tbl_user_idtbl_user, true, true)).trigger('change');

                    // Load product & batch details
                    data.details.forEach((detail, i) => {
                        $('#loadingDetailsContainer').append(getProductDetailRow(productIndex));

                        let productSelect = $(`#productCard_${productIndex} select[name="vehicle_loading_details[${productIndex}][tbl_product_idtbl_product]"]`);
                        productSelect.append(new Option(detail.productname, detail.tbl_product_idtbl_product, true, true)).trigger('change');

                        $(`#productCard_${productIndex} input[name="vehicle_loading_details[${productIndex}][qty]"]`).val(detail.qty);
                        $(`#productCard_${productIndex} input[name="vehicle_loading_details[${productIndex}][salesprice]"]`).val(detail.salesprice);
                        $(`#productCard_${productIndex} input[name="vehicle_loading_details[${productIndex}][unitprice]"]`).val(detail.unitprice);

                        // Add batches
                        detail.batches.forEach((batch, j) => {
                            $(`#batchContainer_${productIndex}`).append(getBatchDetailRow(productIndex, j));

                            let batchSelect = $(`#batchRow_${productIndex}_${j} select[name="vehicle_loading_details[${productIndex}][batches][${j}][tbl_batch_idtbl_batch]"]`);
                            batchSelect.append(new Option(batch.batchno, batch.tbl_batch_idtbl_batch, true, true)).trigger('change');

                            $(`#batchRow_${productIndex}_${j} input[name="vehicle_loading_details[${productIndex}][batches][${j}][qty_from_batch]"]`).val(batch.qty_from_batch);
                        });

                        productIndex++;
                    });

                    // Store the editing ID
                    $('#vehicleLoadingForm').attr('data-edit-id', id);

                    // Show modal
                    $('#modalcreatevehicleloading').modal('show');
                } else {
                    Swal.fire('Error', 'Failed to load vehicle loading data.', 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'An error occurred while fetching data.', 'error');
            }
        });
    });


    $(document).on('click', '.btndelete', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This will mark this record as deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'process/updateVehicleLoadingStatus.php',
                    type: 'POST',
                    data: { idtbl_vehicle_loading: id, status: 4 },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Deleted!', 'Vehicle loading deleted.', 'success');
                            $('#vehicleLoadingTable').DataTable().ajax.reload(null, false);
                        } else {
                            Swal.fire('Error', response.message || 'Failed to delete.', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Unable to connect to the server.', 'error');
                    }
                });
            }
        });
    });


    $(document).on('click', '.changeStatus', function () {
        const id = $(this).data('id');
        const newStatus = $(this).data('status');
        const statusText = ["Pending", "Loaded", "In Transit", "Completed"][newStatus];

        Swal.fire({
            title: 'Change Status',
            text: `Do you want to change this record to '${statusText}'?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, change it'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'process/updateVehicleLoadingStatus.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { idtbl_vehicle_loading: id, status: newStatus },
                    success: function (res) {
                        if (res.success) {
                            Swal.fire('Updated!', `Status changed to '${statusText}'.`, 'success');
                            $('#vehicleLoadingTable').DataTable().ajax.reload(null, false);
                        } else {
                            Swal.fire('Error', res.message || 'Failed to update.', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Could not connect to the server.', 'error');
                    }
                });
            }
        });
    });


</script>
<?php include "include/footer.php"; ?>
