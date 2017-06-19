<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-06-13
 * Time: 11:27
 */

namespace app\index\controller;
use think\Request;
use think\Controller;
class Test  extends  Controller
{
    public function  testAccessToken()
    {
        $get_access_token = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&';
        $query_arr['appid'] = config('weixin.appid');
        $query_arr['secret'] = config('weixin.appsecret');
        $get_access_token  .= http_build_query($query_arr);
        $result = file_get_contents($get_access_token);
        return $result;
    }
    public function getAccessToken()
    {
        $token_path = config('weixin.tokenpath');
        if(!file_exists($token_path)){
            $need_get = true;
        }else{
            $content = file_get_contents($token_path);
            $info    = json_decode($content,true);
            if(filemtime($token_path)+$info['expires_in']<=time()){
                 $need_get = true;
            }else{
                 $need_get = false;
            }
        }
        if($need_get){
            $content = $this->testAccessToken();
            file_put_contents($token_path,$content);
        }
        return json_decode($content)->access_token;
    }
    public function testGetAccessToken()
    {
        dump($this->getAccessToken());

    }
    public function  testCreateMenu()
    {
        $create_menu_url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=';
        $access_token    = $this->getAccessToken();
        $create_menu_url .= $access_token;
        $post_data = [  'button'=> [
                                  [ 'name'=>'A',
                                    'sub_button' => [
                                        ['name'=>'a','type'=>'view','url'=>'http://www.baidu.com']
                                    ],
                                  ],
                                  [ 'name'=>'B','type'=>'view','url'=>'https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzAxNzU4ODQ2Mg==&scene=124#wechat_redirect']
            ]
            ];
        $body = json_encode($post_data);
        $request_info['http'] = [
                                   'method' => 'POST',
                                   'content'=> $body,
                                    'header'=> 'content-type:application/x-www-form-urlencoded',//存在主体时， 必须设置content-type 首部
        ];
        $request_info_resource = stream_context_create($request_info);
        //dump($request_info_resource);die;
        $result = file_get_contents($create_menu_url,false,$request_info_resource);
        dump($result);
    }
    public function test_uploadify()
   {

      if(Request::instance()->isPost()){

          dump($_FILES);die;
      }
       return $this->fetch();
   }

}