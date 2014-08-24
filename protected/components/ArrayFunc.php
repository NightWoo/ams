<?php
class ArrayFunc
{
    /**
    * 从数组中删除空白的元素（包括只有空白字符的元素）
    *
    * @param array $arr
    * @param boolean $trim
    */ 
    public static function array_remove_empty(&$arr, $trim = true){
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                array_remove_empty($arr[$key]);
            } else {
                $value = trim($value);
                if ($value == '') {
                    unset($arr[$key]);
                } else if ($trim) {
                    $arr[$key] = $value;
                }
            }
        }
    } 
    
}
