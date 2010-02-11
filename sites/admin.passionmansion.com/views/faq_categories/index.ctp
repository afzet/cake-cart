
<h2>FAQ Categories </h2>

<?php
if ($session->check('Message.flash')) {
	$session->flash();
}
?>
<table class="top">	
	<tr>
		<td colspan="5" class="count">Showing Page <?php echo $paginator->counter()?></td>
		<td colspan="5" class="add_row"><?php echo $html->link('Add New Category','/faq_categories/add')?></td>
	</tr>
</table>
<table>	
	<?php echo $html->tableHeaders(
			array(
				$paginator->sort('Id'), 
				$paginator->sort('Category', 'name'),
				'Questions',
				$paginator->sort('Created', 'created'),
				$paginator->sort('Modified', 'modified'),
				'Edit',
				'Delete'
				)
			);
	$i=1; 
	foreach($data as $key=>$value) 
	{
		echo $html->tableCells(
			array(
				$value['FaqCategory']['id'],
				$value['FaqCategory']['name'],
				count($value['Faq']),
				$value['FaqCategory']['created'],
				$value['FaqCategory']['modified'],
				$html->link($html->image('icons/edit.png'),'/faq_categories/edit/'.$value['FaqCategory']['id'], null, null, false),
				$html->link($html->image('icons/delete.png'),'/faq_categories/delete/'.$value['FaqCategory']['id'], null, 'Are you sure?', false),
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