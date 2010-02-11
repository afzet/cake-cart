<?php
/**
 * SVN FILE: $Id: index.ctp 379 2008-08-31 09:36:04Z jonathan $
 *
 * Contact Index View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 379 $
 * Last Modified: $Date: 2008-08-31 05:36:04 -0400 (Sun, 31 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
?>

<div style="background-color: #fed4cb; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: #f96444; font-weight: bold;">
					Webmasters
				</div>				
			</td>
		</tr>
	</table>
</div>

<br />

<div style="padding: 10px;">	  
<?php
if ($session->check('Message.flash')) {
	$session->flash();	
}
?>
</div>