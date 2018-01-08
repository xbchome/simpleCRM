<?php

namespace app\index\model;

use think\Model;

class Customers extends Model
{
    //
    public function getCustomersList($page=1,$limit=10,$map=[],$uid)
    {
      $count =  $this->name('framework')
            ->alias('a')
            ->join('__USERS__ u','a.id = u.position')
            ->join('__CUSTOMERS__ c','c.uid=u.id')
            ->whereOr('c.uid','=',$uid)
            ->whereOr('paht','like','%'.$uid.'%')
            ->where($map)
            ->count();

       $data = $this->name('framework')
            ->alias('a')
            ->join('__USERS__ u','a.id = u.position')
            ->join('__CUSTOMERS__ c','c.uid=u.id')
            ->whereOr('c.uid','=',$uid)
            ->whereOr('paht','like','%'.$uid.'%')
            ->field('c.id,user_name,cm_name,cm_phone,cm_sex,cm_type,company,industry,c.create_time')
            ->where($map)
           ->page($page,$limit)
            ->select();
       $sex =['女','男'];
        foreach ($data as &$v) {
            $v['cm_sex'] = $sex[$v['cm_sex']];
        }
        $datas = [
            'msg'   =>"",
            'code'  =>0,
            'count' => $count,
            'data'  =>$data
        ];

        return $datas;
    }

    /**
     * 跟单关联
     */
    public function follows()
    {
        return $this->hasMany('Follow','cid','id');
    }

    /**
     * 方案关联模型
     */
    public function schemes()
    {
        return $this->hasMany('Scheme','cid','id');
    }

    /**
     * 上门时间
     */
    public function visitss()
    {
        return $this->hasMany('Visits','cid');
    }
}
