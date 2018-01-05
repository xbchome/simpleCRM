<?php

namespace app\index\model;

use think\Model;

class Users extends Model
{
    //

    /**
     * @param $name
     * @param int $id
     * @param $value
     * @return bool
     * 计算当前用户是否存在或者等于本身
     */
    public function is_null($name,$id=0,$value)
    {
        $u = $this->where($name,'=',$value)->find();
        if(empty($u) || $u['id']==$id)
            return true;  // 如果没有记录存在或者记录id等于当前id返回true
        return false;
    }

    public function getFrameworkUser($position,$uid)
    {
       return $this->name('framework')
            ->alias('f')
            ->join('users u','f.id=u.position')
            ->where('f.paht','like','%,'.$position.',%')
            ->whereOr('u.id','=',$uid)
            ->field('u.id,u.user_name')
            ->select();
    }
}
