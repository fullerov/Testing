<?
    header('Content-type: image/jpeg');
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Expires: '.date('r'));
	session_start();
	
	$str=substr(rand(),0,6);
	$_SESSION['captcha']=crypt($str,'x)p_q1');
 	$img=imagecreatefromjpeg('../../css/captcha/noise.jpg');
	imageantialias($img,true);
	
	$x=rand(1,9);
	$arr=str_split($str);
	for($i=0;$i<count($arr);$i++)
	{
		$x+=14;
		$y=rand(20,23);
		$angle=rand(0,11);
		$size=rand(22,25);
		$r=rand(0,255);
		$g=rand(0,255);
		$b=rand(0,255);
		$color=imagecolorallocate($img,$r,$g,$b);
		imagettftext($img,$size,$angle,$x,$y+6,$color,"../../css/captcha/georgia.ttf",$arr[$i]);
		
	}

	imagejpeg($img);
	imagedestroy($img);
	

?>
