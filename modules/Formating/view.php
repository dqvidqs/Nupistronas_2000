
<div class=''>
    <div class='row justify-content-between'>
        <div class='col-10 p-md-2 text-white bg-dark'>
            <h5>Formating</h5>
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
<?php include $layout . '/templates/config.php';?>
<hr>
<div class="">
    <form action="Formating/format" method="post">
        <div class="row p-md-1">
            <label class="col-sm-4 col-form-label" for="target">Target:</label>
            <div class="col-sm-8">

                <select id="target" name="target" class="form-control" >
                    <option value="" selected></option>
                    <?php 
                        foreach($targets as $row){
                            echo "<option value=\"{$row}\"selected>{$row}</option>";
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="row p-md-1">
            <label class="col-sm-4 col-form-label" for="additions">Additions file:</label>
            <div class="col-sm-8">
                <select id="additions" name="additions" class="form-control">
                    <option value=""selected></option>
                    <?php 
                        foreach($additions as $row){
                            echo "<option value=\"{$row}\"selected>{$row}</option>";
                        }
                    ?>
                </select>
            </div>
        </div>
        <br>
        <div class="col-md-12 text-right p-md-1">
            <input class="btn btn-dark" type="submit" value="Submit">
        </div>
    </form>
</div>