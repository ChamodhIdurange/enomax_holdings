<?php
include "connection/db.php";
include "include/header.php";
include "include/topnavbar.php";
?>

<style>
.kpi-row {
    margin-top: 15px;
    margin-bottom: 10px;
}

.kpi-card {
    border-radius: 10px;
    padding: 0;
    border: none;
    background: #ffffff;
    box-shadow: 0 3px 10px rgba(15, 23, 42, 0.06);
    display: flex;
    align-items: stretch;
}

.kpi-inner {
    padding: 18px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.kpi-label {
    font-size: 13px;
    font-weight: 500;
    letter-spacing: .03em;
    text-transform: uppercase;
    color: #6b7280;
    margin-bottom: 6px;
}

.kpi-value {
    font-size: 30px;
    font-weight: 700;
    color: #111827;
    line-height: 1.2;
}

.kpi-unit {
    font-size: 16px;
    font-weight: 600;
    margin-right: 4px;
}

.kpi-icon-wrap {
    text-align: right;
}

.kpi-icon-wrap i {
    opacity: 0.9;
}

.kpi-sub {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 6px;
}

.kpi-border-blue {
    border-top: 3px solid #1f77b4;
}

.kpi-border-red {
    border-top: 3px solid #e74c3c;
}

.kpi-border-green {
    border-top: 3px solid #22c55e;
}

.kpi-border-purple {
    border-top: 3px solid #8b5cf6;
}

@media (max-width: 991.98px) {
    .kpi-card {
        margin-bottom: 10px;
    }
    .kpi-value {
        font-size: 24px;
    }
}

</style>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <!-- Page Header -->
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title font-weight-light">
                            <div class="page-header-icon"><i class="fas fa-chart-line"></i></div>
                            <span>Dashboard</span>
                        </h1>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="container-fluid mt-3">

                <!-- TOP KPI CARDS -->
                <div class="row kpi-row">
                    <div class="col-lg-3 col-md-6 col-sm-12 d-flex">
                        <div class="card kpi-card kpi-border-blue flex-fill">
                            <div class="kpi-inner">
                                <div>
                                    <div class="kpi-label">Total Accounts Receivable</div>
                                    <div class="kpi-value">
                                        <span class="kpi-unit">Rs.</span>
                                        <span id="kpi_receivable">0</span>
                                    </div>
                                </div>
                                <div class="kpi-icon-wrap">
                                    <i class="fas fa-file-invoice-dollar fa-2x" style="color:#1f77b4"></i>
                                    <div class="kpi-sub">Outstanding</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-12 d-flex">
                        <div class="card kpi-card kpi-border-red flex-fill">
                            <div class="kpi-inner">
                                <div>
                                    <div class="kpi-label">Total Accounts Payable</div>
                                    <div class="kpi-value">
                                        <span class="kpi-unit">Rs.</span>
                                        <span id="kpi_payable">0</span>
                                    </div>
                                </div>
                                <div class="kpi-icon-wrap">
                                    <i class="fas fa-hand-holding-usd fa-2x" style="color:#e74c3c"></i>
                                    <div class="kpi-sub">Payables</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-12 d-flex">
                        <div class="card kpi-card kpi-border-green flex-fill">
                            <div class="kpi-inner">
                                <div>
                                    <div class="kpi-label">Equity</div>
                                    <div class="kpi-value">
                                        <span class="kpi-unit">Rs.</span>
                                        <span id="kpi_equity">0</span>
                                    </div>
                                </div>
                                <div class="kpi-icon-wrap">
                                    <i class="fas fa-balance-scale fa-2x" style="color:#2ecc71"></i>
                                    <div class="kpi-sub">Solvency</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-12 d-flex">
                        <div class="card kpi-card kpi-border-purple flex-fill">
                            <div class="kpi-inner">
                                <div>
                                    <div class="kpi-label">Debt</div>
                                    <div class="kpi-value">
                                        <span class="kpi-unit">Rs.</span>
                                        <span id="kpi_debteq">0</span>
                                    </div>
                                </div>
                                <div class="kpi-icon-wrap">
                                    <i class="fas fa-file-contract fa-2x" style="color:#8e44ad"></i>
                                    <div class="kpi-sub">Leverage</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <br/>

                <div class="row">
                    <!-- Customer Sales Chart -->
                    <div class="col-lg-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header e d-flex justify-content-between align-items-center">
                                <span>Customer Sales Report</span>
                                <div class="d-flex align-items-center">
                                    <input type="date" id="validfrom" class="form-control form-control-sm mr-2">
                                    <input type="date" id="validto" class="form-control form-control-sm mr-2">
                                    <select id="periodType" class="form-control form-control-sm">
                                        <option value="week">Week Wise</option>
                                        <option value="month">Month Wise</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="salesChart" height="180"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Rep Wise Sales Chart -->
                    <div class="col-lg-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header e d-flex justify-content-between align-items-center">
                                <span>Rep Wise Sales Report</span>
                                <div class="d-flex align-items-center">
                                    <select id="repList" class="form-control form-control-sm mr-2" multiple></select>
                                    <input type="date" id="repFrom" class="form-control form-control-sm mr-2">
                                    <input type="date" id="repTo" class="form-control form-control-sm mr-2">
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="repSalesChart" height="150"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Profit Analysis Report (Invoice)</span>
                                <div class="d-flex align-items-center">
                                    <input type="date" id="profitFrom" class="form-control form-control-sm mr-2">
                                    <input type="date" id="profitTo" class="form-control form-control-sm mr-2">
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="profitChart" height="100"></canvas>
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>

let salesChart, repSalesChart, purchaseOrderChart, profitChart;

function loadKPIData() {
    $.ajax({
        url: 'mobile_api/getkpidata.php',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            $('#kpi_receivable').text('Rs. ' + Number(data.sales_today).toLocaleString());
            $('#kpi_payable').text('Rs. ' + Number(data.profit_item).toLocaleString());
            $('#kpi_equity').text('Rs. ' + Number(data.purchases_month).toLocaleString());
            $('#kpi_debteq').text('Rs. ' + Number(data.profit_month).toLocaleString());
        },
        error: function() {
            console.log("Failed to load KPI data");
        }
    });
}



function loadRepList(callback) {
    const fromdate = $("#repFrom").val() || new Date().toISOString().split('T')[0];
    const todate   = $("#repTo").val() || new Date().toISOString().split('T')[0];

    $.post("mobile_api/getreplist.php", { fromdate: fromdate, todate: todate }, function(data) {
        const $repList = $("#repList");
        $repList.html(data);

        $repList.select2({
            placeholder: "Select up to 5 Sales Reps",
            maximumSelectionLength: 5,
            width: '200px'
        });

        const options = $repList.find("option");
        if (options.length > 0) {
            options.slice(0, 3).prop("selected", true);
            $repList.trigger("change");
        }

        if (typeof callback === "function") callback();
    });
}


function loadSalesChart() {
    let validfrom = $("#validfrom").val();
    let validto   = $("#validto").val();
    let type      = $("#periodType").val();

    $.post("getprocess/getcustomersalereportgraph.php", {
        validfrom: validfrom,
        validto: validto,
        type: type
    }, function(res) {
        let labels = res.map(d => d.period);
        let values = res.map(d => d.total_sales);

        if (salesChart) salesChart.destroy();

        salesChart = new Chart(document.getElementById("salesChart"), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: "Sales",
                    data: values,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return 'Rs. ' + Number(value).toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }, "json");
}

function loadRepSalesChart() {
    let fromdate = $("#repFrom").val();
    let todate   = $("#repTo").val();
    let replist  = $("#repList").val();
    if (!Array.isArray(replist)) replist = [replist];

    $.post("getprocess/getrepsalereportgraph.php", {
        fromdate: fromdate,
        todate: todate,
        replist: replist
    }, function(res) {
        let filtered = res.filter(r => parseFloat(r.sales) > 0 || parseFloat(r.approved_sales) > 0 || parseFloat(r.net_sale) > 0);
        if (filtered.length === 0) {
            if (repSalesChart) repSalesChart.destroy();
            return;
        }
        let labels = filtered.map(r => r.name);
        let sales  = filtered.map(r => r.sales);
        let approvedSales = filtered.map(r => r.approved_sales);
        let netSales = filtered.map(r => r.net_sale);

        if (repSalesChart) repSalesChart.destroy();

        repSalesChart = new Chart(document.getElementById("repSalesChart"), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Sales', data: sales, backgroundColor: 'rgba(54, 162, 235, 0.6)' },
                    { label: 'Approved Sales', data: approvedSales, backgroundColor: 'rgba(75, 192, 192, 0.6)' },
                    { label: 'Net Sales', data: netSales, backgroundColor: 'rgba(255, 159, 64, 0.6)' }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: {
                        ticks: { callback: v => 'Rs. ' + Number(v).toLocaleString() }
                    }
                }
            }
        });
    }, "json");
}

function loadPurchaseOrderChart() {
    let from = $("#poFrom").val();
    let to   = $("#poTo").val();

    $.post("getprocess/getpurchaseordergraph.php", { validfrom: from, validto: to }, function(res) {
        let data = (typeof res === "string") ? JSON.parse(res) : res;
        if (!Array.isArray(data) || data.length === 0) return;

        let suppliers = data.map(r => r.supplier);
        let totals    = data.map(r => parseFloat(r.total) || 0);

        if (purchaseOrderChart) purchaseOrderChart.destroy();

        purchaseOrderChart = new Chart(document.getElementById("purchaseOrderChart"), {
            type: 'bar',
            data: {
                labels: suppliers,
                datasets: [{
                    label: 'Net Total',
                    data: totals,
                    backgroundColor: 'rgba(255, 159, 64, 0.6)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => 'Rs. ' + Number(v).toLocaleString() }
                    }
                }
            }
        });
    }, "json");
}

function loadProfitChart() {
    let fromdate = $("#profitFrom").val();
    let todate   = $("#profitTo").val();

    $.post("getprocess/getprofitreportgraph.php", {
        fromdate: fromdate,
        todate: todate
    }, function(res) {
        if (profitChart) profitChart.destroy();

        let labels = res.map(r => r.date);
        let totalSales = res.map(r => r.sale);
        let netSales = res.map(r => r.net_sale);
        let costs = res.map(r => r.cost);
        let profits = res.map(r => r.profit);
        let profitWithDiscounts = res.map(r => r.profit_with_discount);

        profitChart = new Chart(document.getElementById("profitChart"), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    { label: "Total Sales", data: totalSales, borderColor: 'blue', backgroundColor: 'rgba(0,0,255,0.1)', fill: false },
                    { label: "Net Sales", data: netSales, borderColor: 'green', backgroundColor: 'rgba(0,128,0,0.1)', fill: false },
                    { label: "Cost", data: costs, borderColor: 'orange', backgroundColor: 'rgba(255,165,0,0.1)', fill: false },
                    { label: "Profit", data: profits, borderColor: 'red', backgroundColor: 'rgba(255,0,0,0.1)', fill: false },
                    { label: "Profit w/ Discounts", data: profitWithDiscounts, borderColor: 'purple', backgroundColor: 'rgba(128,0,128,0.1)', fill: false }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: { ticks: { callback: v => 'Rs. ' + Number(v).toLocaleString() } }
                }
            }
        });
    }, "json");
}

$(document).ready(function() {
    let today = new Date();
    let toDate = today.toISOString().split('T')[0];
    $("#validto, #repTo, #poTo, #profitTo").val(toDate);

    let from1 = new Date(); from1.setMonth(from1.getMonth() - 3);
    $("#validfrom").val(from1.toISOString().split('T')[0]);

    let from2 = new Date(); from2.setMonth(from2.getMonth() - 1);
    $("#repFrom").val(from2.toISOString().split('T')[0]);

    let from3 = new Date(); from3.setMonth(from3.getMonth() - 2);
    $("#poFrom, #profitFrom").val(from3.toISOString().split('T')[0]);

    loadKPIData();
    loadSalesChart();
    loadPurchaseOrderChart();
    loadProfitChart();

    // Bind changes
    $("#validfrom, #validto, #periodType").on("change input", loadSalesChart);
    $("#repFrom, #repTo, #repList").on("change input", loadRepSalesChart);
    $("#poFrom, #poTo").on("change input", loadPurchaseOrderChart);
    $("#profitFrom, #profitTo").on("change input", loadProfitChart);

    // Load reps once, then draw rep chart
    loadRepList(() => loadRepSalesChart());
});


</script>

<?php include "include/footer.php"; ?>
