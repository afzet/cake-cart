<div id="report_form" class="report_form">

<table>
<tr>
	<td valign="top">
	
	<form action="/<?= $cur_controller; ?>/createReport/" method="post">

	<fieldset>
	<legend style="background: #E51336;">Saved Reports</legend>
	<table class="report_small">
	<tr>
		<td><?php echo $html->selectTag('Misc/saved_reports', $existing_reports);  ?></td>
		<td><input type="submit" name="existing" value="Pull" style="font: normal normal bold 8pt arial; color: #FFFFFF; background: #0066CC;"></td>
		<td><input type="submit" name="existing" value="Delete" style="font: normal normal bold 8pt arial; color: #FFFFFF; background: #E51336;" onclick="return confirm('Are you sure you want to delete this report?')"></td>
	</tr>
	</table>
	</fieldset>

	</form>

	</td>
	<td width="20"></td>
	<td valign="top">
	
	<form action="/<?= $cur_controller; ?>/createReport/" method="post">

	<fieldset>
	<legend style="background: #E51336;">New Report</legend>
	<table class="report_small">
	<tr>
		<td>Report Name</td>
		<td><input type="text" name="report_name" style="width: 180px;"></td>
	</tr>
	<tr>
		<td></td>
		<td>* Field required if saving report</td>
	</tr>
	</table>
	</fieldset>

	</td>
</tr>
</table>


<? foreach ($report_form as $key => $value): ?>
<? if($key!='associated_table') { ?>

	<div style="height: 15px;"><!-- Spacer --></div>

	<fieldset>
	<legend><?= $key; ?> Table</legend>
	
	<table class="report">
	<tr class="header">
		<td>Field</td>
		<td>Display Name</td>
		<td style="text-align: center;">Priority</td>
		<td style="text-align: center;">Sort By Primary</td>
		<td style="text-align: center;">Sort By Secondary</td>
		<td style="text-align: center;">Include</td>
	</tr>
		
	<? for ($i=0; $i<count($value); $i++) { ?>

	<tr class="body" onClick="if($('<?= $key; ?><?= $i; ?>').checked == true){ this.className='body_selected'; } else { this.className='body'; }">
		<td>
		
		<?= $value[$i]; ?><input type="hidden" name="data[<?= $key; ?>][<?= $value[$i] ;?>][field_name]" value="<?= $value[$i]; ?>"><input type="hidden" name="data[<?= $key; ?>][<?= $value[$i] ;?>][model]" value="<?= $key; ?>"><input type="hidden" name="data[<?= $key; ?>][<?= $value[$i] ;?>][associated_table]" value="<? if(!empty($table_data['associated_table'][$key])) { echo $table_data['associated_table'][$key]; } ?>">	
		
		</td>
		<td>
		
		<input type="text" name="data[<?= $key; ?>][<?= $value[$i] ;?>][display_name]" onFocus="if($('<?= $key; ?><?= $i; ?>').checked == false){ this.className='body_selected'; $('<?= $key; ?><?= $i; ?>').checked = true; }"></td>
		<td style="text-align: center;"><input type="text" name="data[<?= $key; ?>][<?= $value[$i] ;?>][priority]" style="width: 50px;" onFocus="if($('<?= $key; ?><?= $i; ?>').checked == false){ this.className='body_selected'; $('<?= $key; ?><?= $i; ?>').checked = true; }">
		
		</td>
		<td style="text-align: center;"><input type="radio" name="order_by_primary" value="<?= $key; ?>.<?= $value[$i]; ?>"></td>
		<td style="text-align: center;"><input type="radio" name="order_by_secondary" value="<?= $key; ?>.<?= $value[$i]; ?>"></td>
		<td style="text-align: center;"><input type="checkbox" id="<?= $key; ?><?= $i; ?>" name="data[<?= $key; ?>][<?= $value[$i] ;?>][include]"></td>
	</tr>

	<? } ?>
	
	</table>
	</fieldset>
	
<? } ?>
<?php endforeach; ?>

<div style="height: 15px;"><!-- Spacer --></div>

<table cellspacing="0" cellpadding="0">
<tr>
	<td><input type="submit" name="submit" value="Create Report"></td>
	<td width="10"></td>
	<td><input type="submit" name="submit" value="Create And Save Report"></td>
</tr>
</table>

</form> 
</div>







