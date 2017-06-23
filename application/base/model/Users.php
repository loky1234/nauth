<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-06-19
 * Time: 15:25
 */

namespace app\base\model;
use think\Db;

class Users extends Basic
{
    protected  $table_name = 'users';
    protected  $errmsg     = ['not found','add error'];
    public function addUser($data)
    {
       // 检查
       $group = new Groups();
       if(!$group->hasTheGroup($data['gid'])){
           $this->registerError(0);
           return false;
       }
       Db::startTrans();
       $group_id = $data['gid'];
       unset($data['gid']);
       $uid = $this->insertUser($data);
       if(!$uid){
          $this->registerError(1);
          Db::rollback();
          return false;
       }
       $group_data = ['gid'=>$group_id,'uid'=>$uid];
       $status = Db::name('access_group')->data($group_data)->insert();
       if($status){
          Db::commit();
          return true;
       }else{
          $this->registerError(2);
          Db::rollback();
          return false;
       }
    }
    public function insertUser($user_data)
    {
        $user_data['pwd'] = passwdEncode($user_data['pwd']);
        return  $this->addGetId($user_data);
    }
    public function getGroupidByUserid($uid)
    {
        $map['uid'] = $uid;
        $map_info = Db::name('access_group')->where($map)->find();
        return  empty($map_info)?false:$map_info['gid'];
    }
    public function  getRulesOfUser($uid)
    {
        $gid = $this->getGroupidByUserid($uid);
        if (!$gid) {
            $this->registerError(3);
            return false;
        }
        $group = new Groups();
        $rules = $group->getRulesOfGroup($gid);
        return $rules;
    }
    public function getRulesInfoOfUser($uid)
    {
        static $rules_list = [];
        if(!isset($rules_list[$uid])){
            $rules = $this->getRulesOfUser($uid);
            if(!$rules){
                return false;
            }
            $auth = new Auth();
            $rules_list[$uid] = $auth->idsToList($rules);
        }
        return $rules_list[$uid];
    }
    public function checkUserRule($uid,$auth,$input=null)
    {
       $rules_list = $this->getRulesInfoOfUser($uid);
       foreach($rules_list as $rule){
           if(strpos($rule,'?')===false){
               $action = $rule;
           }else{
               list($action,$param) = explode('?',$rule);
           }
           if($auth===$action){
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
   public function getRuleInfoByAccessURL($access_url)
   {




   }
}