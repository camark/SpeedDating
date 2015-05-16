<?php
header ( "content-Type: text/html; charset=utf-8" );
class WebAPI {
	// 学校新闻
	public function get_gdpu_news() {
		$url = 'http://av.jejeso.com/helper/api/news/get_news.php';
		$result = file_get_contents ( $url );
		return $result;
	}
	// 就业信息
	public function get_gdpu_jobs() {
		$url = 'http://av.jejeso.com/helper/api/jobs/get_jobs.php';
		$result = file_get_contents ( $url );
		return $result;
	}
	// 图书信息
	public function get_lib_book($keyword) {
		$url = 'http://av.jejeso.com/helper/api/book/book.php?keyword=' . $keyword;
		$result = file_get_contents ( $url );
		return $result;
	}
	// 还书信息
	public function get_lib_borrowbook($keyword) {
		$url = 'http://av.jejeso.com/helper/api/book/lib.php?xh=' . $keyword;
		$result = file_get_contents( $url );
		return $result;
	}
	// 勤管兼职
	public function get_gdpu_partime() {
		$url = 'http://av.jejeso.com/helper/api/partime/get_partime.php';
		$result = file_get_contents ( $url );
		return $result;
	}
	//查四六级  
 	public function get_ours_cet($zkzh,$xm)    
	{    				
        $url = 'http://av.jejeso.com/helper/api/cet/cet.php?zkzh='.$zkzh.'&xm='.$xm;		
		$result= file_get_contents($url);
		return $result;   
	}
	public function get_cet($zkzh)    
	{    				
        $url = 'http://av.jejeso.com/helper/api/cet/cet_wx.php?xh='.$zkzh;		
		$result= file_get_contents($url);
		return $result;   
	}
	//翻译
	public function enTozh($key) {
		$url = "http://brisk.eu.org/api/translate.php?from=en&to=zh-CN&text=" . $key;
		$curl = curl_init ();
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		$data = curl_exec ( $curl );
		curl_close ( $curl );
		$json = json_decode ( $data );
		return $json->{'res'} . "\n";
	}
	// 快递查询
	public function kuaidi($com, $no) {
		$url = "http://av.jejeso.com/helper/api/kuaidi/get_kuaidi.php?com=" . $com . "&no=" . $no;
		$result = file_get_contents ( $url );
		return $result;
	}
	// 周公解梦
	public function get_dream($key) {
		$key = rawurlencode ( mb_convert_encoding ( $key, 'gbk', 'utf-8' ) );
		$url = 'http://www.gpsso.com/WebService/Dream/Dream.asmx/SearchDreamInfo?Dream=' . $key;
		$string = file_get_contents ( $url );
		$xml = simplexml_load_string ( $string );
		return $xml->DREAM;
	}
	// 今日彩票
	public function get_award() {
		$url = 'http://www.gpsso.com/webservice/caipiao/award.asmx/GetAward?';
		$string = file_get_contents ( $url );
		$xml = simplexml_load_string ( $string );
		$result = '[' . $xml->SSQ . ']' . '[' . $xml->SD . ']' . '[' . $xml->QLC . ']' . '[' . $xml->DLT . ']' . '[' . $xml->PLS . ']' . '[' . $xml->PLW . ']' . '[' . $xml->QXC . ']' . '[' . $xml->SYXW . ']' . '[' . $xml->XSSC . ']';
		return $result;
	}
	// 身份证号
	public function get_idcard($no) {
		$url = 'http://www.gpsso.com/webservice/idcard/idcard.asmx/SearchIdCard?IdCard=' . $no;
		$string = file_get_contents ( $url );
		$xml = simplexml_load_string ( $string );
		$result = '性别:' . '[' . $xml->SIX . ']' . "\n" . '出生:' . '[' . $xml->BIRTHDAY . ']' . "\n" . '农历:' . '[' . $xml->NONGLI . ']' . "\n\t" . '[' . $xml->WEEK . ']' . "\n" . '地址:' . '[' . $xml->ADDRESS . ']';
		return $result;
	}
	// 查手机号
	public function get_mobile($number) {
		$url = 'http://av.jejeso.com/helper/api/mobile/get_mobile.php?number=' . $number;
		$result = file_get_contents ( $url );
		return $result;
	}
	// 发找找帮
	public function send_zzbon($text) {
		$url = 'http://av.jejeso.com/helper/api/zzbon/zzbon.php?key=' . $text . '&wx_token=' . md5 ( 'zzbon' );
		$result = file_get_contents ( $url );
		return $result;
	}
	// 豆瓣听歌
	public function get_song_douban() {
		$url = 'http://av.jejeso.com/helper/api/song/douban_song.php';
		$result = file_get_contents ( $url );
		return $result;
	}
	// 腾讯音乐
	public function get_song_tencent($key) {
		$url = 'http://av.jejeso.com/helper/api/song/tencent_song.php?key=' . $key;
		$result = file_get_contents ( $url );
		return $result;
	}
	// 优酷视频
	public function get_video_youku($key) {
		$url = 'http://av.jejeso.com/helper/api/video/youku.php?key=' . $key;
		$result = file_get_contents ( $url );
		return $result;
	}
	// 表白
	public function get_biaobai($key, $from) {
		$url = 'http://phpdo9.nat123.net:52182/helper/api/biaobai/biaobai.php?key=' . $key . '&from=' . $from;
		$result = file_get_contents ( $url );
		return $result;
	}
	// 公交线路
	public function get_bus($city, $no) {
		$url = 'http://av.jejeso.com/helper/api/bus/get_bus.php?city=' . $city . '&no=' . $no;
		$result = file_get_contents ( $url );
		return $result;
	}
	// 解码字符
	function get_utf8_string($content) {
		$encoding = mb_detect_encoding ( $content, array (
				'ASCII',
				'UTF-8',
				'GB2312',
				'GBK',
				'BIG5' 
		) );
		return mb_convert_encoding ( $content, 'utf-8', $encoding );
	}
	// 天气预报
	public function get_weather($key) {
		$content = iconv ( "utf-8", "gb2312//IGNORE", $key );
		$con = urlencode ( $content );
		$urlweather = 'http://php.weather.sina.com.cn/xml.php?city=' . $con . '&password=DJOYnieT8234jlsK&day=1';
		$xmlweather = new DOMDocument ();
		$xmlweather->load ( $urlweather );
		$allweather = $xmlweather->getElementsByTagName ( "Weather" );
		$wea1 = $allweather->item ( 0 );
		function getNodeVal($MyNode, $tagName) {
			return $MyNode->getElementsByTagName ( $tagName )->item ( 0 )->nodeValue;
		}
		$city = getNodeVal ( $wea1, "city" ); // 0 数字为数组序号
		$time = getNodeVal ( $wea1, "savedate_weather" ); // 时间点 1
		$day = getNodeVal ( $wea1, "status1" ); // 白天天气 2
		$night = getNodeVal ( $wea1, "status2" ); // 晚上天气 3
		$direction1 = getNodeVal ( $wea1, "direction1" ); // 白天风向 4
		$direction2 = getNodeVal ( $wea1, "direction2" ); // 晚上风向 5
		$power1 = getNodeVal ( $wea1, "power1" ); // 白天风力等级 6
		$power2 = getNodeVal ( $wea1, "power2" ); // 晚上风力等级 7
		$temperature1 = getNodeVal ( $wea1, "temperature1" ); // 白天温度 8
		$temperature2 = getNodeVal ( $wea1, "temperature2" ); // 晚上温度 9
		// 生活指数
		$chy_l = getNodeVal ( $wea1, "chy_l" ); // 穿衣指数 薄短袖类 10
		$chy_shuoming = getNodeVal ( $wea1, "chy_shuoming" ); // 穿衣建议 11
		$gm_l = getNodeVal ( $wea1, "gm_l" ); // 感冒指数 12
		$gm_s = getNodeVal ( $wea1, "gm_s" ); // 预防建议 13
		$pollution_l = getNodeVal ( $wea1, "pollution_l" ); // 污染指数 14
		$pollution_s = getNodeVal ( $wea1, "pollution_s" ); // 天气条件对污染物扩散的影响 15
		$ssd_l = getNodeVal ( $wea1, "ssd_l" ); // 体感度指数 16
		$ssd_s = getNodeVal ( $wea1, "ssd_s" ); // 体感度指数说明 17
		$zwx_l = getNodeVal ( $wea1, "zwx_l" ); // 紫外线指数 18
		$zwx_s = getNodeVal ( $wea1, "zwx_s" ); // 紫外线指数说明 19
		$ktk_l = getNodeVal ( $wea1, "ktk_l" ); // 空调指数 20
		$ktk_s = getNodeVal ( $wea1, "ktk_l" ); // 空调指数说明 21
		$xcz_l = getNodeVal ( $wea1, "xcz_l" ); // 洗车指数 22
		$xcz_s = getNodeVal ( $wea1, "xcz_s" ); // 洗车指数说明 23
		$yd_l = getNodeVal ( $wea1, "yd_l" ); // 运动指数 24
		$yd_s = getNodeVal ( $wea1, "yd_s" ); // 运动指数 25
		$data = array (
				$city,
				$time,
				$day,
				$night,
				$direction1,
				$direction2,
				$power1,
				$power2,
				$temperature1,
				$temperature2,
				$chy_l,
				$chy_shuoming,
				$gm_l,
				$gm_s,
				$pollution_l,
				$pollution_s,
				$ssd_l,
				$ssd_s,
				$zwx_l,
				$zwx_s,
				$ktk_l,
				$ktk_s,
				$xcz_l,
				$xcz_s,
				$yd_l,
				$yd_s 
		);
		$result = "【" . $str_key . "天气预报】\n" . $data [1] . "天气情况" . "\n白天天气：" . $data [2] . "\n" . "夜晚天气：" . $data [3] . "\n白天温度： " . $data [8] . "℃\n夜晚温度：" . $data [9] . "℃\n" . "【生活指数】：\n穿衣指数：" . $data [10] . "\n穿衣建议： " . $data [11] . "\n空气污染指数：" . $data [14] . "\n紫外线指数：" . $data [18] . "\n紫外线指数说明： " . $data [19] . "\n";
		return $result;
	}
}
?>
