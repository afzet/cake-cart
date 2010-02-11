
<h2>FAQs </h2>

<?php
if ($session->check('Message.flash')) {
	$session->flash();
}
?>
<table class="top">	
	<tr>
		<td colspan="5" class="count">Showing Page <?php echo $paginator->counter()?></td>
		<td colspan="5" class="add_row"><?php echo $html->link('Add New FAQ','/faqs/add')?></td>
	</tr>
</table>
<table>	
	<?php echo $html->tableHeaders(
			array(
				$paginator->sort('Id'), 
				$paginator->sort('Category', 'faq_category_id'),
				$paginator->sort('Question', 'title'),
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
				$value['Faq']['id'],
				$value['FaqCategory']['name'],
				$value['Faq']['title'],
				$value['Faq']['created'],
				$value['Faq']['modified'],
				$html->link($html->image('icons/edit.png'),'/faqs/edit/'.$value['Faq']['id'], null, null, false),
				$html->link($html->image('icons/delete.png'),'/faqs/delete/'.$value['Faq']['id'], null, 'Are you sure?', false),
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