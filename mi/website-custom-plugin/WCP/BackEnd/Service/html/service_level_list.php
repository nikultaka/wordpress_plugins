<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'website-custom-plugin/WCP/assets/css/bootstrap.min.css' ); ?>" >
<!-- Latest compiled and minified JavaScript -->
<script type="text/javascript" src="<?php echo plugins_url( 'website-custom-plugin/WCP/assets/js/bootstrap.min.js' ); ?>"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/css/bootstrapValidator.min.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>

<div class="flex_container" style="padding-top:20px;">
    <div class="col-sm-12">
        <div class="col-sm-6">
            <h3>Service List</h3>
        </div>
        <div class="col-sm-6">
            <input type="button" value="Add Service" name="btn_add_service_price" id="btn_add_service_price" style="margin-top: 15px;float:right;" class="btn btn-info btn-lg" onclick="add_service_price_btn()"  />
        </div>

        <hr style="background-color:#000000; height:2px;">
        <div style="padding-bottom:10px;">
            <div style="clear:both;"></div>
        </div>
        <div class="table-responsive">
            <table id='service-table' class="table table-bordered">
                <thead>
                    <tr>
                        <th class="all">Service</th>
                        <th class="all">Price</th>
                        <th class="all">Date</th>
                        <th class="all">Status</th>
                        <th class="all">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
include_once dirname(__FILE__) . '/add_service_price.php';
?>
<style>
    .modal-dialog {
        margin: 100px auto 30px!important;
        width: 515px!important;
    }
</style>
<script>
    $(document).ready(function () {
        $ = jQuery;
        var is_completed_request = false;
        var is_processing_request = false;

        var number_updates = 0;
        var unique_query_id = "";

        var number_inserts = 0;

        var page_number = 1;

        $("#service_id").on("change", function () {
            var service_id = $(this).val();
            if (typeof service_id != 'undefined' && service_id != '')
            {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: {"action": "WCP_BackEnd_Service_Price_Controller::get_service_level_dropdown", service_id: service_id},
                    success: function (data) {
                        var result = JSON.parse(data);
                        if (result.status == 1)
                        {
                            $("#div_service_level").html(result.service_level_dropdown);
                        }
                    }
                });
            }
        });
        
        reload_table();
    });
    function reload_table() {
            $('#service-table').dataTable({
                    "paging": true,
                    "pageLength": 10,
                    "bProcessing": true,
                    "serverSide": true,
                     "bDestroy": true,
                    "ajax": {
                        type: "post",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {"action": "WCP_BackEnd_Service_Controller::get_data"}

                    },
                    "aoColumns": [
                        {mData: 'Service'},
                        {mData: 'Price'},
                        {mData: 'Date'},
                        {mData: 'Status'},
                        {mData: 'action'}
                    ],
                    "order": [[ 0, "desc" ]],        

                    "columnDefs": [{
                        "targets": [4],
                        "orderable": false
                    }]
            });
        }
    function service_record_delete(id) {
    if (confirm("Are you sure?")) {
        var service_id = id;

        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {"action": "WCP_BackEnd_Service_Controller::delete_service_price_record", service_id: service_id},
            success: function (data) {
                var result = JSON.parse(data)
                if (result.status == 1) {
                    reload_table();
                }
            }
        });
    }
     return false;
    }
    function service_record_update(id) {
     $('#frmAddService #id').val(id);
     $("#frmAddService #btnAddService").val("Update");
     $(".modal-title").html("Edit Service");
     
    $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {"action": "WCP_BackEnd_Service_Controller::get_update_service_product", id: id},
            success: function (data) {
                var result = JSON.parse(data);
                if (result.status == 1) {
                    $("#frmAddService #service_name").val(result.serice_price_details.service_name);
                    $("#frmAddService #price").val(result.serice_price_details.price);
                    $("#frmAddService #service_Status").val(result.serice_price_details.status);
                    
                    $('#AddSericePriceModal').modal('show');
                    
                }
            }
        });
       
    }
    
    function add_service_price_btn(){
        //clearvalue();
        $("#frmAddService #btnAddService").val("Save");
        $(".modal-title").html("Add Service");
        $('#frmAddService #id').val(null);
        $("#saveservice").show();
        $("#Updateservice").hide();
        $('#AddSericePriceModal').modal('show');
    }
</script>
