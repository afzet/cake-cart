
<h2>Tickets </h2>

<?php
if ($session->check('Message.flash')) {
	$session->flash();
}
?>
<table class="top">	
	<tr>
		<td colspan="5" class="count">Showing Page <?php echo $paginator->counter()?></td>
		<td colspan="5" class="add_row"><?=$paginator->prev().'&nbsp;'.$paginator->numbers().'&nbsp;'.$paginator->next();?></td>
	</tr>
</table>
<table>	
	<?php echo $html->tableHeaders(
			array(
				$paginator->sort('Ticket #', 'id'),
				$paginator->sort('Subject', 'topic'),
				$paginator->sort('Recieved From', 'customer'),
				$paginator->sort('Status', 'status'),
				$paginator->sort('Created', 'created')
				)
			);
	$i=1; 
	foreach($data as $key=>$value) 
	{
		switch($value['Ticket']['status']) {
			case 1: $status = 'Open'; break;
			case 2: $status = 'Waiting on Customer'; break;
			case 3: $status = 'Replied'; break;
			case 4: $status = 'Closed'; break;
		}
		
		echo $html->tableCells(
			array(
				$html->link($value['Ticket']['id'],'/tickets/view/'.$value['Ticket']['id']),
				$value['Ticket']['topic'],
				$value['Ticket']['customer'],
				$status,
				$value['Ticket']['created']
			),
			array('class'=>'row'),
			array('class'=>'altrow')
			);
	$i++;
	}
	?>
</table>