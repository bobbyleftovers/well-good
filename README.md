# WordPress

This is a WordPress repository configured to run on the [Pantheon platform](https://pantheon.io).

Pantheon is website platform optimized and configured to run high performance sites with an amazing developer workflow. There is built-in support for features such as Varnish, Redis, Apache Solr, New Relic, Nginx, PHP-FPM, MySQL, PhantomJS and more. 

## Getting Started

### 1. Spin-up a site

If you do not yet have a Pantheon account, you can create one for free. Once you've verified your email address, you will be able to add sites from your dashboard. Choose "WordPress" to use this distribution.

### 2. Load up the site

When the spin-up process is complete, you will be redirected to the site's dashboard. Click on the link under the site's name to access the Dev environment.

![alt](http://i.imgur.com/2wjCj9j.png?1, '')

### 3. Run the WordPress installer

How about the WordPress database config screen? No need to worry about database connection information as that is taken care of in the background. The only step that you need to complete is the site information and the installation process will be complete.

We will post more information about how this works but we recommend developers take a look at `wp-config.php` to get an understanding.

![alt](http://i.imgur.com/4EOcqYN.png, '')

If you would like to keep a separate set of configuration for local development, you can use a file called `wp-config-local.php`, which is already in our .gitignore file.

### 4. Enjoy!

![alt](http://i.imgur.com/fzIeQBP.png, '')


#### Setting up Laradock
 
  - Clone Laradock to root of project `git clone https://github.com/Laradock/laradock.git`
  - cd to laradock and copy the env file, `cp env-example .env`
  - Edit the `.env` file and update the following
    - `COMPOSE_PROJECT_NAME=wellgood`
    - `WORKSPACE_INSTALL_WP_CLI=true`

  - Create a new file on root, `wp-config-local.php`

    ```
    <?php
      if (php_sapi_name() == 'cli') {
        define( 'DB_HOST', '127.0.0.1' );
      }
      else {
        define( 'DB_HOST', 'mariadb' );
      }
      define('DB_NAME', 'wellgood');
      define('DB_USER', 'root');
      define('DB_PASSWORD', 'root');
      define('DB_CHARSET',       'utf8');
      define('DB_COLLATE',       '');
      define('AUTH_KEY',         'put your unique phrase here');
      define('SECURE_AUTH_KEY',  'put your unique phrase here');
      define('LOGGED_IN_KEY',    'put your unique phrase here');
      define('NONCE_KEY',        'put your unique phrase here');
      define('AUTH_SALT',        'put your unique phrase here');
      define('SECURE_AUTH_SALT', 'put your unique phrase here');
      define('LOGGED_IN_SALT',   'put your unique phrase here');
      define('NONCE_SALT',       'put your unique phrase here');

      define( 'WP_HOME', 'https://well-good.lndo.site/' );
      define( 'WP_SITEURL', 'https://well-good.lndo.site/' );
    ```

  - Edit laradock/nginx/sites/default.conf

   ```
    server {
        listen 80;
        listen [::]:80;

        # For https
        listen 443 ssl;
        listen [::]:443 ssl ipv6only=on;
        ssl_certificate /etc/nginx/ssl/default.crt;
        ssl_certificate_key /etc/nginx/ssl/default.key;

        server_name well-good.lndo.site;
        root /var/www;
        index index.php index.html index.htm;

        location / {
            try_files $uri $uri/ /index.php$is_args$args;
        }

        location ~ \.php$ {
            try_files $uri /index.php =404;
            fastcgi_pass php-upstream;
            fastcgi_index index.php;
            fastcgi_buffers 16 16k;
            fastcgi_buffer_size 32k;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            #fixes timeouts
            fastcgi_read_timeout 600;
            include fastcgi_params;
        }

        location ~ /\.ht {
            deny all;
        }

        location /.well-known/acme-challenge/ {
            root /var/www/letsencrypt/;
            log_not_found off;
        }

        error_log /var/log/nginx/app_error.log;
        access_log /var/log/nginx/app_access.log;
    }

   ```
  - Edit laradock/php-fpm/php7.3.ini and set `memory_limit = 768M`

  - Edit laradock/docker-compose.yml and **comment** out the following
    ```
      # - "${WORKSPACE_BROWSERSYNC_HOST_PORT}:3000"
      # - "${WORKSPACE_BROWSERSYNC_UI_HOST_PORT}:3001"
    ```

  - Edit laradock/docker-compose.yml and add the following under **enviroment** on the `php-fpm` service
    ```
      - LANDO=ON
      - PANTHEON_ENVIRONMENT=dev
    ```

  - Edit /etc/hosts and add the following
    ```
    127.0.0.1 well-good.lndo.site
    ```

  - Start the docker with the first start might take 15min as it installs all the images
  ```
    docker-compose up -d nginx mariadb workspace
  ```
  - To Stop the docker image
  ```
  docker-compose stop
  ```
  
  - Import/Create DB using SequelPRO, Download a backup [here](https://drive.google.com/file/d/1xHrL2lCaOQF7uQCmY-ViGK9WHLoEn8cL/view?usp=sharing)
  ```
  Connection Settings:
  Host: 127.0.0.1
  Username: root
  Password: root
  ```
 - Select DB from the Dropdown to `wellgood`
 - Import SQL Dump

#### Reduce local DB size
  ```
  wp post delete $(wp post list --post_type=post --format=ids --year=2009) --force && wp post delete $(wp post list --post_type=post --format=ids --year=2010) --force && wp post delete $(wp post list --post_type=post --format=ids --year=2011) --force && wp post delete $(wp post list --post_type=post --format=ids --year=2012) --force && wp post delete $(wp post list --post_type=post --format=ids --year=2013) --force && wp post delete $(wp post list --post_type=post --format=ids --year=2014) --force && wp post delete $(wp post list --post_type=post --format=ids --year=2015) --force && wp post delete $(wp post list --post_type=post --format=ids --year=2016) --force && wp post delete $(wp post list --post_type=post --format=ids --year=2017) --force && wp post delete $(wp post list --format=ids  --post_type=revision --year=2018) --force && wp post delete $(wp post list --format=ids  --post_type=revision --year=2019) --force && wp post delete $(wp post list --format=ids  --post_type=revision --year=2020) --force
  ```