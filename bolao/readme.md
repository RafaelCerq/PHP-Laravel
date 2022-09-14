# PHP: 7.4
# Lavavel: 5.6


- Criar Seed:
- php artisan make:seed TesteSeeder

- Rodar seed:
- php artisan db:seed

# Migrate
- php artisan migrate
- php artisan migrate:reset
- php artisan migrate --seed

# Caso tenha problemas de classes não encontradas ao executar comandos (exemplo: db:seed), execute os comando abaixo para ajustar o caminho relativo ao projeto para seus arquivos php.
- composer update 
- composer dump-autoload