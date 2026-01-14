<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_cheque_info` WHERE `status` IN (1,0,2)";
$result =$conn-> query($sql); 

$sqlcommonnames="SELECT DISTINCT `common_name` FROM `tbl_product` WHERE `status`=1";
$resultcommonnames =$conn-> query($sqlcommonnames); 

include "include/topnavbar.php"; 
?>
<style>
    .tableprint {
        table-layout: fixed;
    }

    .chequedetails-modal {
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
                            <span>Cheque Payment List</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right"
                                            id="btnnewchequecreate"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalcreatenewcheque">
                                            <i class="fas fa-plus"></i>&nbsp;Create New Cheque Payment
                                        </button>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-bordered table-striped table-sm nowrap" id="chequeDetailsTable">
                                    <thead>
                                        <tr>
                                            <th>Method</th>
                                            <th>Amount</th>
                                            <th>Branch</th>
                                            <th>Receipt No</th>
                                            <th>Cheque No</th>
                                            <th>Cheque Date</th>
                                            <th>Bank</th>
                                            <th>Updated</th>
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
<!-- Modal Add New Cheque -->
<div class="modal fade" id="modalcreatenewcheque" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Cheque</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

        <div class="modal-body">
            <form id="checkdetailsForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="startno" class="form-label">Start No</label>
                        <input type="text" class="form-control" id="startno" name="startno" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="endno" class="form-label">End No</label>
                        <input type="text" class="form-control" id="endno" name="endno" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="bankSelect" class="form-label">Bank</label>
                        <select id="bankSelect" name="bank_id" class="form-control" required>
                            <option value="">Select Bank</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="branchSelect" class="form-label">Branch</label>
                        <select id="branchSelect" name="branch_id" class="form-control" required>
                            <option value="">Select Branch</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="account" class="form-label">Account</label>
                        <select id="account" name="account" class="form-control" required>
                            <option value="">Select Account</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="updateuser" class="form-label">User</label>
                        <input type="text" id="updateuser" name="updateuser" class="form-control" value="admin" readonly>
                        <!-- Or change to select if you have user list -->
                    </div>
                </div>
            </form>
        </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="saveNewChequeBtn">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal cheque view -->
<div class="modal fade" id="paymentMethodsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Payment Methods</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered" id="paymentMethodsTable">
          <thead>
            <tr>
              <th>Method</th>
              <th>Amount</th>
              <th>Cheque No</th>
              <th>Cheque Date</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
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

        $('#chequeDetailsTable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "scripts/chequepaymentlist.php",
                type: "POST",
                error: function (xhr, error, thrown) {
                    console.log("AJAX error:", xhr.responseText);
                }
            },
            order: [[0, "desc"]],
            columns: [
                { data: "idtbl_invoice_payment" },  // ID
                { data: "date" },                   // Payment Date
                { data: "payment" },                // Payment Amount
                { data: "balance" },                // Balance Amount
                { data: "chequeno" },               // Cheque Number
                { data: "chequedate" },             // Cheque Date
                { data: "branch" },                 // Branch
                { data: "updateuser" },             // Updated By (Username)
                {
                    data: "status",
                    className: 'text-center',
                    render: function(data, type, full) {
                        let html = '';
                        // Customize statuses based on your status values
                        if (data === 'settled') {
                            html = '<span class="badge bg-success">Settled</span>';
                        } else if (data === 'not_settled') {
                            html = '<span class="badge bg-warning text-dark">Not Settled</span>';
                        } else if (data === 'cancelled') {
                            html = '<span class="badge bg-danger">Cancelled</span>';
                        } else {
                            html = '<span class="badge bg-secondary">Unknown</span>';
                        }
                        return html;
                    }
                },
                {
                    data: null,
                    className: 'text-right',
                    orderable: false,
                    render: function(data, type, full) {
                        return `
                            <button class="btn btn-outline-secondary btn-sm mr-1 btnedit" id="${full['idtbl_invoice_payment']}">
                                <i class="fa fa-pen"></i>
                            </button>
                            <button class="btn btn-outline-dark btn-sm mr-1 btnView" id="${full['idtbl_invoice_payment']}">
                                <i class="far fa-eye"></i>
                            </button>
                            <button type="button"
                                data-url="process/deleteInvoicePayment.php?record=${full['idtbl_invoice_payment']}"
                                data-actiontype="3"
                                title="Delete"
                                class="btn btn-outline-danger btn-sm mr-1 btntableaction btndelete"
                                data-id="${full['idtbl_invoice_payment']}">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        `;
                    }
                }
            ]
        });



        $('#btnnewchequecreate').click(function () {
            $.getJSON('mobile_api/getAllBanks.php', function (data) {
                $('#bankSelect').html('<option value="">Select Bank</option>');
                $.each(data, function (index, bank) {
                    $('#bankSelect').append(`<option value="${bank.bank_id}">${bank.bankname}</option>`);
                });
            });

            // Optional: clear branch and account dropdowns
            $('#branchSelect').html('<option value="">Select Branch</option>');
            $('#account').html('<option value="">Select Account</option>');

            $('#modalcreatenewcheque').modal('show');
        });

        $('#bankSelect').on('change', function () {
            const bankId = $(this).val();
            const branchSelect = $('#branchSelect');

            // Reset branch dropdown
            branchSelect.html('<option value="">Select Branch</option>');

            if (bankId) {
                $.getJSON('mobile_api/getAllBankBranches.php', { bank_id: bankId }, function (data) {
                    if (data.length === 0) {
                        branchSelect.append('<option value="">No branches available</option>');
                    } else {
                        data.forEach(function (br) {
                            branchSelect.append(`<option value="${br.branch_id}">${br.branchname}</option>`);
                        });
                    }
                }).fail(function () {
                    branchSelect.append('<option value="">Error loading branches</option>');
                });
            }
        });

        $.getJSON('mobile_api/getAccounts.php', function (data) {
            const accountSelect = $('#account'); // fixed

            accountSelect.html('<option value="">Select Account</option>');

            if (data.length === 0) {
                accountSelect.append('<option value="">No accounts available</option>');
                accountSelect.prop('disabled', true);
            } else {
                $.each(data, function (index, acc) {
                    accountSelect.append(`<option value="${acc.account_id}">${acc.accountno} - ${acc.accountname}</option>`);
                });
                accountSelect.prop('disabled', false);
            }
        });



    });

    $('#saveNewChequeBtn').on('click', function () {
        const formData = $('#checkdetailsForm').serialize();
        $.post('process/addChequeInfo.php', formData, function (response) {
            if (response.success) {
                $('#modalcreatenewcheque').modal('hide');
                $('#chequeDetailsTable').DataTable().ajax.reload(null, false);
            } else {
                alert(response.message || "Failed to save check.");
            }
        }, 'json');
    });



    $(document).on('click', '.btnView', function () {
        var id = $(this).attr('id');
        $.post('scripts/getPaymentMethods.php', {id: id}, function(data) {
            var html = '';
            data.forEach(function(row) {
                html += `<tr>
                            <td>${row.method_name}</td>
                            <td>${row.amount}</td>
                            <td>${row.chequeno || '-'}</td>
                            <td>${row.chequedate || '-'}</td>
                        </tr>`;
            });
            $('#paymentMethodsTable tbody').html(html);
            $('#paymentMethodsModal').modal('show');
        }, 'json');
    });

    

    // $(document).on('click', '.view-payment', function() {
    //     var id = $(this).data('id');

    //     $.getJSON('getPaymentMethods.php', {id: id}, function(data) {
    //         var html = '';
    //         data.forEach(function(row) {
    //             html += `
    //                 <tr>
    //                     <td>${row.method_name}</td>
    //                     <td>${row.amount}</td>
    //                     <td>${row.chequeno || '-'}</td>
    //                     <td>${row.chequedate || '-'}</td>
    //                 </tr>
    //             `;
    //         });

    //         $('#paymentMethodsTable tbody').html(html);
    //         $('#paymentMethodsModal').modal('show');
    //     });
    // });



</script>
<?php include "include/footer.php"; ?>
