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
function passwdEncode($pwd)
{
    $pwd = md5($pwd);
    $func = config('passwdencode');
    return $func($pwd);
}
function isRoot($uid=null)
{
    // todo $uid 未提供的情况
    return config('root_tag')===$uid;
}
function isProviderModel()
{
    $model_type = strtoupper(config('run_model'));
    return $model_type === 'PROVIDER';
}
function accessURLInList($access_url,$url_list_info,$url_tag='rule',$input=[])
{
    foreach($url_list_info as $rule_info){
        $rule = $rule_info[$url_tag];
        if(strpos($rule,'?')===false){
            $action = $rule;
        }else{
            list($action,$param) = explode('?',$rule);
        }
        if($access_url===$action){
            if(isset($param)){
                parse_str($param,$params);
                $input || $input = $_REQUEST;
                $access = true;
                foreach($params as $key =>$value){
                    if(!isset($_REQUEST[$key])||$_REQUEST[$key]!==$value){
                        $access = false;
                        break;
                    }
                }
                if($access){
                    return true;
                }
            }else{
                return true;
            }
        }
    }
    return false;



}
