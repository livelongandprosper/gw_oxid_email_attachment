<?php
namespace gw\gw_oxid_email_attachment\Core;

class Email extends Email_parent {

	/**
	 * sendOrderEmailToUser function.
	 *
	 * @access public
	 * @param mixed $order
	 * @param mixed $subject (default: null)
	 * @return void
	 */
    public function sendOrderEmailToUser($order, $subject = null) {
		// original Funktionszeilen...
		// die verwendeung von parent::sendOrderEmailToUser(...)
		// funktioniert nicht, wenn Dateien angehangen werden sollen, da durch _setMailParams() in der stammfunktion
		// auch die funktion _clearMailer() und somit die Funktion clearAttachments() aufgerufen wird, die alle zuvor angefügten
		// Anhänge entfernt
		$myConfig = $this->getConfig();

		// add user defined stuff if there is any
		$order = $this->_addUserInfoOrderEMail($order);

		$shop = $this->_getShop();
		$this->_setMailParams($shop);

		$user = $order->getOrderUser();
		$this->setUser($user);

		// create messages
		$smarty = $this->_getSmarty();
		$this->setViewData("order", $order);

		if ($myConfig->getConfigParam("bl_perfLoadReviews")) {
			$this->setViewData("blShowReviewLink", true);
		}

		// Process view data array through oxOutput processor
		$this->_processViewArray();

		$this->setBody($smarty->fetch($this->_sOrderUserTemplate));
		$this->setAltBody($smarty->fetch($this->_sOrderUserPlainTemplate));

		// #586A
		if ($subject === null) {
			if ($smarty->template_exists($this->_sOrderUserSubjectTemplate)) {
				$subject = $smarty->fetch($this->_sOrderUserSubjectTemplate);
			} else {
				$subject = $shop->oxshops__oxordersubject->getRawValue() . " (#" . $order->oxorder__oxordernr->value . ")";
			}
		}

		$this->setSubject($subject);

		$fullName = $user->oxuser__oxfname->getRawValue() . " " . $user->oxuser__oxlname->getRawValue();

		$this->setRecipient($user->oxuser__oxusername->value, $fullName);
		$this->setReplyTo($shop->oxshops__oxorderemail->value, $shop->oxshops__oxname->getRawValue());

		////////////////////////////////////////////
		////////////////////////////////////////////
		// mail attachments start

		// language
		$language = \OxidEsales\Eshop\Core\Registry::getLang();

		// attachments to send
		$attachments_to_send = false;

		// get attachement directory
		$attachment_path = $myConfig->getOutDir()."attachments/".$language->getLanguageAbbr(  (int) ( isset( $order->oxorder__oxlang->value ) ? $order->oxorder__oxlang->value : 0 )  )."/";

		// assign email attachement file list
		if( is_dir($attachment_path) && $handle_file = opendir( $attachment_path ) ){
			while( false !== ( $file = readdir($handle_file) ) ){
				if(  is_file( $attachment_path.$file )  ) {
					// attach file to email
					$this->addAttachment( $attachment_path.$file , $file );
					$attachments_to_send = true;
				}
			}
			closedir($handle_file);
		}

		// use parent function if no file has to be attached to email
		if(!$attachments_to_send) {
			return parent::sendOrderEmailToUser($order, $subject);
		}

		// mail attachments end
		////////////////////////////////////////////
		////////////////////////////////////////////

		return $this->send();
	}
}
?>
