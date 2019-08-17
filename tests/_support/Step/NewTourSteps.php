<?php

namespace Step;

use Page\NewTourPage as NewTourPage;
use Page\ManagePage as ManagePage;

/**
 * Class NewTourSteps
 *
 * @package Step
 */
class NewTourSteps extends ManageSteps
{
	public function create($name, $url, $title, $description)
	{
		$I = $this;
		$I->click(ManagePage::$btnAddNew);
		$I->waitForElement(NewTourPage::$fieldName, 30);
		$I->comment('Fill Name Text Field');
		$I->waitForElement(NewTourPage::$fieldName, 30);
		$I->fillField(NewTourPage::$fieldName, $name);
		$I->waitForElement(NewTourPage::$fieldFriendlyURL, 30);
		$I->comment('Fill URL Text Field');
		$I->fillField(NewTourPage::$fieldFriendlyURL, $url);

		$usePage = new NewTourPage();
		$I->waitForElement($usePage->nameField(2), 30);
		$I->waitForElement($usePage->nameField(2), 30);
		$I->fillField($usePage->nameField(2), $title);
		$I->fillField($usePage->descriptionField(2), $description);
		$I->attachFile($usePage->imageInput(2), NewTourPage::$imageFirst);
		$I->waitForElement(ManagePage::$checkboxAutoRation, 30);
		$I->waitForElement(ManagePage::$checkBoxSocial, 30);
		$I->click(ManagePage::$checkBoxSocial);
        
        $I->comment('I click Create button');
		$I->waitForElement(NewTourPage::$btnCreate, 30);
        $I->click(NewTourPage::$btnCreate);
        $I->waitForElement(ManagePage::$alterSaveSuccess, 500);
        $I->waitForElement(ManagePage::$searchId,30);
        $I->fillField(ManagePage::$searchId,$name);
        $I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(ManagePage::$btnLogout, 60);
		$I->click(ManagePage::$btnEditFrirst);
		$I->waitForElement(NewTourPage::$fieldName, 30);
		$I->seeInField(NewTourPage::$fieldName, $name);
		$I->seeInField(NewTourPage::$fieldFriendlyURL, $url);
    }

	public function createWithoutAnyScreen($name)
	{
		$I = $this;
		$I->click(ManagePage::$btnAddNew);
		$I->waitForElement(NewTourPage::$fieldName, 30);
		$I->comment('Fill Name Text Field');
		$I->waitForElement(NewTourPage::$fieldName, 30);
		$I->fillField(NewTourPage::$fieldName, $name);
		$I->fillField(NewTourPage::$fieldFriendlyURL, $name);
		$I->waitForElement(NewTourPage::$buttonRemoveScene, 30);
		$I->pauseExecution();
		$I->click(NewTourPage::$buttonRemoveScene);
		$I->click(NewTourPage::$btnCreate);
		$I->waitForElement(ManagePage::$closeButtonSuccess, 100);
	}

	public function checkURL($name, $url)
	{
		$I = $this;
		$I->waitForElement(ManagePage::$searchId, 30);
		$I->fillField(ManagePage::$searchId, $name);
		$I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->dontSee($url);
	}

	public function createWithURLReady($name, $url)
	{
		$I = $this;
		$I->click(ManagePage::$btnAddNew);
		$I->comment('create Tour with url is already taken ');
		$I->waitForElement(NewTourPage::$fieldName, 30);
		$I->switchToIFrame();
		$I->comment('Fill Name Text Field');
		$I->waitForElement(NewTourPage::$fieldName, 30);
		$I->fillField(NewTourPage::$fieldName, $name);
		$I->comment('Fill URL Text Field');
		$usePage = new NewTourPage();
		$I->waitForElement($usePage->nameField(2), 30);
		$I->waitForElement($usePage->nameField(2), 30);
		$I->fillField($usePage->nameField(2), $name);
		$I->fillField($usePage->descriptionField(2), $name);
		$I->attachFile($usePage->imageInput(2), NewTourPage::$imageFirst);
		$I->fillField(NewTourPage::$fieldFriendlyURL, $url);


		$I->click(NewTourPage::$btnCreate);
		$I->waitForElement(ManagePage::$closeButtonSuccess, 30);
	}

	public function checkMissing($name, $url, $title, $description)
	{
		$I = $this;
		$I->click(ManagePage::$btnAddNew);
		$I->waitForElement(NewTourPage::$fieldName, 30);
		$I->comment('Fill Name Text Field');
		$I->wait(1);
		$I->fillField(NewTourPage::$fieldName, $name);
		$I->wait(1);
		$I->comment('Fill URL Text Field');
		$I->fillField(NewTourPage::$fieldFriendlyURL, $url);

		$usePage = new NewTourPage();
		$I->waitForElement($usePage->nameField(2), 30);
		$I->waitForElement($usePage->nameField(2), 30);
		$I->fillField($usePage->nameField(2), $title);
		$I->fillField($usePage->descriptionField(2), $description);

		$I->waitForElement(NewTourPage::$btnCreate, 30);
		$I->wait(1);
		$I->click(NewTourPage::$btnCreate);
		$I->wait(1);
		$I->waitForElement(NewTourPage::$missingField, 30);
		$I->see(NewTourPage::$messageMissingField);

		$I->comment('Check missing name');
		$I->fillField(NewTourPage::$fieldName, '');
		$I->attachFile($usePage->imageInput(2), NewTourPage::$imageFirst);
		$I->wait(1);
		$I->click(NewTourPage::$btnCreate);
		$I->wait(1);
		$I->waitForElement(NewTourPage::$missingField, 30);
		$I->see(NewTourPage::$messageMissingField);

		$I->comment('Check missing url');
		$I->fillField(NewTourPage::$fieldName, $name);
		$I->fillField(NewTourPage::$fieldFriendlyURL, '');
		$I->wait(1);
		$I->click(NewTourPage::$btnCreate);
		$I->wait(1);
		$I->waitForElement(NewTourPage::$missingField, 30);
		$I->see(NewTourPage::$messageMissingField);

		$I->comment('Creat with missing title for pano');
		$I->fillField(NewTourPage::$fieldFriendlyURL, $url);
		$I->fillField($usePage->nameField(2), '');
		$I->wait(1);
		$I->click(NewTourPage::$btnCreate);
		$I->wait(1);
		$I->waitForElement(NewTourPage::$missingField, 30);
		$I->see(NewTourPage::$messageMissingField);
	}

	public function createWithClose($name, $url, $title, $description)
	{
		$I = $this;
		$I->click(ManagePage::$btnAddNew);
		$I->waitForElement(NewTourPage::$fieldName, 30);
		$I->comment('Fill Name Text Field');
		$I->wait(1);
		$I->fillField(NewTourPage::$fieldName, $name);
		$I->wait(1);
		$I->comment('Fill URL Text Field');
		$I->fillField(NewTourPage::$fieldFriendlyURL, $url);
		$usePage = new NewTourPage();
		$I->waitForElement($usePage->nameField(2), 30);
		$I->waitForElement($usePage->nameField(2), 30);
		$I->fillField($usePage->nameField(2), $title);
		$I->fillField($usePage->descriptionField(2), $description);
		$I->attachFile($usePage->imageInput(2), NewTourPage::$imageFirst);

		$I->comment('I click close button');
		$I->click(ManagePage::$btnClose);
		$I->waitForElement(ManagePage::$btnLogout, 60);
		$I->waitForElement(ManagePage::$searchId, 30);
		$I->fillField(ManagePage::$searchId, $name);
		$I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->dontSee($url);
	}

	public function editTour($name, $nameEdit, $url)
	{
		$I = $this;
		$I->waitForElement(ManagePage::$searchId, 30);
		$I->fillField(ManagePage::$searchId, $name);
		$I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->click(ManagePage::$btnEditFrirst);
		$I->wait(5);
		
		$I->waitForElement(NewTourPage::$fieldName, 30);
		$I->fillField(NewTourPage::$fieldName, $nameEdit);
		$I->click(NewTourPage::$btnCreate);
		$I->waitForElement(ManagePage::$alterSaveSuccess, 400);
		$I->waitForElement(ManagePage::$searchId,30);
		$I->fillField(ManagePage::$searchId, $nameEdit);
		$I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(ManagePage::$nameField, 30);
		$I->click(ManagePage::$btnEditFrirst);
		$I->waitForElement(NewTourPage::$fieldName, 30);
		$I->seeInField(NewTourPage::$fieldName, $nameEdit);
	}

	public function delete($name)
	{
		$I = $this;
		$I->waitForElement(ManagePage::$searchId, 30);
		$I->fillField(ManagePage::$searchId, $name);
		$I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->waitForElement(ManagePage::$btnRemove, 30);
		$I->click(ManagePage::$btnRemove);
		$I->acceptPopup();
		$I->wait(3);
		$I->waitForElement(ManagePage::$searchId, 30);
		$I->fillField(ManagePage::$searchId, $name);
		$I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->dontSee($name, ManagePage::$nameTour);
	}
    

	public function preview($name, $url, $firstTitle)
	{
		$I = $this;
		$I->comment('Check preview page ');
		$I->waitForElement(ManagePage::$searchId, 30);
		$I->fillField(ManagePage::$searchId, $name);
		$I->pressKey(ManagePage::$searchId, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->wait(3);
		$I->see($url);
		$I->click(ManagePage::$btnPreview);
		$I->wait(3);
		$I->switchToNextTab();
		$use = new ManagePage();
		$I->amOnUrl($use->returnURL($url));
		$I->wait(10);
//        $I->waitForElement(ManagePage::$titlePreview,30);
//        $I->waitForText($firstTitle, 30 , ManagePage::$titlePreview);
//        $I->waitForElement(ManagePage::$buttonFacePreview, 30);
//        $I->waitForElement(ManagePage::$buttonGGPreview, 30);
//        $I->waitForElement(ManagePage::$buttonTwPreview, 30);
//        $I->waitForElement(ManagePage::$buttonShowImage, 30);
//        $I->click(ManagePage::$buttonShowImage);
//        $I->wait(2);
//        $I->waitForElement(ManagePage::$fistImagePreview, 30);
	}

	public function hotSpot($name)
	{

	}
}