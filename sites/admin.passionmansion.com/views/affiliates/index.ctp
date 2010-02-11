<?
function format_phnum($ph) {
	$num  = '(' . substr($ph,0,3) . ') ';
	$num .= substr($ph,3,3) . '-';
	$num .= substr($ph,6,4);
	return $num;
}
?>
<h2>Affiliates </h2>

<?php
if ($session->check('Message.flash')) {
	$session->flash();
}
?>
<table class="top">	
	<tr>
		<td colspan="5" class="count">Showing Page <?php echo $paginator->counter()?></td>
		<td colspan="5" class="add_row"><?php echo $html->link('Add New Affiliate','/Affiliates/add')?></td>
	</tr>
</table>
<table>	
	<?php echo $html->tableHeaders(
			array(
				$paginator->sort('Id'), 
				$paginator->sort('Affiliate Code', 'aff_code'),
				$paginator->sort('Contact Name', 'contact_name'),
				$paginator->sort('Email', 'email'),
				$paginator->sort('Phone', 'phone'),
				$paginator->sort('Created', 'created'),
				'View',
				)
			);
	$i=1; 
	foreach($data as $key=>$value) 
	{
		echo $html->tableCells(
			array(
				strtoupper($value['Affiliate']['customer_code']),
				$value['Affiliate']['aff_code'],
				$value['Affiliate']['contact_name'],
				$html->link($value['Affiliate']['email'],'mailto:'.$value['Affiliate']['email'].''),
				format_phnum($value['Affiliate']['phone']),
				$value['Affiliate']['created'],
				$html->link($html->image('icons/view.png'),'/affiliates/view/'.$value['Affiliate']['id'], null, null, false),
			),
			array('class'=>'row'),
			array('class'=>'altrow')
			);
	$i++;
	}
	?>
</table>
<?php
echo $paginator->prev().'&nbsp;';
echo $paginator->numbers().'&nbsp;';
echo $paginator->next();
?>