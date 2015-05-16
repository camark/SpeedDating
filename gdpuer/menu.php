<?php
header("Content-type: text/html; charset=utf-8"); 
$APPID="wxdec4cacd20cacc89";
$nothing="8e56eb3b05b4cca636f17f8cece6f84b";

$TOKEN_URL="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID."&secret=".$nothing;

$json=file_get_contents($TOKEN_URL);
$result=json_decode($json,true);

$ACC_TOKEN=$result['access_token'];

$MENU_URL="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$ACC_TOKEN;
//content
$tu=unicode2utf8("\ue02d");

//转码函数
function unicode2utf8($str) { // unicode编码转化，用于显示emoji表情
        $str = '{"result_str":"' . $str . '"}'; // 组合成json格式
        $strarray = json_decode ( $str, true ); // json转换为数组，利用 JSON 对 \uXXXX 的支持来把转义符恢复为 Unicode 字符
        return $strarray ['result_str'];
  }
$hehe= unicode2utf8("\ue02d");
$data='{
    "button": [
        
        {    
          "type":"click",
          "name":"点我开始",
          "key":"date"
        },
        {    
               "type":"view",
               "name":"详情介绍",
               "url":"http://mp.weixin.qq.com/s?__biz=MzAwNjUxMzcwNA==&mid=207779817&idx=1&sn=9262e599f34718f70fa6e51caf4dd367#rd"
            },
        {
            "name": "more", 
            "sub_button": [
                
                
                {
                    "type": "click", 
                    "name": "more", 
                    "key": "jianshe"
                },
                {
                    "type": "click", 
                    "name": "合作联系", 
                    "key": "chat"
                }
            
            ]
        }
    ]

}';

$ch = curl_init(); 

curl_setopt($ch, CURLOPT_URL, $MENU_URL); 
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

$info = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Errno'.curl_error($ch);
}

curl_close($ch);

var_dump($info);

?>