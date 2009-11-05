<?php
/**
 * SVN FILE: $Id: whats_new.ctp 144 2008-08-24 19:28:26Z jonathan $
 *
 * Products What's New View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 144 $
 * Last Modified: $Date: 2008-08-24 15:28:26 -0400 (Sun, 24 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
 ?>
<?
$currentUrl = isset($this->params['pass'])?$this->params['pass']:array();
$paginator->options(array('url' => $currentUrl));

?>
<div style="background-color: rgb(252, 214, 196); height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: rgb(243, 107, 43); font-weight: bold;">
					What's New <a href="http://passionmansion.com/rss/new.xml"><img src="/img/icons/rss.png" alt="RSS Feed" valign="middle" /></a>
				</div>				
			</td>
		</tr>
	</table>
</div>
<br />
<?=$this->renderElement('product_view', array('data' => $data));?>