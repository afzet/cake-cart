<?php
/**
 * SVN FILE: $Id: nav_top.ctp 122 2008-08-19 12:29:24Z jonathan $
 *
 * Element Nav Top View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 122 $
 * Last Modified: $Date: 2008-08-19 08:29:24 -0400 (Tue, 19 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
?>
<div id="middlebar">
	<ul class="nav">
		<li><a href="/"> Home</a></li>
	</ul>	
	<ul class="nav">
		<li><a href="/parties"> Home Parties</a></li>
	</ul>	
	<ul class="nav">
		<li><a href="/affiliates"> Affiliates</a></li>
	</ul>	
	<ul class="nav">
		<li><a href="/cart"> My Cart</a></li>
	</ul>	
	<ul class="nav">
		<li><a href="/orders/track"> Order Tracking</a></li>
	</ul>
	<ul class="nav">
		<li><a href="/contact"> Contact Us</a></li>
	</ul>
		
	<ul class="nav">
		<li><a href="/faqs"> FAQ</a></li>
	</ul>
	
	<ul class="nav">
		<li class="search">
	        <form style="margin: 0pt;" action="/product/search" method="post">
		    <table border="0" cellspacing="0" cellpadding="0">
		        <tr>
		            <td valign="top">
		                &nbsp;<input style="padding:2px; font-size: 11px;" type="text" name="mainkeyword" onfocus="clearField(this)" value="Search Our Site" />
	                </td>
		            <td valign="middle">
		                &nbsp;<input style="margin: 5px 0 5px 0;" type="image" src="/img/buttons/search.gif" value="Search" id="some_name" />		                
	                </td>		        
	            </tr>
		    </table>
	        </form>
		</li>
	</ul>
</div>
