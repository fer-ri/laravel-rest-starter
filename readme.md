# Laravel Rest Starter

## Installation
```
git clone https://github.com/ghprod/laravel-rest-starter laravel-rest-starter
cd laravel-rest-starter
cp .env.example .env
composer install
php artisan key:generate
php artisan jwt:generate
php artisan migrate
```

## Features
- Two method of auth
    - JWT
    - Private Token per user
- Completely auth process with JWT
    - Login
    - Logout
    - Register
    - Activation
    - Password recovery
    - Refresh token
    - Validate token
    - Facebook
- Split routes for `Web` and `API`
- Using Repository and Transformer for abstraction
- Command generator for create resource for basic CRUD
    - Controller
    - FormRequest
    - Repository
    - Model
    - Route
    - Transformer
- Use `UUID` for each entry database, so id is not revealed when access API
- Unit testing

## Testing
Go to installation folder and just run `vendor/bin/phpunit`

## TODO
- [ ] Role and authorization
- [x] Persistent settings (https://github.com/ghprod/laravel-settings)
- [ ] Test generator
- [ ] Migration generator
- [ ] Policy generator
- [ ] Add testing for generator
- [ ] Make resource filterable
- [ ] Make resource sortable
- [ ] Make resource orderable

