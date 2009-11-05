<?php
/**
 * SVN FILE: $Id: countries.ctp 473 2008-09-01 04:36:05Z jonathan $
 *
 * Element Countries View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 473 $
 * Last Modified: $Date: 2008-09-01 00:36:05 -0400 (Mon, 01 Sep 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
 ?>
	<tr>
		<td colspan="3" valign="middle" height="22" class="cart" style="text-align: right">
			<form action="" method="get">
			Please Select A Country:
			<select onchange="goto_URL(this.form.country)" name="country" class="smaller" class="contact">
				<option value=""> Select Country</option>
				<option value="/cart/add_country/US">United States</option>
				<option value="/cart/add_country/CA">Canada</option>			
			</select>
		</form>
		</td>
	</tr>