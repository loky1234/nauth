<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-06-19
 * Time: 13:54
 */

namespace app\base\model;
use think\Db;
class Groups extends Basic
{
    protected $table_name = 'groups';
    protected $errmsg     = ['other error','不存在该组','the group is not empty'];
    protected  $errcode    = '';
    public function getGroupInfo($gid,$field=null)
    {
        $map['gid'] = $gid;
        return   $this->find($map,$field);
    }
    public function addGroup($group_data)
    {
        return $this->add($group_data);
    }
    public function delGroup($gid)
    {
        if(!$this->hasTheGroup($gid)){
            $this->registerError(1);
            return false;
        }
        if($this->hasUsers($gid)){
            $this->registerError(2);
            return false;
        }
        $data['status'] = 1 ;
        $map['gid']     = $gid;
        return  $this->update($map,$data);
    }
    private function hasUsers($gid)
    {
        $map['gid'] = $gid;
        $result = Db::name($this->table_name)->where($map)->select();
        return empty($result)?false:true;
    }
//    $map,$field=null,$order=null,$page=null,$num=null
    public function getGroupList($map=[],$field=null,$order=null,$page=null,$num=null)
    {
        $map['status']  = 0;
        return $this->select($map,$field,$order,$page,$num);
    }
    public function hasTheGroup($gid)
    {
        $map['gid']    = $gid;
        $map['status'] = 0;
        $result = $this->find($map);
        return empty($result)?false:true;
    }
}