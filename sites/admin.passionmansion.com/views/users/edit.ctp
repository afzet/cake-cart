
<h2><a href="/users">Users</a> &raquo; Edit</h2>
<?php
echo $form->create('User', array('action' => 'edit'));
echo $form->input('User.id', array('type'=>'hidden')); 
echo $form->input('User.fname', array('label' => 'First Name', 'style' => 'width: 130px'));
echo $form->input('User.lname', array('label' => 'Last Name', 'style' => 'width: 130px'));
echo $form->input('User.email', array('label' => 'Email', 'style' => 'width: 280px'));
echo $form->input('User.username', array('label' => 'Username', 'style' => 'width: 130px'));
echo $form->input('User.group_id', array('0'=>'Member', '1'=>'Admin'), array('label' => 'Group'));
echo $form->input('User.active', array('0'=>'In-Active', '1'=>'Active'), array('label' => 'Status'));
echo $form->submit();
echo $form->end();
?>