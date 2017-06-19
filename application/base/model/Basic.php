<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-06-19
 * Time: 13:55
 */

namespace app\base\model;
use think\Db;

class Basic
{
    protected  $table_name = '';
    protected    $errcode = 0;
    protected    $errmsg  = [] ;
    protected  function add($data)
    {
        $status = Db::name($this->table_name)->insert($data);
        return $status?:false;
    }
    protected  function addGetId($data)
    {
        $status = Db::name($this->table_name)->insertGetId($data);
        return $status?:false;
    }
    protected  function update($map,$data)
    {
        $status = Db::name($this->table_name)->where($map)->update($data);
        return $status?:false;
    }
    protected  function delete($map)
    {
        $status = Db::name($this->table_name)->where($map)->delete();
        return $status?:false;
    }
    protected  function select($map,$field=null,$order=null,$page=null,$num=null)
    {
       $result = Db::name($this->table_name)->field($field)->where($map)->page($page,$num)->order($order)->select();
       return empty($result)?[]:$result;
    }
    protected  function find($map,$field=null,$order=null)
    {
        $result = Db::name($this->table_name)->field($field)->where($map)->order($order)->find();
        return empty($result)?[]:$result;
    }
    public function  getError()
    {
        $errcode = $this->errcode;
        return isset($this->errmsg[$errcode])?$this->errmsg[$errcode]:null;
    }
    protected function registerError($code)
    {
        $this->errcode = $code;
    }

}