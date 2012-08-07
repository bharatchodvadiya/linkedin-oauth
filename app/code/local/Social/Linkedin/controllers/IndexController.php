<?php
class Social_Linkedin_IndexController extends Mage_Core_Controller_Front_Action{
	public function IndexAction() {
	  if(isset($_REQUEST['connect']))
      {
      	$this->_redirect('customer/social');
      }
      elseif(isset($_REQUEST['connecterror']))
      {      
        $message='Your connection is already being used by &lt;'.$_REQUEST['user'].'&gt; [with identity &lt;'.$_REQUEST['identity'].'&gt;]';
      	
      	Mage::getSingleton('core/session')->addError($message);
      	$this->_redirect('customer/social');
      	return;
      }
      else{
     
			if(isset($_REQUEST['data']))
			{  
				$identityid = $_REQUEST['mid'];
				$email = Mage::getSingleton('customer/session')->getCustomer()->getEmail();
				Mage::getModel('linkedin/client')->setDisconnectConnector($email,'Linkedin',$identityid);
			//	Mage::getModel('linkedin/client')->logout();
				$this->_redirect('customer/social');
				
			}
			else
			{   
				if(isset($_REQUEST['mid']))
				{
				  $mid = $_REQUEST['mid'];
				  Mage::getSingleton('core/session')->setIdentityId($mid);
				}
				$url=Mage::getModel('linkedin/client')->login();
				
				if($url['url']!='')
			        $this->_redirectUrl($url['url']);
			    else
			    {  
			    	
			      	Mage::getModel('linkedin/client')->oauthuser($url['user_data']);
			    }
			    
	         }
      }
	}
}
?>
