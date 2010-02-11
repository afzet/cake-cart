
<h2><a href="/categories">Categories</a> &raquo; Edit</h2>
<?php
echo $form->create('Category', array('action' => 'edit'));
echo $form->input('Category.id', array('type'=>'hidden')); 
echo $form->input('Category.name', array('label' => 'Category', 'style' => 'width: 130px'));
echo $form->submit();
echo $form->end();
?>