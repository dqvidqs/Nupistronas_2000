<div class=''>
    <div class='row justify-content-between'>
        <div class='col-10 p-md-2 text-white bg-dark'>
            <h5><?php echo $controller?></h5>
        </div>
        <div class='col-2 bg-dark p-md-1 text-right'>
            <a href="/" class="btn btn-light border-0 btn-sm">
                <h5>Back</h5>
            </a>
        </div>
    </div>
</div>
<br>
<div class="row d-flex justify-content-between">
    <div class="col-4">
        <strong>Configuration</strong>
    </div>
    
    <?php if(isset($_GET['saved'])){ echo '<div class="col-1 alert alert-success">Saved!</div>'; }?>
</div>
<?php include $_LAYOUT . '/templates/config.php';?>