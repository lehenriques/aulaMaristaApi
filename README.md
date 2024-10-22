
# Aula de API

Projeto desenvolvido para ser utilizado em sala de aula no Colégio Marista Social Cascavel, este conteúdo esta sendo ministrado para os alunos do segundo ano do ensino médio técnico em desenvolvimento para web.

Para iniciar o projeto basta fazer o clone do projeto no seu computador na pasta onde esta configurado seu WAMP ou LAMP.

O projeto ainda esta sendo desenvolvido junto com os alunos, previsão de finalizar em Dezembro.

## Iniciando seu projeto

Primeiro passo realize  o clone do projeto exemplo abaixo
```git
git clone git@github.com:lehenriques/aulaMaristaApi.git
```

Segundo passo realize a configuração do arquivo "config.inc.php" localizado na pasta config.
```php
define('BASE_URL', 'url do seu projeto');

// dados de conexão com o banco de dados.
define('DB_HOST', 'localhost');
define('DB_BASE', 'nome do seu banco de dados');
define('DB_NAME', 'nome de usuário do banco');
define('DB_PASS', 'senha do usuário');
```

Depois basta acessar a url do seu projeto exemplo abaixo
```url
http://localhost/api/install
```