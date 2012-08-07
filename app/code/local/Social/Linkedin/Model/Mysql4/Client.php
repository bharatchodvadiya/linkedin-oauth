<?php
    class Social_Linkedin_Model_Mysql4_Client extends Mage_Core_Model_Mysql4_Abstract
    {
        protected function _construct()
        {
            $this->_init("linkedin/client", "tablename_id");
        }
    }
	 