<h2>Tickets </h2>

<?php
if ($session->check('Message.flash')) {
	$session->flash();
}
?>
<table style="width:100%" class="tickets">	
	<?php echo $html->tableHeaders(
			array(
				'Ticket #',
				'Subject',
				'Recieved From',
				'Status',
				'Created'
				)
			);
			
		switch($data['Ticket']['status']) {
			case 1: $status = 'Open'; break;
			case 2: $status = 'Waiting on Customer'; break;
			case 3: $status = 'Replied'; break;
			case 4: $status = 'Closed'; break;
		}
		echo $html->tableCells(
			array(
				$data['Ticket']['id'],
				$data['Ticket']['topic'],
				$data['Ticket']['customer'],
				$status,
				$data['Ticket']['created']
			)
		);
	?>
</table>
<br />
<h2>Ticket Responses</h2>
<table style="width:100%" class="tickets">	
<tr><td style="border-bottom: 1px solid #c0c0c0">&nbsp;</td></tr>
<?
  echo '<tr>';
  echo '<td style="text-align: left; padding: 4px;"><strong>From:</strong> '.$data['Ticket']['customer'].' - '.$data['Ticket']['created'].'</td>';
  echo '<tr>';
  echo '</tr>';
  echo '<td style="text-align: left; padding: 4px;">
  <strong>Message:</strong> '.$data['Ticket']['body'].'</td>';
  echo '</tr>';
  echo '<tr><td style="border-bottom: 1px solid #c0c0c0">&nbsp;</td></tr>';
  echo '<tr><td style="font-size: 3px">&nbsp;</td></tr>';
foreach ($data['TicketResponse'] as $response) {
  echo '<tr>';
  echo '<td style="text-align: left; padding: 4px;"><strong>From:</strong> '.$response['from'].' - '.date('m-d-Y',$response['created']).'</td>';
  echo '<tr>';
  echo '</tr>';
  echo '<td style="text-align: left; padding: 4px;">
  <strong>Message:</strong> '.$response['body'].'</td>';
  echo '</tr>';
  echo '<tr><td style="border-bottom: 1px solid #c0c0c0">&nbsp;</td></tr>';
  echo '<tr><td style="font-size: 3px">&nbsp;</td></tr>';
}
?>
</table>
<h2>Add Comment</h2>
<?
echo $form->create('TicketResponse');
echo $form->input('TicketResponse.ticket_id', array('type'=>'hidden','value'=>$data['Ticket']['id'])); 
echo $form->input('TicketResponse.from', array('type'=>'hidden','value'=>$auth_user['User']['fname'])); 
echo $form->input('TicketResponse.body', array('class'=>'contact','label'=>'','style'=>'width: 100%'));
echo $form->submit();
echo $form->end();
?>