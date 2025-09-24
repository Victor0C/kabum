<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="shortcut icon" href="https://static.kabum.com.br/conteudo/favicon/favicon.ico">
  <title>
    <?php echo $title ?? 'Dev Victor'; ?>
  </title>

</head>

<body class="d-flex flex-column min-vh-100">
  <header>

    <nav class="navbar navbar-expand-lg bg-primary mb-2" data-bs-theme="dark">
      <div class="container">

        <a href="/" class="navbar-brand">
          <i class="fa-solid fa-user-ninja"></i> Teste do Dev Victor Hugo
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUserMenu" aria-controls="navbarUserMenu" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarUserMenu">
          <?php if (isset($_SESSION['userName'])): ?>
            <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <?= htmlspecialchars($_SESSION['userName']) ?>
                  <i class="fa-solid fa-user"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                  <li>
                    <form action="/logout" method="POST" class="px-3 py-1">
                      <input type="hidden" name="_method" value="DELETE">
                      <button class="btn btn-danger btn-sm w-100" type="submit">Sair</button>
                    </form>
                  </li>
                </ul>
              </li>
            </ul>
          <?php endif; ?>
        </div>
      </div>
    </nav>
  </header>

  <main class="container d-flex flex-grow-1">