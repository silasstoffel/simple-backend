### Dependências

- Node JS;
- Yarn;
- Composer;

Após instalação das dependências, rodar os comandos:

`yarn`

`cd /src`

`composer install`

### Configurações

Duplicar os arquivos (\*.exemple) abaixo e renomear para extensão `.php`

`./src/config/config_ambiente.php.exemple` para `./src/config/config_ambiente.php`

`./src/config/config_db.php.exemple` para `./src/config/config_db.php`

`./src/config/config_php.php.example` para `./src/config/config_php.php`

### Builds

Rodar o comando quando for em modo de desenvolvimento
`npm run dev`
Após rodar o comando os fontes compilados ficarão no diretório **build**

Roda o comando em modo de desenvolvimento
`npm run dev`

Roda o comando em modo de produção
`npm run prod`

Após rodar o comando os fontes compilados ficarão no diretório **bin**

**Importante**

Se estiver usando windows, você pode facilitar o processo de build acessando diretamente os utilitários **build.dev.bat** e **build.prod.bat**

### API

- **Criar usuário**

`POST: /signup`

Parametros

```json
{
  "email": "email@hotmail.com",
  "password": "sua senha",
  "mobile_phone": "270000000",
  "name": "Nome"
}
```

Retorno de Sucesso (200)

```json
{
  "id": 4,
  "name": "XXXX",
  "email": "XXX",
  "mobile_phone": "XXX",
  "created_at": "2019-11-24 23:03:47",
  "updated_at": "2019-11-24 23:03:47",
  "active": 1
}
```

- **Recuperar usuário**

`GET: /v1/users/:id`

Retorno de Sucesso (200)

```json
{
  "id": 0,
  "name": "XXX",
  "email": "XXX",
  "mobile_phone": "XXX",
  "created_at": "2019-11-24 12:47:00",
  "updated_at": "2019-11-24 17:15:47",
  "active": 1
}
```

- **Alterar perfil do usuário logado**

Parametros:

```json
{
  "name": "XXXX",
  "mobile_phone": "XXXX"
}
```

Retorno de Sucesso (200)

```json
{
  "id": 1,
  "name": "XXX",
  "email": "XXX",
  "mobile_phone": "XXX",
  "created_at": "2019-11-24 12:47:00",
  "updated_at": "2019-11-24 17:15:47",
  "active": 1
}
```

- **Login (Criar token de acesso)**

Parametros:

```json
{
  "email": "email@gmail.com",
  "password": "sua senha"
}
```

Retorno de Sucesso (200)

```json
{
  "user": {
    "id": 1,
    "name": "XXXX",
    "email": "email@gmail.com",
    "mobile_phone": "27999999999",
    "active": 1
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1NzQ2MjEwNTUsImV4cCI6MTU3NDY0OTg1NSwiaWQiOjF9.MXI2EUtksjETiBPTegB-C4_jFCcGpgttxxwv5Dwzjhs"
}
```

### API - Erros

Em caso de acontecer algum erro nas requisições, o http status retornado será 400 a 499, quando esse status for reportado o retorno virá neste layout.

```json
{
  "error": true,
  "message": "Usuário existente"
}
```
