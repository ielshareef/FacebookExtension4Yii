Facebook Extension for the Yii Web Framework
============================================

Authenticate a Facebook user within a Yii Web Application and consequently use the Facebook API. This extension is based on [PHP-SDK](https://github.com/facebook/php-sdk).

Developed by [@ielshareef](http://twitter.com/ielshareef).

Installation
------------

Clone the repo onto your machine
Copy it to the protected/extensions folder in your Yii Web Application root
Rename the folder to "facebook"
Update the config file:

	'import'=>array(
		'application.models.*',
		'application.components.*',
		'ext.facebook.*',
		'ext.facebook.lib.*',
	)

Add an action to your Controller (default is SiteController.php under protected/controllers)

	// Facebook log in
	public function actionFacebooklogin() {
		Yii::import('ext.facebook.*');
	    $ui = new FacebookUserIdentity(YOUR APP ID, YOUR APP SECRET);
		if ($ui->authenticate()) {
	        $user=Yii::app()->user;
	        $user->login($ui);
	    	$this->redirect($user->returnUrl);
	 	} else {
	    	throw new CHttpException(401, $ui->error);
		}
	}
	
The default actionLogout() can handle all types of authentication logouts, so you don't need a new one

In your layout's main.php file, edit the menu to all the Facebook login:

	<?php $this->widget('zii.widgets.CMenu',array(
		'items'=>array(
			....
			array('label'=>'Sign in with Twitter', 'url'=>array('/site/twitterlogin'), 'visible'=>Yii::app()->user->isGuest),
			array('label'=>'Sign in with Facebook', 'url'=>array('/site/facebooklogin'), 'visible'=>Yii::app()->user->isGuest),
			array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
			array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
		),
	)); ?>
	
You are all good to go!

Authenticated User Data
-----------------------

Once the user is authenticated, his/her information will be stored in Yii::app()->session['facebook_user'].