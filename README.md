# Mini Mercado

Sistema de e-commerce simples para venda de frutas.

## Stack

- Laravel 12
- Vue.js 3 + Inertia.js
- TailwindCSS v4
- SQLite
- Laravel Fortify (autenticação padrão)
- Mailjet (emails)

## O que faz

- Criar conta e fazer login
- Ver catálogo de produtos (frutas)
- Adicionar produtos ao carrinho
- Fazer checkout (pagamento com cartão)
- Ver histórico de compras
- Receber email com resumo da compra (assíncrono via queue)

## Ambiente de Desenvolvimento

Este projeto foi desenvolvido e testado com:

- **Windows** com **Laravel Herd**
- **PHP 8.4** (via Herd)
- **Node.js v22**

## Instalação

### 1. Instalar Laravel Herd

Download: https://herd.laravel.com/

Depois de instalar:
1. Abrir Laravel Herd
2. Ir em Settings
3. Baixar PHP 8.4 (se não tiver)
4. Selecionar PHP 8.4 como versão ativa

### 2. Instalar Node.js v22

Download: https://nodejs.org/

### 3. Clonar e instalar dependências

```bash
git clone https://github.com/joselmoises/mini-mercado.git
cd mini-mercado

composer install
npm install
```

### 4. Configurar ambiente

```bash
cp .env.example .env
php artisan key:generate
```

O `.env.example` já tem as credenciais Mailjet configuradas.

### 5. Criar arquivo da base de dados

```bash
New-Item database/database.sqlite
```

### 6. Criar tabelas e popular dados

```bash
php artisan migrate:fresh --seed
```

Isso vai criar:
- Tabelas necessárias (users, products, cart_items, orders, etc)
- 1 utilizador de teste: `test@example.com` / `password`
- 20 produtos (frutas)

### 7. Executar

O Laravel Herd já serve a aplicação automaticamente via Nginx. Apenas executar:

**Terminal 1 - Compilar assets (Vue/JS/CSS):**
```bash
npm run dev
```

**Terminal 2 - Processar emails:**
```bash
php artisan queue:work
```

Aceder: `http://mini-mercado.test`

## Como Testar

1. Criar uma conta nova com um email verdadeiro (ou usar `test@example.com` / `password`)
2. Fazer login
3. Adicionar frutas ao carrinho
4. Ir para o checkout
5. Selecionar "Cartão de Crédito"
6. Completar a compra
7. Verificar email de confirmação recebido
8. Ver histórico de pedidos

**Nota:** O pagamento é simulado, não processa nada real.

## Estrutura Básica

```
mini-mercado/
├── app/
│   ├── Http/Controllers/
│   │   ├── ProductController.php    # Lista e detalhes de produtos
│   │   ├── CartController.php       # Gestão do carrinho
│   │   └── OrderController.php      # Checkout e pedidos
│   ├── Models/
│   │   ├── Product.php
│   │   ├── CartItem.php
│   │   ├── Order.php
│   │   └── OrderItem.php
│   ├── Mail/
│   │   └── OrderConfirmation.php    # Email de confirmação
│   └── Policies/
│       ├── CartItemPolicy.php
│       └── OrderPolicy.php
│
├── resources/
│   ├── js/pages/
│   │   ├── auth/                    # Login e Registo
│   │   ├── Products/                # Catálogo
│   │   ├── Cart/                    # Carrinho
│   │   ├── Checkout/                # Finalizar compra
│   │   └── Orders/                  # Histórico
│   └── views/emails/
│       └── order-confirmation.blade.php
│
├── database/
│   ├── migrations/
│   └── seeders/
│       └── ProductSeeder.php        # 20 frutas
│
└── routes/
    └── web.php
```

## Base de Dados

**users** - Utilizadores (Laravel Fortify padrão)

**products** - Produtos disponíveis
- name, description, price, image, stock, is_available

**cart_items** - Itens no carrinho
- user_id, product_id, quantity

**orders** - Pedidos confirmados
- user_id, total, status, payment_method

**order_items** - Itens de cada pedido
- order_id, product_id, product_name, quantity, price

**jobs** - Fila de emails (queue worker)

## Métodos de Pagamento

No checkout aparecem 2 opções:
- **Cartão de Crédito** (ativo)
- **M-Pesa** (visível mas não implementado)

Apenas o cartão funciona. É uma simulação - não processa pagamento real.

## Controle de Stock

- Ao adicionar ao carrinho, valida se tem stock disponível
- No checkout, usa pessimistic locking para evitar vender mais do que existe
- Stock é decrementado automaticamente após compra

## Emails

Os emails de confirmação de pedido são enviados automaticamente para o endereço de email usado no registo.

**Importante:** O `php artisan queue:work` precisa estar rodando para processar o envio de emails.

## Notas

- Projeto testado **apenas no Windows** com **Laravel Herd**
- Autenticação usa Laravel Fortify padrão (criar conta e login)
- Credenciais Mailjet incluídas para facilitar testes
- Pagamento com cartão é simulado (não processa nada real)
- Stock controlado automaticamente
- Imagens são URLs de placeholder
