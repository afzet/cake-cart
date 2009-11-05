<?php
/**
 * SVN FILE: $Id: pagnation.ctp 94 2008-08-19 09:04:12Z jonathan $
 *
 * Element Pagnation View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 94 $
 * Last Modified: $Date: 2008-08-19 05:04:12 -0400 (Tue, 19 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
?> 
<table border="0" cellspacing="5" cellpadding="5" style="border: 0;" width="100%">
	<tr>
		<td colspan="3" class="smaller">
			<?=$paginator->counter(array('format' => 'Total Products: %count% <br /> Page %page% of %pages%'))?>
		</td>
		<td colspan="12" class="smaller" style="text-align: right" valign="bottom">
			<?php
				echo $paginator->prev('« Previous ', null, null, array('class' => 'disabled'));
				echo '&nbsp;';
				echo $paginator->numbers(); 
				echo '&nbsp;';
				echo $paginator->next(' Next »', null, null, array('class' => 'disabled'));
			?> 
		</td>
		<td width="15" />
	</tr>
</table>