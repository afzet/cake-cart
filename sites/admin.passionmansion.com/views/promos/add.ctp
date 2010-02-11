
<h2><a href="/promos">Promo Codes</a> &raquo; Add</h2>

<?
if ($session->check('Message.flash')) {
	$session->flash();
}
?>
<?php
echo $form->create('Promo');
echo $form->input('Promo.code', array('label'=>'Code'));
echo $form->input('Promo.type', array('options'=>array(1=>'Percent',0=>'Money')));
echo $form->input('Promo.amount', array('label'=>'Amount of Discount'));
echo $form->input('Promo.min_purchase', array('label'=>'Minimum Purchase Amount'));
echo $form->input('Promo.limit_max', array('label'=>'Max # times code can be used','between' => '<span style="color: red" class="field_info">X = N/A</span><br />'));
echo $form->input('Promo.skip_date',array('type'=>'checkbox','label'=>'Check if you wish to last forever'));
echo $form->input('Promo.date_start', array('type'=>'date','label'=>'Start Date'));
echo $form->input('Promo.date_end', array('type'=>'date','label'=>'End Date'));
echo $form->submit();
echo $form->end();
?>