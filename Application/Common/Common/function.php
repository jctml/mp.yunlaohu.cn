<?php

/**
 * 获取随机字符串
 * @param int       指定字符串长度 
 * @param boolean   指定字符串是否为是否为纯数字，默认为false 
 * @return string     生成指定长度的随机字符串并返回。
 */
function random( $length, $numeric = FALSE ){
    $seed = base_convert( md5( microtime() . $_SERVER['DOCUMENT_ROOT'] ), 16,
        $numeric ? 10 : 35  );
    $seed = $numeric ? (str_replace( '0', '', $seed ) . '012340567890') : ($seed . 'zZ' . strtoupper( $seed ));
    if( $numeric ){
        $hash = '';
    }else{
        $hash = chr( rand( 1, 26 ) + rand( 0, 1 ) * 32 + 64 );
        $length--;
    }
    $max = strlen( $seed ) - 1;
    for( $i = 0; $i < $length; $i++ ){
        $hash .= $seed{mt_rand( 0, $max )};
    }
    return $hash;
}
