<?php

function pe($a){
		dump($a);
		exit;
	if(I('get.client_ip','')){
		cookie('client_ip', I('get.client_ip',''));
	}
	if(cookie('client_ip') == get_client_ip() || $_SERVER['REMOTE_ADDR'] == '127.0.0.1'){
		dump($a);
		exit;
	}
} 
function p($a){
	dump($a);
}
function short_url($url){
	$uri = 'http://dwz.cn/create.php';
	$data = array (
        'url' => $url,
	);
	$ch = curl_init ();
	curl_setopt($ch,CURLOPT_URL,$uri);
	curl_setopt($ch,CURLOPT_POST,1);
	curl_setopt($ch,CURLOPT_HEADER,0);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
	$short_url = curl_exec($ch);
	curl_close($ch);
	$json = json_decode($short_url,true);
	if($json['status'] == 0){
		return $json['tinyurl'];
	}else{
		return $url;
	}
}
/**
*
+--------------------------------------------------------------------
* Description 递归创建目录
+--------------------------------------------------------------------
* @param  string $dir 需要创新的目录
+--------------------------------------------------------------------
* @return 若目录存在,或创建成功则返回为TRUE
+--------------------------------------------------------------------
* @author gongwen
+--------------------------------------------------------------------
*/
function mkdirs($dir, $mode = 0777){ 
	if (is_dir($dir) || mkdir($dir, $mode)) return TRUE; 
	if (!mkdirs(dirname($dir), $mode)) return FALSE; 
	return mkdir($dir, $mode); 
}

function hp($a){
	echo('<!--');
	dump($a);
	echo('-->');
}
function md5_16($str){
    return substr(md5($str),8,16);
}
function is_app() {
	return get_device_type() == 'app' || strtolower($_SERVER['HTTP_CLIENT_ID']) == 'tukeji-app' || I('get.is_app',0,'intval') == 1;
}

function is_mobile(){
	return get_device_type() == 'ios' || get_device_type() == 'android';
}

function get_device_type() {
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$type = 'other';
	if (strpos($agent, 'iphone') !== false){//} || strpos($agent, 'ipad') !== false) {
		$type = 'ios';
	}
	if (strpos($agent, 'android') !== false) {
		$type = 'android';
	}
	// if (strpos($agent, 'micromessenger') !== false) {
	// 	$type = 'weixin';
	// }
	if (strpos($agent, 'tukeji-app') !== false) {
		$type = 'app';
	}
	return $type;
}

/*
*   判断是否爬虫
*/
function is_crawler() {
	$userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$spiders = array(
		'Googlebot', // Google 爬虫
		'Baiduspider', // 百度爬虫
		'Yahoo! Slurp', // 雅虎爬虫
		'YodaoBot', // 有道爬虫
		'msnbot', // Bing爬虫
		'douban', // Bing爬虫
		'weibo',
		'renren',
		'qzone',
		'soso',
		'sina',
		// 更多爬虫关键字
	);
	foreach ($spiders as $spider) {
		$spider = strtolower($spider);
		if (strpos($userAgent, $spider) !== false) {
			return true;
		}
	}
	return false;
}

/**
 * 友好的时间显示
 *
 * @param int    $sTime 待显示的时间
 * @param string $type  类型. normal | mohu | full | ymd | other
 * @return string
 */
function friendly_date($sTime, $type = 'normal', $default='-') {
	if(!$sTime) return $default;
	//sTime=源时间，cTime=当前时间，dTime=时间差
	$cTime = NOW_TIME;
	$dTime = $cTime - $sTime;
	//$dDay     =   intval(date("Ymd",$cTime)) - intval(date("Ymd",$sTime));
	$dDay = $dTime / 3600 / 24;
	$dYear = intval(date("Y", $cTime)) - intval(date("Y", $sTime));
	//normal：n秒前，n分钟前，n小时前，日期
	if ($type == 'normal') {
		if ($dTime < 0) {
			if ($dYear == 0) {
				return date("m月d日 H:i", $sTime);
			} else {
				return date("Y-m-d H:i", $sTime);
			}
		}
		if ($dTime < 3) {
			return "刚刚";
		}elseif ($dTime < 60) {
			return $dTime . "秒前";
		} elseif ($dTime < 3600) {
			return intval($dTime / 60) . "分钟前";
		} elseif ($dTime >= 3600 && $dDay == 0) {
			//return intval($dTime/3600)."小时前";
			return '今天' . date('H:i', $sTime);
		} elseif ($dYear == 0) {
			return date("m月d日 H:i", $sTime);
		} else {
			return date("Y-m-d H:i", $sTime);
		}
	} elseif ($type == 'short_date') {
	   if ($dYear == 0) {
			return date("m-d", $sTime);
		} else {
			return date("Y-m-d", $sTime);
		}
	} elseif ($type == 'mohu') {
		if ($dTime < 0) {
			return date("未来", $sTime);
		}
		if ($dTime < 3) {
			return "刚刚";
		}elseif ($dTime < 60) {
			return $dTime . "秒前";
		} elseif ($dTime < 3600) {
			return intval($dTime / 60) . "分钟前";
		} elseif ($dTime >= 3600 && $dDay == 0) {
			return intval($dTime / 3600) . "小时前";
		} elseif ($dDay > 0 && $dDay <= 7) {
			return intval($dDay) . "天前";
		} elseif ($dDay > 7 && $dDay <= 30) {
			return ceil($dDay / 7) . '周前';
		} elseif ($dDay > 30 && $dYear < 1) {
			return ceil($dDay / 30) . '个月前';
		}else if($$dYear > 0){
			return $dYear . '年前';
		}
		//full: Y-m-d , H:i:s
	} elseif ($type == 'full') {
		return date("Y-m-d H:i:s", $sTime);
	} elseif ($type == 'ymd') {
		return date("Y-m-d", $sTime);
	} elseif ($type == 'chinese') {
		if ($dYear == 0) {
			return date("m月d日", $sTime);
		}else{
			return date("Y年m月d日", $sTime);
		}
	} else {
		if ($dTime < 0) {
			return date("Y-m-d H:i:s", $sTime);
		}
		if ($dTime < 3) {
			return "刚刚";
		}elseif ($dTime < 60) {
			return $dTime . "秒前";
		} elseif ($dTime < 3600) {
			return intval($dTime / 60) . "分钟前";
		} elseif ($dTime >= 3600 && $dDay == 0) {
			return intval($dTime / 3600) . "小时前";
		} elseif ($dYear == 0) {
			return date("Y-m-d H:i:s", $sTime);
		} else {
			return date("Y-m-d H:i:s", $sTime);
		}
	}
}

//计算时间间隔
function datediff($part, $begin, $end) {
	$diff = $end - $begin;
	switch ($part) {
		case "y":
			$retval = bcdiv($diff, (60 * 60 * 24 * 365), 0);
			break;
		case "m":
			$retval = bcdiv($diff, (60 * 60 * 24 * 30), 0);
			break;
		case "w":
			$retval = bcdiv($diff, (60 * 60 * 24 * 7), 0);
			break;
		case "d":
			$retval = bcdiv($diff, (60 * 60 * 24), 0);
			break;
		case "h":
			$retval = bcdiv($diff, (60 * 60), 0);
			break;
		case "n":
			$retval = bcdiv($diff, 60, 0);
			break;
		case "s":
			$retval = $diff;
			break;
	}
	return round($retval);
}
//时间计算
function dateadd($part, $number, $date) {
	$date_array = getdate(strtotime($date));
	$hor = $date_array["hours"];
	$min = $date_array["minutes"];
	$sec = $date_array["seconds"];
	$mon = $date_array["mon"];
	$day = $date_array["mday"];
	$yar = $date_array["year"];

	switch ($part) {
		case "y":
			$yar+= $number;
			break;
		case "q":
			$mon+= ($number * 3);
			break;
		case "m":
			$mon+= $number;
			break;
		case "w":
			$day+= ($number * 7);
			break;
		case "d":
			$day+= $number;
			break;
		case "h":
			$hor+= $number;
			break;
		case "n":
			$min+= $number;
			break;
		case "s":
			$sec+= $number;
			break;
	}
	return date("Y-m-d H:i:s", mktime($hor, $min, $sec, $mon, $day, $yar));
}

/* * *************************************************************************
 * Pinyin.php
 * ------------------------------
 * Date : Nov 7, 2006
 * Copyright : 修改自网络代码,版权归原作者所有
 * Mail :
 * Desc. : 拼音转换
 * History :
 * Date :
 * Author :
 * Modif. :
 * Usage Example :
  echo Pinyin('这是小超的网站，欢迎访问http://www.163.com'); //默认是gb编码
  echo Pinyin('第二个参数随意设置',2); //第二个参数随意设置即为utf8编
 * ************************************************************************* */

function pinyin($_String, $_Code = 'utf-8', $isInitial = false) {
	$_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha" .
			"|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|" .
			"cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er" .
			"|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui" .
			"|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang" .
			"|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang" .
			"|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue" .
			"|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne" .
			"|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen" .
			"|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang" .
			"|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|" .
			"she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|" .
			"tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu" .
			"|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you" .
			"|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|" .
			"zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";

	$_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990" .
			"|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725" .
			"|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263" .
			"|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003" .
			"|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697" .
			"|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211" .
			"|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922" .
			"|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468" .
			"|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664" .
			"|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407" .
			"|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959" .
			"|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652" .
			"|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369" .
			"|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128" .
			"|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914" .
			"|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645" .
			"|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149" .
			"|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087" .
			"|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658" .
			"|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340" .
			"|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888" .
			"|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585" .
			"|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847" .
			"|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055" .
			"|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780" .
			"|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274" .
			"|-10270|-10262|-10260|-10256|-10254";
	$_TDataKey = explode('|', $_DataKey);
	$_TDataValue = explode('|', $_DataValue);

	$_Data = (PHP_VERSION >= '5.0') ? array_combine($_TDataKey, $_TDataValue) : _array_combine($_TDataKey, $_TDataValue);
	arsort($_Data);
	reset($_Data);

	if ($_Code != 'gb2312')
		$_String_fb = _u2_utf8_fb($_String);
	$_Res = '';
	for ($i = 0; $i < strlen($_String_fb); $i++) {
		$_P = ord(substr($_String_fb, $i, 1));
		if ($_P > 160) {
			$_Q = ord(substr($_String_fb, ++$i, 1));
			$_P = $_P * 256 + $_Q - 65536;
		}
		$_Res .= _pinyin($_P, $_Data) . ' ';
	}
	$py[0] = preg_replace("/[^a-zA-Z0-9]*/", '', $_Res);
	if(empty($py[0])){
		load('Common.pinyin');
		$py = pinyin2($_String);
	}else{
		$py[0] = strtolower($py[0]);
		foreach (explode(' ', $py[0]) as $key => $value) {
			$py[1] .= substr($value, 0, 1);
		}
		$py[0] = str_replace(' ', '', $py[0]);
	}
	return $py;
}

function _pinyin($_Num, $_Data, $isInitial) {
	if ($_Num > 0 && $_Num < 160)
		return chr($_Num);
	elseif ($_Num < -20319 || $_Num > -10247)
		return '';
	else {
		foreach ($_Data as $k => $v) {
			if ($v <= $_Num)
				break;
		}
		if ($isInitial)
			$k = substr($k, 0, 1); //是否只显示首写
		return $k;
	}
}

function _u2_utf8_fb($_C) {
	$_String = '';
	if ($_C < 0x80)
		$_String .= $_C;
	elseif ($_C < 0x800) {
		$_String .= chr(0xC0 | $_C >> 6);
		$_String .= chr(0x80 | $_C & 0x3F);
	} elseif ($_C < 0x10000) {
		$_String .= chr(0xE0 | $_C >> 12);
		$_String .= chr(0x80 | $_C >> 6 & 0x3F);
		$_String .= chr(0x80 | $_C & 0x3F);
	} elseif ($_C < 0x200000) {
		$_String .= chr(0xF0 | $_C >> 18);
		$_String .= chr(0x80 | $_C >> 12 & 0x3F);
		$_String .= chr(0x80 | $_C >> 6 & 0x3F);
		$_String .= chr(0x80 | $_C & 0x3F);
	}
	return iconv('UTF-8', 'GB2312', $_String);
}

function _array_combine($_Arr1, $_Arr2) {
	for ($i = 0; $i < count($_Arr1); $i++)
		$_Res[$_Arr1[$i]] = $_Arr2[$i];
	return $_Res;
}


function get_zodiac($date){
	if (strstr ( $date, '-' ) === false && strlen ( $date ) !== 8)
		$date = date ( "Y-m-d", $date );
	if (strlen ( $date ) === 8) {
		if (eregi ( '([0-9]{4})([0-9]{2})([0-9]{2})$', $date, $bir ))
			$date = "{$bir[1]}-{$bir[2]}-{$bir[3]}";
	}
	if (strlen ( $date ) < 8)
		return false;
	$tmpstr = explode ( '-', $date );
	if (count ( $tmpstr ) !== 3)
		return false;
	$month =substr($date,5,2); //取出月份
	$day   =substr($date,8,2); //取出日期
	$y = ( int ) $tmpstr [0];
	$m = ( int ) $tmpstr [1];
	$d = ( int ) $tmpstr [2];
	$result = array ();
	$xzdict = array ('摩羯', '宝瓶', '双鱼', '白羊', '金牛', '双子', '巨蟹', '狮子', '处女', '天秤', '天蝎', '射手' );
	$zone = array (1222, 122, 222, 321, 421, 522, 622, 722, 822, 922, 1022, 1122, 1222 );
	if ((100 * $m + $d) >= $zone [0] || (100 * $m + $d) < $zone [1]) {
		$i = 0;
	} else {
		for($i = 1; $i < 12; $i ++) {
			if ((100 * $m + $d) >= $zone [$i] && (100 * $m + $d) < $zone [$i + 1])
				break;
		}
	}
	$result ['xz'] = $xzdict [$i] . '座';
	$gzdict = array (array ('甲', '乙', '丙', '丁', '戊', '己', '庚', '辛', '壬', '癸' ), array ('子', '丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉', '戌', '亥' ) );
	$i = $y - 1900 + 36;
	$result ['gz'] = $gzdict [0] [($i % 10)] . $gzdict [1] [($i % 12)];

	$sxdict = array ('鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪' );
	$result ['sx'] = $sxdict [(($y - 4) % 12)];
	return $result;
}

/**作用：统计字符长度包括中文、英文、数字
 * 参数：需要进行统计的字符串、编码格式目前系统统一使用UTF-8
 * 时间：2009-07-15
 * 修改记录:
		 $str = "kds";
		echo sstrlen($str,'utf-8');
 * */
 function mbstrlen($str) {
	return ceil((strlen($str) + mb_strlen($str,"UTF8")) / 2);
}

function cutstr($string, $length, $dot = '...') {
	if(mbstrlen($string) <= $length) {
		return $string;
	}
	$pre = chr(1);
	$end = chr(1);
	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), $string);

	$strcut = '';
	$n = $tn = $noc = 0;
	while($n < strlen($string)) {

		$t = ord($string[$n]);
		if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
			$tn = 1; $n++; $noc++;
		} elseif(194 <= $t && $t <= 223) {
			$tn = 2; $n += 2; $noc += 2;
		} elseif(224 <= $t && $t <= 239) {
			$tn = 3; $n += 3; $noc += 2;
		} elseif(240 <= $t && $t <= 247) {
			$tn = 4; $n += 4; $noc += 2;
		} elseif(248 <= $t && $t <= 251) {
			$tn = 5; $n += 5; $noc += 2;
		} elseif($t == 252 || $t == 253) {
			$tn = 6; $n += 6; $noc += 2;
		} else {
			$n++;
		}
		if($noc >= $length) {
			break;
		}
	}
	if($noc > $length) {
		$n -= $tn;
	}
	$strcut = substr($string, 0, $n);
	$strcut = str_replace(array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

	$pos = strrpos($strcut, chr(1));
	if($pos !== false) {
		$strcut = substr($strcut,0,$pos);
	}
	return $strcut.$dot;
}
function array_get_by_key($array, $key){
	if (!trim($key)) return false;
	preg_match_all("/\"" . $key . "\";\w{1}:(?:\d+:|)\"?(.*?)\"?;/", serialize($array), $res);
	return $res[1];
}
function get_ip_place($ip){
	$ret = file_get_contents("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=" . $ip);
	return json_decode($ret, true);
}
/**
 * XSS 清除处理
 */
function xssClean($data, $htmlentities = 0)
{
	$htmlentities && $data = htmlentities($data, ENT_QUOTES, 'utf-8');
	// Fix &entity\n;
	$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
	$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
	$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
	$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

	// Remove any attribute starting with "on" or xmlns
	$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

	// Remove javascript: and vbscript: protocols
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"\\\\]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"\\\\]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"\\\\]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

	// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"\\\\]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"\\\\]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"\\\\]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

	// Remove namespaced elements (we do not need them)
	$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

	do
	{
		// Remove really unwanted tags
		$old_data = $data;
		$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
	}
	while ($old_data !== $data);

	// we are done...
	return $data;
}
/**
 * 在数据列表中搜索
 * @access public
 * @param array $list 数据列表
 * @param mixed $condition 查询条件
 * 支持 array('name'=>$value) 或者 name=$value
 * @return array
 */
function ls_search($list,$condition) {
	if(is_string($condition))
		parse_str($condition,$condition);
	// 返回的结果集合
	$resultSet = array();
	foreach ($list as $key=>$data){
		$find   =   false;
		foreach ($condition as $field=>$value){
			if(isset($data[$field])) {
				if(0 === strpos($value,'/')) {
					$find   =   preg_match($value,$data[$field]);
				}elseif($data[$field]==$value){
					$find = true;
				}
			}
		}
		if($find)
			$resultSet[]     =   &$list[$key];
	}
	return $resultSet;
}
function makeLink($string) {
	$validChars = "a-z0-9\/\-_+=.~!%@?#&;:$\|";
	$patterns = array(
					"/(^|[^]_a-z0-9-=\"'\/])([a-z]+?):\/\/([{$validChars}]+)/ei",
					"/(^|[^]_a-z0-9-=\"'\/])www\.([a-z0-9\-]+)\.([{$validChars}]+)/ei",
					"/(^|[^]_a-z0-9-=\"'\/])ftp\.([a-z0-9\-]+)\.([{$validChars}]+)/ei",
					"/(^|[^]_a-z0-9-=\"'\/:\.])([a-z0-9\-_\.]+?)@([{$validChars}]+)/ei");
	$replacements = array(
					"'\\1<a href=\"\\2://\\3\" title=\"\\2://\\3\" target=\"_blank\" rel=\"external\">\\2://'.cutstr( '\\3',50 ).'</a>'",
					"'\\1<a href=\"http://www.\\2.\\3\" title=\"www.\\2.\\3\" rel=\"external\">'.cutstr( 'www.\\2.\\3',50 ).'</a>'",
					"'\\1<a href=\"ftp://ftp.\\2.\\3\" title=\"ftp.\\2.\\3\" rel=\"external\">'.cutstr( 'ftp.\\2.\\3',50 ).'</a>'",
					"'\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>'");
	return preg_replace($patterns, $replacements, $string);
}


function remove_xss($val) {
   // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
   // this prevents some character re-spacing such as <java\0script>
   // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

   // straight replacements, the user should never need these since they're normal characters
   // this prevents like <IMG SRC=@avascript:alert('XSS')>
   $search = 'abcdefghijklmnopqrstuvwxyz';
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $search .= '1234567890!@#$%^&*()';
   $search .= '~`";:?+/={}[]-_|\'\\';
   for ($i = 0; $i < strlen($search); $i++) {
      // ;? matches the ;, which is optional
      // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

      // @ @ search for the hex values
      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
      // @ @ 0{0,7} matches '0' zero to seven times
      $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
   }

   // now the only remaining whitespace attacks are \t, \n, and \r
   $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
   $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
   $ra = array_merge($ra1, $ra2);

   $found = true; // keep replacing as long as the previous round replaced something
   while ($found == true) {
      $val_before = $val;
      for ($i = 0; $i < sizeof($ra); $i++) {
         $pattern = '/';
         for ($j = 0; $j < strlen($ra[$i]); $j++) {
            if ($j > 0) {
               $pattern .= '(';
               $pattern .= '(&#[xX]0{0,8}([9ab]);)';
               $pattern .= '|';
               $pattern .= '|(&#0{0,8}([9|10|13]);)';
               $pattern .= ')*';
            }
            $pattern .= $ra[$i][$j];
         }
         $pattern .= '/i';
         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
         if ($val_before == $val) {
            // no replacements were made, so exit the loop
            $found = false;
         }
      }
   }
   return $val;
}

/**
 * 把返回的数据集转换成Tree
 * @access public
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 */
function list_to_tree($list, $pk='id',$pid = 'pid',$child = '_child',$root=0) {
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * 对查询结果集进行排序
 * @access public
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
function list_sort_by($list,$field, $sortby='asc') {
   if(is_array($list)){
       $refer = $resultSet = array();
       foreach ($list as $i => $data)
           $refer[$i] = &$data[$field];
       switch ($sortby) {
           case 'asc': // 正向排序
                asort($refer);
                break;
           case 'desc':// 逆向排序
                arsort($refer);
                break;
           case 'nat': // 自然排序
                natcasesort($refer);
                break;
       }
       foreach ( $refer as $key=> $val)
           $resultSet[] = &$list[$key];
       return $resultSet;
   }
   return false;
}

/**
 * 在数据列表中搜索
 * @access public
 * @param array $list 数据列表
 * @param mixed $condition 查询条件
 * 支持 array('name'=>$value) 或者 name=$value
 * @return array
 */
function list_search($list,$condition) {
    if(is_string($condition))
        parse_str($condition,$condition);
    // 返回的结果集合
    $resultSet = array();
    foreach ($list as $key=>$data){
        $find   =   false;
        foreach ($condition as $field=>$value){
            if(isset($data[$field])) {
                if(0 === strpos($value,'/')) {
                    $find   =   preg_match($value,$data[$field]);
                }elseif($data[$field]==$value){
                    $find = true;
                }
            }
        }
        if($find)
            $resultSet[]     =   &$list[$key];
    }
    return $resultSet;
}

// 自动转换字符集 支持数组转换
function auto_charset($fContents, $from='gbk', $to='utf-8') {
    $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
    $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
    if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
        //如果编码相同或者非字符串标量则不转换
        return $fContents;
    }
    if (is_string($fContents)) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($fContents, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $fContents);
        } else {
            return $fContents;
        }
    } elseif (is_array($fContents)) {
        foreach ($fContents as $key => $val) {
            $_key = auto_charset($key, $from, $to);
            $fContents[$_key] = auto_charset($val, $from, $to);
            if ($key != $_key)
                unset($fContents[$key]);
        }
        return $fContents;
    }
    else {
        return $fContents;
    }
}
/**
 * 代码加亮
 * @param String  $str 要高亮显示的字符串 或者 文件名
 * @param Boolean $show 是否输出
 * @return String
 */
function highlight_code($str,$show=false) {
    if(file_exists($str)) {
        $str    =   file_get_contents($str);
    }
    $str  =  stripslashes(trim($str));
    // The highlight string function encodes and highlights
    // brackets so we need them to start raw
    $str = str_replace(array('&lt;', '&gt;'), array('<', '>'), $str);

    // Replace any existing PHP tags to temporary markers so they don't accidentally
    // break the string out of PHP, and thus, thwart the highlighting.

    $str = str_replace(array('&lt;?php', '?&gt;',  '\\'), array('phptagopen', 'phptagclose', 'backslashtmp'), $str);

    // The highlight_string function requires that the text be surrounded
    // by PHP tags.  Since we don't know if A) the submitted text has PHP tags,
    // or B) whether the PHP tags enclose the entire string, we will add our
    // own PHP tags around the string along with some markers to make replacement easier later

    $str = '<?php //tempstart'."\n".$str.'//tempend ?>'; // <?

    // All the magic happens here, baby!
    $str = highlight_string($str, TRUE);

    // Prior to PHP 5, the highlight function used icky font tags
    // so we'll replace them with span tags.
    if (abs(phpversion()) < 5) {
        $str = str_replace(array('<font ', '</font>'), array('<span ', '</span>'), $str);
        $str = preg_replace('#color="(.*?)"#', 'style="color: \\1"', $str);
    }

    // Remove our artificially added PHP
    $str = preg_replace("#\<code\>.+?//tempstart\<br />\</span\>#is", "<code>\n", $str);
    $str = preg_replace("#\<code\>.+?//tempstart\<br />#is", "<code>\n", $str);
    $str = preg_replace("#//tempend.+#is", "</span>\n</code>", $str);

    // Replace our markers back to PHP tags.
    $str = str_replace(array('phptagopen', 'phptagclose', 'backslashtmp'), array('&lt;?php', '?&gt;', '\\'), $str); //<?
    $line   =   explode("<br />", rtrim(ltrim($str,'<code>'),'</code>'));
    $result =   '<div class="code"><ol>';
    foreach($line as $key=>$val) {
        $result .=  '<li>'.$val.'</li>';
    }
    $result .=  '</ol></div>';
    $result = str_replace("\n", "", $result);
    if( $show!== false) {
        echo($result);
    }else {
        return $result;
    }
}

//输出安全的html
function h($text, $tags = null) {
  $text = trim($text);
  //完全过滤注释
  $text = preg_replace('/<!--?.*-->/','',$text);
  //完全过滤动态代码
  $text = preg_replace('/<\?|\?'.'>/','',$text);
  //完全过滤js
  $text = preg_replace('/<script?.*\/script>/','',$text);

  $text = str_replace('[','&#091;',$text);
  $text = str_replace(']','&#093;',$text);
  $text = str_replace('|','&#124;',$text);
  //过滤换行符
  $text = preg_replace('/\r?\n/','',$text);
  //br
  $text = preg_replace('/<br(\s\/)?'.'>/i','[br]',$text);
  $text = preg_replace('/(\[br\]\s*){10,}/i','[br]',$text);
  $text = preg_replace('/<p(\s\/)?'.'>/i','[br]',$text);
  //过滤危险的属性，如：过滤on事件lang js
  while(preg_match('/(<[^><]+)( lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i',$text,$mat)){
    $text=str_replace($mat[0],$mat[1],$text);
  }
  while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
    $text=str_replace($mat[0],$mat[1].$mat[3],$text);
  }
  if(empty($tags)) {
    $tags = 'table|td|th|tr|i|b|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt|a';
  }
  //允许的HTML标签
  $text = preg_replace('/<('.$tags.')( [^><\[\]]*)?>/i','[\1\2]',$text);
  $text = preg_replace('/<\/('.$tags.')>/Ui','[/\1]',$text);
  //过滤多余html
  $text = preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml)[^><]*>/i','',$text);
  //过滤合法的html标签
  while(preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i',$text,$mat)){
    $text=str_replace($mat[0],str_replace('>',']',str_replace('<','[',$mat[0])),$text);
  }
  //转换引号
  while(preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i',$text,$mat)){
    $text=str_replace($mat[0],$mat[1].'|'.$mat[3].'|'.$mat[4],$text);
  }
  //过滤错误的单个引号
  while(preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i',$text,$mat)){
    $text=str_replace($mat[0],str_replace($mat[1],'',$mat[0]),$text);
  }
  //转换其它所有不合法的 < >
  $text = str_replace('<','&lt;',$text);
  $text = str_replace('>','&gt;',$text);
  $text = str_replace('"','&quot;',$text);
   //反转换
  $text = str_replace('[','<',$text);
  $text = str_replace(']','>',$text);
  $text = str_replace('|','"',$text);
  //过滤多余空格
  $text = str_replace('  ',' ',$text);
  return $text;
}

function ubb($Text) {
  $Text=trim($Text);
  //$Text=htmlspecialchars($Text);
  $Text=preg_replace("/\\t/is","  ",$Text);
  $Text=preg_replace("/\[h1\](.+?)\[\/h1\]/is","<h1>\\1</h1>",$Text);
  $Text=preg_replace("/\[h2\](.+?)\[\/h2\]/is","<h2>\\1</h2>",$Text);
  $Text=preg_replace("/\[h3\](.+?)\[\/h3\]/is","<h3>\\1</h3>",$Text);
  $Text=preg_replace("/\[h4\](.+?)\[\/h4\]/is","<h4>\\1</h4>",$Text);
  $Text=preg_replace("/\[h5\](.+?)\[\/h5\]/is","<h5>\\1</h5>",$Text);
  $Text=preg_replace("/\[h6\](.+?)\[\/h6\]/is","<h6>\\1</h6>",$Text);
  $Text=preg_replace("/\[separator\]/is","",$Text);
  $Text=preg_replace("/\[center\](.+?)\[\/center\]/is","<center>\\1</center>",$Text);
  $Text=preg_replace("/\[url=http:\/\/([^\[]*)\](.+?)\[\/url\]/is","<a href=\"http://\\1\" target=_blank>\\2</a>",$Text);
  $Text=preg_replace("/\[url=([^\[]*)\](.+?)\[\/url\]/is","<a href=\"http://\\1\" target=_blank>\\2</a>",$Text);
  $Text=preg_replace("/\[url\]http:\/\/([^\[]*)\[\/url\]/is","<a href=\"http://\\1\" target=_blank>\\1</a>",$Text);
  $Text=preg_replace("/\[url\]([^\[]*)\[\/url\]/is","<a href=\"\\1\" target=_blank>\\1</a>",$Text);
  $Text=preg_replace("/\[img\](.+?)\[\/img\]/is","<img src=\\1>",$Text);
  $Text=preg_replace("/\[color=(.+?)\](.+?)\[\/color\]/is","<font color=\\1>\\2</font>",$Text);
  $Text=preg_replace("/\[size=(.+?)\](.+?)\[\/size\]/is","<font size=\\1>\\2</font>",$Text);
  $Text=preg_replace("/\[sup\](.+?)\[\/sup\]/is","<sup>\\1</sup>",$Text);
  $Text=preg_replace("/\[sub\](.+?)\[\/sub\]/is","<sub>\\1</sub>",$Text);
  $Text=preg_replace("/\[pre\](.+?)\[\/pre\]/is","<pre>\\1</pre>",$Text);
  $Text=preg_replace("/\[email\](.+?)\[\/email\]/is","<a href='mailto:\\1'>\\1</a>",$Text);
  $Text=preg_replace("/\[colorTxt\](.+?)\[\/colorTxt\]/eis","color_txt('\\1')",$Text);
  $Text=preg_replace("/\[emot\](.+?)\[\/emot\]/eis","emot('\\1')",$Text);
  $Text=preg_replace("/\[i\](.+?)\[\/i\]/is","<i>\\1</i>",$Text);
  $Text=preg_replace("/\[u\](.+?)\[\/u\]/is","<u>\\1</u>",$Text);
  $Text=preg_replace("/\[b\](.+?)\[\/b\]/is","<b>\\1</b>",$Text);
  $Text=preg_replace("/\[quote\](.+?)\[\/quote\]/is"," <div class='quote'><h5>引用:</h5><blockquote>\\1</blockquote></div>", $Text);
  $Text=preg_replace("/\[code\](.+?)\[\/code\]/eis","highlight_code('\\1')", $Text);
  $Text=preg_replace("/\[php\](.+?)\[\/php\]/eis","highlight_code('\\1')", $Text);
  $Text=preg_replace("/\[sig\](.+?)\[\/sig\]/is","<div class='sign'>\\1</div>", $Text);
  $Text=preg_replace("/\\n/is","<br/>",$Text);
  return $Text;
}

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
        if(false === $slice) {
            $slice = '';
        }
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice.'...' : $slice;
}

/**
 * 产生随机字串，可用来自动生成密码 默认长度6位 字母和数字混合
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
 * @return string
 */
function rand_string($len=6,$type='',$addChars='') {
    $str ='';
    switch($type) {
        case 0:
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
            break;
        case 1:
            $chars= str_repeat('0123456789',3);
            break;
        case 2:
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
            break;
        case 3:
            $chars='abcdefghijklmnopqrstuvwxyz'.$addChars;
            break;
        case 4:
            $chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借".$addChars;
            break;
        default :
            // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
            $chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
            break;
    }
    if($len>10 ) {//位数过长重复字符串一定次数
        $chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
    }
    if($type!=4) {
        $chars   =   str_shuffle($chars);
        $str     =   substr($chars,0,$len);
    }else{
        // 中文随机字
        for($i=0;$i<$len;$i++){
          $str.= msubstr($chars, floor(mt_rand(0,mb_strlen($chars,'utf-8')-1)),1);
        }
    }
    return $str;
}

/**
 * 获取登录验证码 默认为4位数字
 * @param string $fmode 文件名
 * @return string
 */
function build_verify($length=4,$mode=1) {
    return rand_string($length,$mode);
}

/**
 * 字节格式化 把字节数格式为 B K M G T 描述的大小
 * @return string
 */
function byte_format($size, $dec=2) {
	$a = array("B", "KB", "MB", "GB", "TB", "PB");
	$pos = 0;
	while ($size >= 1024) {
		 $size /= 1024;
		   $pos++;
	}
	return round($size,$dec)." ".$a[$pos];
}

/**
 * 检查字符串是否是UTF8编码
 * @param string $string 字符串
 * @return Boolean
 */
// function is_utf8($string) {
//     return preg_match('%^(?:
//          [\x09\x0A\x0D\x20-\x7E]            # ASCII
//        | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
//        |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
//        | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
//        |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
//        |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
//        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
//        |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
//     )*$%xs', $string);
// }

// 随机生成一组字符串
function build_count_rand ($number,$length=4,$mode=1) {
    if($mode==1 && $length<strlen($number) ) {
        //不足以生成一定数量的不重复数字
        return false;
    }
    $rand   =  array();
    for($i=0; $i<$number; $i++) {
        $rand[] =   rand_string($length,$mode);
    }
    $unqiue = array_unique($rand);
    if(count($unqiue)==count($rand)) {
        return $rand;
    }
    $count   = count($rand)-count($unqiue);
    for($i=0; $i<$count*3; $i++) {
        $rand[] =   rand_string($length,$mode);
    }
    $rand = array_slice(array_unique ($rand),0,$number);
    return $rand;
}

/**
  +----------------------------------------------------------
 * 过滤前后空格以及转换html标签
  +----------------------------------------------------------
 * @return string
  +----------------------------------------------------------
 */
function safe_text($str) {
	if(is_string($str)){
    	return htmlspecialchars(trim($str));
	}else{
		return $str;
    }
}

function html_encode($str){
    return htmlspecialchars($str);
}

function html_decode($str){
    return htmlspecialchars_decode($str);
}


/**
 * 字符串转换为数组，主要用于把分隔符调整到第二个参数
 * @param  string $str  要分割的字符串
 * @param  string $glue 分割符
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function str2arr($str, $glue = ','){
  return explode($glue, $str);
}

/**
 * 数组转换为字符串，主要用于把分隔符调整到第二个参数
 * @param  array  $arr  要连接的数组
 * @param  string $glue 分割符
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function arr2str($arr, $glue = ','){
  return implode($glue, $arr);
}
function str_format($string, $array)
{
  if(is_array($array) && !empty($array)){
    $keys    = array_keys($array);
    $keysmap = array_flip($keys);
    $values  = array_values($array);

    while (preg_match('/%\(([a-zA-Z0-9_ -]+)\)/', $string, $m))
    {
      if (!isset($keysmap[$m[1]]))
      {
        echo "No key $m[1]\n";
        return false;
      }

      $string = str_replace($m[0], '%' . ($keysmap[$m[1]] + 1) . '$', $string);
    }
    array_unshift($values, $string);
    return call_user_func_array('sprintf', $values);
  }else if(is_string($array) || is_int($array)){
    return sprintf($string, $array);
  }else{
    return $string;
  }
}

if(!function_exists('array_column')){
  function array_column(array $input, $columnKey, $indexKey = null) {
    $result = array();
    if (null === $indexKey) {
      if (null === $columnKey) {
        $result = array_values($input);
      } else {
        foreach ($input as $row) {
          $result[] = $row[$columnKey];
        }
      }
    } else {
      if (null === $columnKey) {
        foreach ($input as $row) {
          $result[$row[$indexKey]] = $row;
        }
      } else {
        foreach ($input as $row) {
          $result[$row[$indexKey]] = $row[$columnKey];
        }
      }
    }
    return $result;
  }
}
if(!function_exists('array_sort')){
	function array_sort($array,$sort_key,$sort=SORT_ASC){
		if(is_array($array)){
			foreach ($array as $row_array){
				if(is_array($row_array)){
					$key_array[] = $row_array[$sort_key];
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
		array_multisort($key_array,$sort,$array);
		return $array;
	}
}
function is_include_chinese($str){
    if(preg_match("#[\x7f-\xff]#", $str)) return true;
    return false;
}


//getmonth获得某日期前后$month月数月头、月尾值
//$date是某日期，$month是月数，$start_end是月头、月尾
function getmonth($date,$month,$start_end) {
	if( $date == date('Y-m-d',strtotime($date)) && is_numeric($month)){
        if($month>0){
            $startDay = date('Y-m-01',strtotime("$date + ".$month." month"));
        }elseif($month<0){
            $startDay = date('Y-m-01',strtotime("$date - ".abs($month)." month"));
        }else{
            $startDay = date('Y-m-01',strtotime("$date"));
        }
        $retarr['start'] = $startDay;
        $retarr['end'] = date('Y-m-d',strtotime("$startDay + 1 month -1 day"));
        if($start_end == 1 ){
            $dates = $startDay;
        }elseif($start_end == 2 ){
            $dates = $retarr['end'];
        }else{
            $dates = $retarr;
        }
    }
    return $dates;
}

function verify_idcard($idcard=''){
	if(strlen($idcard) == 15){
		// 如果身份证顺序码是996 997 998 999,这些是为百岁以上老人的特殊编码
		if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) != false) {
		    $idcard = substr($idcard, 0, 6) . '18' . substr($idcard, 6, 9);
		} else {
		    $idcard = substr($idcard, 0, 6) . '19' . substr($idcard, 6, 9);
		}
		$idcard = $idcard . _idcard_verify_number($idcard);
	}
    if (strlen($idcard) != 18) {
        return false;
    }
    $aCity = array(11 => "北京", 12 => "天津", 13 => "河北", 14 => "山西", 15 => "内蒙古", 21 => "辽宁", 22 => "吉林", 23 => "黑龙江", 31 => "上海", 32 => "江苏", 33 => "浙江", 34 => "安徽", 35 => "福建", 36 => "江西", 37 => "山东", 41 => "河南", 42 => "湖北", 43 => "湖南", 44 => "广东", 45 => "广西", 46 => "海南", 50 => "重庆", 51 => "四川", 52 => "贵州", 53 => "云南", 54 => "西藏", 61 => "陕西", 62 => "甘肃", 63 => "青海", 64 => "宁夏", 65 => "新疆", 71 => "台湾", 81 => "香港", 82 => "澳门", 91 => "国外");
    
    // 非法地区
    if (!array_key_exists(substr($idcard, 0, 2), $aCity)) {
        return false;
    }
    
    // 验证生日
    if (!checkdate(substr($idcard, 10, 2), substr($idcard, 12, 2), substr($idcard, 6, 4))) {
        return false;
    }
    
    // 校验码比对
    $idcard_base = substr($idcard, 0, 17);
    if (_idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))) {
        return false;
    } else {
        return true;
    }
}

function _idcard_verify_number($idcard_base) {
    // 加权因子
    $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
    
    // 校验码对应值
    $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
    $checksum = 0;
    for ($i = 0; $i < strlen($idcard_base); $i++) {
        $checksum+= substr($idcard_base, $i, 1) * $factor[$i];
    }
    $mod = strtoupper($checksum % 11);
    $verify_number = $verify_number_list[$mod];
    
    return $verify_number;
}
/*
 ------------------------------------------------------
参数：
$str_cut    需要截断的字符串
$length     允许字符串显示的最大长度
$symbol      自定义的省略符号
$encoding    编码
程序功能：截取全角和半角（汉字和英文）混合的字符串以避免乱码
------------------------------------------------------
*/
function substr_cut($str_cut,$length,$symbol="...",$encoding="utf-8")
{
	if (mb_strlen($str_cut) > $length)
	{
		for($i=0; $i < $length; $i++)
		if (ord($str_cut[$i]) > 128)    $i++;
		$str_cut = mb_substr($str_cut,0,$i,$encoding).$symbol;
	}
	return $str_cut;
}
function api_return($status=0, $info="", $data=null){
	if (is_array($status)) {
		return array(
			'status' => $status['status'],
			'info' => $status['info'],
			'data' => is_int($status['data']) ? intval($status['data']) : (is_float($status['data']) ? floatval($status['data']) : $status['data']),
		);
	}else{
		return array(
			'status' => $status,
			'info' => $info,
			'data' => is_int($data) ? intval($data) : (is_float($data) ? floatval($data) : $data),
		);
	}
}

function password_encrpty($password){
	if (!empty($password)){
		$key = "QE~I+uU]W9a)CV@P";
		$m1 = md5($password.$key);
		$m2 = md5($m1);
	}
	
	return $m2;	
}
function gen_password($password='',$key=''){
	$hash_password = md5($password);
	if(empty($key))
		$key = substr($hash_password, 0, 6);
	return md5($hash_password . $key);
	// return md5(md5(trim($password)).$key);
}

function export_csv($name='', $data){
	
	header('Content-Type: application/vnd.ms-excel;');  
	header('Content-Disposition: attachment;filename="' . $name . '.csv"');
	header('Cache-Control: max-age=0');
    
	ini_set('max_execution_time',0);
    set_time_limit(0);

	$fp = fopen('php://output', 'a');

	foreach ($data as $key => $sheet) {
		foreach ($sheet as $k => $v) {
			$row = array();
			foreach ($v as $n => $c) {
				$row[$n] = iconv('utf-8', 'gbk', $c);
			}
			fputcsv($fp, $row);  
		}  
	}

	fclose($fp);
}
