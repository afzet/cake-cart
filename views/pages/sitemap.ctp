<?php
/**
 * SVN FILE: $Id: advertise.ctp 58 2008-08-08 01:51:02Z jonathan $
 *
 * Pages Advertise View
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
					Sitemap		
				</div>				
			</td>
			<td align="right">&nbsp;</td>
		</tr>
	</table>
</div>

<div style="padding: 10px;">
  <p>
	<ul>
	&nbsp;<strong><a href="/">Home</a></strong><br />
	&nbsp;<strong><a href="/cart">Cart</a></strong><br />
	&nbsp;<strong><a href="/orders/track">Order Tracking</a></strong><br />
	&nbsp;<strong><a href="/contact">Contact Us</a></strong><br />
	&nbsp;<strong><a href="/faqs">F.A.Q.</a></strong><br />
	&nbsp;<strong><a href="/search">Search</a></strong><br />
	&nbsp;<strong>Categories</strong><br />
	<?
	foreach ($session->read('NavCats') as $category) {
		echo '
		&nbsp;&nbsp; &raquo; 
			<a href="/c/'.$category['Category']['id'].'/'.urlencode($category['Category']['name']).'.html">
				'.htmlspecialchars_decode($category['Category']['name']).'
			</a>
		<br />';
	}
	?>
	&nbsp;<strong><a href="/faqs/view/26/Terms_Of_Use_Agreement">Terms Of Use Agreement</a></strong><br />
	&nbsp;<strong><a href="/faqs/view/27/Privacy_Policy">Privacy Policy</a></strong><br />
	&nbsp;<strong><a href="/press">Press</a></strong><br />
	&nbsp;<strong><a href="/docs/advertise">Advertising</a></strong><br />
	&nbsp;<strong><a href="/affiliates">Affiliates</a></strong><br />
	&nbsp;<strong><a href="/parties">Home Parties</a></strong><br />
	&nbsp;<strong><a href="/docs/2257">18 U.S.C Section 2257 Compliance Notice</a></strong><br />
	</ul>
  </p>
</div>







