<?php

return array(
    //'配置项'=>'配置值'
    'DB_TYPE'      => 'mysql',//分布式数据库类型必须相同
//    'DB_HOST'      => 'rds4g9c31081yxyifr43o.mysql.rds.aliyuncs.com',
//    'DB_NAME'      => 'www',//如果相同可以不用定义多个
//    'DB_USER'      => 'yunlaohu',
//    'DB_PWD'       => 'ywkj987654321',
    
    'DB_HOST'   => 'rds4g9c31081yxyifr43o.mysql.rds.aliyuncs.com',
    'DB_NAME'   => 'ylh_test',//如果相同可以不用定义多个
    'DB_USER'   => 'ylh_test',
    'DB_PWD'    => 'testywkj',
    
    'DB_PORT'      => '3306',
    'DB_PREFIX'    => 'ylh_',
    'TMPL_L_DELIM' => '<{',// 模板引擎普通标签开始标记
    'TMPL_R_DELIM' => '}>',// 模板引擎普通标签结束标记
);
