
<div style="background-color: rgb(252, 214, 196); height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: rgb(243, 107, 43); font-weight: bold;">
					Here are some sample products for Home Parties
				</div>				
			</td>
		</tr>
	</table>
</div>
<br />
<?=$this->renderElement('pagnation');?>
<?=$this->renderElement('party_product_view', array('data' => $data));?>
<?=$this->renderElement('pagnation');?>