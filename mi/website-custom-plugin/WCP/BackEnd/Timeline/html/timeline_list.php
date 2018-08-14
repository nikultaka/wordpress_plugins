<!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css "-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>

<div class="flex_container" style="padding-top:20px;">
    <div class="col-sm-12">
        <div class="col-sm-6">
            <h3>Timeline List</h3>
        </div>
        <hr style="background-color:#000000; height:2px;">
        <!--button type="button" class="btn btn-info btn_csv" id="btn_csv">Export CSV</button>
        <button type="button" style="display:none;" class="btn btn-info btn_del" id="btn_del">Delete</button>
        <a id="exportdata" style="display:none;">Download CSV</a-->
        <div style="padding-bottom:10px;">
            <div style="clear:both;"></div>
        </div>
        <div class="table-responsive">
            <table id='timeline-table' class="table table-bordered">
                <thead>
                    <tr>
                        <th class="all">Service</th>
                        <th class="all">Days</th>
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
    include_once dirname(__FILE__) . '/add_timeline.php';
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
        reload_table();
    });
    function reload_table() {
	$('#timeline-table').dataTable({
		"paging": true,
		"pageLength": 10,
		"bProcessing": true,
		"serverSide": true,
		 "bDestroy": true,
		"ajax": {
		    type: "post",
		    url: "<?php echo admin_url('admin-ajax.php'); ?>",
		    data: {"action": "WCP_BackEnd_Timeline_Controller::get_data"}

		},
		"aoColumns": [
		    {mData: 'Service'},
		    {mData: 'Days'},
		    {mData: 'action'}
		]
	});
    }
    function get_data(id,days) {
	$('#TimelineModal').modal('show');
	$("#days").val(days);
	$("#timeline_id").val(id);
    }
    function timeline_update() {
	var id = $("#timeline_id").val();
	var days = $("#days").val();
	$.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {"action": "WCP_BackEnd_Timeline_Controller::update_timeline_list", id: id,days:days},
            success: function (data) {
                reload_table();
            }
        });  
    }
</script>
