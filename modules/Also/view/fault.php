<div class=''>
    <div class='row justify-content-between'>
        <div class='col-10 p-md-2 text-white bg-dark'>
            <h5><?php echo $_LINEAGE['controller']?></h5>
        </div>
        <div class='col-2 bg-dark p-md-1 text-right'>
            <a href=<?php echo "/" . $_LINEAGE['controller']?> class="btn btn-light border-0 btn-sm">
                <h5>Back</h5>
            </a>
        </div>
    </div>
</div>
<br>
<h4>
    HTTP response code: <?php echo $code?>
</h4>