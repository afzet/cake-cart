<?php
/**
 * SVN FILE: $Id: view.ctp 58 2008-08-08 01:51:02Z jonathan $
 *
 * Category View View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
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
					<?=htmlspecialchars_decode($category['Category']['name'])?>
				</div>				
			</td>
		</tr>
	</table>
</div>
<br />
<?=$this->renderElement('category_overview');?>