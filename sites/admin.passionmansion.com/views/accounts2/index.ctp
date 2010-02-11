
<h2>Login Accounts </h2>

<?

if ($session->check('Message.flash')) {
	$session->flash();
}
?>
<table class="top">	
	<tr>
		<td colspan="5" class="count">Showing Page <?=$paginator->counter()?></td>
		<td colspan="5" class="add_row"><a href="/accounts/add">Add New Account</a></td>
	</tr>
</table>
<table>	
	<?php echo $html->tableHeaders(
			array(
				$paginator->sort('Account ID', 'id'), 
				$paginator->sort('Website', 'website'),
				$paginator->sort('Username', 'username'),
				$paginator->sort('Password', 'acct_password'),
				'Edit',
				'Delete'
				)
			);
	$i=1; 
	foreach($data as $key=>$value) 
	{
		echo $html->tableCells(
			array(
				$value['Account']['id'],
				$html->link($value['Account']['website'],'http://'.$value['Account']['website'], null, null, false),
				$value['Account']['username'],
				$value['Account']['acct_password'],
				$html->link($html->image('icons/edit.png'),'/accounts/edit/'.$value['Account']['id'], null, null, false),
				$html->link($html->image('icons/delete.png'),'/accounts/delete/'.$value['Account']['id'], null, 'Are you sure?', false),
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