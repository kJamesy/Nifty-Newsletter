<?php
namespace Jamesy;

use URL;
use Carbon\Carbon;
use Sentry;

class BackendUsers
{
	public static function getUsersHtml($users)
	{
		$html = '';
		$currentUser = Sentry::getUser();

		foreach ($users as $user) {
			$thisUser = Sentry::findUserById( $user->id );
			$throttle = Sentry::findThrottlerByUserId( $user->id );
			$groups = $thisUser->getGroups();
			$groupsNum = count($groups);
			$emailsNum = count($user->emails);

			$html .= "<tr class='hover-row'>";
			$html .= "<td><input type='checkbox' class='acheckbox' value='" . $user->id . "'></td>";
			if ( $currentUser->id == $user->id )
				$html .= "<td><a href='" . URL::to('dashboard/users/profile') . "'>$user->first_name $user->last_name</a> (You)";
			else
				$html .= "<td><a href='" . URL::to('dashboard/users/' . $user->id . '/edit') . "'>$user->first_name $user->last_name</a>";
			if ( $throttle->isBanned() )
				$html .= " <span class='text-danger'>( <i class='fa fa-ban'></i> Banned)</span>";
			$html .= "<div class='visibility more-options'>";
			if ( $currentUser->id == $user->id )
				$html .= "<a href='" . URL::to('dashboard/users/profile') . "'>Edit</a></div></td>";
			else {
				$html .= "<a href='" . URL::to('dashboard/users/' . $user->id . '/edit') . "'>Edit</a> | ";
				if ( $throttle->isBanned() )
					$html .= "<a href='" . URL::to('dashboard/users/' . $user->id . '/unban') . "' class='text-success'>Un-ban</a> | ";
				else
					$html .= "<a href='" . URL::to('dashboard/users/' . $user->id . '/ban') . "' class='text-danger'>Ban</a> | ";
				$html .= "<a href='" . URL::to('dashboard/users/' . $user->id . '/destroy') . "' class='text-danger'>Delete Permanently</a></div></td>";
			}
			$html .= "<td>$user->email</td>";
			$html .= "<td>";	
				foreach ($groups as $key=>$group) {
					$html .= $group->name;
					$html .= $key != $groupsNum - 1 ? ', ' : '';
				}
				
			$html .= "</td>";
			if ( $emailsNum )
				$html .= "<td><a href='" . URL::to('dashboard/emails/sent?author=' . $user->id) . "' class='btn btn-default btn-circle btn-grad'>$emailsNum</a></td>";
			else
				$html .= "<td><a href='" . URL::to('dashboard/emails/sent?author=' . $user->id) . "' class='btn btn-default btn-circle btn-grad disabled'>$emailsNum</a></td>";		

			$html .= "<td><abbr title='" . $user->created_at->format('D jS \\of M, Y H:i A') . "'>" . $user->created_at->format('jS M, Y') . "</abbr></td>";
			if ( $thisUser->last_login )
				$html .= "<td><abbr title='" . $thisUser->last_login->format('D jS \\of M, Y H:i A') . "'>" . $thisUser->last_login->diffForHumans() . "</abbr></td>";
			else
				$html .= "<td>Never</td>";
			$html .= "</tr>";
		}
		return $html;
	}
}