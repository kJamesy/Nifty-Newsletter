<?php
namespace Jamesy;
use Str;
use URL;

class FrontendHelpers
{
	public static function getAnchorsHtml($anchors)
	{
		$html = '';
		$anchorsArr = explode(',', $anchors);
		// $anchorsArrLength = count($anchorsArr);

		foreach ($anchorsArr as $key => $anchor) {
			$html .= "<li><a class='anchor' href='#" . $anchor . "'>" . Str::upper( str_replace('-', ' ', $anchor) ) . "</a></li>";
		}

		return $html;
	}

	public static function getPreviousHtml($pages)
	{
		$html = '';

		foreach ($pages as $page) {
			$html .= "<li><a href='" . URL::to("email/$page->slug") . "'>" . Str::upper( $page->title ) . "</a></li>";
		}

		return $html;
	}

}