<?php

namespace Helpers;

class Mailer{
	public function sendMail($to, $type, $subject, $language, $params){
    	$body = $this->renderMailToTemplate($type, $language, $params);
		$transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')->
  			setUsername(ConfigHelper::getValue('mail.username'))->
  			setPassword(ConfigHelper::getValue('mail.password'));
  		$mailer = \Swift_Mailer::newInstance($transport);
  		$message = \Swift_Message::newInstance($subject)->
  			setFrom(array(ConfigHelper::getValue('mail.username') . '@gmail.com'))->
  			setTo(array('mbrunas.p@gmail.com'))->
  			setBody($body, 'text/html');
  		$result = $mailer->send($message);
	}

	public function renderMail($type, $language, $params){
		$path = "Views/Mails/".$type;
		if(is_dir($path)){
			$html = renderToString($path. '/' . $language . '.phtml', $params);
		}
		else{
			$html = renderToString($path.'.phtml', $params);
		}
		return $html;
	}
	public function renderMailToTemplate($type, $language, $params){
		$html = $this->renderMail($type, $language, $params);
		return renderToString("Views/Mails/template." . $language . '.phtml', ['content' => $html]);
	}

	public static function sendEmailConfirmMail($user, $language){
		$mailer = new Mailer();
		$mailer->sendMail($user->getEmail(), "confirmEmail", "Ověřte si prosím váš email", $language, ['token' => $user->getEmailConfirmToken()]);
	}
}