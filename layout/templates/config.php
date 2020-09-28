<hr>
    <?php
        echo '<form method="post" action="/'. $controller .'/save_config">';
        
        foreach($config as $key => $row){
            $a = '<div class="row">
                    <label class="col-sm-4 col-form-label" for="'. $key . '">' . $row['name'] . '</label>
                    <div class="col-sm-8">
                        <input type="text" name="' . $key . '" class="form-control form-control-sm" id="' . $key . '" value="' . $row['value'] . '">
                    </div>
                </div>';
            echo $a;
        }
        ?>
        <div class="text-right">
        <hr>
            <input type="submit" class="btn btn-dark border-0 btn-sm">
        </div>
    </form>
<br><br>