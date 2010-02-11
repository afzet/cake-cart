
<h2><a href="/groups">User Groups</a> &raquo; Add</h2>
<?php
echo $form->create('Group', array('action' => 'add'));
echo $form->input('Group.name', array('label' => 'Group Name', 'style' => 'width: 130px'));
echo $form->submit();
echo $form->end();
?>