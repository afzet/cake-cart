
<h2><a href="/faq_categories">FAQ Categories</a> &raquo; Add</h2>
<?php
echo $form->create('FaqCategory', array('action' => 'add'));
echo $form->input('FaqCategory.name', array('label' => 'Category Name', 'style' => 'width: 130px'));
echo $form->submit();
echo $form->end();
?>