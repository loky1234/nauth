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






}