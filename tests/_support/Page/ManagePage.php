<?php

namespace Page;

class ManagePage
{
	public static $btnLogout = ['id' => 'user-logout'];

	public static $btnAddNew = ['id' => 'tour-add'];

	public static $searchId = ['id' => 'search-keyword'];

	public static $buttonReset = ['id' => 'search-reset'];

	public static $nameTour = ['class' => 'tour-name'];

	public static $noResults = ['xpath' => '//div[contains(concat(\' \', @class, \' \'), \'alert-warning \')]'];

	public static $createSuccessXpath = ['xpath' => '//div[contains(concat(\' \', @class, \' \'), \'alert-info \')]'];

	public static $closeButtonSuccess = ['xpath' => '//button[contains(concat(\' \', @class, \' \'), \' ajax-close \')]'];

	public static $alterSaveSuccess = ['xpath' => '//div[contains(concat(\' \', @class, \' \'), \'alert-success\')]'];

	public static $messageSaveSuccess = 'Tour is created';

	public static $nameField = ['xpath' => '//span[contains(concat(\' \', @class, \' \'), \'tour-name\')]'];
	
	public static $btnEmbedfirst = ['xpath' => '//button[contains(concat(\' \', @class, \' \'), \'tour-embed \')]'];

	public static $btnEditFrirst = ['xpath' => '//button[contains(concat(\' \', @class, \' \'), \'tour-edit \')]'];

	public static $btnHostPot = ['xpath' => '//button[contains(concat(\' \', @class, \' \'), \'tour-edit-hotspots \')]'];

	public static $btnPreview = ['xpath' => '//a[contains(concat(\' \', @class, \' \'), \'tour-preview \')]'];

	public static $btnRemove = ['xpath' => '//button[contains(concat(\' \', @class, \' \'), \'btn-danger tour-delete \')]'];


	public static $checkBoxSocial = ['id' => 'tour-param-socials'];

	public static $checkboxAutoRation = ['id' => 'tour-param-rotation'];

	//edit page
	public static $contentEditXpath = ['xpath' => '(//h4[@id=\'myModalLabel\'])[2]'];

	//profile view
	public static $profileImg = ['xpath' => '//img[@id=\'avatar\']'];

	//content
	public static $contentEdit = 'Edit tour';

	//already

    /**
     * @var array
     */
    public static $btnClose = ['id' => 'modal-close'];

	//hotpot
	public static $addHotsPotXpath = ['id' => 'myModalLabel'];

	/**
	 * @param   string  $url
	 *
	 * @return  string
	 */
	public function returnURL($url)
	{
		$urlShow = 'http://dev.globalvision.ch/' . $url;
		
		return $urlShow;
	}
}