<?php
namespace Jamesy;

use URL;
use Carbon\Carbon;

class Maillists
{
	public static function getListsHtml($lists)
	{
		$html = '';

		foreach ( $lists as $list ) {
			$html .= "<tr class='hover-row'>";
			$html .= "<td><input type='checkbox' class='acheckbox' value='" . $list->id . "'></td>";
			$html .= "<td><a href='" . URL::to('dashboard/lists/' . $list->id . '/edit') . "'>$list->name</a>";
			$html .= "<div class='visibility more-options'>";
			$html .= "<a href='" . URL::to('dashboard/lists/' . $list->id . '/edit') . "'>Edit</a> | ";
			if ( $list->active ) {
				$html .= "<a href='" . URL::to('dashboard/emails/create?lists=' . $list->id ) . "' class='text-success'>Email</a> | ";
				$html .= "<a href='" . URL::to('dashboard/lists/' . $list->id . '/deactivate') . "' class='text-warning'>Deactivate</a> | ";
			}
			else 
				$html .= "<a href='" . URL::to('dashboard/lists/' . $list->id . '/activate') . "' class='text-success'>Activate</a> | ";			
			$html .= "<a href='" . URL::to('dashboard/lists/' . $list->id . '/destroy') . "' class='text-danger'>Delete Permanently</a></div></td>";	
			$list->active ? $html .= "<td><i class='fa fa-check-circle text-success'></i></td>" : $html .= "<td><i class='fa fa-times-circle text-danger'></i></td>";		
			if ( count($list->subscribers) )
				$html .= "<td><a href='" . URL::to('dashboard/subscribers?list=' . $list->id) . "' class='btn btn-default btn-circle btn-grad'>" . count($list->subscribers) . "</a></td>";
			else
				$html .= "<td><a href='" . URL::to('dashboard/subscribers?list=' . $list->id) . "' class='btn btn-default btn-circle btn-grad disabled'>0</a></td>";
			$html .= "<td><abbr title='" . $list->created_at->format('D jS \\of M, Y H:i A') . "'>" . $list->created_at->format('jS M, Y') . "</abbr></td>";
			$html .= "<td><abbr title='" . $list->updated_at->format('D jS \\of M, Y H:i A') . "'>" . $list->updated_at->diffForHumans() . "</abbr></td>";
			$html .= "</tr>";
		}

		return $html;
	}
}