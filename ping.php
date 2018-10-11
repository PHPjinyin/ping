<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require 'config.php';	
		
		//ping 地址是否通
		function pingAddress($address){
			$status = 0;
			//判断当前服务器
			if (strcasecmp(PHP_OS,'WINNT') === 0){
				//windows 服务器
				$pingresult = exec("ping -n 1 {$address}", $outcome, $status);

			}elseif(strcasecmp(PHP_OS,'linux') === 0){
				//linux 服务器
				$pingresult = exec ("ping -c 1 {$address}", $outcome, $status);
			}

			
			if($status ===0){
				$status = true;
			} else { 
				$status = false;
			}
			return $status;
		}


		$content='';
		foreach ($address as $val) {
			$res=pingAddress($val);
			if(!$res){
				$content.=','.$val;
			}
		}



		//有断网的网站 发送ip或域名到邮箱

		if( !empty( $content ) ){
		//断网的服务器总内容
			$content=substr($content,1);
		}
		send_Mail($jszdz ,$config,$content);
	
		function send_Mail( $jszdz ,$config,$content){
			require_once("PHPMailer/PHPMailer.php");
			require_once("PHPMailer/SMTP.php");

			// 实例化PHPMailer核心类
			$mail = new PHPMailer();
			// 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
			$mail->SMTPDebug = 1;
			// 使用smtp鉴权方式发送邮件
			$mail->isSMTP();
			// smtp需要鉴权 这个必须是true
			$mail->SMTPAuth = true;
			// 链接qq域名邮箱的服务器地址
			$mail->Host = 'smtp.qq.com';
			// 设置使用ssl加密方式登录鉴权
			$mail->SMTPSecure = 'ssl';
			// 设置ssl连接smtp服务器的远程服务器端口号
			$mail->Port = 465;
			// 设置发送的邮件的编码
			$mail->CharSet = 'UTF-8';
			// 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
			$mail->FromName = '系统';
			// smtp登录的账号 QQ邮箱即可
			$mail->Username = $config['Username'];
			// smtp登录的密码 使用生成的授权码
			$mail->Password = $config['Password'];
			// 设置发件人邮箱地址 同登录账号
			$mail->From = $config['Username'];
			// 邮件正文是否为html编码 注意此处是一个方法
			$mail->isHTML(true);
			// 设置收件人邮箱地址
			//邮件接收者地址
			foreach ($jszdz as $val) {
				$mail->addAddress($val);
			}

			// 添加该邮件的主题
			$mail->Subject = '有服务器断网';
			// 添加邮件正文
			$mail->Body = $content;
			// 为该邮件添加附件
			//$mail->addAttachment('./example.pdf');
			// 发送邮件 返回状态
			$status = $mail->send();
		}
		





 ?>