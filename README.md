# Vehicles SaaS - CRUD de Veículos em Laravel 12

Sistema de gerenciamento de veículos desenvolvido em Laravel 12, seguindo o padrão MVC com autenticação e rotas protegidas.

## Funcionalidades

- ✅ Autenticação de usuários (Login/Registro)
- ✅ CRUD completo de veículos
- ✅ Upload de múltiplas imagens por veículo
- ✅ Definição de imagem de capa
- ✅ Validação de placa (formato Mercosul)
- ✅ Validação de chassi (17 caracteres)
- ✅ Filtros por marca, modelo e busca global
- ✅ Ordenação por valor, km, data
- ✅ Auditoria (quem criou/alterou)
- ✅ Políticas de autorização (owner/admin)
- ✅ Interface responsiva com Tailwind CSS

## Requisitos

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL / PostgreSQL / SQLite

## Instalação

### 1. Clone o repositório

```bash
git clone <repository-url>
cd vehicles-saas
```

### 2. Instale as dependências PHP

```bash
composer install
```

### 3. Configure o ambiente

```bash
cp .env.example .env
```

Edite o arquivo `.env` e configure as variáveis de banco de dados:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vehicles_saas
DB_USERNAME=root
DB_PASSWORD=
```

Para usar SQLite (mais simples para testes):

```env
DB_CONNECTION=sqlite
# Comente ou remova as outras variáveis DB_*
```

```bash
touch database/database.sqlite
```

### 4. Gere a chave da aplicação

```bash
php artisan key:generate
```

### 5. Execute as migrations e seeders

```bash
php artisan migrate --seed
```

### 6. Crie o link simbólico para storage

```bash
php artisan storage:link
```

### 7. Instale as dependências frontend e compile os assets

```bash
npm install
npm run build
```

### 8. Inicie o servidor de desenvolvimento

```bash
php artisan serve
```

Acesse: http://localhost:8000

## Usuários de Teste

O seeder cria dois usuários para teste:

| Tipo     | Email               | Senha    |
|----------|---------------------|----------|
| Admin    | admin@example.com   | password |
| Usuário  | user@example.com    | password |

## Estrutura do Projeto

```
app/
├── Enums/
│   ├── Cambio.php           # Enum para tipos de câmbio
│   └── Combustivel.php      # Enum para tipos de combustível
├── Http/
│   ├── Controllers/
│   │   ├── VehicleController.php
│   │   └── VehicleImageController.php
│   └── Requests/
│       ├── StoreVehicleRequest.php
│       ├── UpdateVehicleRequest.php
│       └── StoreVehicleImageRequest.php
├── Models/
│   ├── User.php
│   ├── Vehicle.php
│   └── VehicleImage.php
└── Policies/
    └── VehiclePolicy.php

database/
├── factories/
│   ├── VehicleFactory.php
│   └── VehicleImageFactory.php
├── migrations/
│   ├── 2026_03_04_000001_add_is_admin_to_users_table.php
│   ├── 2026_03_04_000002_create_vehicles_table.php
│   └── 2026_03_04_000003_create_vehicle_images_table.php
└── seeders/
    ├── DatabaseSeeder.php
    └── VehicleSeeder.php

resources/views/
└── vehicles/
    ├── index.blade.php
    ├── create.blade.php
    ├── show.blade.php
    ├── edit.blade.php
    └── partials/
        └── form.blade.php
```

## Rotas Principais

| Método | URI                                      | Ação                    |
|--------|------------------------------------------|-------------------------|
| GET    | /vehicles                                | Listar veículos         |
| GET    | /vehicles/create                         | Formulário de criação   |
| POST   | /vehicles                                | Criar veículo           |
| GET    | /vehicles/{vehicle}                      | Ver detalhes            |
| GET    | /vehicles/{vehicle}/edit                 | Formulário de edição    |
| PUT    | /vehicles/{vehicle}                      | Atualizar veículo       |
| DELETE | /vehicles/{vehicle}                      | Excluir veículo         |
| POST   | /vehicles/{vehicle}/images               | Upload de imagens       |
| PATCH  | /vehicles/{vehicle}/images/{image}/cover | Definir capa            |
| DELETE | /vehicles/{vehicle}/images/{image}       | Remover imagem          |

## Validações

### Placa (Formato Mercosul)
- Regex: `/^[A-Z]{3}[0-9][A-Z0-9][0-9]{2}$/i`
- Exemplo: ABC1D23

### Chassi
- 17 caracteres alfanuméricos
- Sem letras I, O, Q (padrão internacional)

### Imagens
- Tipos permitidos: JPEG, JPG, PNG, GIF, WebP
- Tamanho máximo: 2MB por arquivo

## Políticas de Acesso

- **Visualizar**: Todos os usuários autenticados
- **Criar**: Todos os usuários autenticados
- **Editar/Excluir**: Apenas o dono do veículo ou administradores
- **Gerenciar imagens**: Apenas o dono do veículo ou administradores

## Tecnologias Utilizadas

- Laravel 12
- PHP 8.2+
- Blade Templates
- Tailwind CSS
- Laravel Breeze (autenticação)
- Alpine.js
- SQLite/MySQL/PostgreSQL

## Licença

Este projeto é open-source sob a licença MIT.
