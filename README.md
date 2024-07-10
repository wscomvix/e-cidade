## e-Cidade

O e-Cidade destina-se a informatizar a gestão dos Municípios Brasileiros de forma integrada. Esta informatização contempla a integração entre os entes municipais: Prefeitura Municipal, Câmara Municipal, Autarquias, Fundações e outros.

A economia de recursos é somente uma das vantagens na adoção do e-cidade, além da liberdade de escolha dos fornecedores e garantia de continuidade do sistema, uma vez apoiado pelo Ministério do Planejamento.

Este repositório é um clone de  https://github.com/e-cidade/e-cidade. 

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
2. Instale o php7.4
    - Adicione o repositório para baixar a versão 7.4 do PHP
        ```
            add-apt-repository ppa:ondrej/php
            apt update && apt upgrade
            apt install php7.4
        ```

    - Adicione as extensões do PHP
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
3. Instale o composer (conforme recomendações do site)
    - copie o script abaixo
        ```
            php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
            php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
            php composer-setup.php
            php -r "unlink('composer-setup.php');"

            cp composer.phar /usr/local/bin/composer
        ```


4. Clone este repositório com o comando abaixo
    
    ```
       cd /tmp
       git clone https://github.com/wscomvix/e-cidade.git
       
    ```

5. Mova a pasta clonada do repositório para /var/www
    ```
       mv /tmp/e-cidade  /var/www/

    ``

