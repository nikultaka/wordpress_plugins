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
	<div>
		<h3>Orders History</h3>
		<div>
			Below is a User Basic Detail
		</div>
		<hr style="background-color:#000000; height:2px;">
		<div style="padding-bottom:10px;">
			<div style="clear:both;"></div>
		</div>
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered dt-responsive" width="100%"  id="order_table">
			<thead>
			<tr>
				<th class="all">ID</th>
                                <th class="all">Service</th>
				<th class="all">User</th>
				<th class="all">Name</th>
				<th class="all">Sur Name</th>
				<th class="all">Email</th>
				<th class="all">Contact Number</th>
                                <th class="all">Address</th>
                                <th class="all">City</th>
                                <th class="all">Postal Code</th>
                                <th class="all">Status</th>
                                <th class="all">Created On</th>
                                <th class="all">Action</th>
			</tr>
			</thead>
			<tbody>
			</tbody>
			</table>
		</div>
	</div>
</div>

<?php include_once 'view_order.php'; ?>

<script>
jQuery(document).ready(function() {
    var $table = jQuery("#order_table").DataTable({
			"processing": true,
			"serverSide": true, 
			"pageLength": 5,
			"dom": 'lftrip<"clear">',
			
			"ajax": {
				type: "post",
				url : "<?php echo admin_url('admin-ajax.php'); ?>",
				data: function ( d ) {
					return jQuery.extend( {}, d, {
						"action":"WCP_BackEnd_Orders_Controller::get_orders",
					});
				}
			},
			"columns": [
				    { "data": "id" },
				    { "data": "service_name" },
				    { "data": "user_nicename" },
				    { "data": "first_name" },
				    { "data": "surname" },
				    { "data": "email" },
                                    { "data": "contact_no" },
                                    { "data": "address" },
                                    { "data": "city" },
                                    { "data": "postal_code" },
                                    { "data": "status" },
                                    { "data": "created_on" },
                                    { "data": "action" },
			],
			"paging": true,
                        columnDefs: [ 
                            { orderable: false, targets: [12] },
                        ],
		});
		
		
		function reload_table(){
			$table.ajax.reload();
		}
  	
});	

function view_order(id){
    if(id !='')
    {
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {"action": "WCP_BackEnd_Orders_Controller::get_order_details", id: id},
            success: function (data) {
                var result = JSON.parse(data);
                if (result.status == 1) {
                    
                    $("#view_firstname").html(result.order_details.first_name);
                    $("#view_surname").html(result.order_details.surname);
                    $("#view_email").html(result.order_details.email);
                    $("#view_contact_number").html(result.order_details.contact_no);
                    $("#view_address").html(result.order_details.address);
                    $("#view_city").html(result.order_details.city);
                    $("#view_postal_code").html(result.order_details.postal_code);
                    $("#view_description").html(result.order_details.description);
                    $("#view_status").html(result.order_details.payment_status);
                    $("#view_created_on").html(result.order_details.gm_created);
                    $("#view_product_list").html(result.order_details.product_string);
                    $("#view_usernicname").html(result.order_details.user_nicename);
                    $("#viewOrderModal").modal('show');
                }
            }
        });
    }
}

</script>
