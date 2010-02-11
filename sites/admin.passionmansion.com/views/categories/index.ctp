
<h2>Categories </h2>

<?php
if ($session->check('Message.flash')) {
	$session->flash();
}
?>
<table class="top">	
	<tr>
		<td colspan="5" class="count">Showing Page <?php echo $paginator->counter()?></td>
		<td colspan="5" class="add_row"><?php echo $html->link('Add New Category','/categories/add')?></td>
	</tr>
</table>
<table>	
	<?php echo $html->tableHeaders(
			array(
				$paginator->sort('Id'), 
				$paginator->sort('Category', 'name'),
				$paginator->sort('Created', 'created'),
				'Edit',
				'Delete'
				)
			);
	$i=1; 
	foreach($data as $key=>$value) 
	{
		$view = '/categories/child/'.$value['Category']['id'].'/'.urlencode($value['Category']['name']);
		echo $html->tableCells(
			array(
				$value['Category']['id'],
				$value['Category']['name'],
				$value['Category']['created'],
				$html->link($html->image('icons/edit.png'),'/categories/edit/'.$value['Category']['id'], null, null, false),
				$html->link($html->image('icons/delete.png'),'/categories/delete/'.$value['Category']['id'], null, 'Are you sure?', false),
			),
			array('class'=>'row', 'onClick'=>"redirect('$view')"),
			array('class'=>'altrow', 'onClick'=>"redirect('$view')")
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