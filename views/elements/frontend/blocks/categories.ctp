<?php
/**
 * SVN FILE: $Id: categories.ctp 58 2008-08-08 01:51:02Z jonathan $
 *
 * Element Sidebar Categories Box
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
?>
<div style=" width: 100%; background-color: #fed4cb; height: 29px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: #f96444; font-weight: bold;">
					Our Catalog
				</div>				
			</td>
		</tr>
	</table>
</div>
<table width="224" cellspacing="12" cellpadding="0" border="0" bgcolor="#ececec">
	<tr>
		<td valign="top">	
		<div class="menu">
			 <ul>
			 	
		<?
		foreach ($navCats as $category) {
			echo '
			<li>
				<a href="/c/'.$category['Category']['id'].'/'.urlencode($category['Category']['name']).'.html">
					'.htmlspecialchars_decode($category['Category']['name']).'
				</a>
			</li>';
		}
		?>
			 </ul>
		</div>
		</td>
		
	</tr>
</table>
<br /><br />