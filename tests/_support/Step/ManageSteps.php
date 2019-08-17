<?php

namespace Step;

use Page\LoginPage as LoginPage;
use Page\ManagePage as ManagePage;

class ManageSteps extends \AcceptanceTester
{
	/**
	 * @param   string  $username
	 * @param   string  $pass
	 */
	public function login($username, $pass)
	{
		$I = $this;
		$I->amOnPage('');
		$I->comment('Fill Username Text Field');
		$I->fillField(LoginPage::$usernameField, $username);
		$I->comment('Fill Password Text Field');
		$I->fillField(LoginPage::$passField, $pass);
		$I->comment('I click Login button');
		$I->click(LoginPage::$btnLogin);
		$I->waitForElement(ManagePage::$btnLogout, 30);
		$I->comment('I see Administrator Control Panel');
	}
}