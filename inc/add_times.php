<?php


use App\Crud\read;

$query = new read();

/* Query retorna os estados para serem usados no slect option*/
$oe = $query->read("tbestado", "nome, id");
$qnt = count($oe)
?>
<!-- modal do bootstrap -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cadastro de novo time</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id='add_fut'>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Times:</label>
            <input type="text" class="form-control" id="recipient-name" name="time" id="" placeholder="Nome do time">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Estados:</label>
            <select name='estado'class="form-select form-select-lg form-control mb-3" id='estado' aria-label=".form-select-lg example">
              <option value="default">Selecione um estado</option>
              <?php for ($i = 0; $i < $qnt; $i++) {
                /* select option que vai mostrar os estados para o usuario criar um novo time, com um value e nome */
                echo "<option value=" . $oe[$i]['id'] . "> " . $oe[$i]['nome'] . "</option>";
              } ?>
            </select>
          </div>
          <div class="modal-footer">
            <button type="submit" value="Submit" class="btn" name='success'>Criar</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>