<?php

use Step\LoginSteps as LoginSteps;
use Step\NewTourSteps as NewTourSteps;

/**
 * Class NewTourCest
 */
class NewTourCest
{
	/**
	 * NewTourCest constructor.
	 */
	public function __construct()
	{
		$this->faker    = Faker\Factory::create();

		/**
		 * @TODO Move these value to defines
		 */
		$this->userName = 'designteam';
		$this->pass     = '123';

		/**
		 * @TODO    Create name over than 255 character
		 * @TODO    Create name with UTF-8 characters
		 */
		$this->nameTour        = $this->faker->bothify('nametour?ThisisName?##???');
		$this->nameTourAlreday = $this->faker->bothify('nametourThisIsNameAlready?##???');
		$this->url             = $this->faker->bothify('URLFaceOfTour?#######??');
		$this->title           = $this->faker->bothify('TitleTesting?##?');
		$this->description     = $this->faker->bothify('Description?##?');

		//edit name tour
		$this->nameTourEdit = $this->nameTour . 'edit';
		$this->urlEdit      = $this->url . 'edit';
	}

	public function _before(NewTourSteps $I)
	{
		$I->login($this->userName, $this->pass);
	}
	
//	/**
//	 * @param NewTourSteps $I
//	 * create new tour without any screen
//	 */
//    public function createWithoutAnyScreen(NewTourSteps $I)
//    {
//        $I->login($this->userName, $this->pass);
//        $I->createWithoutAnyScreen($this->nameTour);
//    }

	/**
	 * @param NewTourSteps $I
	 * Check missing for all cases
	 */
	public function checkMissing(NewTourSteps $I)
	{
		$I->checkMissing($this->nameTour, $this->url, $this->title, $this->description);
	}

	/**
	 * @param NewTourSteps $I
	 * Create new tour
	 */
	public function createNew(NewTourSteps $I)
	{
		$I->create($this->nameTour, $this->url, $this->title, $this->description);
	}

	/**
	 * @param NewTourSteps $I
	 * Preview tour
	 */
	public function preview(NewTourSteps $I)
	{
		$I->preview($this->nameTour, $this->url, $this->title);
	}

	/**
	 * @param NewTourSteps $I
	 * Edit name for this tour
	 */
	public function editTour(NewTourSteps $I)
	{
		$I->editTour($this->nameTour, $this->nameTourEdit, $this->url);
	}

	/**
	 * @param NewTourSteps $I
	 * Create new tour with url is ready
	 */
	public function createReady(NewTourSteps $I)
	{
		$I->createWithURLReady($this->nameTourAlreday, $this->url);
	}

	/**
	 * @param NewTourSteps $I
	 * Delete tour
	 */
	public function delete(NewTourSteps $I)
	{
		$I->delete($this->nameTourEdit);
//        $I->wantTo('Check Close button ');
//        $I->createWithClose($this->nameTour, $this->url, $this->title, $this->description);
	}

}
