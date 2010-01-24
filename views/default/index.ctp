<?php
/**
 * SVN FILE: $Id: index.ctp 167 2008-08-26 17:42:10Z jonathan $
 *
 * Default Index View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 167 $
 * Last Modified: $Date: 2008-08-26 13:42:10 -0400 (Tue, 26 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
?>
<div style="background-color: rgb(252, 214, 196); height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td title="Top Vibrators at PassionMansion.com">
				<div style="font-size: 16px; font-family: Arial; color: rgb(243, 107, 43); font-weight: bold;">
					News & Updates
				</div>				
			</td>
		</tr>
	</table>
</div>
<br />
<table border="0" cellspacing="5" cellpadding="5" style="border: 0;" width="100%">
	<tr>
		<td>
		<p><strong><?=$post[0]['Post']['title'];?></strong></p>
		<br />
		<p><?=$post[0]['Post']['body'];?></p>
		</td>
	</tr>
</table>
<br />
<div style="background-color: rgb(252, 214, 196); height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td title="Top Vibrators at PassionMansion.com">
				<div style="font-size: 16px; font-family: Arial; color: rgb(243, 107, 43); font-weight: bold;">
					Featured Vibrators at Passion Mansion
				</div>				
			</td>
		</tr>
	</table>
</div>
<br />
<table border="0" cellspacing="5" cellpadding="5" style="border: 0;" width="100%">
	<tr>
		<td>
		<?=$session->read('Settings.front_vibrators');?>
		</td>
	</tr>
</table>
<?=$this->renderElement('product_view', array('data' => $vibrators));?>
	
<!--
<div style="background-color: rgb(252, 214, 196); height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td title="Top Vibrators at PassionMansion.com">
				<div style="font-size: 16px; font-family: Arial; color: rgb(243, 107, 43); font-weight: bold;">
					Best Sellers at Passion Mansion
				</div>				
			</td>
		</tr>
	</table>
</div>
<br />
<table border="0" cellspacing="5" cellpadding="5" style="border: 0;" width="100%">
	<tr>
		<td>
		<?=$session->read('Settings.best_sellers');?>
		</td>
	</tr>
</table>
<?=$this->renderElement('product_view', array('data' => $featured));?>
<br />
-->
<div style="background-color: rgb(252, 214, 196); height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td title="Video Items at Passion Mansion">
				<div style="font-size: 16px; font-family: Arial; color: rgb(243, 107, 43); font-weight: bold;">
					Video Items at Passion Mansion
				</div>				
			</td>
		</tr>
	</table>
</div>
<br />
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center">
				<a href="http://sextoy.private.camz.com/z/index/default/emisc/passionmansion" target="_blank"><img border="0" src="/img/liveanim.gif"/></a>
				<a href="http://vod.sextoytv.com?a=passionmansion" target="_blank"><img border="0" src="/img/vod_sm.gif"/></a>
				<a href="/category/view/1/Movies" target="_blank"><img border="0" src="/img/vodanim.gif"/></a>
		</td>
	</tr>
</table>
<br />
<div style="background-color: rgb(252, 214, 196); height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td title="Newest Vibrators at PassionMansion.com">
				<div style="font-size: 16px; font-family: Arial; color: rgb(243, 107, 43); font-weight: bold;">
					Featured Videos at Passion Mansion
				</div>				
			</td>
		</tr>
	</table>
</div>
<br />
<table border="0" cellspacing="5" cellpadding="5" style="border: 0;" width="100%">
	<tr>
		<td>
		<?=$session->read('Settings.most_popular');?>
		</td>
	</tr>
</table>
<?=$this->renderElement('product_view', array('data' => $featured));?>
