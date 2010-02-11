
<h2>Promo Codes </h2>

<?
if ($session->check('Message.flash')) {
	$session->flash();
}
?>

<table class="top">	
	<tr>
		<td colspan="5" class="count">Showing Page <?=$paginator->counter()?></td>
		<td colspan="5" class="add_row"><a href="/promos/add">Add New Promo Code</a></td>
	</tr>
</table>
<table>	
	<?php echo $html->tableHeaders(
			array(
				$paginator->sort('Promo Code', 'code'),
				$paginator->sort('Amount', 'amount'),
				$paginator->sort('Min Purchase', 'min_purchase'),
				$paginator->sort('Max Uses', 'limit_max'),
				$paginator->sort('Times Used', 'used'),
				$paginator->sort('Starts', 'date_start'),
				$paginator->sort('Expires', 'date_end'),
				'Edit',
				'Delete'
				)
			);
	$i=1; 
	foreach($data as $key=>$value) 
	{
	  switch ($value['Promo']['type']) {
	    case 1: 
	      $amount = number_format($value['Promo']['amount'],0) .'%';
	      break;
	    default: 
	      $amount = '$'. number_format($value['Promo']['amount'],2);
	  }
	  
	  switch($value['Promo']['date_start']) {
	    case "X":
	      $start = 'Forever';
	      $end = 'N/A';
	    break;
	    default: 
	      $start = date('m-d-Y',$value['Promo']['date_start']);
	      $end = date('m-d-Y',$value['Promo']['date_end']);
	  }
	  
	  switch ($value['Promo']['min_purchase']) {
	    case "X": $min = 'N/A'; break;
	    default: $min = '$'. number_format($value['Promo']['min_purchase'],2);
	  }
	  
	  switch ($value['Promo']['limit_max']) {
	    case "X": $limit_max = 'N/A'; break;
	    default: $limit_max = $value['Promo']['limit_max'];
	  }
	  
	  
		echo $html->tableCells(
			array(
				$value['Promo']['code'],
				$amount,
				$min,
				$limit_max,
				$value['Promo']['used'],
				$start,
				$end,
				$html->link($html->image('icons/edit.png'),'/promos/edit/'.$value['Promo']['id'], null, null, false),
				$html->link($html->image('icons/delete.png'),'/promos/delete/'.$value['Promo']['id'], null, 'Are you sure?', false),
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