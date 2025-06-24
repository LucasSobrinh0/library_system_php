composer install

php app/console doctrine:schema:update --force   

php app/console doctrine:schema:update --dump-sql


Como criar um projeto em symfony 2.x

composer create-project symfony/framework-standard-edition library_system "2.8.*" --no-interaction

Criar getters e setters automaticamente no symfony

php app/console doctrine:generate:entities AppBundle

Listar arquivos temporários

Get-ChildItem -Recurse -Filter '*~'

Remover eles

Get-ChildItem -Recurse -Filter '*~' | Remove-Item -Force

1. Criar os Repositórios (opcional)

Se precisar de consultas customizadas, crie repositórios para suas entidades:

php app/console doctrine:generate:entities AppBundle:Loan

2. Criar os Formulários

Para cada entidade que será manipulada por formulários (Book, Category, Author, User, etc):

php app/console doctrine:generate:form AppBundle:Loan

3. Criar os Controllers

Crie os controllers com ações list, new, edit, delete, por exemplo:

php app/console generate:controller --controller=Loan --route-format=annotation

Digite AppBundle:{nome do controlador}

Ativar composer

cd C:\xampp\htdocs\library_system>

Instalar composer

composer install

parameters:
    database_driver: pdo_mysql
    database_host: 127.0.0.1
    database_port: null
    database_name: library
    database_user: root
    database_password: ""

CREATE DATABASE library CHARACTER SET utf8 COLLATE utf8_general_ci;

2. Gerar os arquivos de metadados (se necessário)

Execute:

php app/console doctrine:generate:entities AppBundle

3. Atualizar o schema no banco de dados

Agora crie as tabelas no banco com:

php app/console doctrine:schema:update --force

php app/console doctrine:schema:update --dump-sql

Accessar página do sistema online

http://localhost/library_system_php/web/app_dev.php

Debugar rotas
php app/console router:debug
