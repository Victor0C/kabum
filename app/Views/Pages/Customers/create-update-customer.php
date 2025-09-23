<div class="card my-2">
  <div class="card-header bg-primary text-white">
    Criar Novo Cliente
  </div>
  <div class="card-body">
    <form>
      <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Nome do cliente" required>
      </div>

      <div class="mb-3">
        <label for="cpf" class="form-label">CPF</label>
        <input type="text" class="form-control" id="cpf" name="cpf" required placeholder="Apenas números"
          inputmode="numeric"
          minlength="11"
          maxlength="11">
      </div>

      <div class="mb-3">
        <label for="rg" class="form-label">RG</label>
        <input type="text" class="form-control" id="rg" name="rg" placeholder="Apenas números" required
          inputmode="numeric"
          pattern="[0-9]{9}"
          minlength="9"
          maxlength="9">
      </div>

      <div class="mb-3">
        <label for="birth_date" class="form-label">Data de Nascimento</label>
        <input type="date" class="form-control" id="birth_date" name="birth_date" required>
      </div>

      <div class="mb-3">
        <label for="phone" class="form-label">Telefone</label>
        <input
          type="text"
          class="form-control"
          id="phone"
          name="phone"
          required
          placeholder="Apenas números"
          inputmode="numeric"
          pattern="\d{10,11}"
          minlength="10"
          maxlength="11">
      </div>

      <hr>
      <h5>Endereço principal</h5>

      <div class="address-fields mb-3">
        <div class="address-block">
          <div class="mb-2">
            <label for="addresses[0][cep]" class="form-label">CEP</label>
            <input type="text" class="form-control" name="addresses[0][cep]" placeholder="Apenas números" required>
          </div>

          <div class="mb-2">
            <label for="addresses[0][street]" class="form-label">Logradouro</label>
            <input type="text" class="form-control" name="addresses[0][street]" placeholder="Logradouro do endereco" required>
          </div>

          <div class="mb-2">
            <label for="addresses[0][number]" class="form-label">Número</label>
            <input type="text" class="form-control" name="addresses[0][number]" required placeholder="Ex: 123">
          </div>

          <div class="mb-2">
            <label for="addresses[0][neighborhood]" class="form-label">Bairro</label>
            <input type="text" class="form-control" name="addresses[0][neighborhood]" required placeholder="Ex: Centro">
          </div>

          <div class="mb-2">
            <label for="addresses[0][city]" class="form-label">Cidade</label>
            <input type="text" class="form-control" name="addresses[0][city]" required placeholder="Ex: São Paulo">
          </div>

          <div class="mb-2">
            <label for="addresses[0][state]" class="form-label">Estado</label>
            <select class="form-control" name="addresses[0][state]" required id="state-0">

            </select>
          </div>

          <div class="mb-2">
            <label for="addresses[0][country]" class="form-label">País</label>
            <input type="text" class="form-control" name="addresses[0][country]" required value="Brasil" placeholder="Brasil">
          </div>
        </div>

      </div>

      <div class="d-flex justify-content-between w-100 mt-2">
        <button type="button" class="btn btn-secondary" id="add-address">Adicionar outro endereço</button>
        <button type="submit" class="btn btn-primary">Salvar</i></button>
      </div>
    </form>
  </div>
</div>

<script>
  let addressIndex = 1;

  function allowOnlyNumbers(event) {
    event.target.value = event.target.value.replace(/\D/g, '');
  }

  const numberInputs = document.querySelectorAll('#cpf, #rg, #phone, input[name^="addresses"][name$="[cep]"]');
  numberInputs.forEach(input => {
    input.addEventListener('input', allowOnlyNumbers);
  });

  const states = [
    "AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS",
    "MG", "PA", "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC", "SP", "SE", "TO"
  ];

  function populateStateSelect(select) {
    select.innerHTML = '';
    states.forEach(uf => {
      const option = document.createElement('option');
      option.value = uf;
      option.textContent = uf;
      select.appendChild(option);
    });
  }

  populateStateSelect(document.getElementById('state-0'));

  document.getElementById('add-address').addEventListener('click', function() {
    const container = document.querySelector('.address-fields');
    const firstBlock = container.querySelector('.address-block');
    const clone = firstBlock.cloneNode(true);

    clone.querySelectorAll('input, select').forEach(input => {
      const name = input.getAttribute('name');
      input.setAttribute('name', name.replace(/\d+/, addressIndex));
      if (input.tagName === 'INPUT') input.value = '';
      if (input.tagName === 'SELECT') populateStateSelect(input);
    });

    const divider = document.createElement('hr');
    container.appendChild(divider);
    const title = document.createElement('h5');
    title.textContent = 'Endereço ' + addressIndex;
    container.appendChild(title);
    container.appendChild(clone);

    addressIndex++;
  });

  const form = document.querySelector('form');

  form.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(form);
    const data = {};

    formData.forEach((value, key) => {
      if (key.includes('addresses')) {
        const matches = key.match(/addresses\[(\d+)\]\[(\w+)\]/);
        if (matches) {
          const index = matches[1];
          const field = matches[2];
          if (!data.addresses) data.addresses = [];
          if (!data.addresses[index]) data.addresses[index] = {};
          data.addresses[index][field] = value;
        }
      } else {
        data[key] = value;
      }
    });

    fetch('/create', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      })
      .then(async response => {
        if (!response.ok) {
          const errorData = await response.json();
          alert(errorData.message || 'Erro não catalogado');
          return;
        }

        alert('Cliente criado com sucesso!');
        window.location.href = '/';
      })
      .catch(err => {
        console.error(err);
        alert('Ocorreu um erro ao enviar o formulário.');
      });
  });
</script>