<div class="d-flex justify-content-center align-items-center w-100">
  <div class="card shadow-sm p-4" style="max-width: 400px; width: 100%;">
    <h3 class="card-title text-center mb-4">Login</h3>

    <?php if (isset($_SESSION['errors']) && isset($_SESSION['errors']['msg'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['errors']['msg'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>


    <form action="/login" method="POST">
      <div class="mb-3">
        <label for="mail" class="form-label">E-mail</label>
        <input type="mail" class="form-control" id="mail" name="mail" placeholder="Digite seu e-mail" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Senha</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha" required>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Entrar</button>
      </div>
    </form>
  </div>
</div>