<?php
namespace app\classes;
use app\models\Mails;
use app\modules\settings\models\Settings;


class MailTemplateSend{
	public static function sendMail($to, $one, $two, $zone, $sub_one='', $sub_two=''){		
		/* subject **********************/	
			
		$subject = str_replace($sub_one, $sub_two, Mails::find()->where(['zone'=>$zone])->one()['subject']);
		
		/* content **********************/
		

		$message = '<a href="https://dressyshop.ru/" style="display:block"><img src="https://dressyshop.ru/img/html_header.jpg" style="display: block;width: 100%;margin-bottom: 10px"></a>';
		$message .= str_replace($one,$two,Mails::find()->where(['zone'=>$zone])->one()['content']);
		$message .= '<a href="https://dressyshop.ru/latest" style="display:block"><img src="https://dressyshop.ru/img/incat.jpg" style="display: block;margin:0 auto"></a>';


		/* from *************************/
		if(Mails::find()->where(['zone'=>$zone])->one()['from'] !=''){
			$from_letter = Mails::find()->where(['zone'=>$zone])->one()['from'];
			}
		else{
			$from_letter = Settings::find()->select(['admin_email'])->one()->admin_email;
			}
		$from_letter = trim($from_letter);
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= "From: Dressyshop <$from_letter>\n";
			
		@mail($to, $subject, $message, $headers);
		
	}
}