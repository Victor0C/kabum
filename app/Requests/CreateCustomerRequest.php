<?php

require_once __DIR__ . '/../Interfaces/RequestInterface.php';

class CreateCustomerRequest implements RequestInterface
{
  public static function validate(array $data): array
  {
    $errors = [];
    $validated = [];

    if (empty($data['name'])) {
      $errors['name'] = "O nome é obrigatório.";
    } else {
      $validated['name'] = $data['name'];
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

    if (empty($data['birth_date'])) {
      $errors['birth_date'] = "A data de nascimento é obrigatória.";
    } elseif (!DateTime::createFromFormat('Y-m-d', $data['birth_date'])) {
      $errors['birth_date'] = "A data de nascimento deve estar no formato YYYY-MM-DD.";
    } else {
      $validated['birth_date'] = $data['birth_date'];
    }

    if (empty($data['phone'])) {
      $errors['phone'] = "O telefone é obrigatório.";
    } elseif (!preg_match('/^\d{10,11}$/', preg_replace('/\D/', '', $data['phone']))) {
      $errors['phone'] = "O telefone deve ter 10 ou 11 dígitos.";
    } else {
      $validated['phone'] = $data['phone'];
    }

    if (empty($data['addresses']) || !is_array($data['addresses'])) {
      $errors['addresses'] = "É necessário informar ao menos um endereço.";
    } else {
      $validated['addresses'] = [];
      foreach ($data['addresses'] as $index => $address) {
        $validAddress = [];

        if (empty($address['zip'])) {
          $errors["addresses.$index.zip"] = "O CEP é obrigatório.";
        } elseif (!preg_match('/^\d{8}$/', preg_replace('/\D/', '', $address['zip']))) {
          $errors['zip'] = "O Cep deve ter 8 dígitos numéricos.";
        } else {
          $validAddress['zip'] = $address['zip'];
        }

        if (empty($address['city'])) {
          $errors["addresses.$index.city"] = "A cidade é obrigatória.";
        } else {
          $validAddress['city'] = $address['city'];
        }

        if (empty($address['country'])) {
          $errors["addresses.$index.country"] = "O país é obrigatório.";
        } else {
          $validAddress['country'] = $address['country'];
        }

        if (empty($address['street'])) {
          $errors["addresses.$index.street"] = "O logradouro é obrigatório.";
        } else {
          $validAddress['street'] = $address['street'];
        }

        if (empty($address['state'])) {
          $errors["addresses.$index.state"] = "O estado é obrigatório.";
        } else {
          $validAddress['state'] = $address['state'];
        }

        if (empty($address['neighborhood'])) {
          $errors["addresses.$index.neighborhood"] = "O bairro é obrigatório.";
        } else {
          $validAddress['neighborhood'] = $address['neighborhood'];
        }

        if (!empty($address['number']) && $address['number'] !== '') {
          $validAddress['number'] = $address['number'];
        }

        if (!empty($validAddress)) {
          $validated['addresses'][] = $validAddress;
        }
      }
    }

    if (!empty($errors)) {
      throw new ValidationException($errors);
    }

    return $validated;
  }
}
