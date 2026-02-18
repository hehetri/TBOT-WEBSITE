# TBOT 2011 Reconstruction (PHP edition)

## Conversão e backend
- Site convertido para `.php` mantendo a estrutura histórica.
- Banco configurado em `config.php`:
  - host: `127.0.0.1`
  - user: `bout`
  - pass: `202040pp`
  - db: `bout_evolution`
- Login principal conectado em `auth/login.php`.
- Registro inline conectado em `auth/register_inline.php`.
- Ranking dinâmico em `ranks/index.php`.

## Ajustes solicitados de layout/fluxo
- **Registro no meio da index**:
  - criado bloco central `#register-center` dentro da coluna principal da home.
- **Login na área marcada em vermelho**:
  - a box lateral esquerda (`.o_login`) permanece como área de login.
  - após autenticar, a mesma box mostra dados da conta.

## Dados exibidos após login
Na box esquerda, após login, exibe:
- Nome do Character
- Level
- Cash
- Gigas

Também inclui ações:
- Troca de senha
- Troca de email
- Logout

## Arquivos principais
- `index.php`
- `config.php`
- `auth/login.php`
- `auth/register_inline.php`
- `auth/update_account.php`
- `auth/logout.php`
- `ranks/index.php`

## Executar local
```bash
php -S 0.0.0.0:8000 -t .
```
