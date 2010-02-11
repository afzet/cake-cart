<?php echo $this->renderElement('addon_tinymce'); ?>

<h2><a href="/newsletters">Newsletters</a> &raquo; Compose Newsletter</h2>
<?php
echo $form->create('Newsletter', array('url' => '/newsletters/compose'));
echo $form->input('topic', array('label' => '<strong>Subject of News Letter</strong>', 'style' => 'width: 600px'));
echo $form->input('message', array('label' => '', 'type' => 'textarea', 'style' => 'width: 600px; height:500px'));
echo $form->submit();
echo $form->end();
?>