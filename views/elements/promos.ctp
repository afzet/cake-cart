<?php
/**
 * SVN FILE: $Id: countries.ctp 104 2008-08-19 11:43:25Z jonathan $
 *
 * Element Promos View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 104 $
 * Last Modified: $Date: 2008-08-19 07:43:25 -0400 (Tue, 19 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
 ?>
	<tr>
		<td colspan="3" valign="middle" height="22" class="cart" style="text-align: right">
			<form action="/cart/add_discount" method="post">
			Promo Code: 
			<input type="text" name="data[Promo][code]" value="" id="code" />
			<input type="submit" value="Submit" />
		</form>
		</td>
	</tr>