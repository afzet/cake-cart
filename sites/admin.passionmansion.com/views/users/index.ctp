
<h2>Users </h2>

<?php
if ($session->check('Message.flash')) {
	$session->flash();
}
?>
<table class="top">	
	<tr>
		<td colspan="5" class="count">Showing Page <?php echo $paginator->counter()?></td>
		<td colspan="5" class="add_row"><?php echo $html->link('Add New User','/users/add')?></td>
	</tr>
</table>
<table>	
	<?php echo $html->tableHeaders(
			array(
				$paginator->sort('Id'), 
				$paginator->sort('First Name', 'fname'),
				$paginator->sort('Last Name', 'lname'),
				$paginator->sort('Email', 'email'),
				$paginator->sort('Group', 'name'),
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
				$value['User']['id'],
				$value['User']['fname'],
				$value['User']['lname'],
				$html->link($value['User']['email'],'mailto:'.$value['User']['email'].''),
				$value['Group']['name'],
				$value['User']['created'],
				$html->link($html->image('icons/edit.png'),'/users/edit/'.$value['User']['id'], null, null, false),
				$html->link($html->image('icons/delete.png'),'/users/delete/'.$value['User']['id'], null, 'Are you sure?', false),
			),
			null,
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