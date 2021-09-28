<?php 
                if(!isset($_POST['_filterObj'])){
                    for($i = 0; $i < $limit; $i++){
                
                    
                ?>
                <div class="row row-content">
                    <div class="col"><?php echo $oa[$i]['time']?></div>
                    <div class="col"><?php echo $oa[$i]['nome']?></div>
                    <div class="col"><?php echo $oa[$i]['UF']?></div>
                    <div class="col"><button class="add_or_delete" type="submit"><i style="color:red;" class="fas fa-minus"></i></button></div>
                </div>
                <?php }
                ?>
                </div>
                <div class="container pagination-container">
                <p class="qnt">Mostrando página 1 de <?php echo $qntpag ?> de <?php echo $total ?> registros</p>
                    <ul class="pagination">
                        <li class="page-item disabled"><span class="page-link">Anterior</span></li>
                        <?php for($i = 1; $i <= $qntpag; $i++){?>
                        <li class="page-item"><a class="page-link" href="<?php echo $i ?>"><?php echo $i ?></a></li>
                        <?php
                        }?>
                        <li class="page-item"><a class="page-link" href="#">Próximo</a></li>
                    </ul>
                </div>    
                
                <?php 
                    }else{
                        include __DIR__.'/inc/filter.php';
                    } ?>