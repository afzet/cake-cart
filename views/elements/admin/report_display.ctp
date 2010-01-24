<div id="report_display" class="report_display">

<div class="report_name"><?= $report_name ?></div>
<div class="report_date_stamp">Report run on <?= date('m/d/Y'); ?></div>
<div style="height: 25px;"></div>

<table class="report">
<tr class="header">

<? foreach ($report_fields as $field): ?>

<td><? echo ($field['display_name']=='' ? $field['field_name'] : $field['display_name']); ?></td>

<?php endforeach; ?>

</tr>


<? for($i=0; $i<count($report_data); $i++) { ?>

<tr class="body">

<? foreach ($report_fields as $field): ?>

<td>

<?
	//Check to see if associated table is being used
	if(!empty($report_data[$i][$field['associated_table']][$field['model']][$field['field_name']])) {
		echo $report_data[$i][$field['associated_table']][$field['model']][$field['field_name']]; 
	}
	else if(!empty($report_data[$i][$field['model']][$field['field_name']])) {
		echo $report_data[$i][$field['model']][$field['field_name']]; 
	}
?>
	
</td>

<?php endforeach; ?>

</tr>

<? } ?>
</table>

</div>







