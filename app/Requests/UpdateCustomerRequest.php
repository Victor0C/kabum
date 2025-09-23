<?php

require_once __DIR__ . '/../Interfaces/RequestInterface.php';

class UpdateCustomerRequest implements RequestInterface
{
  public static function validate(array $data): array
  {
    $errors = [];
    $validated = [];

    if (isset($data['nome']) && $data['nome'] !== '') {
      $validated['name'] = $data['nome'];
    } elseif (isset($data['nome']) && $data['nome'] === '') {
      $errors['name'] = "O nome não pode ser vazio.";
    }

    if (isset($data['cpf']) && $data['cpf'] !== '') {
      if (!preg_match('/^\d{11}$/', preg_replace('/\D/', '', $data['cpf']))) {
        $errors['cpf'] = "O CPF deve ter 11 dígitos numéricos.";
      } else {
        $validated['cpf'] = $data['cpf'];
      }
    }

    if (isset($data['rg']) && $data['rg'] !== '') {
      $validated['rg'] = $data['rg'];
    }

    if (isset($data['data_nascimento']) && $data['data_nascimento'] !== '') {
      if (!DateTime::createFromFormat('Y-m-d', $data['data_nascimento'])) {
        $errors['birth_date'] = "A data de nascimento deve estar no formato YYYY-MM-DD.";
      } else {
        $validated['birth_date'] = $data['data_nascimento'];
      }
    }

    if (isset($data['telefone']) && $data['telefone'] !== '') {
      if (!preg_match('/^\d{10,11}$/', preg_replace('/\D/', '', $data['telefone']))) {
        $errors['phone'] = "O telefone deve ter 10 ou 11 dígitos.";
      } else {
        $validated['phone'] = $data['telefone'];
      }
    }

    if (isset($data['enderecos'])) {
      if (!is_array($data['enderecos']) || empty($data['enderecos'])) {
        $errors['addresses'] = "É necessário informar ao menos um endereço.";
      } else {
        $validated['addresses'] = [];
        foreach ($data['enderecos'] as $index => $address) {
          if (!isset($address['id'])) {
            $errors["addresses.$index.id"] = "O ID do endereço é obrigatório.";
            continue;
          }
          
          $validAddress = ['id' => $address['id']];

          if (isset($address['cep']) && $address['cep'] !== '') {
            $validAddress['zip'] = $address['cep'];
          }

          if (isset($address['cidade']) && $address['cidade'] !== '') {
            $validAddress['city'] = $address['cidade'];
          }

          if (isset($address['pais']) && $address['pais'] !== '') {
            $validAddress['country'] = $address['pais'];
          }

          if (isset($address['logradouro']) && $address['logradouro'] !== '') {
            $validAddress['street'] = $address['logradouro'];
          }

          if (isset($address['bairro']) && $address['bairro'] !== '') {
            $validAddress['neighborhood'] = $address['bairro'];
          }

          if (isset($address['numero']) && $address['numero'] !== '') {
            $validAddress['number'] = $address['numero'];
          }

          $validated['addresses'][] = $validAddress;
        }
      }
    }

    if (!empty($errors)) {
      throw new Exception(json_encode($errors), 422);
    }

    return $validated;
  }
}
