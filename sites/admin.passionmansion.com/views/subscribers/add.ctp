
<h2><a href="/subscribers">Newsletter Subscribers</a> &raquo; Add</h2>
<?php
echo $form->create('Subscribers', array('action' => 'add'));
echo $form->input('Subscriber.email', array('label' => 'Subscriber Email', 'style' => 'width: 130px'));
echo $form->submit();
echo $form->end();
?>