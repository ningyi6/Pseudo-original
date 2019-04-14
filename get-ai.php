<?php
/*
*不念博客 
*https://www.bunian.cn/
*演示地址
*https://www.bunian.cn/ai/
*/
header('Access-Control-Allow-Origin:*');
header('Content-type:text/html;charset=utf-8');
function translate($text,$from,$to){
	$url = "http://translate.google.cn/translate_a/single?client=gtx&dt=t&ie=UTF-8&oe=UTF-8&sl=$from&tl=$to&q=". urlencode($text);
	set_time_limit(0);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS,20);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 40);
	curl_setopt($ch, CURLOPT_URL, $url);
	$result = curl_exec($ch);
	curl_close($ch);
        $result = json_decode($result);
	if(!empty($result)){
	foreach($result[0] as $k){
		$v[] = $k[0];
	}
	return implode(" ", $v);
	}
}
//去除无字数限制，为提高效率限字数为1000字
//增加判断如为空，返回相应的文字
if($_POST['info']){
	$str = preg_replace('/[\x80-\xff]{1,3}/', ' ', $_POST['info'], -1); 
	$num = strlen($str);
	if($num<1030){
		$zh_en=translate($_POST['info'],'zh-CN','EN');
		if($zh_en){
			$en_zh=translate($zh_en,'EN','zh-CN');
			if($en_zh){
				$info=$en_zh;
			}else{
			$info="超过IP限制，请稍后重试，或者添加qq群:200653131进行反馈";
			}
		}else{
		$info="超过IP限制，请稍后重试，或者添加qq群:200653131进行反馈";
		}
	}else{
	$info="您的字数超过1000文字，请删除一些文字在使用。";
	}
echo $info;
}
