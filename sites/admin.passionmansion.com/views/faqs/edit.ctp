<?php echo $this->renderElement('addon_tinymce'); ?>

<h2><a href="/faqs">FAQs</a> &raquo; Add</h2>
<?php
echo $form->create('Faq', array('action' => 'add'));
echo $form->input('Faq.id', array('type'=>'hidden')); 
echo $form->input('Faq.faq_category_id', array('label' => 'Category', 'style' => 'width: 250px'));
echo $form->input('Faq.title', array('label' => 'Question', 'style' => 'width: 350px'));
echo $form->input('Faq.body', array('label' => 'Answer', 'type' => 'textarea', 'cols' => '60', 'rows' => '10'));
echo $form->submit();
echo $form->end();
?>