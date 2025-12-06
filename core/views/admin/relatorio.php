<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
  <div
    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h3>Painel de Relatorio</h3>
    <div class="btn-toolbar mb-2 mb-md-0">
      <div class="btn-group me-2">
        <button type="button" class="btn btn-sm btn-outline-secondary">Partilhar</button>
        <button type="button" class="btn btn-sm btn-outline-secondary">Expostar</button>
      </div>
      <button type="button"
        class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1">
        <svg class="bi">
          <use xlink:href="#calendar3" />
        </svg>
        Esta Semana
      </button>
    </div>
  </div>


  <div class="container">
    <div class="row p-0">

      <h4 class="">Emitir Relatórios</h4>
      <small>usa essa opção para gerar um relatório persolanizado</small>

      <div class="mt-4 row">
        <label for="inputPassword" class="col-sm-2 col-form-label">Relatório de:</label>
        <div class="col-sm-4">
          <select id="combo-status" class="form-control" onchange="definir_filtro()">
            <option value=""></option>
            <option value="pendente">Pendentes</option>
          </select>
        </div>
      </div>

    </div>
  </div>

  <hr>
  <div class="container">
    <div class="row">
      <h4 class="mb-4">Relatórios disponivéis</h4>
      <hr>
    </div>
  </div>
</main>