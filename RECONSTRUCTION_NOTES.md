# TBOT 2011 Reconstruction (PHP edition)

## Conversão e backend
- User table resolution updated to prioritize `bout_users` (with compatibility fallback).
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
- Botão de troca de senha (abre painel central)
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


## bout_users schema compatibility
- `bout_users.password` is treated as plain text in this deployment (no hashing).
- Login validates plain text username/password directly against the database.
- Account panel uses `coins` from `bout_users` as the Cash display value.


## Login form hardening (legacy PHP)
- Removed frontend dependency on `js2test()` from `index.php` login forms.
- Removed `md5.js` include from `index.php`.
- Removed legacy fields `passx` and `passw-clear` from login forms.
- Login now submits normal POST with only `user_id` and `passw`.
- MySQL connection charset set to `latin1` for legacy OrangeGame compatibility.


## Download area updates
- Full Game Client now points to: `https://mega.nz/file/LZ0HWQhK#-W1pqHKa0-geBTJuq3Ee2_IHCCQwKUY_q7D5LddwK1k`.
- Launcher download now points to: `https://mega.nz/file/2R1xiSYJ#6XKTSy-nmKbrRdn08MA1G64774MBKFH56hx6aKfY3-4`.
- Partial Client section disabled.
- Mirror partners section disabled.

## Rank area updates
- Restored an old-style visual fallback for ranks.
- Rankings are now rendered inside one dedicated wrapper div (`#rank-legacy-panel`) instead of free-floating layout.


## Account panel refresh
- Character data on the home account panel now prioritizes `bout_characters` (`name`, `level`, `gigas`).
- Password form was removed from the left account panel.
- The center panel `#register-center` is now shared:
  - logged out: registration form
  - logged in + Change Password: password update form
- Password updates still submit to `auth/update_account.php` using `action=password`.
