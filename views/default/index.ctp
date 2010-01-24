<div style="background-color: #fed4cb; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td title="Top Vibrators at PassionMansion.com">
				<div style="font-size: 16px; font-family: Arial; color: #f96444; font-weight: bold;">
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
<div style="background-color: #fed4cb; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td title="Top Vibrators at PassionMansion.com">
				<div style="font-size: 16px; font-family: Arial; color: #f96444; font-weight: bold;">
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
		<?php echo $session->read('Settings.front.vibrators');?>
		</td>
	</tr>
</table>
<?php echo $this->element('frontend/blocks/product_view', array('data' => $vibrators));?>
	
<!--
<div style="background-color: #fed4cb; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td title="Top Vibrators at PassionMansion.com">
				<div style="font-size: 16px; font-family: Arial; color: #f96444; font-weight: bold;">
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
		<?php echo $session->read('Settings.front.best_sellers');?>
		</td>
	</tr>
</table>
<?=$this->renderElement('product_view', array('data' => $featured));?>
<br />
-->
<div style="background-color: #fed4cb; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td title="Video Items at Passion Mansion">
				<div style="font-size: 16px; font-family: Arial; color: #f96444; font-weight: bold;">
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
<div style="background-color: #fed4cb; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td title="Newest Vibrators at PassionMansion.com">
				<div style="font-size: 16px; font-family: Arial; color: #f96444; font-weight: bold;">
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
		<?php echo $session->read('Settings.front.popular');?>
		</td>
	</tr>
</table>
<?php echo $this->element('frontend/blocks/product_view', array('data' => $featured));?>
