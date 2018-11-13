<?php
namespace Lib\Util;
/**
 * 
 * @author 王业坤 QQ:417471191
 * 
 *
 */
class EmailUtil {
	/**
	 * 发送邮件
	 * 
	 * @param unknown $to
	 *        	发送地址
	 * @param unknown $content
	 *        	发送内容
	 * @return boolean 是否发送成功，成功返回true
	 */
	static function sendEmail($to,$subject, $content) {
		$mail = new PHPMailer ();
		$mail->IsSMTP (); // 启用SMTP
		$mail->Host = "smtp.126.com"; // SMTP服务器
		$mail->SMTPAuth = true; // 开启SMTP认证
		$mail->Port = '25';
		$mail->Username = "zhongpaiwang@126.com"; // SMTP用户名
		$mail->Password = "zhongpai2013"; // SMTP密码
		$mail->From = "zhongpaiwang@126.com"; // 发件人地址
		$mail->FromName = "众拍网"; // 发件人
		$mail->AddAddress ( $to ); // 添加收件人
		$mail->IsHTML ( true ); // 是否HTML格式邮件
		$mail->CharSet = "utf-8";
		$mail->Encoding = "base64"; // 编码方式
		$mail->Subject = $subject; // 邮件主题
		$content=$content."<br/>本邮件为系统邮件，请勿回复！";
		$mail->Body = $content;
		
		if(!$mail->Send())
		{
			throw new Exception("发送邮件失败 ！联系系统管理员QQ:417471191。\r\n出错信息：$mail->ErrorInfo");
		}else 
		{
			return true;
		}
		
	}
}

?>