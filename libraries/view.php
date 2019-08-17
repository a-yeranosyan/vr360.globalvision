<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360View
 *
 * @since  2.0.0
 */
class Vr360View extends Vr360Layout
{
	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $layoutBase = '';

	/**
	 * Vr360View constructor.
	 *
	 * @param   null $baseDir
	 */
	public function __construct($baseDir = null)
	{
		parent::__construct(__DIR__ . '/view/' . $this->name . '/tmpl/');
	}

	/**
	 * @param   string $layout
	 *
	 * @return  string
	 */
	public function display($layout = 'default')
	{
		return $this->fetch($layout, array('data' => $this));
	}

	/**
	 * @param   string $text
	 *
	 * @return  null|string|string[]
	 */
	public function optimizeHtml($text)
	{
		// 8MB stack. *nix
		ini_set("pcre.recursion_limit", "16777");

		$regex = '%# Collapse whitespace everywhere but in blacklisted elements.
        (?>             # Match all whitespans other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          [^<]*+        # Either zero or more non-"<" {normal*}
          (?:           # Begin {(special normal*)*} construct
            <           # or a < starting a non-blacklist tag.
            (?!/?(?:textarea|pre|script)\b)
            [^<]*+      # more non-"<" {normal*}
          )*+           # Finish "unrolling-the-loop"
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre|script)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %Six';

		$text = preg_replace($regex, " ", $text);

		if ($text === null)
		{
			return $text;
		}

		$search  = array(
			'/\>[^\S ]+/s',
			'/[^\S ]+\</s',
			'/(\s)+/s'
		);
		$replace = array(
			'>',
			'<',
			'\\1'
		);

		if (preg_match("/\<html/i", $text) == 1 && preg_match("/\<\/html\>/i", $text) == 1)
		{
			$text = preg_replace($search, $replace, $text);
		}

		return $text;
	}
}
