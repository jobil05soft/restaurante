<div class="container-fluid">
  <div class="row">

    <div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">
      <div class="offcanvas-md offcanvas-end bg-body-tertiary" tabindex="-1" id="sidebarMenu"
        aria-labelledby="sidebarMenuLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="sidebarMenuLabel"><?= APP_ ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"
            aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page" href="?a=inicio">
                <svg class="bi">
                  <use xlink:href="#house-fill" />
                </svg>
                Painel inicial
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2 " href="?a=lista_reservas">
                <svg class="bi" width="24" height="24">
                  <use xlink:href="#calendar-check" />
                </svg>

                Reservas
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2" href="?a=lista_pedidos">
                <svg class="bi" width="24" height="24">
                  <use xlink:href="#icon-pedido" />
                </svg>
                Pedidos
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2" href="?a=cardapio">
                <svg class="bi" width="24" height="24">
                  <use xlink:href="#icon-cardapio" />
                </svg>
                Cardápio
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2" href="?a=gestao_mesas">

                <i class="fas fa-chair"></i>

                Mesas
              </a>
            </li>
            <!-- <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2" href="?a=relatorios">
                <svg class="bi">
                  <use xlink:href="#graph-up" />
                </svg>
                Relatório
              </a>
            </li> -->
          </ul>

          <h6
            class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-body-secondary text-uppercase">
            <span>Pessoal</span>
            <a class="link-secondary" href="#" aria-label="Add a new report">
              <svg class="bi">
                <use xlink:href="#plus-circle" />
              </svg>
            </a>
          </h6>
          <ul class="nav flex-column mb-auto">

            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2" href="?a=lista_cliente">
                <svg class="bi">
                  <use xlink:href="#people" />
                </svg>
                Clientes
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2" href="?a=lista_usuarios">
                <svg class="bi">
                  <use xlink:href="#file-earmark-text" />
                </svg>
                Usuários
              </a>
            </li>
          </ul>



          <!-- <h6
            class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-body-secondary text-uppercase">
            <span>Relatórios Salvos</span>
            <a class="link-secondary" href="#" aria-label="Add a new report">
              <svg class="bi">
                <use xlink:href="#plus-circle" />
              </svg>
            </a>
          </h6>
          <ul class="nav flex-column mb-auto">
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2" href="#">
                <svg class="bi">
                  <use xlink:href="#file-earmark-text" />
                </svg>
                Relatório das Reservas
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2" href="#">
                <svg class="bi">
                  <use xlink:href="#file-earmark-text" />
                </svg>
                Relatório de Pedidos
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2" href="#">
                <svg class="bi">
                  <use xlink:href="#file-earmark-text" />
                </svg>
                Relatório de Clientes
              </a>
            </li>
          </ul> -->


          <hr class="my-3">

          <ul class="nav flex-column mb-auto">
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2" href="#">
                <svg class="bi">
                  <use xlink:href="#gear-wide-connected" />
                </svg>
                Configurações
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center gap-2" href="?a=logout_admin">
                <svg class="bi">
                  <use xlink:href="#door-closed" />
                </svg>
                Sair
              </a>
            </li>
          </ul>

          <div style="margin-top: 285px;"></div>
        </div>
      </div>
    </div>