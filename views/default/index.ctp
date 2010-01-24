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
					Newest Products
				</div>				
			</td>
		</tr>
	</table>
</div>

<?php echo $this->element('frontend/blocks/product_view', array('data' => $newest));?>
	
<div style="background-color: #fed4cb; height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td title="Top Vibrators at PassionMansion.com">
				<div style="font-size: 16px; font-family: Arial; color: #f96444; font-weight: bold;">
					Featured Products
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
<?php echo $this->element('frontend/blocks/product_view', array('data' => $featured));?>
