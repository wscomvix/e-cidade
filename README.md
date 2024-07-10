## e-Cidade

O e-Cidade destina-se a informatizar a gestão dos Municípios Brasileiros de forma integrada. Esta informatização contempla a integração entre os entes municipais: Prefeitura Municipal, Câmara Municipal, Autarquias, Fundações e outros.

A economia de recursos é somente uma das vantagens na adoção do e-cidade, além da liberdade de escolha dos fornecedores e garantia de continuidade do sistema, uma vez apoiado pelo Ministério do Planejamento.

Este repositório é um clone de  https://github.com/e-cidade/e-cidade. 

> 
> As configurações desta instalação é para fins de estudo - uma instalação única. Para múltiplas instalações entre em contato 
> comigo pelo email wscomvix@gmail.com - WSCOM Consultoria Treinamento e Desenvolvimento - falar com Wanderlei Silva do Carmo (Wander)
> 

## Requisitos Mínimos

* Apache 2
* Navegador web:  (testado com Microsoft Edge e Firefox) 
* PHP 7.4.x 
* PostgreSQL 12.19.x 
* Ubuntu Linux 20.04.x ou superior


## Instalação
1. Atualize o sistema operacional - utilizamos como referência o Ubuntu 20.04 codenome - Focal
    ```
        apt update
        apt upgrade

    ```

    1.1 Ajuste locales
             
        ```
            sudo localedef -i pt_BR -c -f ISO-8859-1 -A /usr/share/locale/locale.alias pt_BR
            sudo locale-gen pt_BR
            sudo dpkg-reconfigure locales

            - escolha  pt_BR, pt_BR.ISO-8859-1, pt_BR-UTF-8

            export LC_ALL=pt_BR
            sudo echo LC_ALL=pt_BR >> /etc/environment   

        ```


2. Instale o php7.4
    - Adicione o repositório para baixar a versão 7.4 do PHP
        ```
            add-apt-repository ppa:ondrej/php
            apt update && apt upgrade
            apt install php7.4
        ```

    2.1. Adicione as extensões do PHP
        ```
            apt install php7.4-cli
            apt install php7.4-mbstring
            apt install php7.4-curl
            apt install php7.4-gd
            apt install php7.4-pgsql
            apt install php7.4-bcmath
            apt install php7.4-xml
            apt install php7.4-soap
            apt install php7.4-zip
            apt install php7.4-imagick
            apt install libcurl4
            apt install libapache2-mod-php7.4
            apt install zip
            apt install curl
        ```
    2.2.  Ajuste os parâmetros em /etc/php/7.4/apache2/php.ini
        
        ```
            sudo vim /etc/php/7.4/apache2/php.ini
 
            short_open_tag = On
            register_argc_argv = on
            post_max_size = 64M
            upload_max_filesize = 64M
            default_socket_timeout = 60000
            max_execution_time = 60000
            max_input_time = 60000
            memory_limit = 512M
            allow_call_time_pass_reference = on
            error_reporting = E_ALL & ~E_NOTICE
            display_errors = off
            log_errors = on
            error_log = /var/www/log/php-scripts.log
            session.gc_maxlifetime = 7200

        ```

3. Instale o Libreoffice

    ```
        sudo apt-get install libreoffice-writer python3-uno openjdk-17-jre-headless
        systemctl status rc-local
        sudo systemctl enable rc-local
        sudo systemctl start rc-local.service
        sudo systemctl status rc-local

    ```

3. Instale o composer (conforme recomendações do site)
    - copie o script abaixo
        ```
            php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
            php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
            php composer-setup.php
            php -r "unlink('composer-setup.php');"

            cp composer.phar /usr/local/bin/composer
        ```

4. Instale o Apache2
 
    ```
         apt install apache2
    ```
    4.1. Crie um arquivo em /etc/apache2/sites-available com o nome ecidade.conf e adicione o seguinte conteúdo:
       ```
       <VirtualHost *:80>
                
                 LimitRequestLine 16382
                 LimitRequestFieldSize 16382
                 Timeout 12000
                 AddDefaultCharset ISO-8859-1
                 SetEnv no-gzip 1
                 <Directory /var/www>
                     Options -Indexes +FollowSymLinks +MultiViews
                     AllowOverride All
                     Require all granted
                 </Directory>
         
                 ServerAdmin webmaster@localhost
                 DocumentRoot /var/www
         
                 ErrorLog /var/log/apache2/error.log
                 CustomLog /var/log/apache2/access.log combined
         
        </VirtualHost>
        
        ```
    4.2. execute os comandos:
       ```
           a2dissite 000-default
           a2ensite ecidade

           a2enmod rewrite
        
           chown -R www-data.www-data /var/www/tmp
           chmod -R 777 /var/www/tmp

           systemctl restart apache2

       ```    

5. Instale o PostgreSQL na versão 12.x
    ```
        
        apt install postgresql-12 postgresql-client-12 postgresql-common

        pg_dropcluster --stop 12 main

        pg_createcluster -e LATIN1 12 main
        
    ```

        5.1. Após instalado altere os parâmetros abaixo do arquivo /etc/postgresql/12/main/postgresql.conf:
    
    ```
        listen_address '*'
        include_if_exists = 'schemas_ecidade.conf'
        max_connections = 20
        bytea_output = 'escape'
        max_locks_per_transaction = 256
        escape_string_warning = off
        standard_conforming_strings = off
        sudo /etc/init.d/postgresql restart
    ```


4. Clone este repositório com o comando abaixo
    
    ```
       cd /tmp
       git clone https://github.com/wscomvix/e-cidade.git
       
    ```

    4.1. Mova a pasta clonada do repositório para /var/www e altere as permissões 
    ```
       mv /tmp/e-cidade  /var/www/
       
       cd /var/www/e-cidade

       cp .env.example .env  
       
       chown -R www-data:www-data /var/www/e-cidade
       chmod -R 775 /var/www/e-cidade
       chmod -R 777 /var/www/e-cidade/storage 

      # remova o diretorios vendor e o arquivo composer.lock
      rm -fr  /var/www/e-cidade/vendor
      rm -fr  /var/www/e-cidade/composer.lock

      #execute o composer install ou  composer update
      cd /var/www/e-cidade

      # confirme com enter o comando...
      composer update 

      # limpe os caches da aplicação
      php artisan clear
      php artisan cache:clear

      # Gere a chave
      php artisan key:generate


    ``
    4.2.  Copie o arquivo schemas_ecidade.conf para /etc/postgresql/12/main/ 
    
    ```
       cp /var/www/e-cidade/schemas_ecidade.conf /etc/postgresql/12/main
    ``
    4.3. Edite o arquivo /etc/postgresql/12/main/pg_hba.conf altere o parâmetro peer para trust 
    
    ```
        # DO NOT DISABLE!
        # If you change this first entry you will need to make sure that the
        # database superuser can access the database using some other method.
        # Noninteractive access to all databases is required during automatic
        # maintenance (custom daily cronjobs, replication, and similar tasks).
        #
        # Database administrative login by Unix domain socket
        local   all             postgres                                trust
        local   all             dbportal                                peer

        # TYPE  DATABASE        USER            ADDRESS                 METHOD

        # "local" is for Unix domain socket connections only
        local   all             all                                     trust
        # IPv4 local connections:
        host    all             all             127.0.0.1/32            trust
        # IPv6 local connections:
        host    all             all             ::1/128                 trust
        # Allow replication connections from localhost, by a user with the
        # replication privilege.
        local   replication     all                                     trust
        host    replication     all             127.0.0.1/32            trust
        host    replication     all             ::1/128                 trust
    
    ```

    4.4.  Reinicie o serviço Postgresql e execute os comandos para criação de usuários no Postgres
    ```
        systemctl restart postgresql

        psql -U postgres -h localhost template1 -c "create role ecidade with superuser login password 'ecidade'"

        psql -U postgres -h localhost template1 -c "create role dbportal with superuser login password 'dbportal'"

        psql -U postgres -h localhost template1 -c "create role dbseller with login password bseller'"

        psql -U postgres -h localhost template1 -c "create role plugin with login password 'plugin'"
        
        psql -U postgres -h localhost template1 -c "create role usersrole with login password 'usersrole'"
        
        createdb -U dbportal e-cidade

    ```

    4.5. Clone a base de dados do e-cidade

    ```
        
        cd /tmp

        git clone https://github.com/e-cidade/base-dados-ecidade.git

        entre na pasta...

        cd /tmp/base-dados-ecidade

        bzcat dump_ecidade-zerada.sql.bz2|psql -U dbportal -d e-cidade

        aguarde o final do restore...

    ```

5.0.  Ajuste o plugin  Desktop 3.0 para o usuário

```
    cd /var/www/
    chmod -R 775 /var/www/e-cidade/*
    chmod -R 777 /var/www/e-cidade/tmp/
    chmod -R 777 /var/www/e-cidade/storage/
    
    cd /var/www/e-cidade/
    cd /var/www/e-cidade/config/
    cp preferencias.json.dist preferencias.json
    cd /var/www/e-cidade/extension/data/extension/
    
    cp Desktop.data.dist Desktop.data  
    
    cd /var/www/e-cidade/extension/modification/data/modification/
    
    cp dbportal-v3-desktop.data.dist dbportal-v3-desktop.data

    /var/www/e-cidade/bin/v3/extension/install Desktop treinamento

```

5.1. Abra a URL no navegador de Edge ou Firefox  (testado e validado por esta instalação)
    http://localhost/e-cidade   (substitua 'localhost' pelo ip de seu VPS ou de sua máquina virtual)

5.2. Comunique se houver algum problema na instalação

    # contato: 
    >
    > E-mail: wscomvix@gmail.com  Assunto: instalação do e-cidade
    > Contato: WSCOMVIX - Wander
    >