# TODOList-Symfony

TODO tasks List practical project using Symfony

### Install

    git clone https://github.com/PierreCharles/TODOList-Symfony.git

    cd TODOList-Symfony/

    composer install

### Symfony project    

Each directory has its own purpose (and set of files):

- **app** contains the application kernel, views, and the configuration
- **src/** contains bundles
- **var/** contains files that change often (like in Unix systems)
- **vendor/** contains dependencies
- **web/** contains your front controllers and your assets.


### Database installation with Docker

    docker run -d \
        --volume /var/lib/mysql \
        --name data_mysql \
        --entrypoint /bin/echo \
        busybox \
        "mysql data-only container"

Initialize MySQL with a user/password and a database

    docker run -d -p 3306 \
      --name mysql \
      --volumes-from data_mysql \
      -e MYSQL_USER=todolist \
      -e MYSQL_PASS=password \
      -e ON_CREATE_DB=todolist \
      tutum/mysql

Executing your SQL from the command line

    mysql utodolist -utodolist -ppassword < app/config/schema.sql

### Launch server

    php bin/console server:run

### Fixer

    php vendor/fabpot/php-cs-fixer/php-cs-fixer fix src/



