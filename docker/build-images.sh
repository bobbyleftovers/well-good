#!/bin/bash
docker build --no-cache -t wordpress:5.1.1-php7.0-fpm-alpine . -f Dockerfile.base

docker build --no-cache -t nginx-wellandgood:1 . -f Dockerfile.nginx

cd .. && docker build --no-cache -t wellandgood:1 . -f docker/Dockerfile && cd docker

# mysql> update wp_options set option_value='http://localhost' where option_name='siteurl';
# mysql> update wp_options set option_value='http://localhost' where option_name='home';
# mysql> update wp_options set option_value='http://localhost' where option_name='readygraph_site_url';

# mysql> insert into wp_users(user_login, user_pass, user_nicename, user_email, user_registered , user_status, display_name) values ('sre', MD5('sre'), 'sre', 'sre@leafgroup.com', now(), 0, 'sre')
# mysql> select id from wp_users where user_login='sre';
# mysql> insert into wp_usermeta (user_id, meta_key, meta_value) values (414, 'wp_capabilities', 'a:1:{s:13:"administrator";s:1:"1";}');
# mysql> insert into wp_usermeta (user_id, meta_key, meta_value) values (414, 'wp_user_level', '10');

# select option_value from wp_options where option_name='siteurl';
# select option_value from wp_options where option_name='home';