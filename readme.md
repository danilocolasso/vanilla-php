# Vanilla PHP
### Requisitos do sistema:
  - PHP: ^7.0
  - MySQL: 5.7
  - ext-pdo: *,
  - ext-yaml: *
 
 Ou Docker instalado.

## Instalação

Clone o repositório

```
git clone https://github.com/danilocolasso/vanilla-php.git
cd vanilla-php
```

O Código fonte do projeto encontra-se na pasta /application.

### Docker - Configuração de ambiente (opcional)

  - Baixe e instale o [Docker](https://www.docker.com/products/docker-desktop). Caso não possua uma conta, [realize o cadastro](https://hub.docker.com/signup), será necessário.
  - Na pasta */docker*  encontram-se os arquivos para executar um container com toda a configuração necessária para o projeto.
  - Após instalado e configurado, certifique-se de que está na pasta */docker* e execute o seguinte comando:

```
docker-compose up -d
```
Pronto, seu ambiente php está configurado!
Para acessar o projeto basta ir até [localhost](http://localhost). Mas antes precisamos configurar o banco de dados e algumas dependências...

### Dependências
Para gerenciamento das dependências, utilizaremos o [Composer](https://getcomposer.org/). Caso não o tenha instalado, baixe e configure-o seguindo [estas instruções](https://getcomposer.org/doc/00-intro.md).
Após instalado, basta entrar na pasta do projeto (/application) e executar:
```
composer install
```
Pronto! O Composer irá instalar todas as dependências do projeto.

### Banco de Dados

Caso não possua nenhuma ferramenta para acesso ao MySQL, pode-se utilizar o container com o PhpMyAdmin. Basta acessar [localhost:8080](http://localhost:8080).

Vamos agora criar a database e as tables. 
Importe o arquivo "*database.sql*" que encontra-se na pasta */database/dump*.
Pode-se utilizar tanto o PhpMyAdmin quanto o terminal do container mysql (caso esteja utilizando o Docker). 

###### Com o Docker
Em seu terminal, execute:
```
docker-compose exec mysql /bin/bash
```
Com isto, estará dentro do terminal do container MySQL. Então execute:
```
mysql -u root -p < /home/dump/database.sql
```
Para sair do terminal do container basta utilizar o comando:
```sh
exit
```
###### Tudo certo, o projeto está pronto para ser executado. ;)

## Explicando o Projeto
###### Ps.: O foco do projeto é simplesmente desenvolver uma aplicação com PHP vanilla. Ou seja, apenas back-end.
##### Tecnologias utilizadas:

  - Composer para gerenciamento de dependências.
  - Twig para sistema de templates.
  -  Configuração de env em arquivos yaml.
  -  Docker como ambiente.

##### To do

 - Implementar os métodos PUT e DELETE no Router.
 - Service para validação de formulários. Muito provavelmente utilizaremos o Respect Validations.
 - Talvez criar entidades e preenche-las automaticamente ao fazer um select no banco de dados (uma espécie de ORM).
 - Tratar melhor as Exceptions.
 - Criar mensagens no front-end para confirmação e informações de sucesso/erro.
 - Gerar documentação (PHPDoc).
 - Testes unitários (PHPUnit).
 - Revisar todo o fonte em busca de melhorias.