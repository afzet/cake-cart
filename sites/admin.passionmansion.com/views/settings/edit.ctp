
<h2><a href="/settings">Settings</a> &raquo; Edit</h2>
<?php
if ($session->check('Message.flash')) {
	$session->flash();
}
?>
<?php
echo $form->create('Setting', array('action' => 'edit'));
echo $form->input('Setting.id', array('type'=>'hidden')); 
echo $form->input('Setting.value', array('label' => 'Site '.ucfirst($setting['Setting']['id']), 'style' => 'width: 400px'));
echo $form->submit();
echo $form->end();
?>