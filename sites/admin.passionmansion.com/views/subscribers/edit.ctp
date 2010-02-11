
<h2><a href="/subscribers">Newsletter Subscribers</a> &raquo; Edit</h2>
<?php
echo $form->create('Subscribers', array('action' => 'edit'));
echo $form->input('Subscriber.id', array('type'=>'hidden')); 
echo $form->input('Subscriber.email', array('label' => 'Email Address', 'style' => 'width: 300px'));
echo $form->submit();
echo $form->end();
?>