# Backend (PHP) Simples

# Dependências

- Node JS;
- Yarn;
- Composer;
- Apache
- PHP 5.6+ (recomendado 7.3). (Tem dockerfile caso queira usar PHP + Apache)
- Servidor Web (se for usar apache lembre-se de habiliar módulo headers (CORS))

Após instalação das dependências, rodar os comandos:

```shell
yarn
cd /src
composer install
```

## Configurações

Duplicar os arquivos (\*.exemple) abaixo e renomear para extensão `.php`

`./src/config/config_ambiente.php.exemple` para `./src/config/config_ambiente.php`

`./src/config/config_db.php.exemple` para `./src/config/config_db.php`

`./src/config/config_php.php.example` para `./src/config/config_php.php`

Criar o banco de dados com script **database.sql**

## Builds

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

## API

**Importante**

Em todas as requisições em que houver erro http >= 400, um objeto como este será retornado:

Http status: >=400:

```json
{
  "error": true,
  "message": "string"
}
```

##

**Criar usuário**

**POST: /signup**

#

**Request**

```json
{
  "email": "email@hotmail.com",
  "password": "sua senha",
  "mobile_phone": "270000000",
  "name": "Nome"
}
```

**Response**

http status: 200:

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

**Login (Criar token de acesso)**

`POST /session`

**Request**

```json
{
  "email": "email@gmail.com",
  "password": "sua senha"
}
```

**Response**

http status: 200:

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

Depois da autenticação com sucesso, em todas às requisições devem ser enviado no header da requisição o token retornado do autenticação.

```shell
Authorization: Bearer <token>
```

Para maiores informações consulte [HTTP authentication scheme](https://developer.mozilla.org/en-US/docs/Web/HTTP/Authentication)

##

Todas às requisições abaixo requer o token (jwt) enviado

##

**Recuperar usuário**

**GET: /v1/users/:id**

**Response**

http status: 200:

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

##

**Alterar perfil do usuário logado**

**PUT: /users/update/me'**

**Request**

```json
{
  "name": "XXXX",
  "mobile_phone": "XXXX"
}
```

**Response**

http status: 200:

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

##

**Excluir perfil do usuário logado**

**DELETE: /users/delete/me'**

**Response**

http status: 200: usuário excluido

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

## Docker

Para montar ambiente com docker use às variáveis definidas no arquivo **docker-compose.env.example** e parametrize essas variáveis no seu arquivo **.env** (se não tiver .env na raíz, então é necessário criar). Feito a parametrização, rode o comando para montar os containers.

```bash
# Para iniciar
docker-compose up -d

# Para finalizar
docker-compose stop
```

## TESTS

Cobertura de testes de _API_

```
cd /src

php vendor/bin/codecept run api

php vendor/bin/codecept run api --steps
```

Framework de Tests: https://codeception.com/docs/10-APITesting
