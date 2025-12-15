<?php 
include "include/header.php"; 
include "include/topnavbar.php"; 

$sqlBanks = "SELECT idtbl_bank, bankname FROM tbl_bank WHERE status = 1 ORDER BY bankname ASC";
$resultBanks = $conn->query($sqlBanks);

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
                            <div class="page-header-icon"><i class="fas fa-hourglass-end"></i></div>
                            <span>Expired Cheques</span>
                        </h1>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt-2 p-2">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-lg nowrap" id="expiredChequesTable">
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
                                    <th>Payment Status</th>
                                    <th >Actions</th>
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

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="addPaymentForm">
        <div class="modal-header">
          <h5 class="modal-title">Add New Payment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="cheque_id" id="paymentChequeId">

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Customer</label>
                <input type="text" class="form-control form-control-sm" id="paymentCustomerName" disabled>
                <input type="hidden" name="customer_id" id="paymentCustomerId">
            </div>

        <div class="col-md-6">
            <label>Amount</label>
            <input type="number" class="form-control form-control-sm" id="paymentAmount" disabled>
            <input type="hidden" name="amount" id="paymentAmountHidden">
        </div>

        </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label>Payment Type</label>
              <select class="form-control form-control-sm" name="payment_type" id="paymentType" required>
                <option value="">Select</option>
                <option value="1">Cash</option>
                <option value="2">Cheque</option>
            </select>

            </div>
          </div>

          <!-- Show only if cheque selected -->
          <div id="chequeDetails" style="display:none;">
            <div class="row mb-3">
              <div class="col-md-6">
                <label>Cheque Number</label>
                <input type="text" class="form-control form-control-sm" name="cheque_number">
              </div>
                <div class="col-md-6">
                <label class="form-label">Bank</label>
                <select class="form-control" name="bank" id="bankSelect" style="width:100%"></select>
                </div>
            </div>

          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Payment</button>
        </div>
      </form>
    </div>
  </div>
</div>



<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function () {
        var table = $('#expiredChequesTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "scripts/chequepaymentlist.php",
            type: "POST",
            data: { statusFilter: 3 }
        },
        "columns": [
            { "data": "id" },
            { "data": "cheque_number" },
            { "data": "customer", "defaultContent": "<i>No customer</i>" },
            { "data": "bank_name" },
            { "data": "branch_name" },
            { "data": "amount" },
            { "data": "payment_date" },
            { 
                "data": "status",
                "render": function(data) {
                    return '<span class="text-danger">Rejected / Expired</span>';
                }
            },
            { 
                "data": "is_payment_added",
                "render": function(data) {
                    return data == 1 
                       ? '<span style="color: green;">Added</span>' 
                        : '<span style="color: orange;">Pending</span>';
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                        let addBtn = '';
                        if(row.is_payment_added != 1) {
                            addBtn = `<button class="btn btn-success btn-sm action-btn me-1" 
                                        data-id="${row.id}" 
                                        data-action="addPayment" 
                                        data-bs-toggle="tooltip" 
                                        title="Add New Payment">
                                        <i class="fas fa-plus"></i>
                                    </button>`;
                        }

                        return `
                            <div class="text-end">
                                ${addBtn}
                                <button class="btn btn-outline-danger btn-sm action-btn" 
                                    data-id="${row.id}" 
                                    data-action="delete" 
                                    data-bs-toggle="tooltip" 
                                    title="Delete Cheque">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                    `;
                }
            }
        ]
    });

    $('#expiredChequesTable').on('draw.dt', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });


    $(document).on('click', '.action-btn', function() {
        const id = $(this).data('id');
        const action = $(this).data('action');

        if(action === 'addPayment') {
            const row = table.row($(this).parents('tr')).data(); 
            $('#paymentChequeId').val(id);
            $('#addPaymentForm')[0].reset();
            $('#chequeDetails').hide();

            $('#paymentCustomerName').val(row.customer);
            $('#paymentCustomerId').val(row.customer_id); 
            $('#paymentAmount').val(row.amount);
            $('#paymentAmountHidden').val(row.amount); 

            $('#addPaymentModal').modal('show');
        } 
        else if(action === 'delete') {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This cheque will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then(result => {
                if(result.isConfirmed) {
                    $.post('process/updateChequeStatus.php', { id: id, status: 2 }, function(response) {
                        if(response.status === 'success') {
                            table.ajax.reload(null, false);
                            Swal.fire('Deleted!', 'Cheque deleted.', 'success');
                        } else {
                            Swal.fire('Error', response.message || 'Delete failed', 'error');
                        }
                    }, 'json');
                }
            });
        }
    });
    
    $('#addPaymentForm button[type="submit"]').prop('disabled', true);

    $('#paymentType').on('change', function() {
        if ($(this).val() !== '') {
            $('#addPaymentForm button[type="submit"]').prop('disabled', false);
        } else {
            $('#addPaymentForm button[type="submit"]').prop('disabled', true);
        }

        if($(this).val() === '2') {
            $('#chequeDetails').show();
        } else {
            $('#chequeDetails').hide();
        }
    });

    $('#addPaymentForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: 'process/addPaymentForRejectedCheques.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    $('#addPaymentModal').modal('hide');
                    $('#expiredChequesTable').DataTable().ajax.reload(null, false);
                    Swal.fire('Success', 'Payment added successfully!', 'success');
                } else {
                    Swal.fire('Error', response.message || 'Failed to save payment', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Something went wrong saving payment.', 'error');
            }
        });
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
        placeholder: "Select Bank",
        dropdownParent: $("#addPaymentModal")
    });

    $('#branchSelect').select2({
        ajax: {
            url: "getprocess/getbranchesforselect2.php",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term,
                    bankId: $('#bankSelect').val()
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        },
        placeholder: "Select Branch",
        dropdownParent: $("#addPaymentModal")
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
                            $branchSelect.append(
                                '<option value="' + branch.idtbl_bank_branch + '">' + branch.branchname + '</option>'
                            );
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


    $('#expiredNewChequeForm').on('submit', function(e){
        e.preventDefault();
        const formData = $(this).serialize();

        $.post('process/saveChequePayment.php', formData, function(response){
            if(response.status === 'success') {
                $('#modalAddNewChequeExpired').modal('hide');
                $('#expiredNewChequeForm')[0].reset();
                $('#expiredChequesTable').DataTable().ajax.reload(null, false);
                Swal.fire('Success', 'New cheque payment added successfully', 'success');
            } else {
                Swal.fire('Error', response.message || 'Failed to save payment', 'error');
            }
        }, 'json');
    });

});
</script>
<?php include "include/footer.php"; ?>
