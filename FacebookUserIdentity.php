<?php
/*
 * This product includes software developed by
 * Ismail Elshareef (http://about.me/ismailelshareef)
 * under Apache 2.0 License (http://www.apache.org/licenses/LICENSE-2.0.html).
 *
 */
class FacebookUserIdentity extends FacebookComponent implements IUserIdentity {

    /**
     * @var string OAuth consumer key.
     */
    public $key = '';
    /**
     * @var string OAuth consumer secret.
     */
    public $secret = '';
	/**
     * @var bool Is the user authenticated?
     */
    private $_authenticated=false;

    public function __construct($key, $secret) {
		if (isset($key) && isset($secret)) {
			$this->key = $key;
			$this->secret = $secret;
		} else {
			throw new FacebookException("Tokens are incorrect or missing.");
		}
    }

    public function getIsAuthenticated() {
        return $this->_authenticated;
    }

    public function getId() {
        return Yii::app()->session['facebook_user']['id'];
    }

    public function getName() {
        return Yii::app()->session['facebook_user']['name'];
    }

    public function getPersistentStates() {
    }

    public function authenticate() {
		$session = Yii::app()->session;
		$facebook = new Facebook(array(
		  'appId'  => $this->key,
		  'secret' => $this->secret,
		));
		$here = Yii::app()->request->hostInfo . Yii::app()->request->url;
		// Get User ID
		$user = $facebook->getUser();
		
		// already logged in
		if (isset($user)) {
			try {
			    // Proceed knowing you have a logged in user who's authenticated.
			    $user_profile = $facebook->api('/me');
				$session['facebook_user'] = $user_profile;
				$this->_authenticated = true;
			} catch (FacebookApiException $e) {
				$user = null;
			  	$params = array("scope" => "user_birthday,email,read_stream");	
				$loginUrl = $facebook->getLoginUrl($params);
				Yii::app()->request->redirect($loginUrl);
			}
		// Log in!
		} else {
			$params = array("scope" => "user_birthday,email,read_stream");	
			$loginUrl = $facebook->getLoginUrl($params);
			Yii::app()->request->redirect($loginUrl);
		}
		return $this->_authenticated;
	}
}
?>
