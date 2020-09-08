<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
    <html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <title>Nupistronas 2000</title>
    </head>
    <body>
        <div class="card-body card">
            <form action="Format" method="post">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="target">Target:</label>

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
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="additions">Additions file:</label>

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
                <div class="col-md-6">
                    <div class="d-flex justify-content-between">
                        <div></div>
                        <div class="col-md-6">
                            <input class="btn btn-primary float-right" type="submit" value="Submit">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>