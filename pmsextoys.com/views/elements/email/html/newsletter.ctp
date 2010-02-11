<?php
/**
 * SVN FILE: $Id: newsletter.ctp 58 2008-08-08 01:51:02Z jonathan $
 *
 * Element Email HTML Newsletter Template
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
?>
<table border="0" cellspacing="0" cellpadding="0" id="main">
<tr>
	<td class="info">
		Thank you again for signing up for our newsletter. Please enter follow the below instructions to 
		validate your email:
		
		<br />
		<ol>
			<li>Visit to <a href="http://passionmansion.com/newsletter/confirm">http://passionmansion.com/newsletter/confirm</a></li>
			<li>Confirmation Code: <?php echo $data['Subscriber']['validation_code']?></li>
		</ol>	
		
		<br /><br />
		Thank you,<br />
		Passion Mansion, Inc.
    <td>
</tr>
</table>