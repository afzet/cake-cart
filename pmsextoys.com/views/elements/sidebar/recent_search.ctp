
<div style=" width: 100%; background-color: #FDD0E2; height: 29px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: #F75696; font-weight: bold;">
					Recent Searches
				</div>				
			</td>
		</tr>
	</table>
</div>
<br />
<table width="90%" cellspacing="0" cellpadding="4" style="margin-left: 15px">
<? 

foreach ($searched as $item):
	echo '<tr>
		<td width="16">'. $html->image('icons' . DS . 'search.png') .'</td>
		<td style="padding: 5px; vertical-align: middle"> ' . $item . '</a></td></tr>';
endforeach; 
?>
</table>
<br />