<?php
/**
 * SVN FILE: $Id: view.ctp 58 2008-08-08 01:51:02Z jonathan $
 *
 * FAQ View View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
 ?>
<div style="background-color: #fed4cb; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: #f96444; font-weight: bold;">
					Frequently Asked Questions
				</div>				
			</td>
			<td align="right">&nbsp;</td>
		</tr>
	</table>
</div>

<div id="faqs">
<h2><?=$data['Faq']['title']?></h2>
<br />
<?=$data['Faq']['body']?>
<br />
<a href="/faqs">&laquo; back</a>
</div>