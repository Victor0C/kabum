# Projeto Kabum

Sistema desenvolvido em **PHP 8.4**.

---

## Requisitos

- PHP 8.4
- MySQL ou MariaDB

---

## Configuração do Projeto

1. Crie o arquivo `.env` na raiz do projeto e preencha as informações conforme o `.env.example`:

```env
BASE_URL=http://127.0.0.1/
DB_HOST=127.0.0.1
DB_USER=root
DB_PASS=
DB_NAME=kabum
```

3. Popule o banco de dados e crie as tabelas executando o seeder:

```bash
php DatabaseSeeder.php
```

4. Suba o servidor PHP local:

```bash
php -S localhost:8000 -t public/
```

---

## Usuário de teste

Após rodar o seeder, você pode acessar o sistema com:

- **Email:** admin@admin.com  
- **Senha:** 123

---

## Rodando os Testes

Para executar os testes automatizados, rode:

```bash
php app/Tests/Test.php
```

Todos os testes irão verificar funcionalidades do `CustomerService` e do `AuthService`.

---

## Observações

- Certifique-se de que o banco definido no `.env` exista antes de rodar o `DatabaseSeeder.php`.  
