<?php
class eight_min_date{
    function __construct(){
        $con = mysql_connect('localhost','christopher','wudbadmin')or die(mysql_error());
        mysql_select_db('ours');
        mysql_query("SET NAMES 'UTF8'");
    }
    public function is_register($open_id){
        $sql = "SELECT * FROM `gdpu_date`  WHERE `open_id` ='$open_id'";
        $result=mysql_query($sql);
        if(mysql_num_rows($result))
            $res = 1;
        else
            $res = 0;
        return $res;
    }

    public function update_all($open_id, $sex,  $start_time, $want_to_talk){
        $sql = "UPDATE `gdpu_date` SET `sex` = '$sex' WHERE `open_id` = '$open_id' ";
        mysql_query($sql);
        $sql = "UPDATE `gdpu_date` SET `start_time` = '$start_time' WHERE `open_id` = '$open_id' ";
        mysql_query($sql);
        $sql = "UPDATE `gdpu_date` SET `want_to_talk` = '$want_to_talk' WHERE `open_id` = '$open_id' ";
        mysql_query($sql);
    }

    public function register($open_id){
        $sex= -1;
        $target_id=$talking=$wechat_id=$start_time=$want_to_talk=$step=0;
        $gdpu_talk_times = 3;
        $real_first_talk_times = 9;
        $left_talk_times = 10;
        $waiting_people = 99;
        $invitation_code = -1;
        $reward = 0;
        $waiting_start_time=$record_waiting_start_time_flag=$transfer=0;
        $sql = "insert into `gdpu_date` values('', '$open_id', '$sex', '$target_id', '$talking','$wechat_id','$start_time','$want_to_talk', '$step', '$gdpu_talk_times', '$real_first_talk_times', '$left_talk_times', '$waiting_people', '$waiting_start_time','$record_waiting_start_time_flag', '$transfer', '$invitation_code', '$reward')";
        mysql_query($sql);
    }

    public function filt_wechat_num($content) {
        $pattern = '(\w{6,})';
        $replacement = ' ';
        $content = preg_replace($pattern, $replacement, $content);
        return $content;
    }

    public function is_talking($open_id){
        $sql = "SELECT `talking` FROM `gdpu_date` WHERE `open_id` = '$open_id' ";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        $talking = $array['talking'];
        return $talking ;
    }

    public function update_left_talk_times($open_id){
        $sql = "UPDATE `gdpu_date` SET `left_talk_times` = `left_talk_times`-1 WHERE `open_id` = '$open_id' ";
        mysql_query($sql);
    }

    public function update_gdpu_talk_times($open_id){
        $sql = "UPDATE `gdpu_date` SET `gdpu_talk_times` = `gdpu_talk_times`-1 WHERE `open_id` = '$open_id' ";
        mysql_query($sql);
    }

    public function update_real_first_talk_times($open_id){
        $sql = "UPDATE `gdpu_date` SET `real_first_talk_times` = `real_first_talk_times`-1 WHERE `open_id` = '$open_id' ";
        mysql_query($sql);
    }

    public function update_waiting_people($open_id){
        $rand_num = rand(0, 9);
        if($rand_num > 5)
            $rand_num = 0;
        $sql = "UPDATE `gdpu_date` SET `waiting_people` = `waiting_people`-'$rand_num' WHERE `open_id` = '$open_id' ";
        mysql_query($sql);
    }

    public function get_waiting_people($open_id){
        $sql = "SELECT `waiting_people` FROM `gdpu_date` WHERE `open_id` = '$open_id' ";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        $waiting_people = $array['waiting_people'];
        return $waiting_people ;
    }

    public function get_Id($open_id){
        $sql = "SELECT `Id` FROM `gdpu_date` WHERE `open_id` = '$open_id' ";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        $Id = $array['Id'];
        return $Id;
    }

    public function get_qbt($open_id){
        $sql = "SELECT `qbt` FROM `gdpu_date` WHERE `open_id` = '$open_id' ";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        $qbt = $array['qbt'];
        return $qbt;
    }

    public function get_invitation_code($open_id){
        $sql = "SELECT `invitation_code` FROM `gdpu_date` WHERE `open_id` = '$open_id' ";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        $invitation_code = $array['invitation_code'];
        return $invitation_code;
    }

    public function get_gdpu_talk_times($open_id){
        $sql = "SELECT `gdpu_talk_times` FROM `gdpu_date` WHERE `open_id` = '$open_id' ";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        $gdpu_talk_times = $array['gdpu_talk_times'];
        return $gdpu_talk_times ;
    }


    public function get_real_first_talk_times($open_id){
        $sql = "SELECT `real_first_talk_times` FROM `gdpu_date` WHERE `open_id` = '$open_id' ";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        $real_first_talk_times = $array['real_first_talk_times'];
        return $real_first_talk_times;
    }

    public function is_transfer($open_id){
        $sql = "SELECT `transfer` FROM `gdpu_date` WHERE `open_id` = '$open_id' ";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        $transfer = $array['transfer'];
        return $transfer;
    }

    public function update_transfer($open_id){
        $flag = 1;
        $sql = "UPDATE `gdpu_date` SET `transfer` = '$flag' WHERE `open_id` = '$open_id' ";
        mysql_query($sql);
    }


    public function get_record_waiting_start_time_flag($open_id){
        $sql = "SELECT `record_waiting_start_time_flag` FROM `gdpu_date` WHERE `open_id` = '$open_id' ";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        $record_waiting_start_time_flag = $array['record_waiting_start_time_flag'];
        return $record_waiting_start_time_flag;
    }

    public function update_record_waiting_start_time_flag($open_id, $flag){
        $sql = "UPDATE `gdpu_date` SET `record_waiting_start_time_flag` = '$flag' WHERE `open_id` = '$open_id' ";
        mysql_query($sql);
    }

    public function update_waiting_start_time($open_id){
        if(self::get_record_waiting_start_time_flag($open_id))
            /* do nothing */;
        else {
            $current_time = time();
            $sql = "UPDATE `gdpu_date` SET `waiting_start_time` = '$current_time' WHERE `open_id` = '$open_id' ";
            mysql_query($sql);
            $flag = 1;
            self::update_record_waiting_start_time_flag($open_id, $flag);
        }
    }

    public function is_wait_enough($open_id){
        $sql = "SELECT `waiting_start_time` FROM `gdpu_date` WHERE `open_id` = '$open_id' ";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        $waiting_start_time = $array['waiting_start_time'];
        $current_time = time();
        $time_gap = $current_time - $waiting_start_time;
        $min = date('i', $time_gap);
        if($min > 5)
            return 1;
        else
            return 0 ;
    }

    public function is_meeting_overflow(){
        $Id = 1;
        $sql = "SELECT `num_of_meetings` FROM `gdpu_meetings`  WHERE `Id` = '$Id' ";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        $num_of_meetings = $array['num_of_meetings'];
        $max_meetings = 50;
        if($num_of_meetings > $max_meetings)
            return 1;
        else
            return 0;
    }

    public function update_is_waiting($open_id, $flag) {
        $sql = "UPDATE `gdpu_date` SET `is_waiting` = '$flag' WHERE `open_id` = '$open_id' ";
        mysql_query($sql);
    }

    public function get_left_talk_times($open_id) {
        $sql = "SELECT `left_talk_times` FROM `gdpu_date` WHERE `open_id` = '$open_id' ";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        $left_talk_times = $array['left_talk_times'];
        return $left_talk_times;
    }

    public function reset_waiting_people($open_id) {
        $waiting_people = 99;
        $sql = "UPDATE `gdpu_date` SET `waiting_people` = '$waiting_people' WHERE `open_id` = '$open_id' ";
        mysql_query($sql);
    }

    public function is_need_to_wait($open_id) {
        if(self::is_meeting_overflow()) {
            if(self::get_left_talk_times($open_id)) {
                self::update_left_talk_times($open_id);
                return 0;
            }else {
                self::update_waiting_start_time($open_id);
                if(self::is_wait_enough($open_id))
                    return 0;
                else if(self::get_waiting_people($open_id)<5) {
                    self::reset_waiting_people($open_id);
                    return 0;
                }
                else {
                    self::update_waiting_people($open_id);
                    return 1;
                }
            }
        }else {
            return 0;
        }
    }

    public function get_target($open_id){
        $sql = "SELECT `target_id` FROM `gdpu_date` WHERE `open_id` = '$open_id' ";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        $target_id = $array['target_id'];
        return $target_id ;
    }

    public function get_time($open_id){
        $sql = "SELECT `start_time` FROM `gdpu_date`  WHERE `open_id` = '$open_id' ";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        $start_time = $array['start_time'];
        return $start_time ;
    }

    public function get_sex($open_id){
        $sql = "SELECT `sex` FROM `gdpu_date`  WHERE `open_id` = '$open_id' ";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        $sex = $array['sex'];
        return $sex ;
    }

    public function update_meetings($add){
        $Id = 1;

        $sql = "SELECT `num_of_meetings` FROM `gdpu_meetings`  WHERE `Id` = '$Id' ";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        $num_of_meetings = $array['num_of_meetings'];

        $num_of_meetings += $add;

        $sql = "UPDATE `gdpu_meetings` SET `num_of_meetings` = '$num_of_meetings' WHERE `Id` = '$Id' ";
//        $sql = "UPDATE `gdpu_meetings` SET `num_of_meetings` = `num_of_meetings`+'$add' WHERE `Id` = '$Id' ";
        mysql_query($sql);
    }

    public function find_target($open_id){
        $sex = self::get_sex($open_id);
        if($sex == 1) {
            $target = "美女";
            $myself = "帅哥";
            $target_sex = 0;
        }elseif($sex == 0){
            $target = "帅哥";
            $myself = "美女";
            $target_sex = 1;
        }
        $sql = "SELECT * FROM `gdpu_date`  WHERE `sex` = '$target_sex' AND `want_to_talk` = 1 ";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        if(mysql_num_rows($result)) {
            $target_id = $array['open_id'];
            $current_time = time();
            $sql = "UPDATE `gdpu_date` SET `target_id` = '$target_id' WHERE `open_id` = '$open_id' ";
            mysql_query($sql);
            $sql = "UPDATE `gdpu_date` SET `target_id` = '$open_id' WHERE `open_id` = '$target_id' ";
            mysql_query($sql);
            $sql = "UPDATE `gdpu_date` SET `start_time` = '$current_time' WHERE `open_id` = '$target_id' ";
            mysql_query($sql);
            $sql = "UPDATE `gdpu_date` SET `talking` = 1 WHERE `open_id` = '$open_id' ";
            mysql_query($sql);
            $sql = "UPDATE `gdpu_date` SET `talking` = 1 WHERE `open_id` = '$target_id' ";
            mysql_query($sql);
            $sql = "UPDATE `gdpu_date` SET `want_to_talk` = 0 WHERE `open_id` = '$open_id' ";
            mysql_query($sql);
            $sql = "UPDATE `gdpu_date` SET `want_to_talk` = 0 WHERE `open_id` = '$target_id' ";
            mysql_query($sql);

            $talking_id = $array['Id'];
            $msg = "匹配成功,你约到了代号为".$talking_id."的".$target."聊天\n发图片和语音聊天更有趣喔\n";
            $type = 'text';
            $video_id = 0;
            self::sendmsg($open_id, $msg, $type, $video_id);

            $sql = "SELECT `Id` FROM `gdpu_date`  WHERE `open_id` = '$open_id' ";
            $result = mysql_query($sql);
            $array = mysql_fetch_array($result);
            $talking_id = $array['Id'];
            $msg = "匹配成功,你约到了代号为".$talking_id."的".$myself."聊天\n发图片和语音聊天更有趣喔\n";
            self::sendmsg($target_id, $msg, $type, $video_id);

            $add = 2;//only excute once
            self::update_meetings($add);
        }else {
            $sql = "UPDATE `gdpu_date` SET `want_to_talk` = 1 WHERE `open_id` = '$open_id' ";
            mysql_query($sql);
            // "匹配..." ;
            $target_id = 0;
        }
        return $target_id;
    }

    public function stop_talking($open_id){
        $step = 4;
        self::update_step($open_id, $step);
        $sql = "UPDATE `gdpu_date` SET `talking` = 0 WHERE `open_id` = '$open_id' ";
        mysql_query($sql);

        //        $sql = "SELECT * FROM `gdpu_date` where `open_id`=(SELECT `target_id` FROM `gdpu_date` WHERE `open_id` = '$open_id')";

        $sql = "SELECT `target_id` FROM `gdpu_date` WHERE `open_id` = '$open_id'";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        $target_id = $array['target_id'];

        $sql = "SELECT `Id` FROM `gdpu_date` where `open_id`='$target_id'";
        $result=mysql_query($sql);
        $array=mysql_fetch_array($result);
        $talking_id = $array['Id'];

        $add = -1;
        self::update_meetings($add);
        $flag = 0;
        self::update_record_waiting_start_time_flag($open_id, $flag);
//        if(self::get_gdpu_talk_times($open_id) > 0)
//            self::update_gdpu_talk_times($open_id);
        if(self::get_real_first_talk_times($open_id) > 0)
            self::update_real_first_talk_times($open_id);

        $content = "sorry,时间已到，如果想继续聊～请记住对方的编号id:".$talking_id."哦～\n 曾有一个人,缘分让她来到了我的世界，8分钟，让我想进一步了解这个人，小助手，你能帮我联系他吗？";
        return $content;
    }
    public function update_wechat_id($open_id, $content){
        $sql = "UPDATE `gdpu_date` SET `wechat_id` = '$content' WHERE `open_id` = '$open_id' ";
        mysql_query($sql);
    }

    /* Use this function to implement session. */
    public function update_step($open_id, $step){
        $sql = "UPDATE `gdpu_date` SET `step` = '$step' WHERE `open_id` = '$open_id' ";
        mysql_query($sql);
    }

    public function get_step($open_id){
        $sql = "SELECT `step` FROM `gdpu_date`  WHERE `open_id` = '$open_id' ";
        $result = mysql_query($sql);
        $array = mysql_fetch_array($result);
        $step = $array['step'];
        return $step ;
    }

    public function caculate_left_time($open_id){
        $start_time = self::get_time($open_id);
        $current_time = time();
        $time_gap = $current_time - $start_time;
        $min = date('i', $time_gap);
        if($min > 8)
            $content = self::stop_talking($open_id);
        else {
            $min = 8 - $min;
            if(self::get_step($open_id)==5 && $min==0) {
                $min = date('s', $time_gap);
                $min = 60 - $min;
                $min .= "秒";
                $content = "你们的聊天还剩下".$min."哦:) \n";
                $step = 6;
                self::update_step($open_id, $step);
            }else if(self::get_step($open_id)==4 && $min == 4) {
                $min .= "分钟";
                $content = "你们的聊天还剩下".$min."哦:) \n";
                $step = 5;
                self::update_step($open_id, $step);
            }else
                $content = "";
        }
        return $content;
    }

    public function find_target_to_talk($open_id){
        if(self::find_target($open_id)) {
            $start_time = time();
            $sql = "UPDATE `gdpu_date` SET `start_time` = '$start_time' WHERE `open_id` = '$open_id' ";
            mysql_query($sql);
            $content = "请跟对方打个招呼吧：）\n";
        }
        else
            $content = "sorry，目前没有想要适合聊天的人，请耐心等候：）\n当有适当的人，小助手会第一时间会通知你\n";
        return $content;
    }

    public function sendmsg($open_id,$content,$type,$video_id){
        $sql = "SELECT `token` FROM `gdpu_token` where `Id`='1'";
        $result=mysql_query($sql);
        $array=mysql_fetch_array($result);
        $ACC_TOKEN = $array['token'];
        $MENU_URL="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$ACC_TOKEN;
        if($type == 'text'){
            $data= array(
            'touser'=>$open_id,
            'msgtype'=>$type,
            'text'=>array(
                'content'=>$content
            )
        );
        }
        else if($type == 'voice'){
            $data= array(
            'touser'=>$open_id,
            'msgtype'=>$type,
            'voice'=>array(
                'media_id'=>$content
            )
        );
        }
        else if($type == 'image'){
            $data= array(
            'touser'=>$open_id,
            'msgtype'=>$type,
            'image'=>array(
                'media_id'=>$content
            )
        );
        }
        else if($type == 'video'){
            $data= array(
            'touser'=>$open_id,
            'msgtype'=>$type,
            'video'=>array(
                'media_id'=>$content,
                'thumb_media_id'=>$video_id,
                'title'=>'my video',
                'description'=>'我的小视频'
            )
        );
        }


        
        $code =self::my_json_encode($data);
        //$param = preg_replace("/\u([0-9a-f]{4})/ie", "iconv('UCS-2', 'UTF-8', pack('H*', '$1'));", $param);
        $ch = curl_init(); 

        curl_setopt($ch, CURLOPT_URL, $MENU_URL); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $code);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

        $info = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Errno'.curl_error($ch);
        }

        curl_close($ch);

    }
    //

    //
    public function my_json_encode($arr){  
        //convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding  
        array_walk_recursive($arr, function (&$item, $key) { if (is_string($item)) $item = mb_encode_numericentity($item, array (0x80, 0xffff, 0, 0xffff), 'UTF-8'); });  
        return mb_decode_numericentity(json_encode($arr), array (0x80, 0xffff, 0, 0xffff), 'UTF-8');  

    }


}
?>
