<?php

defined('_VR360_EXEC') or die;

class Vr360ViewTours extends Vr360View
{
	public $tours;
	protected $name = 'tours';
	protected $layoutBase = '';

	public function display($layout = 'default')
	{
		$model = Vr360ModelTours::getInstance();

		$this->tours      = $model->getList();
		$this->pagination = $model->getPagination();

		return parent::display($layout);
	}
}