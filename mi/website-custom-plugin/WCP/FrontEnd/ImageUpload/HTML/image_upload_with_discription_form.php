<!--<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>-->
<script type="text/javascript" src="<?php echo plugins_url( 'website-custom-plugin/WCP/assets/js/jquery.min.js' ); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'website-custom-plugin/WCP/assets/css/bootstrap.min.css' ); ?>" >
<script type="text/javascript" src="<?php echo plugins_url( 'website-custom-plugin/WCP/assets/js/bootstrap.min.js' ); ?>"></script>
<script type="text/javascript" src="<?php echo plugins_url( 'website-custom-plugin/WCP/assets/js/jquery.blockUI.min.js' ); ?>"></script>


<div class="imageupload panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Add Product</h3>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <form id="frmUploadProduct" name="frmUploadProduct" action="" method="post" enctype="multipart/form-data" onsubmit="return false">
                <div style="margin-top: 15px; margin-left: 30px;">
                <input type='hidden' name='action' id='action' value='<?php echo 'WCP_ImageUpload_Controller::store_image_description'; ?>' />
                <div class="form-group row">
                    <label for="ImageFront">Llave cara A</label>
                    <span id="ImageFront_view"></span>
                    <input type="file" id="ImageFront" name="ImageFront" style='float:left;'>
                </div>

                <div class="form-group row">
                    <label for="ImageBack">Llave cara B</label>
                    <span id="ImageBack_view"></span>
                    <input type="file" id="ImageBack" name="ImageBack" style='float:left;'>
                    
                </div>

                <div class="form-group">
                    <label for="ImageFront">Descripción</label>
                    <textarea class="form-control" name="description" id="description" cols="2" rows="3" class="form-control"></textarea>
                </div>
                <p id="err_msg" style="display:none;">&nbsp;</p>
                <div>
					<input type="submit" value="Guardar" class="submit" />
                </div>
				<div>
					<a href="https://millaveroapp.com/app-v1/menu" class="atrasbtn">Atras</a>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>

<h4 id='loading' hidden >cargando...</h4>
<div id="message"></div>

<script type="text/javascript">
    $(document).ready(function (e) {

        var err_msg = $("#err_msg");
        $("#frmUploadProduct").on('submit', function (e) {
            e.preventDefault();
            $.blockUI();
            err_msg.html('Espere por favor...');
            var formData = new FormData($("#frmUploadProduct")[0]);
            
            $.ajax({
                //url: ajaxurl,
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                method: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData
            }).always(function (data) {

                var response = JSON.parse(data);
                if (response.status == 1) {

                    $('#frmUploadProduct')[0].reset();
                    err_msg.css('color', "green");
                    err_msg.html(response.message);
                    err_msg.show();
                    window.location.href = '<?php echo site_url('account-page'); ?>';
                } else {

                    err_msg.css('color', "red");
                    err_msg.html(response.error);
                    err_msg.show();
                    //alert(response.error);
                }
                $.unblockUI();

            });
        });
        
        $(function () {
            $("#ImageFront").change(function () {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = ImageFrontIsLoaded;
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
        
        $(function () {
            $("#ImageBack").change(function () {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = ImageBackIsLoaded;
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    });

function ImageFrontIsLoaded(e) {
    $("#ImageFront_view").html("<img src='' name='img_front' id='img_front' width='50px;'  />")
    $('#img_front').attr('src', e.target.result);
};

function ImageBackIsLoaded(e) {
    $("#ImageBack_view").html("<img src='' name='img_back' id='img_back' width='50px;' />")
    $('#img_back').attr('src', e.target.result);
};
</script>