<?php

require_once __DIR__ . '/../Interfaces/RequestInterface.php';

class CreateCustomerRequest implements RequestInterface
{
  public static function validate(array $data): array
  {
    $errors = [];
    $validated = [];

    if (empty($data['nome'])) {
      $errors['name'] = "O nome é obrigatório.";
    } else {
      $validated['name'] = $data['nome'];
    }

    if (empty($data['cpf'])) {
      $errors['cpf'] = "O CPF é obrigatório.";
    } elseif (!preg_match('/^\d{11}$/', preg_replace('/\D/', '', $data['cpf']))) {
      $errors['cpf'] = "O CPF deve ter 11 dígitos numéricos.";
    } else {
      $validated['cpf'] = $data['cpf'];
    }

    if (empty($data['rg'])) {
      $errors['rg'] = "O RG é obrigatório.";
    } else {
      $validated['rg'] = $data['rg'];
    }

    if (empty($data['data_nascimento'])) {
      $errors['birth_date'] = "A data de nascimento é obrigatória.";
    } elseif (!DateTime::createFromFormat('Y-m-d', $data['data_nascimento'])) {
      $errors['birth_date'] = "A data de nascimento deve estar no formato YYYY-MM-DD.";
    } else {
      $validated['birth_date'] = $data['data_nascimento'];
    }

    if (empty($data['telefone'])) {
      $errors['phone'] = "O telefone é obrigatório.";
    } elseif (!preg_match('/^\d{10,11}$/', preg_replace('/\D/', '', $data['telefone']))) {
      $errors['phone'] = "O telefone deve ter 10 ou 11 dígitos.";
    } else {
      $validated['phone'] = $data['telefone'];
    }

    if (empty($data['enderecos']) || !is_array($data['enderecos'])) {
      $errors['addresses'] = "É necessário informar ao menos um endereço.";
    } else {
      $validated['addresses'] = [];
      foreach ($data['enderecos'] as $index => $address) {
        $validAddress = [];

        if (empty($address['cep'])) {
          $errors["addresses.$index.zip"] = "O CEP é obrigatório.";
        } else {
          $validAddress['zip'] = $address['cep'];
        }

        if (empty($address['cidade'])) {
          $errors["addresses.$index.city"] = "A cidade é obrigatória.";
        } else {
          $validAddress['city'] = $address['cidade'];
        }

        if (empty($address['pais'])) {
          $errors["addresses.$index.country"] = "O país é obrigatório.";
        } else {
          $validAddress['country'] = $address['pais'];
        }

        if (empty($address['logradouro'])) {
          $errors["addresses.$index.street"] = "O logradouro é obrigatório.";
        } else {
          $validAddress['street'] = $address['logradouro'];
        }

        if (empty($address['bairro'])) {
          $errors["addresses.$index.neighborhood"] = "O bairro é obrigatório.";
        } else {
          $validAddress['neighborhood'] = $address['bairro'];
        }

        if (empty($address['numero'])) {
          $errors["addresses.$index.number"] = "O número é obrigatório.";
        } else {
          $validAddress['number'] = $address['numero'];
        }

        if (!empty($validAddress)) {
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
