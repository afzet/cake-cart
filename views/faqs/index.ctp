<?php
/**
 * SVN FILE: $Id: index.ctp 58 2008-08-08 01:51:02Z jonathan $
 *
 * FAQ Index View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
 ?>
 <div style="background-color: rgb(252, 214, 196); height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: rgb(243, 107, 43); font-weight: bold;">
					Frequently Asked Questions		<a name="top"></a>
				</div>				
			</td>
			<td align="right">&nbsp;</td>
		</tr>
	</table>
</div>

<div id="faqs">
		
<?
foreach ($data as $faq) {
	echo '<strong>'.$faq['FaqCategory']['name'].'</strong>';
	echo '<p class="faq_items">';
	foreach ($faq['Faq'] as $question) {
		echo '&bull; <a href="/faqs/view/'.$question['id'].'/'.str_replace(' ','_',$question['title']).'">'.$question['title'].'</a><br />';
	}
	echo '</p>';
	echo '<br />';
}

?>
</div>
