<?php 
/*Auto load para chamar o crud e o conn */ 
require_once 'vendor/autoload.php';

use App\Crud\Read;

/* Declarando e dando valores iniciais a duas variaveis que vai auxiliar na paginação do nosso aplicativo.
    É declarado como com numero por que, as proximas requisições serão assincronas serão feitas no filter.php
*/
$filterObj = 10;
$offset = 1;

/* Instanciando um novo objeto Read */
$query = new Read();

// Aqui vamos usar o count para pegar todos os registros de times e usar para conseguir a quantidade de pagina total que vai ter nosso app
$total = count($query -> read("tbtimefutebol","tbtimefutebol.id, tbtimefutebol.time,  tbestado.nome, tbestado.UF", "tbtimefutebol INNER JOIN tbestado ON tbtimefutebol.idtbestado = tbestado.id "));

// Query para usar na tabela. Limite inicial de 10 (Padrao) e vai começar do 1 registro (Inicalmente como padrão até a paginação). 
$oa = $query -> read("tbtimefutebol","tbtimefutebol.id, tbtimefutebol.time,  tbestado.nome, tbestado.UF", "tbtimefutebol INNER JOIN tbestado ON tbtimefutebol.idtbestado = tbestado.id LIMIT {$filterObj} OFFSET {$offset}");

/* Usei o ceil para arredondar pra cima a divisão do total com o limite a cada pagina */
$qntpag = ceil($total/($filterObj));

/* Variavel auxiliar do contador do for */
$limit = count($oa);

include __DIR__.'/inc/header.php';
?>

    <title>Times de futebol</title>
</head>
<body>
    <div class="container-fluid">
        <h1 class="text-center">Times de Futebol</h1>
        <div class="container filter-container">
            <span>Montar
                <select class="form-select"  name="" class="link_option" id="filter_table">
                    <option value="5">5</option>
                    <option selected value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                </select>registros por tabela
            </span>
            <form id="form1">
                <div class="input-group-sm">
                    
                    <div class="form-outline">
                        <input type="search" placeholder="search" id='pesquisa' class="form-control" />
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                
                </div>
            </form>  
        </div>
        <!-- Incluindo o modal do arquivo add_times.php - Já vem de com uma consulta pra retornar os estaods disponiveis -->
        <?php include __DIR__.'/inc/add_times.php' ?>
        <div class="container filter_data">
                
        
                <?php 
                /* Nesse trecho do cogio ficou meio confuso mas, quando eu der qualquer requisição com ajax, a funçãp vai pegar o valor do open select e enviar junto com outros possiveis dados.
                
                Porque ?
                    Porque quando eu paginar, o codigo nao ignorar a quantidade por pagina selecionada.
                    Isso ocorre com a maioria ou todas as variaveis que mandadmos por post no js pelo ajax.
                    assim nem um ignora o outro, e se algum desse nao tiver sido preenxido com dados (por exemplo o search) vai ter uma verificação dentro do filter.php

                Se o $_POST['_filterObj'] tiver inicializado, então vai incluir o filter.php com o .html() do jquery e trabalhar com os dados enviados via post
                */
                if(isset($_POST['_filterObj'])){
                    include __DIR__.'/inc/filter.php';
                }else{
                    
                    
                    /* O template que sera mostrado antes de ocorrer alguma requiseção com ajax*/ 
                    ?>
                <div class="container table_container">
                <div class="row cabeçalho-content">
                    <div class="col" id='invert_seq'>Time de futebol <button class='add_or_delete'id="invert"value="ASC"><i class="bi bi-caret-up-fill"></i><i class="bi bi-caret-down-fill"></i></button> </div>
                    <div class="col">Estado</div>
                    <div class="col">UF</div>
                    <div class="col"><button type="button" data-toggle="modal" class='add_or_delete' data-target="#exampleModal" data-whatever="Nome do time"><i style="color: rgb(0 155 65);" class="fas fa-plus"></i></button></div>
                </div>
                <?php
                    for($i = 0; $i < $limit; $i++){  
                ?>
                <div class="row row-content">
                    <div class="col" ><?php echo $oa[$i]['time']?></div>
                    <div class="col"><?php echo $oa[$i]['nome']?></div>
                    <div class="col"><?php echo $oa[$i]['UF']?></div>
                    <div class="col"><button  id='<?php echo $oa[$i]['id']?>' class="add_or_delete delete_time" name="<?php echo $oa[$i]['time']?>" type="submit"><i style="color:red;" class="fas fa-minus"></i></button></div>
                </div>
                <?php }
                ?>
            </div>
            <div class="container pagination-container">
            <p class="qnt">Mostrando página 1 de <?php echo $qntpag ?> de <?php echo $total ?> registros</p>
                <ul class="pagination">
                    <li class="page-item disabled"><span class="page-link">Anterior</span></li>
                    <?php for($i = 1; $i <= $qntpag; $i++){?>
                    <li class="page-item  <?php if($i == 1){echo 'active';}?>"><a class="page-link link_option" href="<?php echo $i ?>"><?php echo $i ?></a></li>
                    <?php
                    }?>
                    <li class="page-item"><a class="page-link" href="2">Próximo</a></li>
                </ul>
            </div> 
            <?php
            }?>
        </div>
    </div>
<?php include __DIR__.'/inc/footer.php' ?>  