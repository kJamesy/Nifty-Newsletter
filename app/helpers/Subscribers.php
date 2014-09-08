<?php
namespace Jamesy;

use URL;
use Carbon\Carbon;

class Subscribers
{
	public static function getSubscribersHtml($subscribers)
	{
		$html = '';

		foreach ( $subscribers as $subscriber ) {
			$html .= "<tr class='hover-row'>";
			$html .= "<td><input type='checkbox' class='acheckbox' value='" . $subscriber->id . "'></td>";
			$html .= "<td><a href='" . URL::to('dashboard/subscribers/' . $subscriber->id . '/edit') . "'>$subscriber->first_name $subscriber->last_name</a>";
			$html .= "<div class='visibility more-options'>";
			if ( $subscriber->active )
				$html .= "<a href='" . URL::to('dashboard/subscribers/' . $subscriber->id . '/deactivate') . "' class='text-danger'>Deactivate</a> | ";
			else 
				$html .= "<a href='" . URL::to('dashboard/subscribers/' . $subscriber->id . '/activate') . "' class='text-success'>Activate</a> | ";
			$html .= "<a href='" . URL::to('dashboard/subscribers/' . $subscriber->id . '/edit') . "'>Edit</a> | ";
			$html .= "<a href='" . URL::to('dashboard/subscribers/' . $subscriber->id . '/delete') . "' class='text-danger'>Trash</a></div></td>";	
			$html .= "<td><a href='" . URL::to('dashboard/emails/create?subs=' . $subscriber->id) . "'>$subscriber->email</a></td>";
			$html .= $subscriber->active ? "<td><i class='fa fa-check-circle text-success'></i></td><td>" : "<td><i class='fa fa-times-circle text-danger'></i></td><td>";		
			if ( $maillistsNum = count($subscriber->maillists) ) {
				foreach ( $subscriber->maillists as $key => $maillist ) {
					if ( $maillist->id != 1 ) {
						$html .= "<a href='" . URL::to('dashboard/subscribers?list=' . $maillist->id) . "'>" . $maillist->name . "</a>";
						$html .=  $key != $maillistsNum -1 ? ', ' : '';
					}
				}
			}
			else
				$html .= "None";
			$html .= "</td><td><abbr title='" . $subscriber->created_at->format('D jS \\of M, Y H:i A') . "'>" . $subscriber->created_at->format('jS M, Y') . "</abbr></td>";
			$html .= "<td><abbr title='" . $subscriber->updated_at->format('D jS \\of M, Y H:i A') . "'>" . $subscriber->updated_at->diffForHumans() . "</abbr></td>";
			$html .= "</tr>";
		}

		return $html;
	}

	public static function getDeletedSubscribersHtml($subscribers)
	{
		$html = '';

		foreach ( $subscribers as $subscriber ) {
			$html .= "<tr class='hover-row'>";
			$html .= "<td><input type='checkbox' class='acheckbox' value='" . $subscriber->id . "'></td>";
			$html .= "<td>$subscriber->first_name $subscriber->last_name";
			$html .= "<div class='visibility more-options'>";
			$html .= "<a href='" . URL::to('dashboard/subscribers/' . $subscriber->id . '/restore') . "' class='text-success' >Restore</a> | ";
			$html .= "<a href='" . URL::to('dashboard/subscribers/' . $subscriber->id . '/destroy') . "' class='text-danger'>Delete Permanently</a></div></td>";	
			$html .= "<td>$subscriber->email</td>";
			$html .= $subscriber->active ? "<td><i class='fa fa-check-circle text-success'></i></td><td>" : "<td><i class='fa fa-times-circle text-danger'></i></td><td>";		
			if ( $maillistsNum = count($subscriber->maillists) ) {
				foreach ( $subscriber->maillists as $key => $maillist ) {
					$html .= "<a href='" . URL::to('dashboard/subscribers/trash?list=' . $maillist->id) . "'>" . $maillist->name . "</a>";
					$html .=  $key != $maillistsNum -1 ? ', ' : '';
				}
			}
			else
				$html .= "None";
			$html .= "</td><td><abbr title='" . $subscriber->created_at->format('D jS \\of M, Y H:i A') . "'>" . $subscriber->created_at->format('jS M, Y') . "</abbr></td>";
			$html .= "<td><abbr title='" . $subscriber->updated_at->format('D jS \\of M, Y H:i A') . "'>" . $subscriber->updated_at->diffForHumans() . "</abbr></td>";
			$html .= "</tr>";
		}

		return $html;
	}

}