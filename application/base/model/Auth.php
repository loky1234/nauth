<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-06-19
 * Time: 12:10
 */

namespace app\base\model;

class Auth extends Basic
{
    protected  $table_name = 'auth';

    public function getMenu($userID)
    {



    }
    public function getRuleIds($id)
    {



    }
   public function getGroupInfoOfUser($uid)
   {



   }
   public function getGroupIdOf($uid)
   {




   }
  public function idsToList($ids)
  {
      if(is_array($ids)){
          $ids = join(',',$ids);
      }
      $map = ["find_in_set(id,'$ids')"=>['gt',0]];
      return  $this->select($map);
  }
  public function getAccessTreeId($start,$pk='id',$pid='pid')
  {
      $ids = [];
      while(true){
          $menu = $this->find([$pk=>$start]);
          if(empty($menu)){
             return $ids;
          }
          $ids[] = $menu[$pk];
          $start = $menu[$pid];
      }
  }

}