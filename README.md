# Instalação do e-Cidade

## O que é o e-Cidade ?

O e-Cidade destina-se a informatizar a gestão dos Municípios Brasileiros de forma integrada. Esta informatização contempla a integração entre os entes municipais: Prefeitura Municipal, Câmara Municipal, Autarquias, Fundações e outros.

A economia de recursos é somente uma das vantagens na adoção do e-cidade, além da liberdade de escolha dos fornecedores e garantia de continuidade do sistema, uma vez apoiado pelo Ministério do Planejamento.

Este repositório é um clone de  https://github.com/e-cidade/e-cidade disponibilizado pela empresa Contass. 

> 
> As configurações desta instalação é para fins de estudo - uma instalação única. Para múltiplas instalações entre em contato comigo
> WSCOM Consultoria Treinamento e Desenvolvimento - falar com Wanderlei Silva do Carmo (Wander)
> E-mail: wscomvix@gmail.com
> Site: https://wscom.dev.br
>

## Requisitos Mínimos

* Apache 2
* Navegador web:  (testado com Microsoft Edge e Firefox) 
* PHP 7.4.x 
* PostgreSQL 12.19.x 
* Ubuntu Linux 20.04.x ou superior
* Para editar os arquivos utilize o editor nano ou vim (vi)
* Os comando deve ser executados como superusuário. Para isso execute o sudo antes de digitar os comandos ou simplesmente digite o comando sudu su, entre com sua senha de administrador. Assim, não mais necessário utilzar o sudo
* Se entrar na VPS ou na máquina virtual local como "root" também não é necessário digitar o comando "sudo" antes dos comandos.

## Instalação
1. Atualize o sistema operacional - utilizamos como referência o Ubuntu 20.04 codenome - Focal
    ```
        #Atualize a lista de pacotes do Ubuntu
        apt update

        #Instale o editor Vi
        apt install vim-nox

        #Faça upgrade do sistema operacional
        apt upgrade
       
    ```

    1.1 Ajuste locales
             
        ```

            sudo localedef -i pt_BR -c -f ISO-8859-1 -A /usr/share/locale/locale.

            alias pt_BR

            sudo locale-gen pt_BR

            sudo dpkg-reconfigure locales


            - escolha  pt_BR, pt_BR.ISO-8859-1, pt_BR-UTF-8

            export LC_ALL=pt_BR

            sudo echo LC_ALL=pt_BR >> /etc/environment   


        ```


2. Instale o php7.4

    - Adicione o repositório para baixar a versão 7.4 do PHP

        ```
    
            1. add-apt-repository ppa:ondrej/php

            2. apt update && apt upgrade

            3. apt install php7.4

        ```


    2.1. Adicione as extensões do PHP

        ```
    
            1. apt install php7.4-cli

            2. apt install php7.4-mbstring

            3. apt install php7.4-curl

            4. apt install php7.4-gd

            5. apt install php7.4-pgsql

            6. apt install php7.4-bcmath

            7. apt install php7.4-xml

            8. apt install php7.4-soap

            9. apt install php7.4-zip

            10. apt install php7.4-imagick

            11. apt install libcurl4

            12. apt install libapache2-mod-php7.4

            13. apt install zip

            14. apt install curl

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

4. Instale o composer (conforme recomendações do site)
   - copie o script abaixo:

        ```
            php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

            php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
            php composer-setup.php

            php -r "unlink('composer-setup.php');"

            cp composer.phar /usr/local/bin/composer

        ```

5. Instale o Apache2
 
    ```
         apt install apache2

         #crie os diretórios "log" e "tmp" em /var/www
         1. mkdir /var/www/log

         2. mkdir /var/www/tmp


    ```

    5.1. Crie o /etc/apache2/sites-available/ecidade.conf:
    
        1. vi /etc/apache2/sites-available/ecidade.conf 

        2. Adicione o seguinte conteúdo nele:

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
    5.2. execute os comandos:

       ```
           a2dissite 000-default
           a2ensite ecidade

           a2enmod rewrite
        
           chown -R www-data.www-data /var/www/tmp
           chmod -R 777 /var/www/tmp

           systemctl restart apache2

       ```    

6. Instale o PostgreSQL na versão 12.x

    ```
        
        apt install postgresql-12 postgresql-client-12 postgresql-common

        pg_dropcluster --stop 12 main

        pg_createcluster -e LATIN1 12 main
        
    ```

    6.1. Após instalado altere os parâmetros abaixo do arquivo /etc/postgresql/12/main/postgresql.conf:

        1. vi /etc/postgresql/12/main/postgresql.conf
    

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
    6.2. Habilite o módulo php7.4 no apache2.
        
        1. a2enmod php7.4  (se houver alguma versão anterior do módulo, desabilite-a com o comando a2dismod phpX, por exemplo, onde o "X" representa o número da versão do módulo.)
        
        > 
        > se for exibido o erro de versão do PHP no navegador quando abrir o sistema pela primeira vez, pode ser devido ao módulo do apache2 na versão incorreta.
        >
        


7. Clone este repositório com o comando abaixo
    
   

    7.1. Mova a pasta clonada do repositório para /var/www e altere as permissões:

    ```
    
        1. cd /tmp

        2. git clone https://github.com/wscomvix/e-cidade.git
       
    
        3. mv /tmp/e-cidade  /var/www/
        
        4. cd /var/www/e-cidade

        5. cp .env.example .env  (após a instalação ajuste os parâmetros nome de usuário e senha do banco, entre outros, se for diferente do padrão)
        
        6. chown -R www-data:www-data /var/www/e-cidade

        7. chmod -R 775 /var/www/e-cidade

        8. chmod -R 777 /var/www/e-cidade/storage 

        # remova o diretorios vendor e o arquivo composer.lock
        9. rm -fr  /var/www/e-cidade/vendor

        10. rm -fr  /var/www/e-cidade/composer.lock

        #execute o composer install ou  composer update
        11. cd /var/www/e-cidade

        # confirme com enter o comando...
        12. composer update 

        # limpe as configurações em cache da aplicação
        13. php artisan clear

        # Limpar o cache do laravel
        14. php artisan cache:clear

        # Gere a chave
        15. php artisan key:generate
    
    ```

    7.2.  Copie o arquivo schemas_ecidade.conf para /etc/postgresql/12/main/ 
    
    ```
       cp /var/www/e-cidade/schemas_ecidade.conf /etc/postgresql/12/main

    ```

    7.3. Edite o arquivo /etc/postgresql/12/main/pg_hba.conf altere o parâmetro peer para trust 

    1. vi /etc/postgresql/12/main/pg_hba.conf
    
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

    7.4.  Reinicie o serviço Postgresql e execute os comandos para criação de usuários no Postgres

    ```
        1. systemctl restart postgresql

        2. psql -U postgres -h localhost template1 -c "create role ecidade with superuser login password 'ecidade'"

        3. psql -U postgres -h localhost template1 -c "create role dbportal with superuser login password 'dbportal'"

        4. psql -U postgres -h localhost template1 -c "create role dbseller with login password bseller'"

        5. psql -U postgres -h localhost template1 -c "create role plugin with login password 'plugin'"
        
        6. psql -U postgres -h localhost template1 -c "create role usersrole with login password 'usersrole'"
        
        7. createdb -U dbportal e-cidade

    ```

    7.5. Clone a base de dados do e-cidade

    ```
        
        # Altere o diretório para o /tmp do sistema operacional
        1. cd /tmp

        # Clonar o repositório
        2. git clone https://github.com/e-cidade/base-dados-ecidade.git

        #entre na pasta...
        3. cd /tmp/base-dados-ecidade

        4. bzcat dump_ecidade-zerada.sql.bz2|psql -U dbportal -d e-cidade

        # aguarde o final do restore...
        5. Altere a senha do usuário dbseller para o hash que corresponde a senha padrão 123Teste - provisoriamente 

        6. A partir deste acesso você poderá criar outros usuários e alterar a senha - ***(recomendável)***

        7. psql -U postgres -d "e-cidade" -c "update db_usuarios set senha='dced8de21d76cb886a0d410732f9d78094b2e4ae' where login='dbseller';"

    ```

8.0.  Ajuste o plugin  Desktop 3.0 para o usuário

```
    # Altere o diretório para o diretório raiz do apache2
    1. cd /var/www/

    # Ajuste as permissões 
    2. chmod -R 775 /var/www/e-cidade/*
    
    3. chmod -R 777 /var/www/e-cidade/tmp/

    4. chmod -R 777 /var/www/e-cidade/storage/
    
    5. cd /var/www/e-cidade/

    6. cd /var/www/e-cidade/config/
    
    7. cp -p preferencias.json.dist preferencias.json

    8. cd /var/www/e-cidade/extension/data/extension/
    
    9. cp -p Desktop.data.dist Desktop.data  
    
    10. cd /var/www/e-cidade/extension/modification/data/modification/
    
    11. cp -p dbportal-v3-desktop.data.dist dbportal-v3-desktop.data

    # Configurando o Desktop 3.0 para o usuário dbseller - ou qualquer outro.. 
    12. /var/www/e-cidade/bin/v3/extension/install Desktop dbseller

    # Dica: os arquivos Desktop.data e o dbportal-v3-desktop.data deve refletir o 13. caminho correto da base da configuração.
    -- edite os arquivos e veja se está apontando para /var/www/e-cidade -- esta linha fica próxima ao final do arquivo.

    #Ajuste novamente as permissões
    14. chown -R www-data:www-data  /var/www/e-cidade

```

8.1. Abra a URL no navegador de Edge ou Firefox  (testado e validado por esta instalação)
    http://localhost/e-cidade   (substitua 'localhost' pelo ip de seu VPS ou de sua máquina virtual)


8.2. Comunique se houver algum problema na instalação

    # contato: 
    >
    > E-mail: wscomvix@gmail.com  Assunto: instalação do e-cidade
    > Contato: WSCOMVIX - Wander
    >

