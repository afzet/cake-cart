
<h2>User Groups </h2>

<?

if ($session->check('Message.flash')) {
	$session->flash();
}
?>
<table class="top">	
	<tr>
		<td colspan="5" class="count">Showing Page <?=$paginator->counter()?></td>
		<td colspan="5" class="add_row"><a href="/groups/add">Add New Group</a></td>
	</tr>
</table>
<table>	
	<?php echo $html->tableHeaders(
			array(
				$paginator->sort('Group ID', 'id'), 
				$paginator->sort('Name', 'name'),
				$paginator->sort('Sent On', 'created'),
				'Edit',
				'Delete'
				)
			);
	$i=1; 
	foreach($data as $key=>$value) 
	{
		echo $html->tableCells(
			array(
				$value['Group']['id'],
				$value['Group']['name'],
				$value['Group']['created'],
				$html->link($html->image('icons/edit.png'),'/groups/edit/'.$value['Group']['id'], null, null, false),
				$html->link($html->image('icons/delete.png'),'/newsletters/delete/'.$value['Group']['id'], null, 'Are you sure?', false),
			),
			array('class'=>'row'),
			array('class'=>'altrow')
			);
	$i++;
	}
	?>
</table>
<?php echo $paginator->prev().'&nbsp;';?> 
<?php echo $paginator->numbers().'&nbsp;';?>
<?php echo $paginator->next()?>