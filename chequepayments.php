<?php 
include "include/header.php"; 

$sql="SELECT * FROM `tbl_cheque_payments` WHERE `status` IN (0,1,2,3)";
$result =$conn->query($sql); 

include "include/topnavbar.php"; 
?>

<style>
    .tableprint {
        table-layout: fixed;
    }
    .cheque-modal {
        max-width: 800px;
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
                            <div class="page-header-icon"><i class="fas fa-money-check-alt"></i></div>
                            <span>Cheque Payments</span>
                        </h1>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt-2 p-2">
                <div class="card">
                    <div class="card-body">
                        <!-- <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right"
                                            id="btnnewchequecreate"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalAddNewCheque">
                                            <i class="fas fa-plus"></i>&nbsp;Add New Cheque
                                        </button> -->

                        <table class="table table-bordered table-striped table-lg nowrap" id="chequePaymentsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cheque No</th>
                                    <th>Customer</th>
                                    <th>Bank</th>
                                    <th>Branch</th>
                                    <th>Amount</th>
                                    <th>Payment Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
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

<!-- Modal: Add Cheque -->
<div class="modal fade" id="modalAddNewCheque" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Cheque Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

            </div>
             <form id="chequeForm">
                <div class="modal-body">
                <div class="row mb-2">
                    <div class="col">
                    <label>Cheque Number</label>
                    <input type="text" name="cheque_number" class="form-control form-control-sm" required>
                    </div>
                    <div class="col">
                    <label>Customer</label>
                        <select name="tbl_customer_idtbl_customer" id="customerId" style="width: 100%;" class="form-control form-control-sm select2" required>
                            <option value="">Select Customer</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col">
                    <label>Bank</label>
                        <select name="tbl_bank_idtbl_bank" id="bankSelect" style="width: 100%;" class="form-control" required>
                            <option value="">Select Bank</option>
                        </select>
                    </div>
                    <div class="col">
                    <label>Branch</label>
                        <select name="tbl_bank_branch_idtbl_bank_branch" class="form-control form-control-sm" required>
                            <option value="">Select Branch</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col">
                    <label>Amount</label>
                    <input type="number" name="amount" class="form-control form-control-sm" required>
                    </div>
                    <div class="col">
                    <label>Payment Date</label>
                    <input type="date" name="payment_date" class="form-control form-control-sm" required>
                    </div>
                </div>

                </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-sm">Save</button>
        </div>
      </form>
        </div>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function () {
        $('#chequePaymentsTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "scripts/chequepaymentlist.php",
                type: "POST"
            },
            "columns": [
                { "data": "id" },
                { "data": "cheque_number" },
                { 
                "data": "customer",
                "defaultContent": "<i>No customer</i>"
                },
                { "data": "bank_name" },
                { "data": "branch_name" },
                { "data": "amount" },
                { "data": "payment_date" },

                {
                    "data": "status",
                    "render": function (data, type, row) {
                        switch(data) {
                            case "0":
                            case 0:
                                return '<span class="text-warning">Unsettled</span>';
                            case "1":
                            case 1:
                                return '<span class="text-success">Settled</span>';
                            case "2":
                            case 2:
                                return '<span class="text-danger">Deleted</span>';
                            case "3":
                            case 3:
                                return '<span class="text-danger">Expired/Rejected</span>';
                            default:
                                return '<span class="text-muted">Unknown</span>';
                        }
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        let settleBtn = '';
                        if(row.status == 0) {
                            settleBtn = `<button class="btn btn-success btn-sm action-btn mr-1" 
                                                data-id="${row.id}" 
                                                data-status="1" 
                                                data-bs-toggle="tooltip" 
                                                title="Settle Cheque">
                                            <i class="fas fa-check"></i>
                                        </button>`;
                        } else if(row.status == 1) {
                            settleBtn = `<button class="btn btn-warning btn-sm action-btn mr-1" 
                                                data-id="${row.id}" 
                                                data-status="0" 
                                                data-bs-toggle="tooltip" 
                                                title="Mark as Unsettled">
                                            <i class="fas fa-undo"></i>
                                        </button>`;
                        }

                        let deleteBtn = `<button class="btn btn-outline-danger btn-sm action-btn mr-1" 
                                                data-id="${row.id}" 
                                                data-status="2" 
                                                data-bs-toggle="tooltip" 
                                                title="Delete Cheque">
                                            <i class="fas fa-trash"></i>
                                        </button>`;

                        let expireBtn = '';
                        if(row.status != 3) {
                            expireBtn = `<button class="btn btn-outline-secondary btn-sm action-btn" 
                                                data-id="${row.id}" 
                                                data-status="3" 
                                                data-bs-toggle="tooltip" 
                                                title="Mark as Reject / Expired">
                                            <i class="fas fa-hourglass-end"></i>
                                        </button>`;
                        }

                        return settleBtn + deleteBtn + expireBtn;
                    }
                }
            ]
        });

        $("#customerId").select2({
            ajax: {
                url: "getprocess/getcustomersforselect2.php",
                type: "post",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            },
            dropdownParent: $("#modalcreateorder")
        });


        $('#bankSelect').select2({
            ajax: {
                url: "getprocess/getbanksforselect2.php", 
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term 
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            },
            dropdownParent: $("#modalAddNewCheque")
        });


        $('#chequeForm').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            $.post('process/saveChequePayment.php', formData, function(response) {
            if (response.status === 'success') {
                $('#addChequeModal').modal('hide');
                $('#chequeForm')[0].reset();
                $('#chequePaymentsTable').DataTable().ajax.reload(null, false);
                Swal.fire('Success', 'Cheque added successfully', 'success');
            } else {
                Swal.fire('Error', response.message || 'Failed to save cheque', 'error');
            }
            }, 'json');
        });

        $('select[name="tbl_bank_idtbl_bank"]').on('change', function() {
            var bankId = $(this).val();
            var $branchSelect = $('select[name="tbl_bank_branch_idtbl_bank_branch"]');

            $branchSelect.html('<option>Loading branches...</option>');
            $branchSelect.prop('disabled', true); 

            if (bankId) {
                $.ajax({
                    url: 'mobile_api/getAllBankBranches.php',
                    type: 'GET',
                    data: { bank_id: bankId },
                    dataType: 'json',
                    success: function(branches) {
                        $branchSelect.empty();
                        if (branches.length === 0) {
                            $branchSelect.append('<option value="0">No Branch</option>');
                        } else {
                            $branchSelect.append('<option value="">Select Branch</option>');
                            $.each(branches, function(i, branch) {
                                $branchSelect.append('<option value="' + branch.idtbl_bank_branch + '">' + branch.branchname + '</option>');
                            });
                        }
                        $branchSelect.prop('disabled', false); 
                    },
                    error: function() {
                        $branchSelect.html('<option value="">Error loading branches</option>');
                        $branchSelect.prop('disabled', false);
                    }
                });
            } else {
                $branchSelect.html('<option value="">Select Branch</option>');
                $branchSelect.prop('disabled', true);
            }
        });

    });

    $(document).ready(function(){
        $('#btnnewchequecreate').click(function(){
            console.log("Modal button clicked");
            $('#modalAddNewCheque').modal('show');
        });
    });

    $(document).on('click', '.action-btn', function() {
        const id = $(this).data('id');
        const newStatus = $(this).data('status');
        
        let actionText = '';
        switch(newStatus) {
            case 0: actionText = 'mark as Unsettled'; break;
            case 1: actionText = 'mark as Settled'; break;
            case 2: actionText = 'delete'; break;
            case 3: actionText = 'mark as Expired'; break;
        }

        const updateStatus = () => {
            $.ajax({
                url: 'process/updateChequeStatus.php',
                method: 'POST',
                data: { id: id, status: newStatus },
                dataType: 'json',
                success: function(response) {
                    if(response.status === 'success') {
                        $('#chequePaymentsTable').DataTable().ajax.reload(null, false);
                        if(newStatus !== 2) { 
                            Swal.fire('Success', `Cheque has been ${actionText}.`, 'success');
                        }
                    } else {
                        Swal.fire('Error', response.message || 'Failed to update status', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to update status', 'error');
                }
            });
        };

        if(newStatus === 2) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This cheque will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then(result => {
                if(result.isConfirmed) {
                    updateStatus();
                    Swal.fire('Deleted!', 'Cheque deleted.', 'success');
                }
            });
        } else {
            updateStatus();
        }
    });

</script>
<?php include "include/footer.php"; ?>
