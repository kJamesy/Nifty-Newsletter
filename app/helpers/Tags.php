<?php
namespace Jamesy;

use URL;
use Carbon\Carbon;

class Tags
{
	public static function getTagsHtml($tags)
	{
		$html = '';

		foreach ( $tags as $tag ) {
			$html .= "<tr class='hover-row'>";
			$html .= "<td><input type='checkbox' class='acheckbox' value='" . $tag->id . "'></td>";
			$html .= "<td><a href='" . URL::to('dashboard/tags/' . $tag->id . '/edit') . "'>$tag->name</a>";
			$html .= "<div class='visibility more-options'>";
			$html .= "<a href='" . URL::to('dashboard/tags/' . $tag->id . '/edit') . "'>Edit</a> | ";
			$html .= "<a href='" . URL::to('dashboard/tags/' . $tag->id . '/destroy') . "' class='text-danger'>Delete Permanently</a></div></td>";			
			if ( count($tag->emails) )
				$html .= "<td><a href='" . URL::to('dashboard/emails/sent') . "' class='btn btn-default btn-circle btn-grad'>" . count($tag->emails) . "</a></td>";
			else
				$html .= "<td><a href='" . URL::to('dashboard/emails?tag=' . $tag->id) . "' class='btn btn-default btn-circle btn-grad disabled'>0</a></td>";
			$html .= "<td><abbr title='" . $tag->created_at->format('D jS \\of M, Y H:i A') . "'>" . $tag->created_at->format('jS M, Y') . "</abbr></td>";
			$html .= "<td><abbr title='" . $tag->updated_at->format('D jS \\of M, Y H:i A') . "'>" . $tag->updated_at->diffForHumans() . "</abbr></td>";
			$html .= "</tr>";
		}

		return $html;
	}
}