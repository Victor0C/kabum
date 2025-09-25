<div class="row w-100">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="m-0 fs-3">Listagem dos clientes</h2>
          <a href="/create" class="btn btn-primary float-end">
            <i class="fa-solid fa-user-plus"></i>
          </a>
        </div>
      </div>
      <div class="card-body px-0 py-1">
        <table class="table table-striped text-center">
          <thead>
            <tr>
              <th scope="col">Nome</th>
              <th scope="col">CPF</th>
              <th scope="col">Telefone</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($customers)): ?>
              <?php foreach ($customers as $customer): ?>
                <tr>
                  <td>
                    <a href="/customer/<?= $customer['id'] ?>">
                      <?= htmlspecialchars($customer['name']) ?>
                    </a>
                  </td>
                  <td>
                    <?= substr($customer['cpf'], 0, 3) . '.' . substr($customer['cpf'], 3, 3) . '.' . substr($customer['cpf'], 6, 3) . '-' . substr($customer['cpf'], 9, 2) ?>
                  </td>
                  <td>
                    <?= htmlspecialchars($customer['phone']) ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="3">Nenhum cliente cadastrado.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>