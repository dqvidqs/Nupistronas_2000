<h1>WELCOME!</h1>
<div class='p-md-2 text-white bg-dark'>
    <h5 class='m-0'>Modules:</h5>
</div>
<ul class="list-group">
    <?php 
        foreach($main_modules as $module){
            echo '<a href="/' . $module . '"><li class="list-group-item">'. $module . '</li></a>';
        }
    ?>
</ul>

