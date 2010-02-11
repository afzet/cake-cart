<?php
/**
 * SVN FILE: $Id: index.ctp 167 2008-08-26 17:42:10Z jonathan $
 *
 * Default Index View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@PMSextoys.com>
 * @copyright Copyright 2008, PM Sextoys, Inc.
 * @version $Revision: 167 $
 * Last Modified: $Date: 2008-08-26 13:42:10 -0400 (Tue, 26 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
?>
<div style="background-color: #FDD0E2; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td title="Top Vibrators at PMSextoys.com">
				<div style="font-size: 16px; font-family: Arial; color: #F75696; font-weight: bold;">
					News &amp; Updates
				</div>				
			</td>
		</tr>
	</table>
</div>
<br />
<table border="0" cellspacing="5" cellpadding="5" style="border: 0;" width="100%">
	<tr>
		<td>
		<p><strong><?php echo $post[0]['Post']['title'];?></strong></p>
		<br />
		<p><?php echo $post[0]['Post']['body'];?></p>
		</td>
	</tr>
</table>
<br />
<div style="background-color: #FDD0E2; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td title="Top Vibrators at PMSextoys.com">
				<div style="font-size: 16px; font-family: Arial; color: #F75696; font-weight: bold;">
					Featured Vibrators at PM Sextoys
				</div>				
			</td>
		</tr>
	</table>
</div>
<br />
<table border="0" cellspacing="5" cellpadding="5" style="border: 0;" width="100%">
	<tr>
		<td>
		<?php echo $session->read('Settings.front_vibrators');?>
		</td>
	</tr>
</table>
<?php echo $this->element('product_view', array('data' => $vibrators));?>
	
<!--
<div style="background-color: #FDD0E2; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td title="Top Vibrators at PMSextoys.com">
				<div style="font-size: 16px; font-family: Arial; color: #F75696; font-weight: bold;">
					Best Sellers at PM Sextoys
				</div>				
			</td>
		</tr>
	</table>
</div>
<br />
<table border="0" cellspacing="5" cellpadding="5" style="border: 0;" width="100%">
	<tr>
		<td>
		<?php echo $session->read('Settings.best_sellers');?>
		</td>
	</tr>
</table>
<?php echo $this->element('product_view', array('data' => $featured));?>
<br />
-->
<div style="background-color: #FDD0E2; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td title="Video Items at PM Sextoys">
				<div style="font-size: 16px; font-family: Arial; color: #F75696; font-weight: bold;">
					Video Items at PM Sextoys
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
<div style="background-color: #FDD0E2; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td title="Newest Vibrators at PMSextoys.com">
				<div style="font-size: 16px; font-family: Arial; color: #F75696; font-weight: bold;">
					Featured Videos at PM Sextoys
				</div>				
			</td>
		</tr>
	</table>
</div>
<br />
<table border="0" cellspacing="5" cellpadding="5" style="border: 0;" width="100%">
	<tr>
		<td>
		<?php echo $session->read('Settings.most_popular');?>
		</td>
	</tr>
</table>
<?php echo $this->element('product_view', array('data' => $featured));?>
