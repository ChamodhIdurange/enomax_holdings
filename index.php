<?php include "include/header.php"; ?>
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4">
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header justify-content-center"><img src="images/logo.png" class="img-fluid" alt=""></div>
                            <div class="card-body">
                                <form action="process/loginprocess.php" method="post" autocomplete="off">
                                    <div class="form-group"><label class="small mb-1" for="inputEmailAddress">Username</label><input class="form-control form-control-sm py-3" id="username" name="username" type="text" placeholder="Enter username" /></div>
                                    <div class="form-group"><label class="small mb-1" for="inputPassword">Password</label><input class="form-control form-control-sm py-3" id="password" name="password" type="password" placeholder="Enter password" /></div>
                                    <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0"><button type="submit" class="btn btn-outline-primary btn-sm ml-auto w-25">Login</button></div>
                                </form>
                            </div>
                            <div class="card-footer bg-laugfs">
                                <div class="row">
                                    <div class="col text-center"><img src="images/logo@2x.png" alt=""></div>
                                    <div class="col-md-12 small text-center text-white">Copyright &copy; ERav Technology 2021</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<?php include "include/footer.php"; ?>
