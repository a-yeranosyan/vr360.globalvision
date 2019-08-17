<?php

define('_VR360_EXEC', true);
define('VR360_PATH_BASE', __DIR__);

require_once VR360_PATH_BASE . '/includes/defines.php';
require_once VR360_PATH_BASE . '/includes/framework.php';
require_once VR360_PATH_VENDOR . DIRECTORY_SEPARATOR . 'autoload.php';

$languageFactory = new \Joomla\Language\LanguageFactory;
$language = $languageFactory->getLanguage('en-GB', null, array('debug' => Vr360Configuration::getConfig('debug', false)));
\Joomla\Language\Text::setLanguage($language);
$language->load('vr360', VR360_PATH_BASE);
