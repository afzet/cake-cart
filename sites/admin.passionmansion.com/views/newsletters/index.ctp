
<h2>Newsletters </h2>

<?php
if ($session->check('Message.flash')) {
	$session->flash();
}
?>
<table class="top">	
	<tr>
		<td colspan="5" class="count">Showing Page <?php echo $paginator->counter()?></td>
		<td colspan="5" class="add_row"><?php echo $html->link('Compose Newsletter','/newsletters/compose')?></td>
	</tr>
</table>
<table>	
	<?php echo $html->tableHeaders(
			array(
				$paginator->sort('ID', 'id'), 
				$paginator->sort('Topic', 'topic'),
				$paginator->sort('Sent By', 'user_id'),
				$paginator->sort('Sent On', 'created'),
				'Delete'
				)
			);
	$i=1; 
	foreach($data as $key=>$value) 
	{
		echo $html->tableCells(
			array(
				$value['Newsletter']['id'],
				$value['Newsletter']['topic'],
				$value['User']['username'],
				$value['Newsletter']['created'],
				$html->link($html->image('icons/delete.png'),'/newsletters/delete/'.$value['Newsletter']['id'], null, 'Are you sure?', false),
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