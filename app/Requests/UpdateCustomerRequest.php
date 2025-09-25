<?php

require_once __DIR__ . '/../Interfaces/RequestInterface.php';

class UpdateCustomerRequest implements RequestInterface
{
  public static function validate(array $data): array
  {
    $errors = [];
    $validated = [];

    if (isset($data['name']) && $data['name'] !== '') {
      $validated['name'] = $data['name'];
    } elseif (isset($data['name']) && $data['name'] === '') {
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

    if (isset($data['birth_date']) && $data['birth_date'] !== '') {
      if (!DateTime::createFromFormat('Y-m-d', $data['birth_date'])) {
        $errors['birth_date'] = "A data de nascimento deve estar no formato YYYY-MM-DD.";
      } else {
        $validated['birth_date'] = $data['birth_date'];
      }
    }

    if (isset($data['phone']) && $data['phone'] !== '') {
      if (!preg_match('/^\d{10,11}$/', preg_replace('/\D/', '', $data['phone']))) {
        $errors['phone'] = "O telefone deve ter 10 ou 11 dígitos.";
      } else {
        $validated['phone'] = $data['phone'];
      }
    }

    if (isset($data['addresses'])) {
      $validated['addresses'] = [];
      foreach ($data['addresses'] as $index => $address) {
        $validAddress = [];

        if (isset($address['id']) && $address['id'] !== '') {
          $validAddress['id'] = $address['id'];
        }

        if (isset($address['zip']) && $address['zip'] !== '') {
          if (!preg_match('/^\d{8}$/', preg_replace('/\D/', '', $address['zip']))) {
            $errors['zip'] = "O Cep deve ter 8 dígitos numéricos.";
          } else {
            $validAddress['zip'] = $address['zip'];
          }
        }

        if (isset($address['city']) && $address['city'] !== '') {
          $validAddress['city'] = $address['city'];
        }

        if (isset($address['country']) && $address['country'] !== '') {
          $validAddress['country'] = $address['country'];
        }

        if (isset($address['street']) && $address['street'] !== '') {
          $validAddress['street'] = $address['street'];
        }

        if (isset($address['state']) && $address['state'] !== '') {
          $validAddress['state'] = $address['state'];
        }

        if (isset($address['neighborhood']) && $address['neighborhood'] !== '') {
          $validAddress['neighborhood'] = $address['neighborhood'];
        }

        if (isset($address['number']) && $address['number'] !== '') {
          $validAddress['number'] = $address['number'];
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
