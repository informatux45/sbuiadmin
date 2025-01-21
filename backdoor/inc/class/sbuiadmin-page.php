<?php
/** *****************************************************************************
*                      INFORMATUX page class (UTF8)                             *
/** *****************************************************************************
* @author     Patrice BOUTHIER <contact[at]informatux.com>                      *
* @copyright  1996-2016 INFORMATUX                                              *
* @link       http://www.informatux.com/                                        *
* @since      1.0                                                               *
* @version    CVS: 1.8                                                          *
* ----------------------------------------------------------------------------- *
* Copyright (c) 2011, INFORMATUX Solutions and web development                  *
* All rights reserved.                                                          *
***************************************************************************** **/

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBUIADMIN_PATH') or die('Are you crazy!');


class page extends Smarty {

	/**
	* Format Header Module Page
	* @param  string  $modulePage
	* @return string
	*/
	public function getHeader($modulePage) {

	}
	
	public function paginate($datas, $infos = false) {
		// ----------------------------------------------
		$url         = $datas['url'] . '&';
		$total       = $datas['total'];
		$by_page     = $datas['by_page'];
		$currentPage = $datas['page'];
		$data_infos  = [
			 'start' => (($currentPage+1)*$by_page)-$by_page+1
			,'end'   => ($currentPage+1)*$by_page
			,'total' => $total
		];
		// ----------------------------------------------

		// ----------------------------------------------
		$pagination = "";
		$pagination .= '
			<style>
			.pagination {
				list-style-type: none;
				padding: 10px 0;
				display: inline-flex;
				justify-content: space-between;
				box-sizing: border-box;
			}
			.pagination li {
				box-sizing: border-box;
				padding-right: 10px;
			}
			.pagination li a {
				box-sizing: border-box;
				background-color: #e2e6e6;
				padding: 8px;
				text-decoration: none;
				font-size: 12px;
				font-weight: bold;
				color: #616872;
				border-radius: 4px;
			}
			.pagination li a:hover {
				background-color: #d4dada;
			}
			.pagination .next a, .pagination .prev a {
				text-transform: uppercase;
				font-size: 12px;
			}
			.pagination .currentpage a {
				background-color: #518acb;
				color: #fff;
			}
			.pagination .currentpage a:hover {
				background-color: #518acb;
			}
			</style>';
		// ----------------------------------------------
		if (isset($infos) && $infos == true) {
			$pagination .= $this->paginateInfos($data_infos);
			$pagination .= '<br>';
		}
		// ----------------------------------------------
        $pagination .= '<ul class="pagination">';
			$pagination .= '<li class="page-item ' . (($currentPage < 1) ? "disabled" : "") . '">';
                $pagination .= '<a href="' . $url . 'page=' . $currentPage - 1 . '" class="page-link">' . SBUIADMIN_GLOBAL_PREV . '</a>';
            $pagination .= '</li>';
            for($page = 1; $page <= $by_page; $page++) {
                $pagination .= '<li class="page-item ' . (($currentPage == $page-1) ? "active" : "") . '">';
					$pagination .= '<a href="' . $url . 'page=' . $page-1 . '" class="page-link">' . $page . '</a>';
                $pagination .= '</li>';
			}
            $pagination .= '<li class="page-item ' . (($currentPage == $by_page) ? "disabled" : "") . '">';
                $pagination .= '<a href="' . $url . 'page=' . $currentPage + 1 . '" class="page-link">' . SBUIADMIN_GLOBAL_NEXT . '</a>';
            $pagination .= '</li>';
        $pagination .= '</ul>';
		
		return $pagination;
	}
	
	public function paginateInfos($datas) {
		$paginate_infos  = "";
		$paginate_infos .= sprintf(SBUIADMIN_GLOBAL_PAGE_INFOS, $datas['start'], $datas['end'], $datas['total']);
		return $paginate_infos;
	}

}

?>
