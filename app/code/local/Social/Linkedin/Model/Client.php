<?php

class Social_Linkedin_Model_Client extends Social_Connectors_Model_Abstract implements Social_Connectors_Model_Interface
{
	public function setConnectorName()
	{
		$connector_name='Linkedin';
		return $connector_name;
	}
	public function showdata()
	{
		echo "hello from Linkedin";
	
	}
	public function logout()
	{
		
	}
	public function login()
	{
		function oauth_session_exists() {
			if((is_array($_SESSION)) && (array_key_exists('oauth', $_SESSION))) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
		
		try {
		
			require_once(Mage::getBaseDir('lib') . '/Connectors/Linkedin/linkedin_3.2.0.class.php');
			if(!session_start()) {
				throw new LinkedInException('This script requires session support, which appears to be disabled according to session_start().');
			}
						
			$API_CONFIG = array(
							    'appKey'       => parent::getApiKey('Linkedin'),
								  'appSecret'    => parent::getSecret('Linkedin'),
								  'callbackUrl'  => '' 
			); 
			
			//$_SESSION['m_identity']= $mid;
			
			define('DEMO_GROUP', '4010474');
			define('DEMO_GROUP_NAME', 'Simple LI Demo');
			define('PORT_HTTP', '80');
			define('PORT_HTTP_SSL', '443');
			
			if($_SERVER['HTTPS'] == 'on') {
				$protocol = 'https';
			} else {
				$protocol = 'http';
			}
			
			$API_CONFIG['callbackUrl'] = $protocol . '://' . $_SERVER['SERVER_NAME'] . ((($_SERVER['SERVER_PORT'] != PORT_HTTP) || ($_SERVER['SERVER_PORT'] != PORT_HTTP_SSL)) ? ':' . $_SERVER['SERVER_PORT'] : '') . $_SERVER['PHP_SELF'] . '?' . LINKEDIN::_GET_TYPE . '=initiate&' . LINKEDIN::_GET_RESPONSE . '=1';
			$OBJ_linkedin = new LinkedIn($API_CONFIG);
			
			$_GET[LINKEDIN::_GET_RESPONSE] = (isset($_GET[LINKEDIN::_GET_RESPONSE])) ? $_GET[LINKEDIN::_GET_RESPONSE] : '';
			if(!$_GET[LINKEDIN::_GET_RESPONSE])
			{
			
				$response = $OBJ_linkedin->retrieveTokenRequest();
				if($response['success'] === TRUE)
				{
			
					$_SESSION['oauth']['linkedin']['request'] = $response['linkedin'];
					$url=LINKEDIN::_URL_AUTH . $response['linkedin']['oauth_token'];
			
					$arr['url']=$url;
					$arr['user_data']='';
					
					return $arr;
				}
				else
				{
					echo "Request token retrieval failed:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response, TRUE) . "</pre><br /><br />LINKEDIN OBJ:<br /><br /><pre>" . print_r($OBJ_linkedin, TRUE) . "</pre>";
				}
			}
			else
			{
			
				$response = $OBJ_linkedin->retrieveTokenAccess($_SESSION['oauth']['linkedin']['request']['oauth_token'], $_SESSION['oauth']['linkedin']['request']['oauth_token_secret'], $_GET['oauth_verifier']);
				if($response['success'] === TRUE)
				{
				  
					$_SESSION['oauth']['linkedin']['access'] = $response['linkedin'];
					$_SESSION['oauth']['linkedin']['authorized'] = TRUE;
					
					if($_SESSION['oauth']['linkedin']['authorized'] === TRUE)
					{
						$OBJ_linkedin = new LinkedIn($API_CONFIG);
						$OBJ_linkedin->setTokenAccess($_SESSION['oauth']['linkedin']['access']);
						$OBJ_linkedin->setResponseFormat(LINKEDIN::_RESPONSE_XML);
							
							
						$response = $OBJ_linkedin->profile('~:(id,first-name,last-name,picture-url,location,positions,current-status,specialties,summary,educations,industry,headline,date-of-birth)');
						if($response['success'] === TRUE) {
								
							$response['linkedin'] = new SimpleXMLElement($response['linkedin']);
							$arr['url']='';
							$arr['user_data']=$response['linkedin'];
					
							return $arr;
					
						}
					
					}
				}
				else
				{
					echo "Access token retrieval failed:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response, TRUE) . "</pre><br /><br />LINKEDIN OBJ:<br /><br /><pre>" . print_r($OBJ_linkedin, TRUE) . "</pre>";
				}
			}	
		} catch(LinkedInException $e) {
			
				echo $e->getMessage();
		}	
	
	}
	public function oauthuser($data)
	{
		$identityid=Mage::getSingleton('core/session')->getIdentityId();
				
		$customer_data= $data;		
		//print_r($customer_data);
		
		$city = $customer_data->{'location'};
		$cityname = $city->{'name'};
		
		$usertoken = $_SESSION['oauth']['linkedin']['access'];
		$oauth_token = $usertoken['oauth_token'];
		$oauth_token_secret = $usertoken['oauth_token_secret'];
		
		$email = Mage::getSingleton('customer/session')->getCustomer()->getEmail();
	
		$username = $customer_data->{'first-name'}.' '. $customer_data->{'last-name'};
		$email = Mage::getSingleton('customer/session')->getCustomer()->getEmail();
		$profileimage = $customer_data->{'picture-url'};
		
		$getdata= Mage::getModel('linkedin/client')->checkConnected($customer_data->{'id'},'Linkedin',$identityid);
	//	print_r($getdata);
			
		if($getdata)
		{
			$user = $getdata['user_screen_name'];
			$identity = $getdata['identity_id'];
			$identity1 = Mage::getModel('linkedin/client')->getidentityname($identity);
						
			$turl= Mage::helper('linkedin/data')->getConnectUrl();
			//$basepath=Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
			?>
			
			<script language="javascript">
			window.opener.location = "<?php echo $turl.'?connecterror=true&user='.$user.'&identity='.$identity1['f_name']; ?>";
			window.close();
			</script>
			
			 
<?php	}
		else {
			
		Mage::getModel('linkedin/client')->setUser($email,$identityid,'Linkedin',$customer_data->{'id'},$username,$oauth_token,$oauth_token_secret,$profileimage,'',$cityname);
		$turl= Mage::helper('linkedin/data')->getConnectUrl();
?>
	    		<script language="javascript">
	    		window.opener.location = "<?php echo $turl.'?connect=true'; ?>";
	    		   	window.close();
	    		</script>
	    		<?php 
		     }	
	}
	
}
