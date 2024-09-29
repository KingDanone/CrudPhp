<?php 
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Projetos - Empresa</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link href="css/styles.css" rel="stylesheet">
  </head>
  <body>
    <header>
        <div class="container" id="nav-container">
          <?php include('navbar.php'); ?>
        </div>
    </header>
    <main>
      <div class="container-fluid">
        <div class="container mt-4">
          <div class="row">
            <div class="col mt-12">
              <div class="card">
                <div class="card-header">
                  <?php
                    switch (@$_REQUEST["page"]) {
                      case "projeto-listar":
                        include('projeto-listar.php');
                        break;
                      case "projeto-create":
                        include('projeto-create.php');
                        break;
                      case "projeto-editar":
                        include('projeto-editar.php');
                        break;
                      case "projeto-delete":
                          include('projeto-delete.php');
                          break;
                      case "funcionario-listar":
                        include('funcionario-listar.php');
                        break;
                      case "funcionario-create":
                        include('funcionario-create.php');
                        break;
                      case "funcionario-editar":
                        include('funcionario-editar.php');
                        break;
                      case "dependente-listar":
                        include('dependente-listar.php');
                        break;
                      case "dependente-create":
                          include('dependente-create.php');
                          break;
                      case "dependente-editar":
                          include('dependente-editar.php');
                          break;
                      case "dependente-delete":
                          include('dependente-delete.php');
                          break;
                      case "departamento-listar":
                        include('departamento-listar.php');
                        break;
                      case "departamento-create":
                        include('departamento-create.php');
                        break;
                      case "departamento-editar":
                        include('departamento-editar.php');
                        break;
                      case "departamento-delete":
                          include('departamento-delete.php');
                          break;
                      case "local-listar":
                        include('local-listar.php');
                        break;
                      case "local-create":
                        include('local-create.php');
                        break;
                      case "local-editar":
                        include('local-editar.php');
                        break;
                      case "local-delete":
                          include('local-delete.php');
                          break;
                      default:
                      ?>
                      <div class="text-center mt-5">
                          <h1>Bem-vindo à Nossa Empresa</h1>
                          <p class="lead mt-4">Nossa missão é fornecer soluções inovadoras para o sucesso dos nossos clientes.</p>
                          <a href="?page=projeto-listar" class="btn btn-primary btn-lg mt-3">Faça seu projeto com a gente</a>
                      </div>
                      <div class="row mt-5">
                      <div class="col-md-4">
                          <div class="card text-center">
                              <div class="card-body">
                                  <h5 class="card-title">Inovação</h5>
                                  <p class="card-text">Trabalhamos com tecnologia de ponta para criar produtos revolucionários.</p>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="card text-center">
                              <div class="card-body">
                                  <h5 class="card-title">Qualidade</h5>
                                  <p class="card-text">Nosso foco é entregar sempre com a mais alta qualidade para nossos clientes.</p>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="card text-center">
                              <div class="card-body">
                                  <h5 class="card-title">Sustentabilidade</h5>
                                  <p class="card-text">Respeitamos o meio ambiente e buscamos práticas empresariais sustentáveis.</p>
                              </div>
                          </div>
                      </div>
                  </div>
                  <?php
                      }
                  ?>
                </div>  
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <!-- Rodapé -->
    <footer>
      <div id="copy-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p>
                      Desenvolvido por Luis Ricardo &copy; 2024
                    </p>
                </div>
            </div>
        </div>
      </div>
    </footer>
    <!-- Bootstrap JS (no final do body) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous" defer></script>
  </body>
</html>