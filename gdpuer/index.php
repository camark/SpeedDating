<?php
/** 
 * File: index.php
 * 该 php 实现了广药小助手微信公众平台的全部功能            
 * 微信账号 gdpuer       
 * 作者：chaowenliu  & Yanson
 * 原文：http://weibo.com/cheman1989    
 * 时间：2013.4.4    
 */
header ( "content-Type: text/html; charset=utf-8" );
require_once (dirname ( __FILE__ ) . "/wechat.php");
require_once ("../api/webAPI.php");
require_once ("../api/user.php");
require_once ("../api/date.php");
define ( "DEBUG", true );

// 下面为需要配置的选项
define ( "TOKEN", "gdpuer" );
// 填写自定义机器人名称http://www.xiaojo.com/myadmin/pages/wx.php?id=1450
define ( "YOURNICK", "广药小助手" );
// 在这里定义你的初次关注后的欢迎语和菜单@title|【网站导航】-
define ( "WELCOME", "欢迎关注8分钟约会!\n精彩功能即将推出，XD我们的第一批粉丝，将会得到一大份惊喜哦" );

define ( "MENU", "description|菜单#title|功能向导@title|【校园资讯】- 回复数字或提示\n\n[1]广药新闻\t\t[2]就业信息\n[3]图书信息\t\t[4]还书\n[5]动漫更新\t\t[6]交通卡余额\n[7]查课表\t\t\t\t[8]发找找帮\n[9]勤管兼职\t\t[10]查成绩\n[11]查选修\t\t\t[cet]查四六级\n[开]开户指南\t[意见]给小助手提意见\n[绑定]绑定学号密码#url|#pic@title|【生活服务】-回复字母\n\n[A]听歌\t[B]公交\t[C]翻译\t[D]快递\n[E]解梦\t[F]手机\t[G]身份\t[H]音乐\n[T]天气#url|#pic@title|\t聊天：任意回复\t\t提意见？回复意见#url|#pic@title|合作事宜：回复 合作 或 推送#url|#pic@title|上不了校园网？网络有故障?\n点击进入查询故障解决方案==>>#url|http://av.jejeso.com/Ours/911/index.php#pic@title| CopyRight By OURStudio#url|#pic" );
define ( "TEXT", "【校园资讯-回复数字或提示】\n[1]广药新闻\t[2]就业信息\n[3]图书信息\t[cet]查四六级\n[5]动漫更新\t[6]交通卡余额\n[7]查课表\t[8]发找找帮\n[9]勤管兼职\t[10]查成绩\n[11]查选修\t[开]开户指南\n\n【生活服务-回复字母】\n[A]听歌\t[B]公交\t[C]翻译\t[D]快递\n[E]解梦\t[F]手机\t[G]身份\t[H]音乐\t\n[T]天气\n聊天： 任意回复\t\t提意见？回复意见\n\n上不了校园网？回复：校园网故障查询 或者 报障 或者 114.w\n\n合作事宜： 回复 合作 或 推送" );
// 星标标识，默认为* ,用户对话里包含此标识则设置为星标，用于留言
//以下为自定义菜单
define ( "BOOK","【找书】发送 图书+书名 \n【还书】发送 4 查询（需绑定学号）");
define ( "TRAFFIC","【公交线路】发送 公交#城市#公交线路\n\n【交通卡余额查询】\n发送:交通#yct#卡号\n即可查询羊城通余额\n\n发送:交通#szt#卡号\n即可查询深圳通余额^_^\n温馨提示：羊城通卡号为 卡上正面下凹的数字哦");
define ( "MORE", "description|菜单#title|功能向导@title|【校园资讯】- 回复数字或提示\n\n[1]广药新闻\t\t[2]就业信息\n[3]图书信息\t\t[4]还书\n[5]动漫更新\t\t[6]交通卡余额\n[7]查课表\t\t\t\t[8]发找找帮\n[9]勤管兼职\t\t[10]查成绩\n[11]查选修\t\t\t[cet]查四六级\n[开]开户指南\t[意见]给小助手提意见\n[绑定]绑定学号密码#url|#pic@title|【生活服务】-回复字母\n\n[A]听歌\t[B]公交\t[C]翻译\t[D]快递\n[E]解梦\t[F]手机\t[G]身份\t[H]音乐\n[T]天气#url|#pic@title|\t聊天：任意回复\t\t提意见？回复意见#url|#pic@title|合作事宜：回复 合作 或 推送#url|#pic@title|上不了校园网？网络有故障?\n点击进入查询故障解决方案==>>#url|http://av.jejeso.com/Ours/911/index.php#pic@title| CopyRight By OURStudio#url|#pic" );
define ( "BINDING","发送格式：绑定#学号#密码 \n（绑定后，您可以便捷地使用查询功能）");

define ( "FANYI","输入:翻译#英文\n或者直接告诉我你想知道的英文单词\n即刻帮您翻译");
define ( "FLAG", "*" );
// 这里为你的私有库账号
$yourdb = "gdpuer";
$yourpw = "ourstudio";
$welcome = '欢迎关注广药小助手';
// 配置结束

$w = new Wechat ( TOKEN, DEBUG );
// 首次验证，验证过以后可以删掉
if (isset ( $_GET ['echostr'] )) {
    $w->valid ();
    exit ();
}

// 回复用户
$w->reply ( "reply_main" );
// 后续必要的处理...
/* TODO */
exit ();
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
//    else if ($w->get_msg_type () == "image") {
//        $PicUrl = $request ['PicUrl'];
//        $w->set_funcflag ();
//        return "咦,我也有这张照片：" . $PicUrl;
//    }   // 用户发语音时回复语音或音乐
//    else if ($w->get_msg_type () == "voice") {
//        return array (
//                "title" => "你好",
//                "description" => "亲爱的主人",
//                "murl" => "http://weixen-file.stor.sinaapp.com/b/xiaojo.mp3",
//                "hqurl" => "http://weixen-file.stor.sinaapp.com/b/xiaojo.mp3" 
//        );
//    }   // 事件检测
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
                case 'book':
                    $book = BOOK;
                    return $book;
                    break;
                case 'traffic':
                    $traffic = TRAFFIC;
                    //$o = new WebAPI ();
                    //$tianqi=$o->get_weather ( $key );
                    return $traffic;
                    break;
                case 'job':
                    $job = $g->get_gdpu_partime ();
                    $job = replypic($job);
                    return $job;
                    break;
                case 'more':
                    $more = MORE;
                    $more = replypic($more);
                    return $more;
                    break;
                case 'binding':
                    $binding = BINDING;
                    return $binding;
                    break;
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
                case 'chengji':
                    $name = $from;
                    $user = new user;
                    $xh = $user->get_num($name);
                    $pw = $user->get_pw($name);
                    if (($xh) && ($pw)) {
                        $url = 'http://av.jejeso.com/helper/api/chengji/get_chengji.php?xh=' . $xh . '&pw=' . $pw;
                        $chengjidan = file_get_contents ( $url );
                        $content = '#title|成绩单@title|亲爱的学霸Orz，这是您的成绩单请笑纳~^_^(若页面为空请确认密码学号无误)#url|#pic@title|'. $chengjidan . '#pic';
                        $content = replypic($content);
                    }  else {
                        $content = "您还没有绑定账号，请输入“绑定”来绑定账号。\n或者是您的帐号或密码错误，请输入“重绑”重新绑定帐号和密码。\n或者按格式#学号#密码  查询";
                    }
                // $content = '由于教务处当前不允许查成绩，所以小助手暂时无法帮您查询，非常抱歉！';
                    return $content;
                    break;
                case 'kebiao':
                    $day = date("w");
                    $name = $from;
                    $user = new user;
                    $xh = $user->get_num($name);
                    $pw = $user->get_pw($name);
                    if (($xh) && ($pw)) {
                    $url = 'http://av.jejeso.com/helper/kb/bookstrap/test.php?xh=' . $xh . '&pw=' . $pw;
                    //$reply_content = file_get_contents ( $url );
                    $content = '#title|我的课程表@title|亲爱的学霸Orz，这是您的课程表请笑纳~^_^(单击获取，若页面为空请确认密码学号无误)' . '#url|' . $url . '#pic';
                    $content = replypic($content);
                } elseif ((! $xh) || (! $pw)) {
                    
                    $content = "【现已支持所有校区】\n按照以下格式获取课表\n\n【今天课表】\n课表#学号#密码\n\n【周X课表】\n课表#学号#密码#X\n\n(X为1-5,或者是all，否则均默认为当天，周六、日显示全部课表)\n\n【例如】\n获取今天课表：\n课表#1207511199#1207511199\n\n获取周1课表：\n课表#1207511199#1207511199#1";
                } else {
                    $content = "【现已支持所有校区】\n按照以下格式获取课表\n\n【今天课表】\n课表#学号#密码\n\n【周X课表】\n课表#学号#密码#X\n\n(X为1-5,或者是all，否则均默认为当天，周六、日显示全部课表)\n\n【例如】\n获取今天课表：\n课表#1207511199#1207511199\n\n获取周1课表：\n课表#1207511199#1207511199#1";
                    
                }
                    return $content;
                    //$kebiao ="开发中";
                    
                    break;
                case 'xuanxiu':
                    $name = $from;
                    $user = new user;
                    $xh = $user->get_num($name);
                    $pw = $user->get_pw($name);
                
                    if (($xh) && ($pw)) {
                        //$url = 'http://branch2.gdpu.edu.cn/gd/jwc/wx.xuanxiu.api.php?xh=' . $xh . '&pw=' . $pw;
                        $url = 'http://gdpuxx.nat123.net/helper/xuanxiu/getXuanxiu2.php?xh=' . $xh . '&pw=' . $pw;
                        $reply_content = file_get_contents ( $url );
                    } else {
                        $reply_content = "您还没有绑定账号，请输入“绑定”来绑定账号。\n或者是您的帐号或密码错误，请输入“重绑”重新绑定帐号和密码。\n或者按格式#学号#密码  查询";
                    }
                    return $reply_content;
                    break;
                case 'holiday':
                    $holiday = "";
                    return $holiday;
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

    if($date_user->is_talking($from)) {
        $target = $date_user->get_target($from);
        $content = $date_user->filt_wechat_num($content);
        $type = "text";
        $date_user->sendmsg($target, $content, $type, NULL);
        $content = $date_user->caculate_left_time($from);
        $content ="dada";
        return $content;
    }
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
?>
