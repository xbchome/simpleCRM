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
                    $temp_array[$key]['son'] = $this->getArray($data,$value['id'],++$level);
                }else
                {
                    $temp_array2=$this->getArray($data,$value['id'],++$level);
                    $temp_array=array_merge($temp_array,$temp_array2);
                }

            }
        }

        return $temp_array;
    }


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
                    $temp_array2= self::getNbsp($data,$value['id'],++$level);
                    $temp_array=array_merge($temp_array,$temp_array2);

            }
        }
        return $temp_array;
    }
}