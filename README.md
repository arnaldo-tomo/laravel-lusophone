# Laravel PT-PT Localization

📦 **Ficheiros de idioma em Português de Portugal (pt-PT) para Laravel**

[![Latest Version on Packagist](https://img.shields.io/packagist/v/arnaldotomo/laravel-pt-pt-localization.svg?style=flat-square)](https://packagist.org/packages/arnaldotomo/laravel-pt-pt-localization)
[![Total Downloads](https://img.shields.io/packagist/dt/arnaldotomo/laravel-pt-pt-localization.svg?style=flat-square)](https://packagist.org/packages/arnaldotomo/laravel-pt-pt-localization)

## 🇵🇹 Sobre

Este pacote fornece traduções completas do Laravel para **Português de Portugal (pt-PT)**, incluindo todas as mensagens de validação, autenticação, paginação e outras strings padrão do framework.

### Diferenças principais do Português do Brasil (pt-BR):
- Uso de "correio electrónico" em vez de "e-mail"
- "Telemóvel" em vez de "celular"  
- "Utilizador" em vez de "usuário"
- "Palavra-passe" em vez de "senha"
- "Ficheiro" em vez de "arquivo"
- Concordância verbal e nominal específica do português europeu

## 📋 Versões Suportadas

| Laravel | Versão do Pacote |
|---------|------------------|
| 12.x    | ✅ 1.0+         |
| 11.x    | ✅ 1.0+         |
| 10.x    | ✅ 1.0+         |

## 📥 Instalação

### 1. Instalar via Composer

```bash
composer require arnaldotomo/laravel-pt-pt-localization --dev
```

### 2. Publicar as traduções

Para Laravel 11.x e 12.x:

```bash
# Primeiro, publicar a estrutura de idiomas
php artisan lang:publish

# Depois, publicar as traduções PT-PT
php artisan vendor:publish --tag=laravel-pt-pt-localization
```

Para Laravel 10.x e anteriores:

```bash
php artisan vendor:publish --tag=laravel-pt-pt-localization
```

### 3. Configurar o idioma

**Para Laravel 11.x e 12.x**, altere o ficheiro `.env`:

```env
APP_LOCALE=pt_PT
```

**Para Laravel 10.x e anteriores**, altere a linha no ficheiro `config/app.php`:

```php
'locale' => 'pt_PT',
```

## 📁 Estrutura dos Ficheiros

O pacote inclui traduções para:

```
resources/lang/pt_PT/
├── auth.php              # Autenticação
├── pagination.php        # Paginação
├── passwords.php         # Redefinição de palavra-passe
└── validation.php        # Validação de formulários
```

## 🚀 Utilização

Após a instalação, todas as mensagens do Laravel serão automaticamente apresentadas em Português de Portugal:

```php
// Mensagens de validação
$validator = Validator::make($data, [
    'email' => 'required|email',
    'name' => 'required|string|max:255'
]);

// Mensagens de autenticação
Auth::attempt($credentials);

// Paginação
$users = User::paginate(15);
```

## 🌟 Exemplos de Traduções

### Validação
```
- "O campo e-mail é obrigatório." (pt-BR)
+ "O campo correio electrónico é obrigatório." (pt-PT)

- "A confirmação da senha não confere." (pt-BR) 
+ "A confirmação da palavra-passe não confere." (pt-PT)
```

### Autenticação
```
- "Essas credenciais não conferem com nossos registros." (pt-BR)
+ "Estas credenciais não conferem com os nossos registos." (pt-PT)
```

### Atributos Personalizados
O pacote inclui traduções para atributos comuns:

- `email` → `correio electrónico`
- `password` → `palavra-passe`
- `phone` → `telefone`
- `mobile` → `telemóvel`
- `address` → `morada`

## 🔧 Personalização

Pode personalizar as traduções editando os ficheiros em `resources/lang/pt_PT/` após a publicação.

### Adicionar traduções personalizadas:

```php
// resources/lang/pt_PT/validation.php
'custom' => [
    'email' => [
        'required' => 'Por favor insira o seu endereço de correio electrónico.',
    ],
],
```

## 🤝 Contribuição

Contribuições são muito bem-vindas! Para contribuir:

1. Faça um Fork do projecto
2. Crie uma branch para a sua funcionalidade (`git checkout -b feature/nova-funcionalidade`)
3. Commit as suas alterações (`git commit -am 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Crie um Pull Request

### Como contribuir com traduções:

- Verifique a consistência terminológica com o português europeu
- Mantenha a formalidade adequada (uso de "Vossa Excelência" vs "Você")
- Teste as traduções em diferentes contextos

## 📜 Licença

Este projecto está licenciado sob a [Licença MIT](LICENSE.md).

## 👨‍💻 Autor

**Arnaldo Tomo**
- GitHub: [@arnaldotomo](https://github.com/arnaldotomo)
- LinkedIn: [Arnaldo Tomo](https://linkedin.com/in/arnaldotomo)

## 🙏 Agradecimentos

Inspirado no excelente trabalho de [lucascudo/laravel-pt-BR-localization](https://github.com/lucascudo/laravel-pt-BR-localization) para português brasileiro.

## 📊 Estatísticas

- ✅ 100+ mensagens traduzidas
- 🎯 Terminologia consistente com português europeu
- 🔄 Actualização regular com novas versões do Laravel
- 📱 Suporte completo para aplicações web e mobile

---

🇵🇹 **Feito com ❤️ em Moçambique para a comunidade lusófona**
