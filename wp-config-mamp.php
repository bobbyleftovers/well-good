<?php


if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') $_SERVER['HTTP_USER_AGENT_HTTPS'] = 'ON';

$_SERVER['LANDO'] = 'ON';
$_SERVER['MAMP'] = 'ON';
$_SERVER['HOME'] = $_SERVER['DOCUMENT_ROOT'];
$_SERVER['PANTHEON_ENVIRONMENT'] = 'dev';
$_ENV['PANTHEON_ENVIRONMENT'] = 'lando';
$_ENV['PANTHEON_SITE'] = false;
$_ENV['DB_NAME'] = 'pantheon';
$_ENV['DB_USER'] = 'root';
$_ENV['DB_PASSWORD'] = 'root';
$_ENV['DB_HOST'] = '127.0.0.1';
$_ENV['DB_PORT'] = '3306'; //memcached: 11211 //mysql:8889
$_ENV['AUTH_KEY'] = 'O}9lsya[U$zxg=6yiC:UDw3933cV}h<$Ovh7O8rj3LBFL73t#s((Jms}K^D/Y,:n';
$_ENV['SECURE_AUTH_KEY'] = 'kVPz_7D1:E7=}ftr+baC):)+9<<C75pN$5}|h[wW,YV24)OxLpz|dj:HW6K=gzMc';
$_ENV['LOGGED_IN_KEY'] = 'a+W88>=yMVV<FkkR|EMYoBg{7q#IL.kl[7PtuON7kjtq:_7)D:Hs,nYx=E5K%Lw{';
$_ENV['NONCE_KEY'] = '>{ai|oKf/4^{3yv7n2UdLt$Ky1Uu}]azw1DrKO/D:()rgeT,CoSODNY1)tQvfqQ%';
$_ENV['AUTH_SALT'] = 'NToYigbWPL(wqCltr37u]AQ0tgR+/h^F1feF{5b%<KscM_^41xTnyjJM[aG78@(f';
$_ENV['SECURE_AUTH_SALT'] = 'cNkJ>{F5^xCDw+7Tq$6Jf]WPLd/:a6T3:3}7k>iblkfCdHV,D@=i(Vy|zr($TCyI';
$_ENV['LOGGED_IN_SALT'] = '6g]LUTn54>7C$_/_%.h9NB3}tm8}ob^Q86c_|[3y2g1xftwbk=FyA9ftQIb5kR^O';
$_ENV['NONCE_SALT'] = '/TM>D]m5SIYx2^+I,=6,8Hj|Q#01@r]^#_8()PoBY(y$3sEsr#iU><PDx[4ubpkG';

//define('WP_TEMP_DIR', ABSPATH . 'wp-content/');