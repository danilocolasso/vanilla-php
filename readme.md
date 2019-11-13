# Test Back-end Webjump!
### Requisitos do sistema:
  - PHP ^7.0
  - pecl
  - libyaml-dev
  - yaml
  - MySQL 5.7

# Instalação

Clone o repositório

```
git clone https://danilocolasso@bitbucket.org/danilocolasso/assessment-backend.git webjump
```

O projeto encontra-se na pasta /app.

### Docker - Configuração de ambiente (opcional)

  - Baixe e instale o [Docker](https://www.docker.com/products/docker-desktop). Caso não possua uma conta, [realize o cadastro](https://hub.docker.com/signup), será necessário.
  - Na pasta raiz encontram-se os arquivos para executar um container com toda a configuração necessária para o projeto.
  - Após instalado e configurado, abra o terminal e execute o seguinte comando:

```
docker-compose up -d
```
Pronto, seu ambiente está configurado!
Para acessar o projeto basta ir até [localhost](http://localhost). Mas antes precisamos configurar o banco de dados e as dependências...

### Banco de Dados

Crie uma base com o nome "webjump".

##### Com o Docker
Pode-se importar o dump via PhpMyAdmin. Basta acessar [localhost:8080](http://localhost:8080).
Ou, em seu terminal, execute o seguinte comando:
```
docker-compose exec mysql mysql -u root -p webjump < database.sql
```

### Dependências
Para gerenciamento das dependências, utilizaremos o [Composer](https://getcomposer.org/). Caso não o tenha instalado, baixe e configure-o seguindo [estas instruções](https://getcomposer.org/doc/00-intro.md).
Após instalado, basta entrar na pasta do projeto (/app) e executar:
```
composer install
```
Pronto! O Composer irá instalar todas as dependências do projeto.

Agora basta configurar seu banco de dados no arquivo *parameters.yml*, que encontra-se na pasta /config (não necessário caso esteja utilizando o docker).

###### Tudo certo, o projeto está pronto para ser executado. ;)

# Explicando o Projeto
Tecnologias utilizadas:

  - Composer para gerenciamento de dependências.
  - Twig para sistema de templates.
  -  Configuração de env em arquivos yaml
  -  Docker como ambiente.

##### To do

 - Implementar os métodos PUT e DELETE no Router.
 - Service para validação de formulários. Muito provavelmente utilizaria o Respect Validations.
 - Talvez criar entidades e preenche-las automaticamente ao fazer um select no banco de dados (uma espécie de ORM).
 - Tratar melhor as Exceptions.
 - Criar mensagens no front-end para confirmação e informações de sucesso/erro.
 - Gerar documentação (PHPDoc).
 - Testes unitários (PHPUnit).
 - Revisar todo o fonte em busca de melhorias.