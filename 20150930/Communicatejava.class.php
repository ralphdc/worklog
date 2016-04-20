<?php
namespace Key\Tools;


class Communicatejava{
    
    private $command;
    
    private $sid;
    
    private $pwd;
    
    private $machine;
    
    private $logserivce;
    
    private $byte;
    
    public function __construct($params)
    {
        if(!in_array(strval($params['command']),array('0x14','0x16'))){
            echo '传参错误！只允许14和16指令！';
            exit;
        }
        $this->command = $params['command'];
        $this->sid = $params['sid'];
        $this->pwd = $params['pwd'];
        $this->machine = $params['machine'];
        $this->logserivce = array('logServiceId'=>'000000-lhh');
        //工具；
        $this->byte = new Bytes();
    }
    
    private function construct_msg_four()
    {
        $response['msg'] = $this->byte->getbytes($this->sid);
        $response['msglength'] = 2 + strlen(strval($this->sid));
        $len = dechex ( strlen ( $this->sid ) );
        
        array_unshift($response['msg'],$len);
        array_unshift($response['msg'],$this->command);
        return $response;
    }
    
    private function construct_msg_six()
    {
        //指令；
        $response['msg'][] = $this->command; 
        //SID长度；
        $response['msg'][] = dechex(strlen(strval($this->sid)));
        //SID;
        $response['msg'][] =  dechex($this->sid);
        //管理员密码长度
        $response['msg'][] = dechex(strlen(strval($this->pwd)));
        //管理员密码
       // $response['msg'][] = $this->byte->getbytes(strval($this->pwd));
        
        $respwd = $this->byte->getbytes(strval($this->pwd));
        
        $response['msgn'] = array_merge($response['msg'],$respwd); 
        
        //主机/备机标志位0：主机1：备机
        $response['msgn'][] = $this->machine == 0 ? 00 : 01;
       // print_r($response);exit;
        
        $response['msglength'] = 3 + strlen(strval($this->sid)) + strlen(strval($this->pwd));
    
        return $response;
    }
    
    private function construct_msg_six2()
    {
        //指令；
        $response['msg'][] = $this->command;
        //SID长度；
        $response['msg'][] = dechex(strlen(strval($this->sid)));
        //SID;
        for($i=0; $i<strlen(strval($this->sid)); $i++){
            
            $response['msg'][] = bin2hex($this->sid[$i]);
        }
        
        
        //管理员密码长度
        $response['msg'][] = dechex(strlen(strval($this->pwd)));
        //管理员密码
        // $response['msg'][] = $this->byte->getbytes(strval($this->pwd));
        for($j=0; $j<8; $j++){

            $response['msg'][] = dechex(6);
        }
    
    
        //主机/备机标志位0：主机1：备机
        $response['msg'][] = $this->machine == 0 ? 0x00 : 0x01;
    
        $response['msglength'] = 4 + strlen(strval($this->sid)) + strlen(strval($this->pwd));
        return $response;
    }
    
    
    
    
    
    public function init_tcp_data_six()
    {
        $message = $this->construct_msg_six2();
        //求得消息体长度；
        $msgLength = $message['msglength'];
        //构造消息体；
        $msgArr = $message['msg'];
        
         
        
        $logjson = json_encode($this->logserivce);
        //主内容转byte[]；
        $bytes_trans_content = $this->byte->getbytes($logjson);
        //求总长度；
        $tranlength = intval(strlen($logjson)) + 2;
        //长度转低高位；
        $byte_trans_length = $this->getHeightLow($tranlength);
         
         
        //合并数组，构造transParams
        $transParam = array_merge($byte_trans_length,$bytes_trans_content);
         
        //求得总长度；
        $dataPackLength = $msgLength + $tranlength +7;
        
        //$dataPackLength = 44;
        
        //求得总长度高低位转换；
        $LC = $this->getHeightLow($dataPackLength);
        
        $INS = array(0xf1);
         
        $SEQ = array(0x00);
         
        $CR = array(0x00,0x00);
         
        $checkArr = array_merge($LC,$INS,$SEQ,$CR,$transParam,$msgArr);
         
        
        $crcs = 0;
        for( $i=0; $i<count($checkArr); $i++) {
            $crcs ^= $checkArr[$i];
        }
         
        $checkArr[] = $crcs;
        $msgStr = $this->byte->tostr ($checkArr);
        \Think\Log::write("\r\n".date('Y-m-d H:i:s')."--"."方法名称：init_tcp_data_six"."--"."构造的数组：".print_r($checkArr,true)."--"."经转换的字符串：".$msgStr);
        return $msgStr;
    }
    
    
    public function init_tcp_data_four()
    {
        $message = $this->construct_msg_four();
        
        //求得消息体长度；
        $msgLength = $message['msglength'];
        //构造消息体；
        $msgArr = $message['msg'];
        
        $logjson = json_encode($this->logserivce);
        //主内容转byte[]；
        $bytes_trans_content = $this->byte->getbytes($logjson);
        //求总长度；
        $tranlength = intval(strlen($logjson)) + 2;
        //长度转低高位；
        $byte_trans_length = $this->getHeightLow($tranlength);
         
         
        //合并数组，构造transParams
        $transParam = array_merge($byte_trans_length,$bytes_trans_content);
         
        //求得总长度；
        $dataPackLength = $msgLength + $tranlength +7;
    
        //求得总长度高低位转换；
        $LC = $this->getHeightLow($dataPackLength);
    
        $INS = array(0xf1);
         
        $SEQ = array(0x00);
         
        $CR = array(0x00,0x00);
         
        $checkArr = array_merge($LC,$INS,$SEQ,$CR,$transParam,$msgArr);
         
        $crcs = 0;
        for( $i=0; $i<count($checkArr); $i++) {
            $crcs ^= $checkArr[$i];
        }
         
        $checkArr[] = $crcs;
        $msgStr = $this->byte->toStr ($checkArr);
        \Think\Log::write("\r\n".date('Y-m-d H:i:s')."--"."方法名称：init_tcp_data_four"."--"."构造的数组：".print_r($checkArr,true)."--"."转换后要发送的字符串：".$msgStr);
        return $msgStr;
    }
    
    
    
    //求低高位；
    
    private function getHeightLow($src)
    {
        $result = array();
        $result[0] =  ($src % 256) & 0xff;
        $result[1] =  ($src / 256) & 0xff;
        return $result;
    }
    
    private function getHL($ints)
    {
        $result = array();
        $result[] =  $ints >> 16;
        $result[] =  $ints & 0xFFFF;
        return $result;
    }
    
    public function parseSocketfour($socketMsg)
    {
        $backup = bin2hex($socketMsg);
        
        $LC1 = substr($backup,0,2);
        $LC2 = substr($backup,2,2);
         
        $parseLength1 = base_convert($LC1,16,10);
        $parseLength2 = base_convert($LC2,16,10);
        $parseLength =  $parseLength1 + $parseLength2;
        //获取的总长度值；
        $totalLength = $parseLength*2;
         
        $transTag = (2 +1 +1 + 2)*2;
         
        $transParams_length_info = substr($backup,$transTag,4);
        $transParams_length1 = substr($transParams_length_info,0,2);
        $transParams_length2 = substr($transParams_length_info,2,2);
         
        $transParams_length1_base = base_convert($transParams_length1,16,10);
        $transParams_length2_base = base_convert($transParams_length2,16,10);
         
        //获取transparams长度；
        $transParams_length = ($transParams_length1_base + $transParams_length2_base)*2;
        $transParamStr = substr($backup,$transTag+4,$transParams_length-4);
        //获取transParams;
        $transParamStr = $this->byte->hexToStr($transParamStr);
         
        //获取msg；
        $msgTag = $transTag + $transParams_length;
        $msgLength = $totalLength - $transTag - $transParams_length - 2;
        $msgStr = substr($backup,$msgTag,$msgLength);
         
        //取指令；
        $command = substr($msgStr,0,2);
        //取Error_code
        $err = substr($msgStr,2,2);
        //Sid长度；
        $sid_len = substr($msgStr,4,2);
        $sid_len = base_convert($sid_len,16,10);
        //Sid;
        $sidStr = substr($msgStr,6,14);
        $sidStr =  $this->byte->hexToStr($sidStr);
         
        //终端主密钥长度；
        $mainKeyLen = substr($msgStr,20,2);
        $mainKeyLen = base_convert($mainKeyLen,16,10);
         
        //子密钥的长度；
        $child_len = intval($mainKeyLen/2)*2;
         
        $tmk1 = substr($msgStr,22,$child_len);
        $tmk2 = substr($msgStr,22+$child_len);
         
        $returnArr['err'] = strval($err);
        $returnArr['command'] = $command;
       
        $returnArr['tmk1'] = empty($tmk1) ? '' : strtoupper($tmk1);
        $returnArr['tmk2'] = empty($tmk2) ? '' : strtoupper($tmk2);
        
        \Think\Log::write( "\r\n".date('Y-m-d H:i:s'));
        \Think\Log::write( "\r\n"."14指令解析：");
        \Think\Log::write( "\r\n"."解析对象：".$socketMsg);
        \Think\Log::write( "\r\n"."转换后的字符串：".$backup);
        
        $logStr .= "\r\n"."获取总长度值：".$totalLength;
        $logStr .= "\r\n"."获取transparams长度：".$transParams_length;
        $logStr .= "\r\n"."获取transParams：".$transParamStr;
        $logStr .= "\r\n"."获取msg：".$msgStr;
        $logStr .= "\r\n"."指令：".$command;
        
        $logStr .= "\r\n"."Error_code：".$err;
        $logStr .= "\r\n"."Sid长度：".$sid_len;
        $logStr .= "\r\n"."Sid：".Sid;
        $logStr .= "\r\n"."终端主密钥长度：".$mainKeyLen;
        $logStr .= "\r\n"."子密钥的长度：".$child_len;
        $logStr .= "\r\n"."TMK1：".strtoupper($tmk1);
        $logStr .= "\r\n"."TMK2：".strtoupper($tmk2);
        
        \Think\Log::write($logStr);
        return $returnArr;
    }
 public function parseSocketsix($socketMsg)
    {
        $backup = bin2hex($socketMsg);
        $LC1 = substr($backup,0,2);
        $LC2 = substr($backup,2,2);
         
        $parseLength1 = base_convert($LC1,16,10);
        $parseLength2 = base_convert($LC2,16,10);
        $parseLength =  $parseLength1 + $parseLength2;
        //获取的总长度值；
        $totalLength = $parseLength*2;
       
         
        $transTag = (2 +1 +1 + 2)*2;
         
        $transParams_length_info = substr($backup,$transTag,4);
        $transParams_length1 = substr($transParams_length_info,0,2);
        $transParams_length2 = substr($transParams_length_info,2,2);
         
        $transParams_length1_base = base_convert($transParams_length1,16,10);
        $transParams_length2_base = base_convert($transParams_length2,16,10);
         
        //获取transparams长度；
        $transParams_length = ($transParams_length1_base + $transParams_length2_base)*2;
        $transParamStr = substr($backup,$transTag+4,$transParams_length-4);
        //获取transParams;
        $transParamStr = $this->byte->hexToStr($transParamStr);
         
        //获取msg；
        $msgTag = $transTag + $transParams_length;
        $msgLength = $totalLength - $transTag - $transParams_length - 2;
        $msgStr = substr($backup,$msgTag,$msgLength);
         
        //取指令；
        $command = substr($msgStr,0,2);
        //取Error_code
        $err = substr($msgStr,2,2);
        
        
        \Think\Log::write( "\r\n".date('Y-m-d H:i:s'));
        \Think\Log::write( "\r\n"."16指令解析：");
        \Think\Log::write( "\r\n"."解析对象：".$socketMsg);
        \Think\Log::write( "\r\n"."转换后的字符串：".$backup);
        
        
        $logStr .= "\r\n"."获取总长度值：".$totalLength;
        $logStr .= "\r\n"."获取transparams长度：".$transParams_length;
        $logStr .= "\r\n"."获取transParams：".$transParamStr;
        $logStr .= "\r\n"."获取msg：".$msgStr;
        $logStr .= "\r\n"."指令：".$command;
        
        $logStr .= "\r\n"."Error_code：".$err;
        
        \Think\Log::write($logStr);
        
        return strval($err);
    }
    
}