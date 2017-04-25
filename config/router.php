<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/4/22 0022
 * Time: 10:57
 */
//获取所有请求
$request = $_SERVER['QUERY_STRING'];
//解析$request变量，得到用户请求的页面（page1）和其它GET变量（&分隔的变量）如一个请求http://你的域名.com/index.php?page1&article=buildawebsite,则被解析为array("page1", "article=buildawebsite")
$parsed = explode('&', $request);

//用户请求的页面，如上面的page1，为$parsed第一个变量,shift之后，数组为array("article=buildawebsite")
$page = array_shift($parsed);

//剩下的为GET变量，把它们解析出来
$getVars = array();
foreach ($parsed as $argument) {
    //用"="分隔字符串，左边为变量，右边为值
    list($variable, $value) = split('=', $argument);
    $getVars[$variable] = $value;
}

//这是测试语句，一会儿会删除
print "The page your requested is '$page'";
print '<br/>';
$vars = print_r($getVars, TRUE);
print "The following GET vars were passed to the page:<pre>" . $vars . "</pre>";