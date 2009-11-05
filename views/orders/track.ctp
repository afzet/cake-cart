<?php
/**
 * SVN FILE: $Id: track.ctp 470 2008-09-01 04:31:43Z jonathan $
 *
 * Order Track View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 470 $
 * Last Modified: $Date: 2008-09-01 00:31:43 -0400 (Mon, 01 Sep 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
 ?>
<div style="background-color: rgb(252, 214, 196); height: 28px; width: auto; padding-left: 12px; padding-right: 12px; padding-top: 6px;">
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div style="font-size: 16px; font-family: Arial; color: rgb(243, 107, 43); font-weight: bold;">
					Track your order
				</div>				
			</td>
			<td align="right">&nbsp;</td>
		</tr>
	</table>
</div>
<div style="padding: 10px;">
<h3><strong>Please fill out the form in order to track your package.</strong></h3>
<br />
	<form method="post" action="http://www.sextoyclub.com/pages/start/ordertracker.html">
<?php
echo $form->hidden('WSUSERID', array('value' => '135491-7194','name'=>'WSUSERID'));
echo $form->hidden('dropship_id', array('value' => 'lmz','name'=>'dropship_id'));
echo $form->hidden('returnUrl', array('value' => 'https://passionmansion.com/orders/track','name'=>'returnUrl'));
echo $form->input('INVNO', array('class'=>'contact','label' => 'Order Number', 'style' => 'width: 130px','name'=>'INVNO'));
echo $form->input('zipCode', array('class'=>'contact','label' => 'Zip Code', 'style' => 'width: 130px','name'=>'zipCode'));
echo $form->submit('buttons/submit.gif');
echo $form->end();
?>
</div>
<? 
if(!empty($data['tracking']['number'])) { 
	
$info = $data['tracking'];
	?>
<br />
<div style="padding: 10px;">
<h3><strong>Invoice#: <?=$info['number']?></strong></h3>
<br />
<table class="tracking">	
<?
if($info['status']!='In Process') {
	$headers = array('Carrier','Tracking #','Ship Date','Message');
	$fields  = array($info['carrier'],$info['track'],$info['shipDate'],$info['msg']);
} else {
	$headers = array('Ship Date','Status','Message');
	$fields  = array('TBD',$info['status'],$info['msg']);
}
echo $html->tableHeaders($headers);
echo $html->tableCells($fields);
	
?>
</table>
</div>
<? } ?>