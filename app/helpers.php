<?php


if ( ! function_exists('getBoolean'))
{
    function getBoolean($val,$StringResponse = false){
        $bool = "";
        if(is_string($val)){
            $val = (int) $val;
        }

        if($StringResponse){
            $bool = ($val === 1) ? "true" : "false";
        }
        else{
            $bool = ($val === 1) ? true : false;
        }
        return $bool;
    }

    function getBooleanStr($val,$StringResponse = false){
        $bool = "";
        if(is_string($val)){
            $val = (int) $val;
        }

        if($StringResponse){
            $bool = ($val === 1) ? "Yes" : "No";
        }
        else{
            $bool = ($val === 1) ? true : false;
        }
        return $bool;
    }

}
