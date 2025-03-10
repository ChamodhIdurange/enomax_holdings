<?php 
include "include/header.php"; 

$thismonth = date("n"); 
$thisyear = date("Y"); 

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
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i data-feather="activity"></i></div>
                                    <span>Dashboard</span>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row row-cols-1 row-cols-md-4">
                                    <?php if(menucheck($menuprivilegearray, 9)==1){ ?> 
                                    <a href="product.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-primary card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-primary py-2">
                                                        <i class="fas fa-cart-plus"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-primary my-2">Product</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 8)==1){ ?>
                                    <a href="productcategory.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-secondary card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-secondary py-2">
                                                        <i class="fas fa-list"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-secondary my-2">Product Category</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 7)==1){ ?>
                                    <a href="porder.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-danger card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-danger py-2">
                                                        <i class="fas fa-archive"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-danger my-2">Purchsing Order</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 6)==1){ ?>
                                    <a href="grn.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-success card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-success py-2">
                                                        <i class="fas fa-truck"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-success my-2">Good Receive</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 68)==1){ ?>
                                    <a href="warehouse.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-orange card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-orange py-2">
                                                        <i class="fas fa-warehouse"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-orange my-2">Warehouse</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 67)==1){ ?>
                                    <a href="customerporder.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-theme card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-theme py-2">
                                                        <i class="fas fa-users"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-theme my-2">Customer POrder</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php } if(menucheck($menuprivilegearray, 26)==1){ ?>
                                    <a href="invoiceview.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-info card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-info py-2">
                                                        <i class="far fa-file-alt"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-info my-2">Invoice View</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 15)==1){ ?>
                                    <a href="invoicepayment.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-indigo card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-indigo py-2">
                                                        <i class="fas fa-file-invoice-dollar"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-indigo my-2">Invoice Payment</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 16)==1){ ?>
                                    <a href="paymentreceipt.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-teal card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-teal py-2">
                                                        <i class="fas fa-receipt"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-teal my-2">Payment Receipt</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 66)==1){ ?>
                                    <a href="dayend.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-purple card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-purple py-2">
                                                        <i class="fas fa-calendar-times"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-purple my-2">Day End</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php } if(menucheck($menuprivilegearray, 42)==1){ ?>
                                    <a href="damagereturn.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-blue card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-blue py-2">
                                                        <i class="fas fa-house-damage"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-blue my-2">Damage Return</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 23)==1){ ?>
                                    <a href="employeetargetadd.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-dark card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-dark py-2">
                                                        <i class="fas fa-bullseye"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-dark my-2">Employee Target</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php } if(menucheck($menuprivilegearray, 4)==1){ ?>
                                    <a href="customer.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-yellow card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-yellow py-2">
                                                        <i class="fas fa-user-plus"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-yellow my-2">Customer</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 12)==1){ ?>
                                    <a href="employee.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-pink card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-pink py-2">
                                                        <i class="fas fa-user-tie"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-pink my-2">Employee</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 13)==1){ ?>
                                    <a href="area.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-success card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-success py-2">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-success my-2">Area</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 18)==1){ ?> 
                                    <a href="stock.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-info card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-info py-2">
                                                        <i class="fas fa-layer-group"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-info my-2">Stock Information</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 21)==1){ ?>
                                    <a href="customeroutstanding.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-indigo card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-indigo py-2">
                                                        <i class="fas fa-money-bill-alt"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-indigo my-2">Customer Outstanding</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 22)==1){ ?>
                                    <a href="dailysale.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-theme card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-theme py-2">
                                                        <i class="fas fa-calendar-day"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-theme my-2">Daily Sale</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 48)==1){ ?>
                                    <a href="payment.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-orange card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-orange py-2">
                                                        <i class="fas fa-money-bill-alt"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-orange my-2">Transactions Payment</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 49)==1){ ?>
                                    <a href="receipt.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-yellow card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-yellow py-2">
                                                        <i class="fas fa-file-export"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-yellow my-2">Transactions Receipt</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 57)==1){ ?>
                                    <a href="pettycash.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-primary card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-primary py-2">
                                                        <i class="fas fa-cash-register"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-primary my-2">Petty Cash</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 57)==1){ ?>
                                    <a href="rpt_ledger_folio.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-indigo card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-indigo py-2">
                                                        <i class="far fa-file-alt"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-indigo my-2">Ledger Folio</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 58)==1){ ?>
                                    <a href="rpt_trial_balance.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-danger card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-danger py-2">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-danger my-2">Trial Balance</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 61)==1){ ?>
                                    <a href="rpt_period_trial_balance.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-info card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-info py-2">
                                                        <i class="far fa-calendar-check"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-info my-2">Period Trial Balance</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 62)==1){ ?>
                                    <a href="rpt_balance_sheet.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-danger card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-danger py-2">
                                                        <i class="fas fa-balance-scale-left"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-danger my-2">Balance Sheet</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php }if(menucheck($menuprivilegearray, 63)==1){ ?>
                                    <a href="rpt_profit_and_loss.php" class="text-decoration-none">
                                        <div class="col mb-3">
                                            <div class="card shadow-none border-dark card-icon p-0 py-2">
                                                <div class="row no-gutters h-100">
                                                    <div class="col-auto card-icon-aside-new text-dark py-2">
                                                        <i class="fas fa-coins"></i>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body p-0 p-2 text-right">
                                                            <h6 class="text-dark my-2">P & L Sheet</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
</script>
<?php include "include/footer.php"; ?>
