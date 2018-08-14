<form id="Add_service_price_form" onsubmit="return false;" >
    <div class="modal fade" id="TimelineModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <input type="hidden" name="ServiceId" id="ServiceId" value=""/>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Update Timeline</h4>
                </div>

                <div class="modal-body">
                    <div class="row" style="padding-bottom: 10px">
                        <div class="col-sm-4">
                            Days
                        </div>
                        <div class="col-sm-8" id="div_service_level">
                            <input type="text" id="days" name="days" class="form-control" value="" >
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
		    <input type="hidden" id="timeline_id" name="timeline_id" value="">
                    <button type="button" onclick="timeline_update()" class="btn btn-default" data-dismiss="modal">Update</button>
                    <button type="button" class="btn btn-default" onclick="clearvalue()" data-dismiss="modal">Close</button>
                </div>

                </form>
                <script>
                    $(document).ready(function() {
                        $("#service_type").change(function() {
                            if(this.value == '2')  {
                                $("#div_zip_code").show();
                            } else {
                                $("#div_zip_code").hide();
                            }
                        });
                    });
                    
                    function addserviceprice() {
                        var service_id = $('#service_id').val();
                        var service_level_id = $('#service_level_id').val();
                        var price = $('#price').val();
                        var warranty_price = $("#warranty_price").val();
                        var zipcode = $('#zipcode').val();
                        var status = $('#service_Status').val();
                        var service_type = $("#service_type").val();
                        
                        
                        var errCount = 0;
                        if(service_id == "") {
                            $("#service_id").parent().addClass('has-error');
                            errCount++;
                        } else {
                            $("#service_id").parent().removeClass('has-error');
                        } 
                        if(service_level_id == "") {
                            $("#service_level_id").parent().addClass('has-error');
                            errCount++;
                        } else {
                            $("#service_level_id").parent().removeClass('has-error');
                        }
                        if(price == "") {
                            $("#price").parent().addClass('has-error');
                            errCount++;
                        } else {
                            $("#price").parent().removeClass('has-error');
                        }
                        if(warranty_price == "") {
                            $("#warranty_price").parent().addClass('has-error');
                            errCount++;
                        } else {
                            $("#warranty_price").parent().removeClass('has-error');
                        }
                         
                        
                        if(service_type == '2') {
                            if(zipcode == "") {
                                $("#zipcode").parent().addClass('has-error');
                                errCount++;
                            } else {
                                $("#zipcode").parent().removeClass('has-error');
                            }
                        }
                        
                        if(errCount == '0') {
                            $.ajax({
                                type: 'POST',
                                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                data: {"action": "WCP_BackEnd_Service_Price_Controller::add_service_price", service_id: service_id, service_level_id: service_level_id, price: price,warranty_price:warranty_price ,zipcode: zipcode, status: status},
                                success: function (data) {
                                    if (data == 'ok') {
                                        $("#AddSericePriceModal").modal('hide');
                                        clearvalue();
                                        reload_table();
                                    }
                                }
                            });
                        } else {
                            return false;
                        }
                    }
                    
                    function updateserviceprice() {

                        var id = $('#ServiceId').val();
                        var service_id = $('#service_id').val();
                        var service_level_id = $('#service_level_id').val();
                        var price = $('#price').val();
                        var warranty_price = $("#warranty_price").val();
                        var zipcode = $('#zipcode').val();
                        var status = $('#service_Status').val();
                        var service_type = $("#service_type").val();
                        
                        var errCount = 0;
                        if(service_id == "") {
                            $("#service_id").parent().addClass('has-error');
                            errCount++;
                        } else {
                            $("#service_id").parent().removeClass('has-error');
                        } 
                        if(service_level_id == "") {
                            $("#service_level_id").parent().addClass('has-error');
                            errCount++;
                        } else {
                            $("#service_level_id").parent().removeClass('has-error');
                        }
                        if(price == "") {
                            $("#price").parent().addClass('has-error');
                            errCount++;
                        } else {
                            $("#price").parent().removeClass('has-error');
                        }
                        if(warranty_price == "") {
                            $("#warranty_price").parent().addClass('has-error');
                            errCount++;
                        } else {
                            $("#warranty_price").parent().removeClass('has-error');
                        }
                         
                        
                        if(service_type == '2') {
                            if(zipcode == "") {
                                $("#zipcode").parent().addClass('has-error');
                                errCount++;
                            } else {
                                $("#zipcode").parent().removeClass('has-error');
                            }
                        }
                        
                        if(errCount == '0') {
                            $.ajax({
                                type: 'POST',
                                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                data: {"action": "WCP_BackEnd_Service_Price_Controller::update_service_list", id: id, service_id: service_id, service_level_id: service_level_id, price: price,warranty_price:warranty_price,zipcode: zipcode, status: status},
                                success: function (data) {
                                    if (data == 'ok') {
                                        reload_table();
                                        clearvalue();
                                    }
                                }
                            });
                        }
                    }
                    function clearvalue() {
                        var price = $('#price').val('');
                        var warranty_price = $("#warranty_price").val('');
                        var zipcode = $('#zipcode').val('');
                        document.getElementById("Add_service_price_form").reset();
                    }
                </script>
            </div>
        </div>
    </div>