
<h2><?php echo $html->link('products'); ?> &raquo; Add New</h2>
<?php
echo $form->create('Product', array('enctype' => 'multipart/form-data'));

echo $form->input('Product.category_id', array('type' => 'select', 'options'=>$categories));
echo $form->input('Product.name');
echo $form->input('Product.desc', array('label' => 'Description'));
echo $form->input('Product.image', array('type' => 'file'));
echo $form->input('Product.model');
echo $form->input('Product.upc');
echo $form->input('Product.cost');
echo $form->input('Product.price');
echo $form->input('Product.length');
echo $form->input('Product.width');
echo $form->input('Product.height');
echo $form->input('Product.weight');
echo $form->input('Product.manufacturer');
echo $form->input('Product.featured');
echo $form->input('Product.status');
echo $form->submit();
echo $form->end();
?>
