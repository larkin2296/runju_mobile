<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function truncate_cn($string,$length=0,$ellipsis='…',$start=0){
    $string=strip_tags($string);
    $string=preg_replace('/\n/is','',$string);
    //$string=preg_replace('/ |　/is','',$string);//清除字符串中的空格
    $string=preg_replace('/ /is','',$string);
    preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/",$string,$string);
    if(is_array($string)&&!empty($string[0])){
        $string=implode('',$string[0]);
        if(strlen($string)<$start+1){
            return '';
        }
        preg_match_all("/./su",$string,$ar);
        $string2='';
        $tstr='';
        //www.phpernote.com
        for($i=0;isset($ar[0][$i]);$i++){
            if(strlen($tstr)<$start){
                $tstr.=$ar[0][$i];
            }else{
                if(strlen($string2)<$length+strlen($ar[0][$i])){
                    $string2.=$ar[0][$i];
                }else{
                    break;
                }
            }
        }
        return $string==$string2?$string2:$string2.$ellipsis;
    }else{
        $string='';
    }
    return $string;
}