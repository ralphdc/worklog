<?php
/**
 * WordPress基础配置文件。
 *
 * 这个文件被安装程序用于自动生成wp-config.php配置文件，
 * 您可以不使用网站，您需要手动复制这个文件，
 * 并重命名为“wp-config.php”，然后填入相关信息。
 *
 * 本文件包含以下配置选项：
 *
 * * MySQL设置
 * * 密钥
 * * 数据库表名前缀
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/zh-cn:%E7%BC%96%E8%BE%91_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL 设置 - 具体信息来自您正在使用的主机 ** //
/** WordPress数据库的名称 */
define('DB_NAME', 'wordpress');

/** MySQL数据库用户名 */
define('DB_USER', 'ralphdc');

/** MySQL数据库密码 */
define('DB_PASSWORD', 'RalphdC1688!@');

/** MySQL主机 */
define('DB_HOST', '127.0.0.1');

/** 创建数据表时默认的文字编码 */
define('DB_CHARSET', 'utf8mb4');

/** 数据库整理类型。如不确定请勿更改 */
define('DB_COLLATE', '');

/**#@+
 * 身份认证密钥与盐。
 *
 * 修改为任意独一无二的字串！
 * 或者直接访问{@link https://api.wordpress.org/secret-key/1.1/salt/
 * WordPress.org密钥生成服务}
 * 任何修改都会导致所有cookies失效，所有用户将必须重新登录。
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '}=x)~I}Jnv($e.<H;ksg0a],ah^A6?loR+@`7xt-o5ulOS`J*GTH*N|?m0#SR+g<');
define('SECURE_AUTH_KEY',  '$!_=|9<MraxlGf:S08]KW2&f}jIOo}xU$d{SxIpoo_Ad}2&xI^:oTQ;go,8LpV|B');
define('LOGGED_IN_KEY',    '2|0-rL~f-R0A {:d68/ieN%B)4eD.RGYDiJlYWBzy.%z/Mse)h(<fZe:9#Zb^a-t');
define('NONCE_KEY',        'V]UQEB,#;r/Cxs1WkTz%nNlfABS>7Ldm9;5%HR -Cv4|DvX=o!}$Z+dp|F]Sc.mO');
define('AUTH_SALT',        ')-X3[+-p*mz|,`cTAtFs$3Mf88=H9ho6l*A$6k2X7?Y17]r;&0G0@nS0}?m -s#@');
define('SECURE_AUTH_SALT', ',nGdap^f)rg5q@x_i6,R3u#kMNb&ov>93wj|+5^<&1.r-zla>dB}G%xR9rFk^|p~');
define('LOGGED_IN_SALT',   '1LLsl2;-+;|C|}oT$}43vz+u;1--Jqt}QQA)N.Snh|3O_gqk7en3rc&+]iJcNp/Q');
define('NONCE_SALT',       'C#D@u9%$0$4edNG!x8lwg+TyrWgnba2/$U]=vpAtj-AbT3<[+|W+foyS+8$j,[Qp');

/**#@-*/

/**
 * WordPress数据表前缀。
 *
 * 如果您有在同一数据库内安装多个WordPress的需求，请为每个WordPress设置
 * 不同的数据表前缀。前缀名只能为数字、字母加下划线。
 */
$table_prefix  = 'wp_';

/**
 * 开发者专用：WordPress调试模式。
 *
 * 将这个值改为true，WordPress将显示所有用于开发的提示。
 * 强烈建议插件开发者在开发环境中启用WP_DEBUG。
 *
 * 要获取其他能用于调试的信息，请访问Codex。
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/**
 * zh_CN本地化设置：启用ICP备案号显示
 *
 * 可在设置→常规中修改。
 * 如需禁用，请移除或注释掉本行。
 */
define('WP_ZH_CN_ICP_NUM', true);

/* 好了！请不要再继续编辑。请保存本文件。使用愉快！ */

/** WordPress目录的绝对路径。 */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** 设置WordPress变量和包含文件。 */
require_once(ABSPATH . 'wp-settings.php');
