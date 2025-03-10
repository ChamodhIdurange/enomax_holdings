<?php
$sessionusertype = $_SESSION['type'];

$sqlnotfication = "SELECT COUNT(`idtbl_customer_order`) AS `count` FROM `tbl_customer_order` WHERE (`confirm` = '0' OR `confirm` IS NULL) AND `status` IN (1, 2)";
$resultnotfication = $conn->query($sqlnotfication);

$sqlnotficationre = "SELECT COUNT(`idtbl_return`) AS `count` FROM `tbl_return` WHERE `acceptance_status` = '0'";
$resultnotficationre = $conn->query($sqlnotficationre);

$sqlusertype = "SELECT `type` FROM `tbl_user_type` WHERE `idtbl_user_type`='$sessionusertype' AND `status`=1";
$resultusertype = $conn->query($sqlusertype);
$rowusertype = $resultusertype->fetch_assoc();
?>
<style>
    .badge {
        background: red;
        color: white;

    }
</style>
<nav class="topnav navbar navbar-expand shadow navbar-light bg-laugfs" id="sidenavAccordion">
    <a class="navbar-brand d-none d-sm-block menu-logo" href="#">ENOMAX Holdings (PVT) LTD</a><button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 mr-lg-2" id="sidebarToggle" href="#"><i class="text-dark" data-feather="menu"></i></button>

    <ul class="navbar-nav align-items-center ml-auto">
    <div style="margin-right: 20px;">
             <?php 
                if ($resultnotficationre->num_rows > 0) {
                    $notficationresre = $resultnotficationre->fetch_assoc();
                    $notifys = $notficationresre['count'];
                    if ($notifys == 0) { ?>
                        <div class="badge"></div>
                    <?php } else { ?>
                        <div class="badge">+ <?php echo $notifys ?></div>
                    <?php } ?>
                    <a href="#" data-toggle="tooltip" data-placement="bottom" title="Return" ><i data-feather="corner-down-left" style="margin-top: 5px;"></i></a>
            <?php } ?> 
           
        </div>
        <div style="margin-right: 20px;">
            <?php
                if ($resultnotfication->num_rows > 0) {
                    $notficationres = $resultnotfication->fetch_assoc();
                    $notify = $notficationres['count'];
                    if ($notify == 0) { ?>
                        <div class="badge"></div>
                    <?php } else { ?>
                        <div class="badge">+ <?php echo $notify ?></div>
                    <?php } ?>
                    <a href="customerporder.php" data-toggle="tooltip" data-placement="bottom" title="Customer Porder"> <i class="fa fa-tasks"></i> </a>
            <?php } ?>
            
        </div>
        <li class="nav-item dropdown no-caret mr-3 dropdown-user">
            <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="far fa-user text-dark"></i></a>
            <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                <h6 class="dropdown-header d-flex align-items-center">
                    <img class="dropdown-user-img" src="<?php if ($_SESSION['image'] != '') {
                                                            echo $_SESSION['image'];
                                                        } else {
                                                            echo 'images/user.jpg';
                                                        } ?>" />
                    <div class="dropdown-user-details">
                        <div class="dropdown-user-details-name"><?php echo ucfirst($_SESSION['name']); ?></div>
                        <div class="dropdown-user-details-email"><?php echo $rowusertype['type']; ?></div>
                    </div>
                </h6>
                <div class="dropdown-divider"></div>
                <!--
                <a class="dropdown-item" href="#!">
                    <div class="dropdown-item-icon"><i data-feather="settings"></i></div>
                    Account
                </a>
-->
                <a class="dropdown-item" href="process/logoutprocess.php">
                    <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>