<?php
$local = '/home/www/m2';
$hookSecret = '111111';  # set NULL to disable check

// 如果仓库目录不存在，返回错误
if (!is_dir($local)) {
    header('HTTP/1.1 500 Internal Server Error');
    die('Local directory is missing');
}


set_error_handler(function($severity, $message, $file, $line) {
	throw new \ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(function($e) {
	header('HTTP/1.1 500 Internal Server Error');
	echo "Error on line {$e->getLine()}: " . htmlSpecialChars($e->getMessage());
	die();
});

$rawPost = NULL;
if ($hookSecret !== NULL) {
	if (!isset($_SERVER['HTTP_X_HUB_SIGNATURE'])) {
		throw new \Exception("HTTP header 'X-Hub-Signature' is missing.");
	} elseif (!extension_loaded('hash')) {
		throw new \Exception("Missing 'hash' extension to check the secret code validity.");
	}

	list($algo, $hash) = explode('=', $_SERVER['HTTP_X_HUB_SIGNATURE'], 2) + array('', '');
	if (!in_array($algo, hash_algos(), TRUE)) {
		throw new \Exception("Hash algorithm '$algo' is not supported.");
	}

	$rawPost = file_get_contents('php://input');
	if ($hash !== hash_hmac($algo, $rawPost, $hookSecret)) {
		throw new \Exception('Hook secret does not match.');
	}
};

if (!isset($_SERVER['HTTP_CONTENT_TYPE'])) {
	throw new \Exception("Missing HTTP 'Content-Type' header.");
} elseif (!isset($_SERVER['HTTP_X_GITHUB_EVENT'])) {
	throw new \Exception("Missing HTTP 'X-Github-Event' header.");
}

switch ($_SERVER['HTTP_CONTENT_TYPE']) {
	case 'application/json':
		$json = $rawPost ?: file_get_contents('php://input');
		break;

	case 'application/x-www-form-urlencoded':
		$json = $_POST['payload'];
		break;

	default:
		throw new \Exception("Unsupported content type: $_SERVER[HTTP_CONTENT_TYPE]");
}

# Payload structure depends on triggered event
# https://developer.github.com/v3/activity/events/types/
$payload = json_decode($json);

switch (strtolower($_SERVER['HTTP_X_GITHUB_EVENT'])) {
	case 'ping':
		echo 'pong';
		break;

	case 'push':
		break;

//	case 'create':
//		break;

	default:
		header('HTTP/1.0 404 Not Found');
		echo "Event:$_SERVER[HTTP_X_GITHUB_EVENT] Payload:\n";
		print_r($payload); # For debug only. Can be found in GitHub hook log.
		die();
}

/*
 * 这里有几点需要注意：
 *
 * 1.确保PHP正常执行系统命令。写一个PHP文件，内容：
 * `<?php shell_exec('ls -la')`
 * 在通过浏览器访问这个文件，能够输出目录结构说明PHP可以运行系统命令。
 *
 * 2、PHP一般使用www-data或者nginx用户运行，PHP通过脚本执行系统命令也是用这个用户，
 * 所以必须确保在该用户家目录（一般是/home/www-data或/home/nginx）下有.ssh目录和
 * 一些授权文件，以及git配置文件，如下：
 * ```
 * + .ssh
 *   - authorized_keys
 *   - config
 *   - id_rsa
 *   - id_rsa.pub
 *   - known_hosts
 * - .gitconfig
 * ```
 *
 * 3.在执行的命令后面加上2>&1可以输出详细信息，确定错误位置
 *
 * 4.git目录权限问题。比如：
 * `fatal: Unable to create '/data/www/html/awaimai/.git/index.lock': Permission denied`
 * 那就是PHP用户没有写权限，需要给目录授予权限:
 * ``
 * sudo chown -R :www-data /data/www/html/awaimai`
 * sudo chmod -R g+w /data/www/html/awaimai
 * ```
 *
 * 5.SSH认证问题。如果是通过SSH认证，有可能提示错误：
 * `Could not create directory '/.ssh'.`
 * 或者
 * `Host key verification failed.`
 *
 */
 
//echo shell_exec("cd {$local} && git pull 2>&1");
shell_exec("cd {$local} && git reset --hard origin/master && git clean -f && git pull 2>&1 && git checkout master");
die("done " . date('Y-m-d H:i:s', time()));

