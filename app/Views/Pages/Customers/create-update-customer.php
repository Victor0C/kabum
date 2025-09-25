<?php
$isUpdate = isset($customer) && !empty($customer);
?>

<div class="card my-2 w-100">
  <div class="card-header bg-primary text-white">
    <?= $isUpdate ? 'Atualizar Cliente' : 'Criar Novo Cliente' ?>
  </div>
  <div class="card-body">
    <form id="form-create-update-customer">
      <?php if ($isUpdate): ?>
        <input type="hidden" name="id" value="<?= (int)$customer['id'] ?>">
      <?php endif; ?>

      <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Nome do cliente" required
          value="<?= $isUpdate ? htmlspecialchars($customer['name']) : '' ?>">
      </div>

      <div class="mb-3">
        <label for="cpf" class="form-label">CPF</label>
        <input type="text" class="form-control" id="cpf" name="cpf" required placeholder="Apenas números"
          inputmode="numeric" minlength="11" maxlength="11"
          value="<?= $isUpdate ? htmlspecialchars($customer['cpf']) : '' ?>">
      </div>

      <div class="mb-3">
        <label for="rg" class="form-label">RG</label>
        <input type="text" class="form-control" id="rg" name="rg" placeholder="Apenas números" required
          inputmode="numeric" pattern="[0-9]{9}" minlength="9" maxlength="9"
          value="<?= $isUpdate ? htmlspecialchars($customer['rg']) : '' ?>">
      </div>

      <div class="mb-3">
        <label for="birth_date" class="form-label">Data de Nascimento</label>
        <input type="date" class="form-control" id="birth_date" name="birth_date" required
          value="<?= $isUpdate ? htmlspecialchars($customer['birth_date']) : '' ?>">
      </div>

      <div class="mb-3">
        <label for="phone" class="form-label">Telefone</label>
        <input type="text" class="form-control" id="phone" name="phone" required placeholder="Apenas números"
          inputmode="numeric" pattern="\d{10,11}" minlength="10" maxlength="11"
          value="<?= $isUpdate ? htmlspecialchars($customer['phone']) : '' ?>">
      </div>

      <hr>
      <div class="address-fields mb-3">
        <?php
        $addresses = $isUpdate && !empty($customer['addresses']) ? $customer['addresses'] : [[]];
        foreach ($addresses as $index => $address):
        ?>

          <?php if ($isUpdate): ?>
            <input type="hidden" name="addresses[<?= $index ?>][id]" value="<?= $address['id'] ?>">
          <?php endif; ?>

          <?php if ($index != 0): ?>
            <hr>
          <?php endif; ?>

          <h5><?= ($index == 0) ? 'Endereço' : 'Outro endereço' ?></h5>
          <div class="address-block">
            <div class="mb-2">
              <label for="addresses[<?= $index ?>][zip]" class="form-label">CEP</label>
              <input type="text" class="form-control" name="addresses[<?= $index ?>][zip]" placeholder="Apenas números" minlength="8" maxlength="8" required
                value="<?= htmlspecialchars($address['zip'] ?? '') ?>">
            </div>

            <div class="mb-2">
              <label for="addresses[<?= $index ?>][street]" class="form-label">Logradouro</label>
              <input type="text" class="form-control" name="addresses[<?= $index ?>][street]" placeholder="Logradouro do endereco" required
                value="<?= htmlspecialchars($address['street'] ?? '') ?>">
            </div>

            <div class="mb-2">
              <label for="addresses[<?= $index ?>][number]" class="form-label">Número</label>
              <input type="text" class="form-control" name="addresses[<?= $index ?>][number]" placeholder="S/n"
                value="<?= htmlspecialchars($address['number'] ?? '') ?>">
            </div>

            <div class="mb-2">
              <label for="addresses[<?= $index ?>][neighborhood]" class="form-label">Bairro</label>
              <input type="text" class="form-control" name="addresses[<?= $index ?>][neighborhood]" required placeholder="Ex: Centro"
                value="<?= htmlspecialchars($address['neighborhood'] ?? '') ?>">
            </div>

            <div class="mb-2">
              <label for="addresses[<?= $index ?>][city]" class="form-label">Cidade</label>
              <input type="text" class="form-control" name="addresses[<?= $index ?>][city]" required placeholder="Ex: São Paulo"
                value="<?= htmlspecialchars($address['city'] ?? '') ?>">
            </div>

            <div class="mb-2">
              <label for="addresses[<?= $index ?>][state]" class="form-label">Estado</label>
              <select class="form-control" name="addresses[<?= $index ?>][state]" required id="state-<?= $index ?>">
              </select>
            </div>

            <div class="mb-2">
              <label for="addresses[<?= $index ?>][country]" class="form-label">País</label>
              <input type="text" class="form-control" name="addresses[<?= $index ?>][country]" required
                value="<?= htmlspecialchars($address['country'] ?? 'Brasil') ?>" placeholder="Brasil">
            </div>
          </div>


        <?php endforeach; ?>
      </div>

      <div class="d-flex justify-content-between w-100 mt-2">
        <button type="button" class="btn btn-secondary" id="add-address">Adicionar outro endereço</button>
        <button type="submit" class="btn btn-primary"><?= $isUpdate ? 'Atualizar' : 'Salvar' ?></button>
      </div>
    </form>
  </div>
</div>

<script>
  let addressIndex = <?= count($addresses) ?> + 1;

  function allowOnlyNumbers(event) {
    event.target.value = event.target.value.replace(/\D/g, '');
  }

  const numberInputs = document.querySelectorAll('#cpf, #rg, #phone, input[name^="addresses"][name$="[zip]"]');
  numberInputs.forEach(input => input.addEventListener('input', allowOnlyNumbers));

  const states = [
    "AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS",
    "MG", "PA", "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC", "SP", "SE", "TO"
  ];

  function populateStateSelect(select, selected = '') {
    select.innerHTML = '';
    states.forEach(uf => {
      const option = document.createElement('option');
      option.value = uf;
      option.textContent = uf;
      if (uf === selected) option.selected = true;
      select.appendChild(option);
    });
  }

  document.querySelectorAll('select[name^="addresses"]').forEach(select => {
    const selectedValue = select.getAttribute('data-selected') || '';
    populateStateSelect(select, selectedValue);
  });

  document.getElementById('add-address').addEventListener('click', function() {
    const container = document.querySelector('.address-fields');
    const firstBlock = container.querySelector('.address-block');

    const wrapper = document.createElement('div');

    const divider = document.createElement('hr');
    const title = document.createElement('h5');
    title.textContent = 'Outro endereço';

    const clone = firstBlock.cloneNode(true);

    clone.querySelectorAll('input, select').forEach(input => {
      const name = input.getAttribute('name');
      input.setAttribute('name', name.replace(/\d+/, addressIndex));
      if (input.tagName === 'INPUT') input.value = '';
      if (input.tagName === 'SELECT') populateStateSelect(input);
    });


    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.textContent = 'Cancela adição desse endereço';
    removeBtn.className = 'btn btn-danger btn-sm mb-2';
    removeBtn.addEventListener('click', () => {
      container.removeChild(wrapper);
    });

    wrapper.appendChild(divider);
    wrapper.appendChild(title);
    wrapper.appendChild(clone);
    clone.appendChild(removeBtn);

    container.appendChild(wrapper);

    addressIndex++;
  });


  const form = document.getElementById('form-create-update-customer');
  form.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(form);
    const data = {};
    formData.forEach((value, key) => {
      if (key.includes('addresses')) {
        const matches = key.match(/addresses\[\d+\]\[(\w+)\]/);
        if (matches) {
          const field = matches[1];
          if (!data.addresses) data.addresses = [];
          let last = data.addresses[data.addresses.length - 1];
          if (!last || last[field] !== undefined) {
            last = {};
            data.addresses.push(last);
          }
          last[field] = value;
        }
      } else {
        data[key] = value;
      }
    });

    const endpoint = <?= $isUpdate ? "'/update/customer/" . (int)$customer['id'] . "'" : "'/create'" ?>;

    fetch(endpoint, {
        method: <?= $isUpdate ? "'PUT'" : "'POST'" ?>,
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      })
      .then(async response => {
        console.log('oioo');
        if (!response.ok) {
          const errorData = await response.json();
          alert(errorData.message || 'Erro não catalogado');
          return;
        }
        alert('Cliente <?= $isUpdate ? "Atualizado" : "Criado" ?> com sucesso!');
        window.location.href = <?= $isUpdate ? "'/customer/" . (int)$customer['id'] . "'" : "'/'" ?>;
      })
      .catch(err => {
        alert('Erro não catalogado');
      });
  });
</script>