<div class="paginador">
    <ul>
        <?php
        if ($pagina != 1) {
        ?>
            <li><a href="?pagina=<?php echo 1; ?>"><i class="fa-solid fa-backward-step"></i></a</li>
            <li><a href="?pagina=<?php echo $pagina - 1; ?>"><i class="fa-solid fa-backward"></i></a></li>
        <?php
        }
        for ($i = 1; $i <= $total_paginas; $i++) {
            # code
            if ($i == $pagina) {
                echo '<li class="pageSelected">' . $i . '</li>';
            } else {
                echo '<li><a href="?pagina=' . $i . '">' . $i . '</a></li>';
            }
        }

        if ($pagina != $total_paginas) {
        ?>
            <li><a href="?pagina=<?php echo $pagina + 1; ?>"><i class="fa-solid fa-forward"></i></a></li>
            <li><a href="?pagina=<?php echo $total_paginas; ?>"><i class="fa-solid fa-forward-step"></i></a></li>
        <?php } ?>
    </ul>
</div>