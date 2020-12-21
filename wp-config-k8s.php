$_SERVER['HTTP_HOST'] = $_ENV['STG_HOST'];
$_ENV['HTTP_HOST'] = $_ENV['STG_HOST'];

// require_once(dirname(__FILE__) . '/pantheon-redirects.php');

if(!isset($_SERVER['HOME']) && isset($_SERVER['DOCUMENT_ROOT'])) $_SERVER['HOME'] = $_SERVER['DOCUMENT_ROOT'];

define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

$table_prefix  = 'wp_';

define('WPLANG', '');

if ($_SERVER['STG_HOST'] != 'wellandgood.com' && $_SERVER['STG_HOST'] != 'www.wellandgood.com') {
    define('WP_DEBUG', true);
    define('WP_DEBUG_LOG', '/dev/stdout');
} else {
    define('WP_DEBUG', false);
}

$_SERVER['HTTPS']='on';

define('WP_HOME', 'https://' . $_SERVER['STG_HOST']);
define('WP_SITEURL', 'https://' . $_SERVER['STG_HOST']);

// cron will run as a k8s cronjob
define('DISABLE_WP_CRON', true);

if (!defined('ABSPATH'))
    define('ABSPATH', dirname(__FILE__) . '/');

/**
* Inject redirection
*/
$redirection = dirname(__FILE__). '/wp-content/plugins/wg-redirections/wp-config.php';
if(file_exists($redirection)) require_once $redirection;

require_once(ABSPATH . 'wp-settings.php');
?>
