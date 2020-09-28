<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
    <html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <title>Nupistronas 2000</title>
    </head>
    <body>
        <div class="container">
            <header class="blog-headeer py-3">
                <div class="row flex-nowrap justify-content-between align-items-center">
                    <div class="col-md-4">
                        <h4>Nupistronas 2000</h4>
                    </div>
                    <div class="col-auto">
                        <h6>made for <a target="_blank" href="https://suzyle.lt/">suzyle.lt</a></h6>
                    </div>
                </div>
                <br>
            </header>
        </div>
        <main role="main" class="container">
            <?php if($module) {echo $module;} ?>
        </main>
        <br>
        <br>
        <br>
        <footer class="footer fixed-bottom">
            <div class="row bg-dark flex-nowrap justify-content-center align-items-center text-white p-md-2">
                <div class="col-auto">
                    <h6>Powered by Calabi © <?php echo date('Y');?></h6>
                </div>
            </div>
        </footer>
    </body>

</html>