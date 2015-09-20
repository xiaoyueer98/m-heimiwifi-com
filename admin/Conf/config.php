<?php

return array(
	//'配置项'=>'配置值'
	'DEFAULT_MODULE'=>'Public',
	'DEFAULT_ACTION'=>'login',
	'APP_DEBUG'=>true,               //开启调试模式

	'URL_MODEL'=> 0,
	'DB_NAME'=> 'Route',
	'DB_PREFIX'=> 'r_',
	'TOKEN_ON'=>false,
	'TOKEN_NAME'=>'__hash__',
	'TOKEN_TYPE'=>'md5',
	'DB_FIELDTYPE_CHECK'=>false,
	'TMPL_ENGINE_TYPE'=>'Smarty',
	'TMPL_ENGINE_CONFIG'=>array(
		'caching'=>false,
		'template_dir'=>TMPL_PATH,
		'compile_dir'=>CACHE_PATH,
		'cache_dir'=>TEMP_PATH,
		'left_delimiter'=>"{{",
		'right_delimiter'=>"}}"
	),
	'TMPL_ACTION_ERROR'     => TMPL_PATH.'default/Public/error.html', // 默认错误跳转对应的模板文件
  	'TMPL_ACTION_SUCCESS'   => TMPL_PATH.'default/Public/success.html',
    'ROOT' => '/var/www/heimiBootstrap',
    'PCROOT' => '/var/www/heimithird/m',
    'WURL' => 'http://www.heimiwifi.com/m/',
);

?>
