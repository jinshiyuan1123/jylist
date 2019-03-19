<?php

/**
 * Class Config
 *
 * 执行Sample示例所需要的配置，用户在这里配置好Endpoint，AccessId， AccessKey和Sample示例操作的
 * bucket后，便可以直接运行RunAll.php, 运行所有的samples
 */
 
  define("OSS_ACCESS_ID", C('OSS_ACCESS_ID'));
  define("OSS_ACCESS_KEY", C('OSS_ACCESS_KEY'));
  define("OSS_ENDPOINT", C('OSS_ENDPOINT'));
  define("OSS_TEST_BUCKET", C('OSS_TEST_BUCKET'));
  define("OSS_URL", C('OSS_URL'));

final class Config
{
    const OSS_ACCESS_ID = '';
    const OSS_ACCESS_KEY = '';
    const OSS_ENDPOINT = '';
    const OSS_TEST_BUCKET = '';
	const OSS_URL = '';
	
}
