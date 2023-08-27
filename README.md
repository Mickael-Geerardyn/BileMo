# BileMo

# ⚙️ Installation
____________________
### Requirement 

- PHP 8.1.0 or higher
- You can check all requirements here:
https://symfony.com/releases/6.3

#### Installation :

click on the green "code" button on the top

copy the "https" link then clone the projet in your root directory local project like this

```bash
git clone https://github.com/Mickael-Geerardyn/BileMo.git
```

Install project with
```bash
composer install
```

Create the .env.local file in root directory with
```bash
cp .env .env.local
```

Then add your database informations
```bash
DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=15&charset=utf8"
```

Create the database
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:schema:update --force
```

Generate the SSL keys for LexikJWTAuthenticator
```bash
php bin/console lexik:jwt:generate-keypair
```

You can check the documentation here: https://symfony.com/bundles/LexikJWTAuthenticationBundle/current/index.html#generate-the-ssl-keys

Load fixtures to fill in the database
```bash
php bin/console hautelook:fixtures:load  
```

Load symfony server in the root project
```bash
symfony server:start
```

You'll can access to Swagger Api documentation from this link : http://localhost:8000/api/doc or https://github.com/Mickael-Geerardyn/BileMo/blob/main/Documentation-BileMo.pdf.
