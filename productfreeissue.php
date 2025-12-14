<?php
include "include/header.php";
include "include/topnavbar.php";
?>

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
                            <div class="page-header-icon"><i data-feather="gift"></i></div>
                            <span>Product Free Issue Management</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-outline-primary btn-sm fa-pull-right" id="btnAddNew">
                            <i class="fas fa-plus"></i> Add New Free Issue
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="dataTable" class="display table table-striped table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Code</th>
                                    <th>Product Name</th>
                                    <th>Buy Qty</th>
                                    <th>Free Qty</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="freeIssueModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="modalTitle">Add Free Issue</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="freeIssueForm">
                <div class="modal-body">
                    <input type="hidden" id="free_issue_id" name="free_issue_id">
                    <input type="hidden" id="action" name="action" value="add">

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label class="small font-weight-bold">Product*</label>
                            <select class="p-5" id="product_id" name="product_id" required>
                                <option value="">Select Product</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">Buy Quantity*</label>
                            <input type="number" class="form-control form-control-sm" id="buy_quantity"
                                name="buy_quantity" min="1" required>
                            <small class="form-text text-muted">Minimum quantity to buy</small>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">Free Quantity*</label>
                            <input type="number" class="form-control form-control-sm" id="free_quantity"
                                name="free_quantity" min="1" required>
                            <small class="form-text text-muted">Free items to give</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">Start Date (Month-Day)*</label>
                            <div class="input-group input-group-sm">
                                <select class="form-control form-control-sm" id="start_month" name="start_month" required>
                                    <option value="">Month</option>
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                                <select class="form-control form-control-sm" id="start_day" name="start_day" required>
                                    <option value="">Day</option>
                                </select>
                                <input type="hidden" id="start_date" name="start_date">
                            </div>
                            <small class="form-text text-muted">Applies every year on this date</small>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">End Date (Month-Day)*</label>
                            <div class="input-group input-group-sm">
                                <select class="form-control form-control-sm" id="end_month" name="end_month" required>
                                    <option value="">Month</option>
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                                <select class="form-control form-control-sm" id="end_day" name="end_day" required>
                                    <option value="">Day</option>
                                </select>
                                <input type="hidden" id="end_date" name="end_date">
                            </div>
                            <small class="form-text text-muted">Applies every year on this date</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="status"
                                    name="status" value="1" checked>
                                <label class="custom-control-label" for="status">Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info alert-sm">
                        <strong>Example:</strong> If Buy Quantity = 10 and Free Quantity = 1,
                        customers get 1 free item for every 10 items purchased during the specified date range each year.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btnSave">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        var table = $('#dataTable').DataTable({
            "processing": true,
            "serverSide": false,
            "ajax": {
                "url": "getprocess/get_free_issues.php",
                "type": "POST"
            },
            "columns": [{
                    "data": "row_num"
                },
                {
                    "data": "product_code"
                },
                {
                    "data": "product_name"
                },
                {
                    "data": "buy_quantity",
                    "className": "text-center"
                },
                {
                    "data": "free_quantity",
                    "className": "text-center"
                },
                {
                    "data": "start_date",
                    "className": "text-center"
                },
                {
                    "data": "end_date",
                    "className": "text-center"
                },
                {
                    "data": "status",
                    "className": "text-center",
                    "render": function(data, type, row) {
                        if (data == 1) {
                            return '<span class="badge badge-success">Active</span>';
                        } else {
                            return '<span class="badge badge-secondary">Inactive</span>';
                        }
                    }
                },
                {
                    "data": null,
                    "className": "text-center",
                    "render": function(data, type, row) {
                        return '<button class="btn btn-info btn-xs btnEdit" data-id="' + row.tbl_product_free_issue_id + '"><i class="fas fa-edit"></i></button> ';
                    }
                }
            ],
            "order": [
                [5, "desc"]
            ]
        });

        function populateDays(monthSelect, daySelect) {
            var month = $(monthSelect).val();
            var currentDay = $(daySelect).val();
            
            $(daySelect).empty().append('<option value="">Day</option>');
            
            if (month) {
                var daysInMonth = new Date(2024, month, 0).getDate(); 
                for (var i = 1; i <= daysInMonth; i++) {
                    var day = i < 10 ? '0' + i : i;
                    $(daySelect).append('<option value="' + day + '">' + i + '</option>');
                }

                if (currentDay && currentDay <= daysInMonth) {
                    $(daySelect).val(currentDay);
                }
            }
        }

        function updateHiddenDate(monthSelect, daySelect, hiddenField) {
            var month = $(monthSelect).val();
            var day = $(daySelect).val();
            
            if (month && day) {
                $(hiddenField).val(month + '-' + day);
            } else {
                $(hiddenField).val('');
            }
        }

        $('#start_month').change(function() {
            populateDays('#start_month', '#start_day');
            updateHiddenDate('#start_month', '#start_day', '#start_date');
        });

        $('#start_day').change(function() {
            updateHiddenDate('#start_month', '#start_day', '#start_date');
        });

        $('#end_month').change(function() {
            populateDays('#end_month', '#end_day');
            updateHiddenDate('#end_month', '#end_day', '#end_date');
        });

        $('#end_day').change(function() {
            updateHiddenDate('#end_month', '#end_day', '#end_date');
        });

        function initProductSelect() {
            $("#product_id").select2({
                dropdownParent: $('#freeIssueModal'),
                ajax: {
                    url: 'getprocess/search_products.php',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results,
                            pagination: {
                                more: data.more
                            }
                        };
                    },
                    cache: true
                },
                placeholder: 'Search and select product...',
                minimumInputLength: 0
            });
        }

        $('#btnAddNew').click(function() {
            $('#freeIssueForm')[0].reset();
            $('#free_issue_id').val('');
            $('#action').val('add');
            $('#modalTitle').text('Add Free Issue');
            $('#product_id').val(null).trigger('change');
            $('#status').prop('checked', true);
            $('#start_day').empty().append('<option value="">Day</option>');
            $('#end_day').empty().append('<option value="">Day</option>');
            initProductSelect();
            $('#freeIssueModal').modal('show');
        });

        $('#dataTable').on('click', '.btnEdit', function() {
            var id = $(this).data('id');
            $.ajax({
                url: 'getprocess/get_free_issue_details.php',
                type: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var data = response.data;

                        $('#free_issue_id').val(data.tbl_product_free_issue_id);
                        $('#action').val('edit');
                        $('#modalTitle').text('Edit Free Issue');
                        $('#buy_quantity').val(data.buy_quantity);
                        $('#free_quantity').val(data.free_quantity);
                        $('#status').prop('checked', data.status == 1);

                        var startParts = data.start_date.split('-');
                        var endParts = data.end_date.split('-');

                        $('#start_month').val(startParts[0]);
                        populateDays('#start_month', '#start_day');
                        $('#start_day').val(startParts[1]);
                        updateHiddenDate('#start_month', '#start_day', '#start_date');

                        $('#end_month').val(endParts[0]);
                        populateDays('#end_month', '#end_day');
                        $('#end_day').val(endParts[1]);
                        updateHiddenDate('#end_month', '#end_day', '#end_date');

                        initProductSelect();
                        var newOption = new Option(data.product_name, data.product_id, true, true);
                        $('#product_id').append(newOption).trigger('change');

                        $('#freeIssueModal').modal('show');
                    } else {
                        alert('Error loading data: ' + (response.message || 'Unknown'));
                    }
                },
                error: function(xhr, status, err) {
                    alert('Request error: ' + err);
                }
            });
        });

        $('#dataTable').on('click', '.btnDelete', function() {
            var id = $(this).data('id');
            if (confirm('Are you sure you want to inactive this free issue?')) {
                $.ajax({
                    url: 'process/delete_free_issue.php',
                    type: 'POST',
                    data: {
                        free_issue_id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Inactive successfully');
                            table.ajax.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                });
            }
        });

        $('#freeIssueForm').submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: 'process/freeissueprocess.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        $('#freeIssueModal').modal('hide');
                        table.ajax.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(response) {
                    alert('An error occurred while processing the request.' + response.message);
                }
            });
        });
    });
</script>
<?php include "include/footer.php"; ?>