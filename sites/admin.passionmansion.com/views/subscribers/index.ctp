
<h2>Newsletter Subscribers </h2>

<?php
if ($session->check('Message.flash')) {
	$session->flash();
}
?>
<table class="top">	
	<tr>
		<td colspan="5" class="count">Showing Page <?php echo $paginator->counter()?></td>
		<td colspan="5" class="add_row">
			<?php echo $html->link('Add Subscriber','/subscribers/add')?> | 	
			<?php echo $html->link('Bulk Upload','/subscribers/bulk')?> 	
		</td>
	</tr>
</table>
<table>	
	<?php echo $html->tableHeaders(
			array(
				$paginator->sort('ID', 'id'), 
				$paginator->sort('Email', 'email'),
				$paginator->sort('Created', 'created'),
				'Edit',
				'Delete'
				)
			);
	$i=1; 
	foreach($data as $key=>$value) 
	{
		echo $html->tableCells(
			array(
				$value['Subscriber']['id'],
				array($value['Subscriber']['email'], array('class'=>'info')),
				$value['Subscriber']['created'],
				$html->link($html->image('icons/edit.png'),'/subscribers/edit/'.$value['Subscriber']['id'], null, null, false),
				$html->link($html->image('icons/delete.png'),'/subscribers/delete/'.$value['Subscriber']['id'], null, 'Are you sure?', false),
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