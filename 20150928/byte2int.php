<?php
$arr=intToLHbyte(31);
echo $arr[0]."<br>";
echo $arr[1]."<br>";


 
echo lHbyteToInt($arr[0],$arr[1]);

function intToLHbyte($src) {
        $result = array();
        $result[0] = ($src % 256)&0xff;
        $result[1] = ($src / 256)&0xff;
        return $result;
    }
    
function lHbyteToInt($high, $low) {
         return ($low&0xff) * 256 + ($high&0xff);
    }
?>