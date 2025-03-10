<?php
include "include/header.php";
$sql = "SELECT *, `tbl_catalog_category`.`category`, `tbl_catalog`.`status` FROM `tbl_catalog` LEFT JOIN `tbl_catalog_category` ON(`tbl_catalog_category`.`idtbl_catalog_category` = `tbl_catalog`.`tbl_catalog_category_idtbl_catalog_category`) WHERE `tbl_catalog`.`status`IN(1,2)";

$result = $conn->query($sql);

$productarray = array();
$sqlproduct = "SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct = $conn->query($sqlproduct);
while ($rowproduct = $resultproduct->fetch_assoc()) {
    $obj = new stdClass();
    $obj->productID = $rowproduct['idtbl_product'];
    $obj->product = $rowproduct['product_name'];

    array_push($productarray, $obj);
}

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
                            <div class="page-header-icon"><i class="fa fa-puzzle-piece" aria-hidden="true"></i></div>
                            <span>Product Catalog Display</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form method="post" id="createorderform" autocomplete="off"
                                    enctype="multipart/form-data">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Catalog Category*</label>
                                        <select class="form-control form-control-sm select2" style="width: 100%;"
                                            name="catalogcat" id="catalogcat" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Product*</label>
                                        <select class="form-control form-control-sm select2" style="width: 100%;"
                                            name="product" id="product" required>
                                            <option value="">Select</option>
                                        </select>

                                    </div>
                                    <!-- <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Product*</label>
                                        <select class="form-control form-control-sm" name="product" id="product" required>
                                            <option value="">Select</option>
                                            <?php foreach ($productarray as $rowproductlist) { ?>
                                                <option value="<?php echo $rowproductlist->productID ?>"><?php echo $rowproductlist->product ?></option>
                                            <?php } ?>
                                        </select>
                                    </div> -->
                                    <!-- <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Uom</label>
                                        <select class="form-control form-control-sm" name="uom" id="uom" required>
                                            <option value="">Select</option>

                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Type*</label>
                                    </div>
                                    <div class="form-group mb-1">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type" id="type1" value="1">
                                            <label class="form-check-label" for="type1">Single</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type" id="type2" value="2">
                                            <label class="form-check-label" for="type2">Group</label>
                                        </div>
                                    </div> -->

                                    <div class="form-group mt-3">
                                        <label class="small font-weight-bold text-dark">Product Image</label>
                                        <input type="file" name="productimage[]" id="productimage"
                                            class="form-control form-control-sm" style="padding-bottom:32px;" multiple>
                                        <small id="" class="form-text text-danger">Image size 800X800 Pixel</small>
                                    </div>
                                    <div class="form-group mt-3">
                                        <button id="formsubmit"
                                            class="btn btn-outline-primary btn-sm w-50 fa-pull-right"
                                            <?php if ($addcheck == 0) {
                                                                                                                                echo 'disabled';
                                                                                                                            } ?>><i class="far fa-save"></i>&nbsp;Add</button>

                                    </div>
                                    <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-9">
                                <table class="table table-bordered table-striped table-sm nowrap" id="tableorder">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Category</th>
                                            <th>Product</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mt-2">
                                            <button type="button" id="btncreateorder"
                                                class="btn btn-outline-primary btn-sm fa-pull-right"
                                                <?php if ($addcheck == 0) {
                                                                                                                                                echo 'disabled';
                                                                                                                                            } ?>><i
                                                    class="fas fa-save"></i>&nbsp;Create
                                                Catalog</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Category</th>
                                            <!-- <th>Type</th> -->

                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['idtbl_catalog'] ?></td>
                                            <td><?php echo $row['category'] ?></td>
                                            <td class="text-right">
                                                <button class="btn btn-outline-secondary btn-sm btnlistview"
                                                    id="<?php echo $row['idtbl_catalog'] ?>" data-toggle="tooltip"
                                                    data-placement="bottom" title=""
                                                    data-original-title="View list image"><i class="fa fa-camera"
                                                        aria-hidden="true"></i></button>
                                                <?php if($editcheck==1){ ?>
                                                <button
                                                    class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>"
                                                    id="<?php echo $row['idtbl_catalog'] ?>"><i
                                                        data-feather="edit-2"></i></button>
                                                <?php } if($statuscheck==1 && $row['status']==1){ ?>
                                                <button
                                                    data-url="process/statuscatalog.php?record=<?php echo $row['idtbl_catalog'] ?>&type=2"
                                                    data-actiontype="2"
                                                    class="btn btn-outline-success btn-sm btntableaction"><i
                                                        data-feather="check"></i></button>
                                                <?php } else if($statuscheck==1 && $row['status']==2){ ?>
                                                <button
                                                    data-url="process/statuscatalog.php?record=<?php echo $row['idtbl_catalog'] ?>&type=1"
                                                    data-actiontype="1"
                                                    class="btn btn-outline-warning btn-sm btntableaction"><i
                                                        data-feather="x-square"></i></button>
                                                <?php } if($deletecheck==1){ ?>
                                                <button
                                                    data-url="process/statuscatalog.php?record=<?php echo $row['idtbl_catalog'] ?>&type=3"
                                                    data-actiontype="3"
                                                    class="btn btn-outline-danger btn-sm btntableaction"><i
                                                        data-feather="trash-2"></i></button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php }
                                        } ?>
                                    </tbody>
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
<!-- Modal Image View -->
<div class="modal fade" id="modalimageview" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close btnclose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row" id="imagelist">

                </div>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    var prodCount = 0;
    $(document).ready(function () {
        $('#dataTable').DataTable();
        $("#product").select2({
            // dropdownParent: $('#addNewInquiryModal'),
            // placeholder: 'Select supplier',
            ajax: {
                url: "getprocess/getcatalogproductname.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
        $("#catalogcat").select2({
            ajax: {
                url: "getprocess/getproductcatalogforselect2.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, 
                    };
                },
                processResults: function (response) { //console.log(response)
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $('#dataTable tbody').on('click', '.btnEdit', async function () {
            var r = await Otherconfirmation("You want to edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getcatalogedit.php',
                    success: function (result) {
                        var obj = JSON.parse(result);
                        // var uomname;

                        // if (obj.uom == 1) {
                        //     uomname = "PCS";
                        // } else if (obj.uom == 2) {
                        //     uomname = "Packet";
                        // } else if (obj.uom == 3) {
                        //     uomname = "Box";
                        // } else if (obj.uom == 4) {
                        //     uomname = "Dozen";
                        // } else if (obj.uom == 5) {
                        //     uomname = "Kilogram";
                        // } else if (obj.uom == 6) {
                        //     uomname = "Bottle";
                        // } else if (obj.uom == 7) {
                        //     uomname = "Roll";
                        // } else if (obj.uom == 8) {
                        //     uomname = "Tin";
                        // } else if (obj.uom == 79) {
                        //     uomname = "Berall";
                        // }


                        $('#recordID').val(obj.id);
                        // $('#uom').empty();
                        // $('#uom').append($('<option>', {
                        //     value: obj.uom,
                        //     text: uomname
                        // }));

                        // if (obj.group_type == 1) {
                        //     $('#type1').prop('checked', true);
                        // } else if (obj.group_type == 2) {
                        //     $('#type2').prop('checked', true);
                        // }
                        $('#catalogcat').val(obj
                            .tbl_catalog_category_idtbl_catalog_category);
                        //  $('#product').val(obj.tbl_product_idtbl_product);
                        $('#recordOption').val('2');
                        $('#btncreateorder').html(
                            '<i class="far fa-save"></i>&nbsp;Update');

                        $.ajax({
                            type: "POST",
                            data: {
                                recordID: id
                            },
                            url: 'getprocess/procatalogtableedit.php',
                            success: function (data) {
                                records = JSON.parse(data);
                                $('#tableorder > tbody').empty();
                                records.forEach(function (obj) {

                                    var catalogcattext = obj
                                        .category;
                                    var product = obj.product_name;
                                    var productID = obj.product_id;
                                    var catalogcat = obj
                                        .tbl_catalog_category_idtbl_catalog_category;

                                    prodCount++;


                                    $('#tableorder > tbody:last')
                                        .append(
                                            '<tr class="pointer"><td>' +
                                            prodCount +
                                            '</td><td class="d-none">' +
                                            catalogcat +
                                            '</td><td class="text-center">' +
                                            catalogcattext +
                                            '</td><td class="text-center">' +
                                            product +
                                            '</td><td class="d-none">' +
                                            productID +
                                            '</td></tr>');
                                });
                                $('#product').val('');
                                $('#product').text('');
                            }


                        });
                    }
                });

            }
        });

        // $('#productimagebtn').change(function() {
        //     var productImages = $('#productimage')[0].files;

        //     for (var i = 0; i < productImages.length; i++) {
        //         console.log(productImages[i].name);
        //     }

        //     var formData = new FormData();

        //     for (var i = 0; i < productImages.length; i++) {
        //         formData.append('productimage[]', productImages[i]);
        //     }

        //     formData.append('tableData', jsonObj);
        //     formData.append('catalogcat', catalogcat);
        //     formData.append('recordOption', recordOption);
        //     formData.append('recordID', recordID);

        //     $.ajax({
        //         type: "POST",
        //         url: 'process/imageuploadcatalog.php',
        //         data: formData,
        //         processData: false,
        //         contentType: false,
        //         success: function(result) {

        //         }
        //     });
        // });

        // $('#product').on('change', function() {

        //     var recordID = $(this).val();

        //     // alert(recordID);

        //     procategory(recordID, '');

        // });

        $('#dataTable tbody').on('click', '.btnlistview', function () {
            var productID = $(this).attr('id');
            // alert(productID);
            loadlistimages(productID);
            $('#modalimageview').modal('show');

        });
        $('#modalimageview').on('click', '.btnclose', function () {
            window.location.reload();
        });
        $("#formsubmit").click(function () {
            if (!$("#createorderform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submitBtn").click();
            } else {
                prodCount++;
                // alert('click');
                var catalogcat = $('#catalogcat').val();
                var product = $('#product').val();
                var catalogcattext = $("#catalogcat option:selected").text();
                var productcattext = $("#product option:selected").text();
                //  var repname = $("#repname2 option:selected").text();
                var product = $('#product').val();
                // var orderdate = $('#orderdate1').val();

                $('#tableorder > tbody:last').append('<tr class="pointer"><td>' + prodCount +
                    '</td><td class="d-none">' + catalogcat +
                    '</td><td class="text-center">' + catalogcattext +
                    '</td><td class="text-center">' + productcattext +
                    '</td><td class="d-none">' + product +
                    '</td></tr>');

                $('#product').val('');
                $('#product').text('');


            }
        });

        $('#tableorder').on('click', 'tr', function () {
            var r = confirm("Are you sure, You want to remove this product ? ");
            if (r == true) {
                $(this).closest('tr').remove();

            }
        });

        $('#btncreateorder').click(function () {
            var tbody = $("#tableorder tbody");
            var formData = new FormData();

            var productImages = $('#productimage')[0].files;

            for (var i = 0; i < productImages.length; i++) {
                formData.append('productimage[]', productImages[i]);
            }

            if (tbody.children().length > 0) {
                jsonObj = [];
                $("#tableorder tbody tr").each(function () {
                    item = {}
                    $(this).find('td').each(function (col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObj.push(item);
                });
                jsonObj = JSON.stringify(jsonObj);

                console.log(jsonObj);

                var catalogcat = $('#catalogcat').val();
                var recordOption = $('#recordOption').val();
                var recordID = $('#recordID').val();

                formData.append('tableData', jsonObj);
                formData.append('catalogcat', catalogcat);
                formData.append('recordOption', recordOption);
                formData.append('recordID', recordID);

                $.ajax({
                    type: "POST",
                    url: 'process/productcatalogprocess.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (result) {
                        action(result);
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    }
                });
            }
        });

    });

    // function procategory(recordID, value) {
    //     //  alert(value);
    //     $.ajax({
    //         type: "POST",
    //         data: {
    //             recordID: recordID
    //         },
    //         url: 'getprocess/getuomcatalog.php',
    //         success: function(result) {

    //             var product = JSON.parse(result);
    //             //  console.log(product.id);

    //             $('#uom').empty();

    //             $('#uom').append('<option value="' + product.id + '">' + product.uom + '</option>');
    //         }
    //     });
    // }

    function loadlistimages(productID) {
        $('#imagelist').addClass('text-center');
        $('#imagelist').html('<img src="images/spinner.gif" class="img-fluid">');

        $.ajax({
            type: "POST",
            data: {
                productID: productID,
            },
            url: 'getprocess/getproductlistimages.php',
            success: function (result) { //alert(result);
                $('#imagelist').removeClass('text-center');
                $('#imagelist').html(result);
                loadlistimages(productID);
            }
        });
    }

    function optionimages(productID) {
        $('#productimagetable tbody').on('click', '.btnremoveimage', function () {
            var imageID = $(this).attr('id');
            var r = confirm("Are you sure, You want to Delete this ? ");
            if (r == true) {
                $.ajax({
                    type: "POST",
                    data: {
                        imageID: imageID,

                    },
                    url: 'process/statusproductimages.php',
                    success: function (result) { //alert(result);
                        $('#imagelist').html(result);
                        loadlistimages(productID);
                    }
                });
            }
        });
    }

    function action(data) { //alert(data);
        var obj = JSON.parse(data);
        $.notify({
            // options
            icon: obj.icon,
            title: obj.title,
            message: obj.message,
            url: obj.url,
            target: obj.target
        }, {
            // settings
            element: 'body',
            position: null,
            type: obj.type,
            allow_dismiss: true,
            newest_on_top: false,
            showProgressbar: false,
            placement: {
                from: "top",
                align: "center"
            },
            offset: 100,
            spacing: 10,
            z_index: 1031,
            delay: 5000,
            timer: 1000,
            url_target: '_blank',
            mouse_over: null,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            onShow: null,
            onShown: null,
            onClose: null,
            onClosed: null,
            icon_type: 'class',
            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title">{1}</span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'
        });
    }
</script>
<?php include "include/footer.php"; ?>