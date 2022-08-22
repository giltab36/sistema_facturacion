<div class="paginador">
    <ul>
        <li><a href="#">|<</a></li>
        <li><a href="#"><<</a></li>
    <?php 
        for ($i=1; $i <= $total_paginas; $i++) {
        echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
        }
    ?>
        <li><a href="#">>></a></li>
        <li><a href="#">>|</a></li>
    </ul>
</div>