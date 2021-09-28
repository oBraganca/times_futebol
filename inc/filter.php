<?php
    require_once '../vendor/autoload.php';

    
    use App\Crud\Create;
    use App\Crud\Delete;
    use App\Crud\Read;

    if (!empty($_POST['time']) && !empty($_POST['time'])) {
        $create = new Create();
        $nome_time = $_POST['time'];
        $nome_estado = $_POST['estado'];
        $create->create('tbtimefutebol', ['time' => $nome_time, 'idtbestado'=>$nome_estado]);
        
    }
    if(isset($_POST['delete_time'])){
        $id = $_POST['delete_time'];
        $ex_time = new Delete();
        $ex_time->delete('tbtimefutebol', 'WHERE tbtimefutebol.id ='.$id);
    }
    
    if(isset($_POST['filterObj'])){
        $filterObj = $_POST['filterObj'];
    }else{
        $filterObj = 10;
    }

    if(isset($_POST['pagination'])){
        $pagination = $_POST['pagination'];
    }else{
        $pagination = 1;
    }

    $offset = $filterObj * ($pagination-1);

    if(!empty($_POST['search'])){
        $pesquisar =$_POST['search'];
        $search = new Read();
        
        $total = count($search -> read("tbtimefutebol","tbtimefutebol.id, tbtimefutebol.time, tbestado.nome, tbestado.UF", "tbtimefutebol INNER JOIN tbestado ON tbtimefutebol.idtbestado = tbestado.id WHERE tbtimefutebol.time LIKE '%{$pesquisar}%'"));

        if(!empty($_POST['desc_or_asc'])){
            $order = $_POST['desc_or_asc'];
            $oa = $search -> read("tbtimefutebol","tbtimefutebol.id, tbtimefutebol.time, tbestado.nome, tbestado.UF", "tbtimefutebol INNER JOIN tbestado ON tbtimefutebol.idtbestado = tbestado.id WHERE tbtimefutebol.time LIKE '%{$pesquisar}%' ORDER BY tbtimefutebol.id {$order} LIMIT {$filterObj} OFFSET {$offset} ");
        }else{
            $oa = $search -> read("tbtimefutebol","tbtimefutebol.id, tbtimefutebol.time, tbestado.nome, tbestado.UF", "tbtimefutebol INNER JOIN tbestado ON tbtimefutebol.idtbestado = tbestado.id WHERE tbtimefutebol.time LIKE '%{$pesquisar}%' LIMIT {$filterObj} OFFSET {$offset}");
        }
    }else{
        $query = new Read();

        // Aqui vamos usar o count para pegar todos os registros de times. usando na paginação
        $total = count($query -> read("tbtimefutebol","tbtimefutebol.id, tbtimefutebol.time, tbestado.nome, tbestado.UF", "tbtimefutebol INNER JOIN tbestado ON tbtimefutebol.idtbestado = tbestado.id "));

        if(!empty($_POST['desc_or_asc'])){
            $order = $_POST['desc_or_asc'];
            
            $oa = $query -> read("tbtimefutebol","tbtimefutebol.id, tbtimefutebol.time,  tbestado.nome, tbestado.UF", "tbtimefutebol INNER JOIN tbestado ON tbtimefutebol.idtbestado = tbestado.id ORDER BY tbtimefutebol.id {$order} LIMIT {$filterObj} OFFSET {$offset} ");
        }else{
            // Query para usar na tabela. Limite inicial de 10 (Padrao). 
            $oa = $query -> read("tbtimefutebol","tbtimefutebol.id, tbtimefutebol.time,  tbestado.nome, tbestado.UF", "tbtimefutebol INNER JOIN tbestado ON tbtimefutebol.idtbestado = tbestado.id LIMIT {$filterObj} OFFSET {$offset}");
        }
    }
    
    $limit = count($oa);
    $qntpag = ceil($total/($filterObj));
?>
<div class="container table_container">
    <div class="row cabeçalho-content">
        <div class="col" id='invert_seq'>Time de futebol <button id="invert"class='add_or_delete' value="<?php echo $order; ?>"><?php if($order == 'ASC'){echo '<i class="bi bi-caret-down-fill"></i>';}else{echo '<i class="bi bi-caret-up-fill"></i>';}?></button></div>
        <div class="col">Estado</div>
        <div class="col">UF</div>
        <div class="col"><button type="button" data-toggle="modal" class='add_or_delete' data-target="#exampleModal" data-whatever="Nome do time"><i style="color: rgb(50, 205, 115);" class="fas fa-plus"></i></button></div><?php include __DIR__.'/add_times.php' ?>
    </div>
    <?php for($i = 0; $i < $limit; $i++){
    ?>
    <div class="row row-content">
        <div class="col" ><?php echo $oa[$i]['time']?></div>
        <div class="col"><?php echo $oa[$i]['nome']?></div>
        <div class="col"><?php echo $oa[$i]['UF']?></div>
        <div class="col"><button  id='<?php echo $oa[$i]['id']?>' class="add_or_delete delete_time" name="<?php echo $oa[$i]['time']?>" type="submit"><i style="color:red;" class="fas fa-minus"></i></button></div>
    </div>
    <?php } ?>
</div>

<div class="container pagination-container">
    <p class="qnt">Mostrando página <?php echo $pagination ?>  de <?php echo $qntpag ?> de <?php echo $total ?> registros</p>
    <ul class="pagination">
        <li class="page-item <?php if($pagination == 1){echo 'disabled';}?>"><a class="page-link" href="<?php if($pagination != 1){echo $pagination - 1;}?>">Anterior</a></li>
        <?php for($i = 1; $i <= $qntpag; $i++){?>
            <li class="page-item <?php if($pagination == $i){echo 'active';}?>"><a class="page-link" href="<?php echo $i ?>"><?php echo $i ?></a></li>
        <?php
        }?>
        <li class="page-item  <?php if($pagination == $qntpag){echo 'disabled';}?>"><a class="page-link" href="<?php if($pagination + 1 <= $qntpag){echo $pagination + 1;}?>">Próximo</a></li>
    </ul>
</div>


<script>
    var url = "staticfiles/script.js";
    $.getScript(url);
</script>