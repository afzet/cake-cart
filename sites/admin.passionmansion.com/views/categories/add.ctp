
<h2><a href="/categories">Categories</a> &raquo; Add</h2>
<?php
echo $form->create('Category', array('action' => 'add'));
echo $form->input('Category.name', array('label' => 'Category', 'style' => 'width: 130px'));
echo $form->submit();
echo $form->end();
?>