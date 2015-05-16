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
define ( "WELCOME", "欢迎关注广药小助手!\n直接回复?或者help即可出现菜单" );
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
    
    session_id($from);
    
    session_start();
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
                        else
                            $date_ret = $date_user->find_target_to_talk($from);
                    }else {
                        $_SESSION['first_date'] = 1;
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
                case 'contact':
                    $contact = "合作联系:陈正勇\n手机号、微信:18825076954(61954)\nQQ:1249192238\n功能建议：彭佳铭 \n微信：pengjiaming1994";
                    return $contact;
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

    if ($w->get_msg_type () == "voice" || $w->get_msg_type () == "image" || $w->get_msg_type () == "video") {
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
        }else
            $content = "咦,我也有这东西喔\n";
        return $content;
    }

    /**
     *  $content:获取http的content字段
     *  $reply_content:返回处理结果
     */
    $content = trim ( $request ['Content'] );
    if(isset($_SESSION['first_date'])) {
        $_SESSION['input_wechat'] = 1;
        $content = "请正确输入您的微信号，否则你将收不到别人的信息，输入后即不可更改\n";
        unset($_SESSION['first_date']);
        return $content;
    }
    if(isset($_SESSION['input_wechat'])) {
        $_SESSION['wechat_id'] = $content;
        $_SESSION['sex'] = 0;
        unset($_SESSION['input_wechat']);
        $content = "现在请输入你的性别(男或女)";
        return $content;
    }
    if(isset($_SESSION['wechat_id'])) {
        if (strstr ( $content, '女' ) || strstr ( $content, '0' )) {
            $_SESSION['sex'] = 0;
        }else
            $_SESSION['sex'] = 1;
        $start_time = time();
        $date_user->register($from, $_SESSION['sex'], 0, 0, $_SESSION['wechat_id'], $start_time, 1);
        $content = $date_user->find_target_to_talk($from);
        unset($_SESSION['wechat_id']);
        session_destroy();
        return $content;
    }

    if($date_user->is_talking($from)) {
        $target = $date_user->get_target($from);
        $content = $date_user->filt_wechat_num($content);
        $type = "text";
        $date_user->sendmsg($target, $content, $type, NULL);
        $content = $date_user->caculate_left_time($from);
        return $content;
    }

    if (strstr ( $content, '开户' )) {
        $url = 'http://mp.weixin.qq.com/s?__biz=MjM5OTA1NzMyMg==&mid=201721356&idx=1&sn=2ab0f94f0514fc6d01b5addc76caf446&scene=4#wechat_redirect';
        $reply_content = '#title|开户@title|开户指南入口#url|' . $url . '#pic';
        // 多图文回复
        if (strstr ( $reply_content, 'pic' )){
            $reply_content = replypic ( $reply_content );
        }
        return $reply_content;
    }

    if ($content=='校历') {
    	$reply_content="#title|广药2014-2015第一学期校历@title|查看校历点此进入#url|http://mp.weixin.qq.com/s?__biz=MjM5OTA1NzMyMg==&mid=202787753&idx=1&sn=c37f9e9566bf0dcd016d685c12bc3baa#rd#pic";
    	$reply_content = replypic($reply_content);
    	return $reply_content;
    }

    //交通卡查询
    if (strstr($content,'交')||strstr($content, '羊')||$content == "6") {
    	$arr = explode('#', $content);
    	$ctype = $arr[1];
    	$cno = $arr[2];
    	if ($ctype=='yct'&&!empty($cno)&&$cno!='') {
    		// return "请到羊城通官方公众号查询 谢谢！";
    		$url = "http://av.jejeso.com/helper/api/transfer/get_yct.php?cardno=$cno";
    		return file_get_contents($url);
    	}elseif ($ctype=='szt'&&!empty($cno)&&$cno!='') {
    		$url = "http://av.jejeso.com/helper/api/transfer/get_szt.php?cardno=$cno";
    		return file_get_contents($url);
    	}else{
    		return "小助手交通卡余额查询Beta\n\n发送:交通#yct#卡号\n即可查询羊城通余额\n\n发送:交通#szt#卡号\n即可查询深圳通余额^_^\n更多便利将陆续推出";
    	}
    }

    //content处理
    if (! empty ( $content )) {
        $flag = "0";
        // 广药内网接口
        if ($content == "?" || $content == '？' || $content == 'help') {
            $flag = 'menu';
        } else if ($content == "菜单" || $content == '帮助' || $content == "列表" || $content == '清单' || $content == '功能') {
            $flag = "text";
        } else if (strstr ( $content, "网号" ) || $content == "6") {
            $flag = "6";
        }       // 广药外网接口
        else if ($content == "1" || $content == "2" || $content == "9" || $content == "3" || $content == "4" || strstr ( $content, "还书" ) || strstr ( $content, "还" ) || strstr ( $content, "图书" )) {
            $flag = "gdpuapi";
        }       // 网页外部接口
        else if (strstr ( $content, "表白" ) || strstr ( $content, "cet" ) || strstr ( $content, "Cet" ) || strstr ( $content, "CET6" ) || strstr ( $content, "CET" ) || strstr ( $content, "cet4" ) || strstr ( $content, "cet6" ) || strstr ( $content, "CET4" ) || strstr ( $content, "四级" ) || strstr ( $content, "六级" ) || strstr ( $content, "4级" ) || strstr ( $content, "6级" ) || strstr ( $content, "手机" ) || $content == "f" || $content == "F" || strstr ( $content, "解梦" ) || $content == "e" || $content == "E" || strstr ( $content, "身份" ) || $content == "G" || $content == "g" || strstr ( $content, "找找帮" ) || $content == "8" || strstr ( $content, "音乐" ) || strstr ( $content, "视频" ) || strstr ( $content, "公交" ) || $content == "A" || $content == "a" || $content == "B" || $content == "b" || $content == "C" || $content == "c" || strstr ( $content, "翻译" ) || $content == "D" || $content == 'd' || strstr ( $content, "快递" ) || strstr ( $content, "天气" ) || strstr ( $content, "t" ) || strstr ( $content, "T" )) {
            $flag = "webapi";
        }       

        // 成绩查询
        elseif (strstr ( $content, "成绩" ) || $content == "10") {
            $content=str_replace('＃','#',$content);
            $ret=explode('#',$content);
            $xh=$ret[1];
            $pw=$ret[2];
            if ((!$xh) || (!$pw)) {
                $name = $from;
                $user = new user;
                $xh = $user->get_num($name);
                $pw = $user->get_pw($name);
            }
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
        }
        // 选修查询
        elseif ($content == "11" || strstr ( $content, "选修" )) {
            $content=str_replace('＃','#',$content);
            $ret=explode('#',$content);
            $xh=$ret[1];
            $pw=$ret[2];
            if ((!$xh) || (!$pw)) {
                $name = $from;
                $user = new user;
                $xh = $user->get_num($name);
                $pw = $user->get_pw($name);
            }
            if (($xh) && ($pw)) {
                //$url = 'http://branch2.gdpu.edu.cn/gd/jwc/wx.xuanxiu.api.php?xh=' . $xh . '&pw=' . $pw;
                $url = 'http://gdpuxx.nat123.net/helper/xuanxiu/getXuanxiu2.php?xh=' . $xh . '&pw=' . $pw;
                $reply_content = file_get_contents ( $url );
            } else {
                $reply_content = "您还没有绑定账号，请输入“绑定”来绑定账号。\n或者是您的帐号或密码错误，请输入“重绑”重新绑定帐号和密码。\n或者按格式#学号#密码  查询";
            }
            return $reply_content;
        }
        //绑定帐号
        elseif (strstr($content,"绑定")&&$content!="重新绑定") {
            //查询用户名
            $name = $from;
            $url = "http://av.jejeso.com/helper/api/check.php?name=".$name;
            $reply_content = file_get_contents ($url);
            if($reply_content!='') {//判断用户是否存在 
                // $reply_content = "用户已绑定";
                return "用户已绑定";
            }else {
                $user = new user;
                $content = str_replace ( '＃', '#', $content );
                $ret = explode ( '#', $content );
                $xh = $ret [1];
                $pw = $ret [2];
                if (($xh) && ($pw)) {
                    $num = $xh;
                    $pwd = $pw;
                    $user->blind($name, $num, $pwd);
                    $content = "绑定成功，以后直接输入成绩即可查成绩，无需再输入学号密码\n";
                }else{
                    $content = "请确认【格式】正确\n\n绑定#学号#密码\n重新绑定请回复\n重绑#学号#密码";   
                }
                return $content;
            }
        }
        //重新绑定
        elseif ($content=="重新绑定"||strstr($content,"重绑")) {
            $name = $from;
            $user = new user;
            $content = str_replace ( '＃', '#', $content );
            $ret = explode ( '#', $content );
            $xh = $ret [1];
            $pw = $ret [2];
            if ((!$xh) || (!$pw)) {
                $content = "请确认【格式】正确\n\n重绑#学号#密码";   
            }else {
                $user->reblind($name, $xh, $pw);
                $content = "重新绑定成功";
            }
            return $content;
        }

        // 课表查询
        else if (strstr ( $content, "课表" ) || $content == "7") {
            $content = str_replace ( '＃', '#', $content );
            $ret = explode ( '#', $content );
            $xh = $ret [1];
            $pw = $ret [2];
            $day = date("w");
            if ((!$xh) || (!$pw)) {
                $name = $from;
                $user = new user;
                $xh = $user->get_num($name);
                $pw = $user->get_pw($name);
                if($ret [1])
                    $day = $ret [1];
            }
            if (isset($ret[3])) {
                if (($ret[3] >= 1) && ($ret[3] <= 5)) {
                    $day = $ret[3];
                }
            }
//			else
//                    $day = 1;
//            }
            if (($xh) && ($pw)) {
                $url = 'http://av.jejeso.com/helper/kb/kb.php?xh=' . $xh . '&pw=' . $pw . '&day=' . $day;
                $reply_content = file_get_contents ( $url );
            } elseif ((! $xh) || (! $pw)) {
                $reply_content = "【现已支持所有校区】\n按照以下格式获取课表\n\n【今天课表】\n课表#学号#密码\n\n【周X课表】\n课表#学号#密码#X\n\n(X为1-5,或者是all，否则均默认为当天，周六、日显示全部课表)\n\n【例如】\n获取今天课表：\n课表#1207511199#1207511199\n\n获取周1课表：\n课表#1207511199#1207511199#1";
            } else {
                $reply_content = "【现已支持所有校区】\n按照以下格式获取课表\n\n【今天课表】\n课表#学号#密码\n\n【周X课表】\n课表#学号#密码#X\n\n(X为1-5,或者是all，否则均默认为当天，周六、日显示全部课表)\n\n【例如】\n获取今天课表：\n课表#1207511199#1207511199\n\n获取周1课表：\n课表#1207511199#1207511199#1";
            }
            return $reply_content;
        }

        //四六级
        else if (strstr ( $content, "cet" ) || strstr ( $content, "Cet" ) || strstr ( $content, "四级" ) || strstr ( $content, "六级" )) {
            if (strstr ( $content, "cet" ) || strstr ( $content, "Cet" ) || strstr ( $content, "四级" ) || strstr ( $content, "六级" )) {
                $content = trim ( $content );
                $content = str_replace ( '＃', '#', $content );
                $ret = explode ( '#', $content );
                $zkzh = trim ( $ret [1] );
                $xm = trim ( $ret [2] );
                if ($zkzh == '' || $xm == '') {
                    $reply_content = "【CET4，6级查询】\nby Ourstudio工作室\n请检查格式是否正确\n发送\n\ncet#准考证号#姓名\n\n即可查询";
                }
                if ($zkzh && $xm) {
                    $url = 'http://av.jejeso.com/helper/api/cet/cet_wx.php?zkzh=' . $zkzh . '&xm=' . $xm;
                    $reply_content = file_get_contents ( $url );
                    if ($reply_content == "")
                        $reply_content = "无法查找到你的成绩，请检查学号、姓名是否正确\n";
                    return $reply_content;
                }
            }
        }       

        // 动漫更新查询
        else if (strstr ( $content, "动漫" ) || $content == "5") {
            $url = 'http://110.75.189.200/chris/helper/media_update.php';
            $reply_content = file_get_contents ( $url );
            return $reply_content;
        } 

        //外卖
        else if (strstr ( $content, "外卖" ) || strstr ( $content, "KFC" ) || strstr ( $content, "快餐" )) {
            $reply_content = "1、龙旺食府 1884214432 短号：66694\n9块钱3肉一菜";
            return $reply_content;
        } 

        //建议
        else if (strstr ( $content, "建议" ) || strstr ( $content, "意见" ) || strstr ( $content, "投诉" )) {
            $content = "#title|有奖征集意见@title|填写意见点此进入.感谢您的建议#url|http://av.jejeso.com/helper/api/add_advices/commit.html#pic";
            if (strstr ( $reply_content, 'pic' ))           // 多图文回复
            {
                $reply_content = replypic ( $reply_content );
            }
            return $reply_content;
        } 

        //报障
        else if (strstr ( $content, "报障" ) || strstr ( $content, "上不了网" ) || strstr ( $content, "校园网常见错误" ) || strstr ( $content, "114.w" )) {
            $reply_content = "#title|校园网常见错误@title|查询校园网错误点此进入#url|http://av.jejeso.com/Ours/911/index.php#pic";
            if (strstr ( $reply_content, 'pic' ))           // 多图文回复
            {
                $reply_content = replypic ( $reply_content );
            }
            return $reply_content;
        } 

        //合作事宜
        else if ($content == "合作" || $content == "推送") {
            $reply_content = "联系人:陈正勇\n手机号、微信:18825076954(61954)\nQQ:1249192238";
            return $reply_content;
        }

        else if ( strstr ( $content, "新闻" )) {
            $reply_content = "#title|广药新闻@title|查看最新广药新闻点此进入#url|http://ours.123nat.com:59832/news/#pic";
            if (strstr ( $reply_content, 'pic' ))           // 多图文回复
            {
                $reply_content = replypic ( $reply_content );
            }
            return $reply_content;
        }
            // menu内容
        if ($flag == "menu" || strstr ( $content, "查询" )) {
            $reply_content = MENU;
        }       
        // text内容
        else if ($flag == "text") {
            $reply_content = TEXT;
        }       

        // // 通过广药内网接口获得返回内容
        // else if ($flag == "6" || $content == "6") {
        //     if ($flag == "6") {
        //         $reply_content = "查询接口<a href=\"http://www.gzekt.com\">http://www.gzekt.com</a>";
        //     }
        // }       

        // 通过广药外网网接口获得返回内容
        else if ($flag == "gdpuapi") {
            $g = new WebAPI ();
            //广药新闻
            if ($content == "1" || strstr ( $content, "新闻" )) {
                $reply_content = "#title|广药新闻@title|查看最新广药新闻点此进入#url|http://ours.123nat.com:59832/news/#pic";
                //$reply_content = $g->get_gdpu_news ();
            } 
            //工作信息
            else if ($content == "2") {
                $reply_content = $g->get_gdpu_jobs ();
            } 
            //勤管兼职
            else if ($content == "9") {
                $reply_content = $g->get_gdpu_partime ();
            } 
            //图书信息
            else if (strstr ( $content, "图书" ) || $content == "3") {
                $keyword = str_replace ( "图书", "", $content );
                $reply_content = $g->get_lib_book ( $keyword );
            } 
            //借书信息
            else if ($content == "4" || strstr ( $content, "还" )) {
                $array = explode ( "#", $content );
                $xh = $array [1];
                if ((!$xh)) {
                    $name = $from;
                    $user = new user;
                    $xh = $user->get_num($name);
                }

                if ($xh == '') {
                    $reply_content = "查询正确格式为:\n还书#学号";
                } else {
                    $reply_content = $g->get_lib_borrowbook ( $xh );
                }
            } else {
                $reply_content = "未知外网接口！";
            }
        }       

        // 通过外部接口获得返回内容
        else if ($flag == "webapi") {
            $o = new WebAPI ();

            //四六级
            if ($content == "4" || strstr ( $content, "cet" ) || strstr ( $content, "Cet" ) || strstr ( $content, "四级" ) || strstr ( $content, "六级" )) {
                if ($content == "4" || strstr ( $content, "cet" ) || strstr ( $content, "Cet" ) || strstr ( $content, "四级" ) || strstr ( $content, "六级" )) {
                    $content = trim ( $content );
                    $content = str_replace ( '＃', '#', $content );
                    $ret = explode ( '#', $content );
                    $zkzh = trim ( $ret [1] );
                    $xm = trim ( $ret [2] );
                    if ($zkzh == '' || $xm == '') {
                        $reply_content = "【CET4，6级查询】\nby Ourstudio工作室\n请检查格式是否正确\n发送\n\ncet#准考证号#姓名\n\n即可查询";
                    }
                    if ($zkzh && $xm) {
                        $url = 'http://av.jejeso.com/helper/api/cet/cet_wx.php?zkzh=' . $zkzh . '&xm=' . $xm;
                        $reply_content = file_get_contents ( $url );
                        if ($reply_content == "")
                            $reply_content = "无法查找到你的成绩，请检查学号、姓名是否正确\n";
                        return $reply_content;
                    }
                }
            } 

            //手机查询
            else if (strstr ( $content, "手机" ) || $content == "f" || $content == "F") {
                $date = explode ( "#", $content );
                $number = $date [1];
                if ($number == '') {
                    $reply_content = "查归属地格式:手机#手机号\n即可查询归属地";
                } else {
                    $reply_content = $o->get_mobile ( $number );
                }
            } 

            //解梦
            else if (strstr ( $content, "解梦" ) || $content == "e" || $content == "E") {
                $date = explode ( "#", $content );
                $key = $date [1];
                if ($key == '') {
                    $reply_content = "发送格式:解梦#关键词\n即可解开你的梦境";
                } else {
                    $reply_content = $o->get_dream ( $key );
                }
            } 

            //今日彩票
            else if ($content == "l" || $content == "L") {
                $reply_content = $o->get_award ();
            } 

            //身份证
            else if (strstr ( $content, "身份" ) || $content == "g" || $content == "G") {
                $date = explode ( "#", $content );
                $no = $date [1];
                if ($no == '') {
                    $reply_content = "查看身份:\n输入\t身份#身份证";
                } else {
                    $reply_content = $o->get_idcard ( $no );
                }
            } 

            //快递
            else if (strstr ( $content, "快递" ) || $content == "D" || $content == "d") {
                $date = explode ( "#", $content );
                $com = $date [1];
                $no = $date [2];
                if ($com == '' || $no == '') {
                    $reply_content = "输入格式:\n快递#快递公司#单号\n即可查询您的包裹\n最新状态";
                } else {
                    $reply_content = $o->kuaidi ( $com, $no );
                }
            } 

            //豆瓣听歌
            else if ($content == "A" || $content == "a") {
                $reply_content = $o->get_song_douban ();
            } 

            //公交
            else if (strstr ( $content, "公交" ) || $content == "B" || $content == "b") {
                $date = explode ( "#", $content );
                $city = $date [1];
                $no = $date [2];
                if ($city == '' || $no == '') {
                    $reply_content = "输入:公交#城市#公交线路\n即可获得线路";
                } else {
                    $reply_content = $o->get_bus ( $city, $no );
                }
            } 

            //翻译
            else if (strstr ( $content, "翻译" ) || $content == "C" || $content == "c") {
                $date = explode ( "#", $content );
                $key = $date [1];
                if ($key == '') {
                    $reply_content = "输入:翻译#英文\n或者直接告诉小助手你想知道的英文单词\n小助手即刻帮您翻译";
                } else {
                    $reply_content = $o->enTozh ( $key );
                }
            } 

            //天气查询
            else if (strstr ( $content, "天气" ) || $content == "t" || $content == "T") {
                
                $date = explode ( "#", $content );
                $key = $date [1];
                if ($key == '') {
                    $reply_content = "发送格式:天气#城市\n即可查询天气预报";
                } else {
                    $reply_content = $o->get_weather ( $key );
                }
            } 

            //找找帮
            else if (strstr ( $content, "找找帮" ) || $content = "8") {
                $text = str_replace ( '找找帮', '', $content );
                $reply_content = $o->send_zzbon ( $text, $from );
            } 

            //腾讯音乐
            else if (strstr ( $content, "音乐" ) || $content == "h" || $content == "H") {
                $reply_content = $o->get_song_tencent ( $content );
                $reply_content = mb_convert_encoding ( $reply_content, 'utf-8', 'gbk' );
            } 

            //视频
            else if (strstr ( $content, "视频" )) {
                $reply_content = $o->get_video_youku ( $content );
            } 

            //表白
            else if (strstr ( $content, "表白" )) {
                $reply_content = $o->get_biaobai ( $content, $from );
            } 
            else {
                $reply_content = "未知外部接口！";
            }
        }
    
        // 通过机器人接口获得返回内容
        else {
            // 表情处理
            $content = $w->biaoqing ( $content );
            // 如果有星标的标记则设为星标(用于留言)
            if (strstr ( $content, FLAG )) {
                $w->set_funcflag ();
            }
            $url = 'http://www.tuling123.com/openapi/api?key=2de48f93cfa6fb3fff1c0ede2ac8b953&info=' . $content;
            $reply_content = file_get_contents ( $url );
            preg_match_all ( '/{"code":100000,"text":"(.+?)"}/is', $reply_content, $core );
            $reply_content = $core [1] [0];
            if (YOURNICK) {
                $reply_content = str_replace ( '小豆', YOURNICK, $reply_content );
            }
            if ($reply_content == "") {
                $reply_content = "你说的话太深奥了，教我如何答你好呢？\n命令：问...答...\n";
            }
        }
    } else if ($welcome != '') {
        $reply_content = WELCOME;
    }
    // 音乐地址
    if (strstr ( $reply_content, 'murl' )) { // 音乐
        $a = array ();
        foreach ( explode ( '#', $reply_content ) as $reply_content ) {
            list ( $k, $v ) = explode ( '|', $reply_content );
            $a [$k] = $v;
        }
        $reply_content = $a;
    } elseif (strstr ( $reply_content, 'pic' ))     // 多图文回复
    {
        $reply_content = replypic ( $reply_content );
    }
    // 最后返回
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
