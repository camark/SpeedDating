<?php
require_once ("../api/webAPI.php");
require_once ("../api/user.php");
require_once ("../api/date.php");
class Wechat {
    public $token;
    public $request = array ();
    protected $funcflag = false;
    protected $debug = false;
    public function __construct($token, $debug = false) {
        $this->token = $token;
        $this->debug = $debug;
    }
    public function get_msg_type() {
        return strtolower ( $this->request ['MsgType'] );
    }
    public function get_media_id() {
        return strtolower ( $this->request ['MediaId'] );
    }
    public function get_event_type() {
        return strtolower ( $this->request ['Event'] );
    }
    public function get_event_key() {
        return strtolower ( $this->request ['EventKey'] );
    }
    public function get_creattime() {
        return strtolower ( $this->request ['CreateTime'] );
    }
    public function valid() {
        $echoStr = $_GET ["echostr"];
        if ($this->checkSignature ()) {
            echo $echoStr;
            exit ();
        }
    }

    public function replyText($message) {
        $message="hai";
        /*if($message=="dada"){
            return "";
        }*/
        $textTpl = <<<eot
<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[%s]]></MsgType>
    <Content><![CDATA[%s]]></Content>
    <FuncFlag>%d</FuncFlag>
</xml>
eot;
        $req = $this->request;
        return sprintf ( $textTpl, $req ['FromUserName'], $req ['ToUserName'], time (), 'text', $message, $this->funcflag ? 1 : 0 );
    }

    public function reply() {
        // get post data, May be due to the different environments
        $postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
        file_put_contents ( "request.txt", $postStr );

        $date_user = new eight_min_date;
        $this->request = ( array ) simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
        $from = $this->request['FromUserName'];
        if($date_user->is_talking($from)) {
            $target = $date_user->get_target($from);
            $content = $date_user->filt_wechat_num($content);
            $type = "text";
            $date_user->sendmsg($target, $content, $type, NULL);
            $content = $date_user->caculate_left_time($from);
            echo "success";
        }else {
            if (!empty($postStr)){
                $this->request = ( array ) simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );

                $message = self::reply_main($this->request, $this);//reply_main

                if (!is_array($message)) {
                    $ret = $from;
                    //$ret = $this->replyText($message);
                }
                echo "success";
                $ret = "";
                echo $ret;
            }else{
                echo "success";
                exit;
            }
        }
    }
    private function checkSignature() {
        $args = array (
            "signature",
            "timestamp",
            "nonce" 
        );
        foreach ( $args as $arg )
            if (! isset ( $_GET [$arg] ))
                return false;

        $signature = $_GET ["signature"];
        $timestamp = $_GET ["timestamp"];
        $nonce = $_GET ["nonce"];

        $tmpArr = array (
            $this->token,
            $timestamp,
            $nonce 
        );
        sort ( $tmpArr, SORT_STRING );
        $tmpStr = implode ( $tmpArr );
        $tmpStr = sha1 ( $tmpStr );

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    function reply_main($request, $w) {
        $to = $request ['ToUserName'];
        $from = $request ['FromUserName'];
        $reply_content ="";

        $date_user = new eight_min_date;
        // store session data

        // 大众接口
        if ($w->get_msg_type () == "location") {
            $lacation = "x@" . ( string ) $request ['Location_X'] . "@" . ( string ) $request ['Location_Y'];
            $lacation = urlencode ( str_replace ( '\.', '\\\.', $lacation ) );
            $lacation = urldecode ( xiaojo ( $lacation, $from, $to ) );
            return $lacation;
        }   // 返回图片地址
        else if ($w->get_msg_type () == "event") {
            // 关注
            if ($w->get_event_type () == "subscribe") {
                $welcome = WELCOME;
                return $welcome;
            }       // 取消关注
            elseif ($w->get_event_type () == "unsubscribe") {
                $unsub = urldecode ( xiaojo ( "subscribe", $from, $to ) );
                return $unsub;
            }

            // 点击菜单
            elseif ($w->get_event_type () == "click") {
                $menukey = $w->get_event_key ();
                $g = new WebAPI ();
                switch ($menukey) {
                case 'date':
                    if($date_user->is_register($from)) {
                        if($date_user->is_talking($from))
                            $date_ret = "你已经在聊天了喔\n";
                        else {
                            /* Delete in Ours */
                            if($date_user->get_gdpu_talk_times($from) == 0) {
                                $date_ret = "要关注我们公众号体验\n";
                                return $date_ret;
                            }

                            /* Delete in Gdpuer */
                            if($date_user->get_real_first_talk_times($from)==0 && $date_user->is_transfer($from)==0) {
                                $date_ret = "转发！！\n";
                                return $date_ret;
                            }

                            if($date_user->is_need_to_wait($from)) {
                                $date_user->update_waiting_start_time($from);
                                $date_ret = "你在排队中还有".$date_user->get_waiting_people($from)."人\n";
                            }else
                                $date_ret = $date_user->find_target_to_talk($from);
                            return $date_ret;
                        }
                    }else {
                        $step = 1;
                        $date_user->register($from);
                        $date_user->update_step($from, $step, 4);
                        $date_ret = "欢迎首次使用8分钟交友,下面是详细介绍:\n 八分钟交友活动中，每一个参加者都有机会所有参加活动的异性进行交谈，交谈的时间被规定为 8 分钟，这样每个人都能够保证有机会和自己心仪的人谈话，同时又不会造成你被人缠住，不胜其扰.\n在活动中，为了保持活动的宗旨，请遵守以下规则：1.在活动中不能问对方真实姓名，每人只有编号;\n2.不能问对方电话号码，电子邮箱地址;\n3.不能问对方详细地址。\n但是，一旦聊得投机而时间已到，怎么办呢？交友结束后，你可将想结交的朋友的编号记下来，再通过可爱小助手去联系对方哦。\n\n下面请回复y继续使用";
                    }
                    return $date_ret;
                    break;
                case 'about':
                    $about = "小助手由ourstudio工作室开发运营";
                    return $about;
                    break;
                case 'jianshe':
                    $holiday = "八分钟约会即将上线，惊喜多多";
                    return $holiday;
                    break;

                default:
                    # code...
                    break;
                }
                //$menu = urldecode ( xiaojo ( $menukey, $from, $to ) );
                //return $menu;
            }       // 点击菜单选项
            else {
                $menukey = $w->get_event_key ();
                return $menukey;
            }
        }

        else if ($w->get_msg_type () == "voice" || $w->get_msg_type () == "image" || $w->get_msg_type () == "video") {
            if($date_user->is_talking($from)) {
                $video_id = NULL;
                if($w->get_msg_type () == "image")
                    $type = 'image';
                else if($w->get_msg_type () == "voice")
                    $type = 'voice';
                else {
                    $type = 'video';
                    $video_id = $request ['ThumbMediaId'];
                }
                $target = $date_user->get_target($from);
                $content = $request ['MediaId'];
                //$content = $w->get_media_id();
                $date_user->sendmsg($target, $content, $type, $video_id);
                $content = $date_user->caculate_left_time($from);
            }else {
                if($date_user->is_transfer($from)==0 && $w->get_msg_type () == "image") {
                    $date_user->update_transfer($from);
                    $content = "thank you for your is_transfer\n";
                }else {
                    $content = "咦,我也有这东西喔\n";
                }
            }
            return $content;
        }

        /**
         *  $content:获取http的content字段
         *  $reply_content:返回处理结果
         */
        else if ($w->get_msg_type () == "text"){
            $content = trim ( $request ['Content'] );
            if($date_user->get_step($from) == 2) {
                $step = 2;
                $date_user->update_step($from, $step, 4);
                $content = "请正确输入您的微信号，否则你将收不到别人的信息，输入后即不可更改\n";
                return $content;
            }
            if($date_user->get_step($from) == 3) {
                $date_user->update_wechat_id($from, $content);
                $step = 3;
                $date_user->update_step($from, $step, 4);
                $content = "现在请输入你的性别(男或女)";
                return $content;
            }

            if($date_user->get_step($from) == 4) {
                if (strstr ( $content, '女' ) || strstr ( $content, '0' )) {
                    $sex = 0;
                }else
                    $sex = 1;
                $start_time = time();
                $step = 4;
                $date_user->update_step($from, $step, 4);
                $want_to_talk = 1;
                $date_user->update_all($from, $sex, $start_time, $want_to_talk);
                $content = $date_user->find_target_to_talk($from);
                return $content;
            }

//            if($date_user->is_talking($from)) {
//                $target = $date_user->get_target($from);
//                $content = $date_user->filt_wechat_num($content);
//                $type = "text";
//                $date_user->sendmsg($target, $content, $type, NULL);
//                $content = $date_user->caculate_left_time($from);
//                $content ="dada";
//                return $content;
//            }
        }

        return $reply_content;
    }

    //多图文回复function
    function replypic($reply_content) {
        $a = array ();
        $b = array ();
        $c = array ();
        $n = 0;
        $contents = $reply_content;
        foreach ( explode ( '@t', $reply_content ) as $b [$n] ) {
            if (strstr ( $contents, '@t' )) {
                $b [$n] = str_replace ( "itle", "title", $b [$n] );
                $b [$n] = str_replace ( "ttitle", "title", $b [$n] );
            }

            foreach ( explode ( '#', $b [$n] ) as $reply_content ) {
                list ( $k, $v ) = explode ( '|', $reply_content );
                $a [$k] = $v;
                $d .= $k;
            }
            $c [$n] = $a;
            $n ++;
        }
        $reply_content = $c;
        return $reply_content;
    }

}


define ( "WELCOME", "欢迎关注8分钟约会!\n精彩功能即将推出，XD我们的第一批粉丝，将会得到一大份惊喜哦" );

$token = "gdpuer";
$w = new Wechat($token);
if (isset ( $_GET ['echostr'] )) {
    $w->valid ();
    exit ();
}
$w->reply();

?>
