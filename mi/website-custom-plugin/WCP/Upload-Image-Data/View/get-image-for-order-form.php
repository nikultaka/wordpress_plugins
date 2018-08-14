<div><br></div>
<table class="table table-bordered">
    <tbody>
        <tr>
            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Select</font></font><br></td>
            
            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Image</font></font><br></td>
            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Description</font></font><br></td>
        </tr>
        <?php
        foreach ($image_list as $key=>$value){?>
            <tr>
                <td style="vertical-align: central !important;"> <input type="checkbox" id="lead<?php echo $value['id'];?>"> </td>
                <td style="vertical-align: central !important;"><img src="<?php echo $value['image']; ?>" alt="<?php echo $value['description']; ?>" style="height: 100px;" class="d-block w-100"> </td>
            <td><font style="vertical-align: central !important;"><font style="vertical-align: inherit;"><?php echo $value['description']; ?></font></font><br></td>
        </tr> 
            
       <?php }
        ?>
       
        
    </tbody>
</table>