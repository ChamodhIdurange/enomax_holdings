<?php 
include "include/header.php";  

// Fetch all completed vehicle loadings (status = 3)
$sql = "SELECT * FROM `tbl_vehicle_loading` WHERE `status` = 3"; 
$result = $conn->query($sql);

include "include/topnavbar.php"; 
?>
<style>
    .tableprint { table-layout: fixed; }
    .vehicleunloading-modal { max-width: 1000px; }
    .table-responsive { position: relative; max-height: 300px; overflow-y: auto; }
    .table thead { position: sticky; top: 0; background: white; z-index: 100; }
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
                            <div class="page-header-icon"><i data-feather="truck"></i></div>
                            <span>Vehicle Unloading</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-2">
                <div class="card">
                    <div class="card-body p-2">
                        <table class="table table-bordered table-striped table-sm nowrap" id="vehicleUnloadingTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Loading Date</th>
                                    <th>Total Items</th>
                                    <th>Remaining</th>
                                    <th>Status</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<!-- Modal for Vehicle Unloading -->
<div class="modal fade" id="modalVehicleUnload" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Unload Vehicle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="vehicleUnloadContent">
                <p class="text-center text-muted">Loading...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="saveVehicleUnloadBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Update Remaining Quantities -->
<div class="modal fade" id="modalUpdateRemaining" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Remaining Quantities</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="updateRemainingContent">
                <p class="text-center text-muted">Loading...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" id="saveRemainingBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>


<?php include "include/footerscripts.php"; ?>
<script>
$(document).ready(function () {

    // Vehicle Unloading DataTable
    $('#vehicleUnloadingTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "scripts/vehicleunloadinglist.php",
            type: "POST",
        },
        "order": [[0, "desc"]],
        "columns": [
            { "data": "idtbl_vehicle_loading" },
            { "data": "vehicleno" },
            { "data": "drivername" },
            { "data": "loadingdate" },
            { "data": "total_items" },
            { "data": "total_remaining" },
            {
                "data": "status",
                "render": function(data) {
                    const statusMap = {
                        3: '<span class="badge bg-warning text-dark">Completed</span>',
                        5: '<span class="badge bg-success">Unloaded</span>'
                    };
                    return statusMap[data] || data;
                }
            },
            {
                "data": null,
                "className": "text-right",
                "render": function(data, type, full) {
                    const id = full.idtbl_vehicle_loading;
                    const status = parseInt(full.status, 10);

                    // If status = 5, show disabled buttons
                    if (status === 5) {
                        return `
                            <div class="btn-group">
                                <button class="btn btn-outline-secondary btn-sm" disabled title="Already Unloaded">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" disabled title="Already Unloaded">
                                    <i class="fas fa-warehouse"></i>
                                </button>
                            </div>`;
                    }

                    // Active record buttons (editable)
                    return `
                        <div class="btn-group">
                            <button class="btn btn-outline-success btn-sm btnEditRemaining" 
                                    data-id="${id}" 
                                    data-vehicleno="${full.vehicleno}" 
                                    data-driver="${full.drivername}" 
                                    data-date="${full.loadingdate}"
                                    title="Update Remaining Quantities">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-primary btn-sm btnUnloadStock" 
                                    data-id="${id}" 
                                    title="Unload Remaining to Stock">
                                <i class="fas fa-warehouse"></i>
                            </button>
                        </div>`;
                }
            }
        ]
    });

    $(document).on("click", ".btnEditRemaining", function() {
        const id = $(this).data('id');
        const vehicleno = $(this).data('vehicleno');
        const driver = $(this).data('driver');
        const date = $(this).data('date');

        // Show modal header summary
        let headerInfo = `
            <div class="mb-3 border-bottom pb-2">
                <strong>Vehicle:</strong> ${vehicleno} &nbsp; | &nbsp;
                <strong>Driver:</strong> ${driver} &nbsp; | &nbsp;
                <strong>Date:</strong> ${date}
            </div>
        `;

        $("#updateRemainingContent").html('<p class="text-center text-muted">Loading...</p>');
        $('#modalUpdateRemaining').modal('show');

        // Fetch details for this specific vehicle loading
        $.ajax({
            url: "mobile_api/getVehicleLoadingDetails.php",
            type: "GET",
            data: { id: id },
            dataType: "json",
            success: function(data) {
                if (!data || !data.details || data.details.length === 0) {
                    $("#updateRemainingContent").html('<p class="text-danger text-center">No product data found.</p>');
                    return;
                }

                let html = `
                    ${headerInfo}
                    <form id="vehicleUnloadForm" data-id="${id}">
                        <h5 class="text-primary mb-3">Products Loaded</h5>
                `;

                data.details.forEach((detail) => {
                    console.log( detail);
                    html += `
                    <div class="card shadow-sm p-3 mb-3">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Product</label>
                                <input type="text" class="form-control form-control-sm" value="${detail.productname}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Total Loaded</label>
                                <input type="number" class="form-control form-control-sm" value="${detail.qty}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-success">Remaining Qty</label>
                                <input type="number" 
                                    class="form-control form-control-sm remainingQtyInput" 
                                    name="remaining[${detail.tbl_product_idtbl_product}]" 
                                    value="${detail.qty_remaining || 0}" 
                                    min="0" max="${detail.qty}" 
                                    required>
                            </div>
                        </div>
                    </div>`;
                });

                html += `</form>`;
                $("#updateRemainingContent").html(html);
            },
            error: function() {
                $("#updateRemainingContent").html('<p class="text-danger text-center">Error fetching data.</p>');
            }
        });
    });


    $('#saveRemainingBtn').click(function() {
        const form = $('#vehicleUnloadForm');
        if (!form.length) return;

        const id = form.data('id');
        const formData = form.serializeArray();
        let structuredData = {};
        formData.forEach(field => {
            structuredData[field.name] = field.value;
        });

        // Validate remaining quantities
        let invalid = false;
        form.find('.remainingQtyInput').each(function() {
            const val = parseFloat($(this).val());
            const max = parseFloat($(this).attr('max'));
            if (isNaN(val) || val < 0 || val > max) {
                $(this).addClass('is-invalid');
                invalid = true;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (invalid) {
            Swal.fire("Invalid Input", "Please enter valid remaining quantities.", "warning");
            return;
        }

        fetch("process/updateVehicleUnloading.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id, data: structuredData })
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === "success") {
                $('#modalUpdateRemaining').modal('hide');
                Swal.fire("Updated", "Remaining quantities updated successfully.", "success");
                $('#vehicleUnloadingTable').DataTable().ajax.reload(null, false);
            } else {
                Swal.fire("Error", res.message || "Failed to update remaining quantities.", "error");
            }
        })
        .catch(() => Swal.fire("Error", "Unexpected error occurred.", "error"));
    });


    // 🔹 Unload remaining products back to stock
    $(document).on("click", ".btnUnloadStock", function() {
        const id = $(this).data("id");

        Swal.fire({
            title: "Unload Remaining to Stock?",
            text: "This will move all remaining quantities back into stock and mark the vehicle as unloaded.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Unload",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("process/unloadToStock.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ id }),
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === "success") {
                        Swal.fire("Unloaded", res.message, "success");
                        $('#vehicleUnloadingTable').DataTable().ajax.reload(null, false);
                    } else {
                        Swal.fire("Error", res.message || "Failed to unload.", "error");
                    }
                })
                .catch(() => Swal.fire("Error", "Unexpected error occurred.", "error"));
            }
        });
    });


});
</script>
<?php include "include/footer.php"; ?>
