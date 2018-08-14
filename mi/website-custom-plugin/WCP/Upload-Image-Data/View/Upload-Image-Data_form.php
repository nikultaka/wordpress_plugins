
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
  
<style>
    .container .carousel.slide {
        width: 100%; 
        max-width: 400px; !important;
    }
</style>  

<div class="row content">
    <div class="col-sm-12">
        <div class="col-sm-6">
            
        <?php if(!empty($image_list)){ ?>
            
          
              <div class="container" style="padding-top: 20px;">
                      <div id="myCarousel" class="carousel slide" data-ride="carousel">
                          <!-- Indicators -->
                          <ol class="carousel-indicators">
                                <?php foreach($image_list as $k1=>$image_data){ ?>
                                        <li data-target="#myCarousel" data-slide-to="<?php echo $k1; ?>" class="active"></li>
                                <?php } ?>
                          </ol>


                  <!-- Wrapper for slides -->
                  <div class="carousel-inner" role="listbox">
                    <?php if(!empty($image_list)){
                          foreach ($image_list as $k1=>$image_data){ ?>  
                              <div class="item <?php echo ($k1 == 1)?"active":""; ?>">
                                  <img src="<?php echo $image_data['image']; ?>" alt="<?php echo $image_data['description']; ?>" class="d-block w-100">
                                  <div class="carousel-caption d-none d-md-block">
                                    <h3><?php echo $image_data['description']; ?></h3>
              <!--                      <p><?php echo $image_data['description']; ?></p>-->
                                  </div>
                              </div>
                    <?php } } ?> 
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
            
<!--            <div class="grve-row-inner grve-bookmark">
                <div class="grve-column-inner wpb_column grve-column-1-2">
                        <div class="grve-column-wrapper-inner">
                        <div class="grve-element grve-align-center"><a href="https://mi-llavero.creativeapps.space/upload-form/" class="grve-btn grve-btn-medium grve-square grve-bg-primary-1 grve-bg-hover-black"><span>Upload Image</span></a></div>
                        </div>
                </div>

                <div class="grve-column-inner wpb_column grve-column-1-2">
                        <div class="grve-column-wrapper-inner">
                            <div class="grve-element grve-align-center"><a href="https://mi-llavero.creativeapps.space/order-form/" class="grve-btn grve-btn-medium grve-square grve-bg-primary-1 grve-bg-hover-black"><span>Order Form</span></a></div>
                        </div>
                </div>
            </div>-->
          <?php }?>
        </div>
        <div class="col-sm-6">&nbsp;</div>
    </div>
</div>
