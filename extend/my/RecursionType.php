<?php
/**
 * Created by PhpStorm.
 * User: CWPC001
 * Date: 2017/12/27
 * Time: 下午 15:55
 */

namespace my;

class RecursionType
{

    public function getArray($data,$pid=0,$level=0)
    {
        $temp_array = [];
        $temp_array2 = [];
        foreach ($data as $key => $value)
        {
            if($value['pid']===$pid)
            {
                $temp_array[$key] = $data[$key];
                unset($data[$key]);
                if($level==0)
                {
                    $temp_array[$key]['son'] =[];
                    $temp_array[$key]['son'] = $this->getArray($data,$value['id'],$level+1);
                }else
                {

                    $temp_array2=$this->getArray($data,$value['id'],$level+1);
                    $temp_array=array_merge($temp_array,$temp_array2);
                }

            }
        }

        return $temp_array;
    }


    /**
     * @param $data 要递归的数据
     * @param int $pid  // pid
     * @param int $level // 层级
     * @return array
     */
    public static function getNbsp($data,$pid=0,$level=0)
    {
        $temp_array = [];
        $temp_array2 = [];
        $nbsp ='';
        foreach ($data as $key => $value)
        {
            if($level!==0)
                $nbsp = '&nbsp;&nbsp;|-';

            if($value['pid']===$pid)
            {
                    $temp_array[$key] = $data[$key];
                    $temp_array[$key]['title'] = $nbsp.' '.$data[$key]['title'];
                    unset($data[$key]);
                    $temp_array2= self::getNbsp($data,$value['id'],$level+1);
                    $temp_array=array_merge($temp_array,$temp_array2);

            }
        }
        return $temp_array;
    }

    public static function getFrameworks($data,$pid=0,$level=1)
    {
        $tempa = [];
        $tempb = [];

        $nbsp = str_repeat('&nbsp;&nbsp;&nbsp;',$level);
        if($level>1)
            $nbsp.='├&nbsp;&nbsp;';
        foreach ($data as $key=>$value)
        {
            if($pid == $value['pid'])
            {

                unset($data[$key]);
                $tempb = self::getFrameworks($data,$value['id'],++$level);
                $value['title'] =$nbsp.''.$value['title'];
//                if(empty($tempb))
//                    $value['title'] = str_replace('├','∟',$value['title']);
                $tempa[] = $value;
                $tempa = array_merge($tempa,$tempb);
            }
        }
        end($tempa)['title'] =  str_replace('├','∟',end($tempa)['title']);
        return $tempa;
    }

}