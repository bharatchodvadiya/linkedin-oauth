<?php

class Social_Linkedin_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getConnectUrl()
	{
		$basepath=Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
		return $basepath.'index.php/linkedin/index';
	}
}
	 