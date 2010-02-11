
<h2><a href="/groups">User Groups</a> &raquo; Edit</h2>
<?php
echo $form->create('Group', array('action' => 'edit'));
echo $form->input('Group.id', array('type'=>'hidden')); 
echo $form->input('Group.name', array('label' => 'Group Name', 'style' => 'width: 130px'));
echo $form->submit();
echo $form->end();
?>