<?php
namespace Jamesy;

use \Carbon\Carbon;
use \Hashids\Hashids;
use \Config;

class Miscellaneous
{
	public static function hashids()
	{
		return new Hashids( Config::get('app.key'), 12, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' );
	}

	public static function getStandardDateHtml( $eloquentDate )
	{
		return "<abbr title='" . $eloquentDate->format('D jS \\of M, Y H:i A') . "'>" . $eloquentDate->format('jS M, Y') . "</abbr>";
	}

	public static function encryptId($original)
	{
		return static::hashids()->encrypt($original);
	}

	public static function decryptId($encrypted)
	{
		return static::hashids()->decrypt($encrypted)[0];
	}

}