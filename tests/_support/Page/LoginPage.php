<?php

namespace Page;

/**
 * Class LoginPage
 *
 * @package Page
 */
class LoginPage
{
    /**
     * @var array
     */
	public static $usernameField = ['xpath' => "//input[@name='username']"];

    /**
     * @var array
     */
	public static $passField = ['xpath' => "//input[@name='password']"];

    /**
     * @var array
     */
	public static $btnLogin = ['id' => 'user-login'];

    /**
     * @var array
     */
	public static $logoXpath = ['xpath' => ' //img[@id=\'logo\']'];

    /**
     * @var array
     */
	public static $warningUserNameOrPassXpath = ['xpath' => '//div[@class=\'label label-warning\']'];

    /**
     * @var array
     */
	public static $warningUsername = ['xpath' => '//div[contains(concat(\' \', @class, \' \'), \'alert-warning \')]'];

    /**
     * @var string
     */
	public static $warningUserNameOrPassXpathMessage = 'Invalid Username or password';

    /**
     * @var string
     */
	public static $warningUsernameMessage = 'Invalid username';

    /**
     * @var string
     */
	public static $warningInvalidPassMessage = 'Invalid password';

    /**
     * @var array
     */
	public static $logoId = ['id' => 'logo'];

	/**
	 * @var array
	 */
	public static $faceIcon = ['xpath' => '//a[@class=\'social-facebook\']'];

	/**
	 * @var string
	 */
	public static $faceURL = 'https://www.facebook.com/globalvision360/';

	/**
	 * @var array
	 */
	public static $tweeticon = ['xpath' => '//a[@class=\'social-twitter\']'];

	/**
	 * @var string
	 */
	public static $twitterURL = 'https://twitter.com/GlobalVision360';

	/**
	 * @var array
	 */
	public static $googleIcon = ['xpath' => '//a[@class=\'social-googleplus\']'];

	/**
	 * @var string
	 */
	public static $googleURL = 'https://plus.google.com/+GlobalVisionSwitzerland';

	/**
	 * @var string
	 */
	public static $pageMain = 'http://globalvision.ch/en/home-page/';
}
