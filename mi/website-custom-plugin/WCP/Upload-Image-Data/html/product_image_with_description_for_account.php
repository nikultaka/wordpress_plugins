 <?php

 ?>
<style>
    .container .carousel.slide {
        width: 100%; 
        max-width: 400px; !important;
    }
</style>  

<div class="row content">
    <div class="col-sm-12">
        <div class="col-sm-6">
            
                    
          
              <div class="container" style="padding-top: 20px;">
                      <div id="myCarousel" class="carousel slide" data-ride="carousel">
                          <!-- Indicators -->
                          <ol class="carousel-indicators">
                              <?php  foreach ($image_list as $k1=>$image_data){ ?>
                                                                        <li data-target="#myCarousel" data-slide-to="<?php echo $k1;?>" class=""></li>
                                                                        
                              <?php }?> 
                                                          </ol>


                  <!-- Wrapper for slides -->
                  <div class="carousel-inner" role="listbox">
                      <?php 
                       foreach ($image_list as $k1=>$image_data){?>
                           <?php if($k1 == 0){ ?>
                           <div class="item active"> <?php }else{?>
                              
                               <div class="item">
                               <?php } ?>
                                  <img src="<?php echo $image_data->front_image; ?>" alt="" class="d-block w-100">
                                  <div class="carousel-caption d-none d-md-block">
                                    <h3><?php echo $image_data->description; ?></h3>
          
                                  </div>
                                  <div class="borrar">
                                      <input type="button" value="Borrar llave" onclick="deleteproduct(<?php echo $image_data->id;?>)">
                                  </div>
                              </div>
                      
                      <?php }
                      ?>
                          
                                     
                     
                  </div>

                  <!-- Left and right controls -->
                  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Previous</span>
                  </a>
                  <a class="right carousel-control" href="#myCarousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                  </a>
                </div>
              </div>
            
                  </div>
        <div class="col-sm-6">&nbsp;</div>
    </div>
        <p id="err_msg" style="display:none;">&nbsp;</p>
</div>
    <script type="text/javascript">
   function  deleteproduct(id){
      
       var productid=id;
       var x = confirm("¿Seguro que quieres borrar la llave?");
       if(x){
         
            var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
            jQuery.ajax({
               url: ajaxurl, // Url to which the request is send
               type: "POST", // Type of request to be send, called as method
               dataType: 'json',
               data: {action:'WCP_UploadImageData_Controller::delete_image',productid:productid}, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
               success: function(data){
                    var err_msg = jQuery("#err_msg");
                    if (data.status == 1) {
                        err_msg.css('color', "green");
                        err_msg.html(data.msg);
                        err_msg.show();
                        // windows.localtion.reload();
                        window.location.href = window.location;
                    }
                   
                }
               
            });
        }
            else{
            return false;
            }
   }
    </script>