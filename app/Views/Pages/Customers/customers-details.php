<div class="row my-2">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="m-0 fs-3">Detalhes do Cliente</h2>
        <a href="/update/customer/<?= $customer['id'] ?>" class="btn btn-warning">
          <i class="fa-solid fa-pen"></i>
        </a>
      </div>

      <div class="card-body">
        <h4>Informações Pessoais</h4>
        <table class="table table-bordered">
          <tbody>
            <tr>
              <th>ID</th>
              <td><?= htmlspecialchars($customer['id']) ?></td>
            </tr>
            <tr>
              <th>Nome</th>
              <td><?= htmlspecialchars($customer['name']) ?></td>
            </tr>
            <tr>
              <th>CPF</th>
              <td>
                <?= substr($customer['cpf'], 0, 3) . '.' . substr($customer['cpf'], 3, 3) . '.' . substr($customer['cpf'], 6, 3) . '-' . substr($customer['cpf'], 9, 2) ?>
              </td>
            </tr>
            <tr>
              <th>RG</th>
              <td>
                <?= substr($customer['rg'], 0, 2) . '.' . substr($customer['rg'], 2, 3) . '.' . substr($customer['rg'], 5, 3) . '-' . substr($customer['rg'], 8, 1) ?>
              </td>
            </tr>
            <tr>
              <th>Data de Nascimento</th>
              <td><?= date('d-m-Y', strtotime($customer['birth_date'])) ?></td>
            </tr>
            <tr>
              <th>Telefone</th>
              <td>
                <?php
                $phone = preg_replace('/\D/', '', $customer['phone']);
                if (strlen($phone) === 10) {
                  echo '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 4) . '-' . substr($phone, 6);
                } elseif (strlen($phone) === 11) {
                  echo '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 5) . '-' . substr($phone, 7);
                } else {
                  echo htmlspecialchars($customer['phone']);
                }
                ?>
              </td>
            </tr>
          </tbody>
        </table>


        <h4 class="mt-4">Endereços</h4>
        <?php if (!empty($customer['addresses'])): ?>
          <?php foreach ($customer['addresses'] as $index => $address): ?>
            <div class="card mb-2">
              <div class="card-header">
                <?= ($index == 0) ? 'Endereço' : 'Outro endereço' ?>
              </div>
              <div class="card-body">
                <p><strong>CEP:</strong> <?= htmlspecialchars($address['zip']) ?></p>
                <p><strong>Rua:</strong> <?= htmlspecialchars($address['street']) ?></p>
                <p><strong>Número:</strong> <?= !empty($address['number']) ? htmlspecialchars($address['number']) : 'S/n' ?></p>
                <p><strong>Bairro:</strong> <?= htmlspecialchars($address['neighborhood']) ?></p>
                <p><strong>Cidade:</strong> <?= htmlspecialchars($address['city']) ?> / <?= htmlspecialchars($address['state']) ?></p>
                <p><strong>País:</strong> <?= htmlspecialchars($address['country']) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Este cliente não possui endereços cadastrados.</p>
        <?php endif; ?>

        <div class="d-flex justify-content-end">
          <button id="deleteCustomerBtn" class="btn btn-danger mt-1">Deletar cliente</button>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
  const deleteBtn = document.getElementById('deleteCustomerBtn');

  deleteBtn.addEventListener('click', function() {
    if (!confirm('Tem certeza que deseja deletar este cliente?')) {
      return;
    }

    const customerId = <?= (int)$customer['id'] ?>;

    fetch(`/delete/customer/${customerId}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json'
        }
      })
      .then(async response => {
        if (!response.ok) {
          const errorData = await response.json().catch(() => ({}));
          alert(errorData.message || 'Erro ao deletar cliente.');
          return;
        }
        alert('Cliente deletado com sucesso!');
        window.location.href = '/';
      })
      .catch(err => {
        alert('Erro não catalogado');
      });
  });
</script>