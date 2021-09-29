<?php
    require_once '../vendor/autoload.php';

    
    use App\Crud\Create;
    use App\Crud\Delete;
    use App\Crud\Read;

    /* Principal função usada pra fazer a criação do time
        Verificamos se o post está setado, se tiver vai estanciar uma crasse Create
        e criar um novo registro atraves do metodo create()

        Passa 2 parametros: Tabela e um array com a chave time e idtbestado
    */
    if (!empty($_POST['time']) && !empty($_POST['estado'])) {
        $create = new Create();
        $nome_time = $_POST['time'];
        $nome_estado = $_POST['estado'];
        $create->create('tbtimefutebol', ['time' => $nome_time, 'idtbestado'=>$nome_estado]);
        
    }

    /* Confere se o post está setado, se sim cria uma instacia do DELETE()
        Passa dois parametros: A tabela e o termo    
    */
    if(isset($_POST['delete_time'])){
        $id = $_POST['delete_time'];
        $ex_time = new Delete();
        $ex_time->delete('tbtimefutebol', 'WHERE tbtimefutebol.id ='.$id);
    }
    
    /* Confere se está setado */
    if(isset($_POST['filterObj'])){
        $filterObj = $_POST['filterObj'];
    }else{
        $filterObj = 10;
    }

    /* Confere se está setado */
    if(isset($_POST['pagination'])){
        $pagination = $_POST['pagination'];
    }else{
        $pagination = 1;
    }

    /* Para mostra o proximo conteudo da paginação, multiplicamos o valor do limit (que pode vim pelo $_POST)
        pela paginação - 1

        se a pagina for 1, vai fazer: 10 * (1-1)
        então o offset vai começar do 0 (pagina 1)

        se a pagina for 2, vai fazer: 10 * (2-1)
        então o offset vai começar do 10 (pagina 2)

        e assim por diante
    */
    $offset = $filterObj * ($pagination-1);

    /* Verificamos se o psot está setado */
    if(!empty($_POST['search'])){
        /* Atribuimos a uma variavel e criamos uma instancia do obj Read */
        $pesquisar =$_POST['search'];
        $search = new Read();
        
        /* Pegamos o total com a filtragem de LIKE para usar no final da pagina
            pagina x de y de $total registros
        */
        $total = count($search -> read("tbtimefutebol","tbtimefutebol.id, tbtimefutebol.time, tbestado.nome, tbestado.UF", "tbtimefutebol INNER JOIN tbestado ON tbtimefutebol.idtbestado = tbestado.id WHERE tbtimefutebol.time LIKE '%{$pesquisar}%'"));

        /* Quando criar na coluna times do cabeçalho vai ser enviado um post para o filter.php */
        if(!empty($_POST['desc_or_asc'])){
            /* Vamos receber o valor dependendo do que clicar:
                No html já deixamos o valor do button como ASC
                e no js quando for asc uma variavel vai receber DESC e mandar via post 
            */
            $order = $_POST['desc_or_asc'];
            /* procedimento padrão que vai ter a pesquisa odernada */
            $oa = $search -> read("tbtimefutebol","tbtimefutebol.id, tbtimefutebol.time, tbestado.nome, tbestado.UF", "tbtimefutebol INNER JOIN tbestado ON tbtimefutebol.idtbestado = tbestado.id WHERE tbtimefutebol.time LIKE '%{$pesquisar}%' ORDER BY tbtimefutebol.id {$order} LIMIT {$filterObj} OFFSET {$offset} ");
        }else{
            /* se nao tiver post, via ser so pesquisa mesmo */
            $oa = $search -> read("tbtimefutebol","tbtimefutebol.id, tbtimefutebol.time, tbestado.nome, tbestado.UF", "tbtimefutebol INNER JOIN tbestado ON tbtimefutebol.idtbestado = tbestado.id WHERE tbtimefutebol.time LIKE '%{$pesquisar}%' LIMIT {$filterObj} OFFSET {$offset}");
        }
    }else{
        $query = new Read();

        // Aqui vamos usar o count para pegar todos os registros de times. usando na paginação
        $total = count($query -> read("tbtimefutebol","tbtimefutebol.id, tbtimefutebol.time, tbestado.nome, tbestado.UF", "tbtimefutebol INNER JOIN tbestado ON tbtimefutebol.idtbestado = tbestado.id "));

        /* Mesmo procedimento com ASC e DESC para ordernar em ordem de criação */
        if(!empty($_POST['desc_or_asc'])){
            $order = $_POST['desc_or_asc'];
            
            $oa = $query -> read("tbtimefutebol","tbtimefutebol.id, tbtimefutebol.time,  tbestado.nome, tbestado.UF", "tbtimefutebol INNER JOIN tbestado ON tbtimefutebol.idtbestado = tbestado.id ORDER BY tbtimefutebol.id {$order} LIMIT {$filterObj} OFFSET {$offset} ");
        }else{
            // Query para usar na tabela. Limite inicial de 10 (Padrao). 
            $oa = $query -> read("tbtimefutebol","tbtimefutebol.id, tbtimefutebol.time,  tbestado.nome, tbestado.UF", "tbtimefutebol INNER JOIN tbestado ON tbtimefutebol.idtbestado = tbestado.id LIMIT {$filterObj} OFFSET {$offset}");
        }
    }
    /* limit explicado */
    $limit = count($oa);
    /* ceil explicado */
    $qntpag = ceil($total/($filterObj));
?>
<div class="container table_container">
    <div class="row cabeçalho-content">
        <div class="col" id='invert_seq'>Time de futebol <button id="invert"class='add_or_delete' value="<?php echo $order; ?>"><?php if($order == 'ASC'){echo '<i class="bi bi-caret-down-fill"></i>';}else{echo '<i class="bi bi-caret-up-fill"></i>';}?></button></div>
        <div class="col">Estado</div>
        <div class="col">UF</div>
        <div class="col"><button type="button" data-toggle="modal" class='add_or_delete' data-target="#exampleModal" data-whatever="Nome do time"><i style="color: rgb(0 155 65);" class="fas fa-plus"></i></button></div><?php include __DIR__.'/add_times.php' ?>
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

<!-- Para a paginação foi uma coisa muito interessante de reproduzir:
    no index, defini como padrao o "Anterior" como disabled como padrão, por que quando clicarmos em algum dos numeros, esse arquivo vai ser incluido e renderizado graças ao jquery e ajax.

    aqui acontece toda a magica
    se $pagination == 1: o anterior recebe disable
    se $pagination == $qntpag: o proximo recebe disable -> ultima pagina
    $pagination == $i: li recebe classe de active, seria a pagina atual do usuario 
-->
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
    /* Para montar esse mini sistema tive que usar dois arquivos js
        Quando incluimos o html no nosso index, alguamns coisas nao funcionavam pq nao tinha js no filter.php
        nesse caso tive que incluir ele, porem como ele tava sendo chamado duas vezes, algumas das funções
        acontecia varias vezes, por exemplo a criação de usuarios.

        Porque no momento que eu criava, ele usava o arquivo que tinha incluido com o filter, e o do header.php
        por que ele não sumia do index. os que eram incluidos com php, ignorava os arquivos incluidos no header.
        php

        Nesse caso eu decidi separar algumas coisas
    */
    var url = "staticfiles/script.js";
    $.getScript(url);
</script>