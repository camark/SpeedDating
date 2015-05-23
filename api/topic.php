<?php
class topic{
    function __construct(){
        $con = mysql_connect('localhost','christopher','wudbadmin')or die(mysql_error());
        mysql_select_db('ours');
        mysql_query("SET NAMES 'UTF8'");
    }
    //
    	public function type(){
    		$num = rand(1,2);
    		if($num==1){
    			$content = self::story();
    			$content = "要不咱们玩故事接龙,你一句，我一句。故事开头是\n[".$content."]\nbegin~go~";
    			//$content = "故事接龙,你一句，我一句。故事开头是\n[".$content."]\nbegin~go~";
    		}else{
    			$content = self::question();
    			$content = "要不要聊聊这个问题\n[".$content."]\n";
    			//$content = "请双方对以下问题进行思考，发表自己的见解\n[".$content."]\ncome on~";
    		}
    		return $content;
    	}
        public function story(){
        $sql = "SELECT * FROM `topic`  WHERE `type` = 1";
        $result=mysql_query($sql);
        $data=array();
        while($row=mysql_fetch_array($result)){
        $data[]=$row;
        }
        
        $max = mysql_num_rows($result)-1;
        $num = rand(0, $max);
        $res = $data[$num]['content'];
        return $res;
    }

    public function question(){
        $sql = "SELECT * FROM `topic`  WHERE `type` = 2";
        $result=mysql_query($sql);
        $data=array();
        while($row=mysql_fetch_array($result)){
        $data[]=$row;
        }
        
        $max = mysql_num_rows($result)-1;
        $num = rand(0, $max);
        $res = $data[$num]['content'];
        return $res;
    }




}
?>