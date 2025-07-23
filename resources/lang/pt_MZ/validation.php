<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines - Mozambique Portuguese  
    |--------------------------------------------------------------------------
    |
    | Mozambican Portuguese validation messages with mixed PT/BR terminology
    | adapted to local usage and cultural context.
    |
    */

    'accepted' => 'O campo :attribute deve ser aceite.',
    'accepted_if' => 'O campo :attribute deve ser aceite quando :other for :value.',
    'active_url' => 'O campo :attribute não é um URL válido.',
    'after' => 'O campo :attribute deve ser uma data posterior a :date.',
    'after_or_equal' => 'O campo :attribute deve ser uma data posterior ou igual a :date.',
    'alpha' => 'O campo :attribute deve conter apenas letras.',
    'alpha_dash' => 'O campo :attribute deve conter apenas letras, números, hífenes e sublinhados.',
    'alpha_num' => 'O campo :attribute deve conter apenas letras e números.',
    'array' => 'O campo :attribute deve ser uma matriz.',
    'ascii' => 'O campo :attribute deve conter apenas caracteres alfanuméricos e símbolos de byte único.',
    'before' => 'O campo :attribute deve ser uma data anterior a :date.',
    'before_or_equal' => 'O campo :attribute deve ser uma data anterior ou igual a :date.',
    'between' => [
        'array' => 'O campo :attribute deve ter entre :min e :max itens.',
        'file' => 'O campo :attribute deve ter entre :min e :max quilobytes.',
        'numeric' => 'O campo :attribute deve estar entre :min e :max.',
        'string' => 'O campo :attribute não pode ser maior que :max caracteres.',
    ],
    'max_digits' => 'O campo :attribute não pode ter mais que :max dígitos.',
    'mimes' => 'O campo :attribute deve ser um arquivo do tipo: :values.',
    'mimetypes' => 'O campo :attribute deve ser um arquivo do tipo: :values.',
    'min' => [
        'array' => 'O campo :attribute deve ter pelo menos :min itens.',
        'file' => 'O campo :attribute deve ter pelo menos :min quilobytes.',
        'numeric' => 'O campo :attribute deve ser pelo menos :min.',
        'string' => 'O campo :attribute deve ter pelo menos :min caracteres.',
    ],
    'min_digits' => 'O campo :attribute deve ter pelo menos :min dígitos.',
    'missing' => 'O campo :attribute deve estar em falta.',
    'missing_if' => 'O campo :attribute deve estar em falta quando :other for :value.',
    'missing_unless' => 'O campo :attribute deve estar em falta a menos que :other seja :value.',
    'missing_with' => 'O campo :attribute deve estar em falta quando :values estiver presente.',
    'missing_with_all' => 'O campo :attribute deve estar em falta quando :values estiverem presentes.',
    'multiple_of' => 'O campo :attribute deve ser um múltiplo de :value.',
    'not_in' => 'O valor selecionado para :attribute é inválido.',
    'not_regex' => 'O formato do campo :attribute é inválido.',
    'numeric' => 'O campo :attribute deve ser um número.',
    'password' => [
        'letters' => 'O campo :attribute deve conter pelo menos uma letra.',
        'mixed' => 'O campo :attribute deve conter pelo menos uma letra maiúscula e uma minúscula.',
        'numbers' => 'O campo :attribute deve conter pelo menos um número.',
        'symbols' => 'O campo :attribute deve conter pelo menos um símbolo.',
        'uncompromised' => 'A :attribute fornecida apareceu numa violação de dados. Por favor escolha uma :attribute diferente.',
    ],
    'present' => 'O campo :attribute deve estar presente.',
    'present_if' => 'O campo :attribute deve estar presente quando :other for :value.',
    'present_unless' => 'O campo :attribute deve estar presente a menos que :other seja :value.',
    'present_with' => 'O campo :attribute deve estar presente quando :values estiver presente.',
    'present_with_all' => 'O campo :attribute deve estar presente quando :values estiverem presentes.',
    'prohibited' => 'O campo :attribute é proibido.',
    'prohibited_if' => 'O campo :attribute é proibido quando :other for :value.',
    'prohibited_unless' => 'O campo :attribute é proibido a menos que :other esteja em :values.',
    'prohibits' => 'O campo :attribute proíbe :other de estar presente.',
    'regex' => 'O formato do campo :attribute é inválido.',
    'required' => 'O campo :attribute é obrigatório.',
    'required_array_keys' => 'O campo :attribute deve conter entradas para: :values.',
    'required_if' => 'O campo :attribute é obrigatório quando :other for :value.',
    'required_if_accepted' => 'O campo :attribute é obrigatório quando :other for aceite.',
    'required_if_declined' => 'O campo :attribute é obrigatório quando :other for rejeitado.',
    'required_unless' => 'O campo :attribute é obrigatório a menos que :other esteja em :values.',
    'required_with' => 'O campo :attribute é obrigatório quando :values estiver presente.',
    'required_with_all' => 'O campo :attribute é obrigatório quando :values estiverem presentes.',
    'required_without' => 'O campo :attribute é obrigatório quando :values não estiver presente.',
    'required_without_all' => 'O campo :attribute é obrigatório quando nenhum dos :values estiver presente.',
    'same' => 'Os campos :attribute e :other devem coincidir.',
    'size' => [
        'array' => 'O campo :attribute deve conter :size itens.',
        'file' => 'O campo :attribute deve ter :size quilobytes.',
        'numeric' => 'O campo :attribute deve ser :size.',
        'string' => 'O campo :attribute deve ter :size caracteres.',
    ],
    'starts_with' => 'O campo :attribute deve começar com um dos seguintes: :values.',
    'string' => 'O campo :attribute deve ser uma string.',
    'timezone' => 'O campo :attribute deve ser um fuso horário válido.',
    'ulid' => 'O campo :attribute deve ser um ULID válido.',
    'unique' => 'O :attribute já está sendo usado.',
    'uploaded' => 'O :attribute falhou no upload.',
    'uppercase' => 'O campo :attribute deve estar em maiúsculas.',
    'url' => 'O campo :attribute deve ser um URL válido.',
    'uuid' => 'O campo :attribute deve ser um UUID válido.',

    /*
    |--------------------------------------------------------------------------
    | Lusophone-Specific Validation Rules - Mozambique
    |--------------------------------------------------------------------------
    */

    'lusophone_tax_id' => 'O :attribute não é um NUIT válido.',
    'lusophone_phone' => 'O :attribute não é um número de celular válido.',
    'lusophone_postal' => 'O :attribute não é um código postal válido.',
    'nif_portugal' => 'O :attribute deve ser um NIF português válido.',
    'nuit_mozambique' => 'O :attribute deve ser um NUIT válido.',
    'cpf_brazil' => 'O :attribute deve ser um CPF válido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes - Mozambique
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'address' => 'endereço',
        'age' => 'idade',
        'city' => 'cidade',
        'content' => 'conteúdo',
        'country' => 'país',
        'date' => 'data',
        'description' => 'descrição',
        'email' => 'email',
        'first_name' => 'primeiro nome',
        'last_name' => 'último nome',
        'mobile' => 'celular',
        'name' => 'nome',
        'password' => 'senha',
        'password_confirmation' => 'confirmação da senha',
        'phone' => 'celular',
        'title' => 'título',
        'username' => 'nome de usuário',

        // Mozambique-specific attributes
        'tax_id' => 'NUIT',
        'nif' => 'NIF',
        'nuit' => 'NUIT',
        'cpf' => 'CPF',
        'postal_code' => 'código postal',
        'current_password' => 'senha actual',
        'new_password' => 'nova senha',
        'confirm_password' => 'confirmar senha',
    ],

];