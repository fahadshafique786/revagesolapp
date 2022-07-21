<?php
use App\Models\Sports;
use App\Models\AppDetails;

if ( ! function_exists('getBoolean')) {
    function getBoolean($val, $StringResponse = false)
    {
        $bool = "";
        if (is_string($val)) {
            $val = (int)$val;
        }

        if ($StringResponse) {
            $bool = ($val === 1) ? "true" : "false";
        } else {
            $bool = ($val === 1) ? true : false;
        }
        return $bool;
    }
}

if ( ! function_exists('getBooleanStr'))
    {
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

if ( ! function_exists('getServerLoad'))
    {
        function getServerLoad(){

            if(file_exists('/proc/stat')){
                $cont = file('/proc/stat');
                $cpuloadtmp = explode(' ',$cont[0]);
                $cpuload0[0] = $cpuloadtmp[2] + $cpuloadtmp[4];
                $cpuload0[1] = $cpuloadtmp[2] + $cpuloadtmp[4]+ $cpuloadtmp[5];
                sleep(1);
                $cont = file('/proc/stat');
                $cpuloadtmp = explode(' ',$cont[0]);
                $cpuload1[0] = $cpuloadtmp[2] + $cpuloadtmp[4];
                $cpuload1[1] = $cpuloadtmp[2] + $cpuloadtmp[4]+ $cpuloadtmp[5];
                return round(($cpuload1[0] - $cpuload0[0])*100/($cpuload1[1] - $cpuload0[1]),3);

            }
            else{

                return 0;

            }

        }

        function getServerLoadX1(){



            if(function_exists('sys_getloadavg')){
                $exec_loads = sys_getloadavg();
                $exec_cores = trim(shell_exec("grep -P '^processor' /proc/cpuinfo|wc -l"));
                $cpu = $exec_loads[1]/($exec_cores + 1)*100;

//                $loads=sys_getloadavg();
//                $core_nums=trim(shell_exec("grep -P '^physical id' /proc/cpuinfo|wc -l"));
//                $load=$loads[0]/$core_nums;

                return $cpu ; //round($load,3);
            }
            else{
                return 0;
            }


        }

        function getServerLoad1($windows = false){
            $os=strtolower(PHP_OS);
            if(strpos($os, 'win') === false){
                if(file_exists('/proc/loadavg')){
                    $load = file_get_contents('/proc/loadavg');
                    $load = explode(' ', $load, 1);
                    $load = $load[0];
                }elseif(function_exists('shell_exec')){
                    $load = explode(' ', `uptime`);
                    $load = $load[count($load)-1];
                }else{
                    return false;
                }

                if(function_exists('shell_exec'))
                    $cpu_count = shell_exec('cat /proc/cpuinfo | grep processor | wc -l');

                return array('load'=>$load, 'procs'=>$cpu_count);
            }elseif($windows){
                if(class_exists('COM')){
                    $wmi=new COM('WinMgmts:\\\\.');
                    $cpus=$wmi->InstancesOf('Win32_Processor');
                    $load=0;
                    $cpu_count=0;
                    if(version_compare('4.50.0', PHP_VERSION) == 1){
                        while($cpu = $cpus->Next()){
                            $load += $cpu->LoadPercentage;
                            $cpu_count++;
                        }
                    }else{
                        foreach($cpus as $cpu){
                            $load += $cpu->LoadPercentage;
                            $cpu_count++;
                        }
                    }
                    return array('load'=>$load, 'procs'=>$cpu_count);
                }
                return false;
            }
            return false;
        }

}

//function getCPU(){
//    $stat1 = file('/proc/stat');
//    sleep(1);
//    $stat2 = file('/proc/stat');
//    $info1 = explode(" ", preg_replace("!cpu +!", "", $stat1[0]));
//    $info2 = explode(" ", preg_replace("!cpu +!", "", $stat2[0]));
//    $dif = array();
//    $dif['user'] = $info2[0] - $info1[0];
//    $dif['nice'] = $info2[1] - $info1[1];
//    $dif['sys'] = $info2[2] - $info1[2];
//    $dif['idle'] = $info2[3] - $info1[3];
//    $total = array_sum($dif);
//    $cpu = array();
//    foreach($dif as $x=>$y) $cpu[$x] = round($y / $total * 100, 1);
//
//    dd()
//
//
//}

if (! function_exists('directoryList')){
    function directoryList ($url) {
        $outp = 0;
        if(dir($url))
        {
            $d = dir($url);
            while($entry = $d->read()) {
                $size = round(filesize($url.$entry)/1024);
                if ($size > 999) $sizestring = ((round($size/100))/10)." mb";
                else $sizestring = $size." kb";
                $outp .= "<a href='".$url.$entry."'>".$entry."</a> [ ".$sizestring." ]<br>\n";
            }
            $outp = "Path: ".$d->path."<br>\n".$outp;
            $d->close();
        }

    }
}

if ( ! function_exists('get_server_memory_usage'))
    {

    //  RAM Consumption function
    function get_server_memory_usage()
    {
        $free = shell_exec('free');
        $free = (string)trim($free);
        $memory_usage = 0;
        if(!empty($free)){
            $free_arr = explode("\n", $free);
            $mem = explode(" ", $free_arr[1]);
            $mem = array_filter($mem);
            $mem = array_merge($mem);
            $memory_usage = round($mem[2]/$mem[1]*100,2);
        }

        return $memory_usage;
    }



}


if ( ! function_exists('getTotalSports'))
    {
    function getTotalSports(){
        $count = Sports::all()->count();
        return $count;
    }

}

if ( ! function_exists('getTotalApp'))
    {
    function getTotalApp($count = 0){
        $count = AppDetails::all()->count();
        return $count;
    }

}

if ( ! function_exists('getServerBandwith'))
    {
    function getServerBandwith($val,$StringResponse = false){
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
