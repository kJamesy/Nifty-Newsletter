<?php 
namespace Jamesy;
use Carbon\Carbon;
use URL;

class BackendPages
{


	public function __construct($pages, $type = null)
	{
		$this->pages = (object) $pages;	
		$this->type = $type;
	}


	public function getPagesHtml()
	{
		$html = '';

		if ( $this->type == 'deleted' ) {
			foreach ( $this->pages as $page ) {
				$html .= "<tr class='hover-row'>";
				$html .= "<td><input type='checkbox' class='acheckbox' value='" . $page->id . "'></td>";
				$html .= "<td>$page->title";
				$html .= "<div class='visibility more-options'>";
				$html .= "<a href='" . URL::to('email/' . $page->slug . '/preview') . "' target='_blank' >Preview</a> | ";
				$html .= "<a href='" . URL::to('dashboard/pages/' . $page->id . '/restore') . "' class='text-success'>Restore</a> | ";
				$html .= "<a href='" . URL::to('dashboard/pages/' . $page->id . '/destroy') . "' class='text-danger'>Delete Permanently</a></div></td>";
				$html .= "<td>" . $page->user->first_name .' '. $page->user->last_name . "</td>";
				$html .= "<td><abbr title='" . $page->created_at->format('D jS \\of M, Y H:i A') . "'>" . $page->created_at->format('jS M, Y') . "</abbr></td>";
				$html .= "<td><abbr title='" . $page->updated_at->format('D jS \\of M, Y H:i A') . "'>" . $page->updated_at->diffForHumans() . "</abbr></td>";
				$html .= "</tr>";
			}
		}

		else {
			foreach ( $this->pages as $page ) {
				$html .= "<tr class='hover-row'>";
				$html .= "<td><input type='checkbox' class='acheckbox' value='" . $page->id . "'></td>";
				$html .= "<td><a href='" . URL::to('dashboard/pages/' . $page->id . '/edit') . "'><strong>$page->title</strong></a>";
				$html .= "<div class='visibility more-options'>";
				$html .= "<a href='" . URL::to('dashboard/pages/' . $page->id . '/edit') . "'>Edit</a> | ";
				$html .= "<a href='" . URL::to('email/' . $page->slug) . "' target='_blank' >View</a>";
				$html .= " | <a href='" . URL::to('dashboard/pages/' . $page->id . '/delete') . "' class='text-danger'>Trash</a></div></td>";
				$html .= "<td>" . $page->user->first_name .' '. $page->user->last_name . "</td>";
				$html .= "<td><abbr title='" . $page->created_at->format('D jS \\of M, Y H:i A') . "'>" . $page->created_at->format('jS M, Y') . "</abbr></td>";
				$html .= "<td><abbr title='" . $page->updated_at->format('D jS \\of M, Y H:i A') . "'>" . $page->updated_at->diffForHumans() . "</abbr></td>";
				$html .= "</tr>";
			}

		}	

		return $html;
	}

}