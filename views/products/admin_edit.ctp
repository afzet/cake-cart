

<h2><a href="/products">Products</a> &raquo; Edit</h2>
<?php
echo $form->create('Product', 					array('action' => 'edit'));
echo $form->input('Product.id', 				array('type'  => 'hidden')); 
echo $form->input('Product.product_name', 		array('label' => 'Product Name', 'div'=>'required', 'style' => 'width: 200px'));
echo $form->input('Product.product_code', 		array('label' => 'SKU#', 'div'=>'required', 'style' => 'width: 230px'));
echo $form->input('Product.category_id', 		array('label' => 'Category', 'div'=>'required', 'style' => 'width: 230px'));
echo $form->input('Product.product_vendor', 	array('label' => 'Distributed', 'style' => 'width: 230px'));
echo $form->input('Product.product_price', 		array('label' => 'Price', 'div'=>'required', 'style' => 'width: 60px'));
echo $form->input('Product.product_cost', 		array('label' => 'Cost', 'div'=>'required', 'style' => 'width: 60px'));
echo $form->input('Product.product_desc', 		array('label' => 'Description',  'div'=>'required', 'style' => 'width: 600px; height: 400px'));
echo $form->input('Product.product_vibe', 		array('label' => 'Vibration', 'style' => 'width: 230px'));
echo $form->input('Product.product_gender', 	array('label' => 'Gender(s)', 'style' => 'width: 230px'));
echo $form->input('Product.product_size', 		array('label' => 'Size', 'style' => 'width: 230px'));
echo $form->input('Product.product_color', 		array('label' => 'Color', 'style' => 'width: 230px'));
echo $form->input('Product.product_shape', 		array('label' => 'Shape', 'style' => 'width: 230px'));
echo $form->input('Product.product_body', 		array('label' => 'Body Part(s)', 'style' => 'width: 230px'));
echo $form->input('product_material', 			array('label' => 'Material(s)', 'style' => 'width: 230px'));
echo $form->input('Product.product_length', 	array('label' => 'Length (in)', 'style' => 'width: 230px'));
echo $form->input('Product.product_width', 		array('label' => 'Width (in)', 'style' => 'width: 230px'));
echo $form->input('Product.product_circum', 	array('label' => 'Circumfernce (in)', 'style' => 'width: 230px'));
echo $form->input('Product.product_powered', 	array('label' => 'Powered By', 'style' => 'width: 230px'));
echo $form->input('Product.product_features', 	array('label' => 'Features', 'style' => 'width: 230px'));
echo $form->input('Product.product_flavor', 	array('label' => 'Flavor(s)', 'style' => 'width: 230px'));
echo $form->input('Product.product_star', 		array('label' => 'Feature Porn Star(s)', 'style' => 'width: 230px'));
echo $form->input('Product.product_discount', 	array('label' => 'Max Allowed Discount', 'style' => 'width: 230px'));
echo $form->input('Product.out_of_stock', 		array('type'=>'checkbox'), array('1'=>'Out of Stock','0'=>'InStock'), array('label' => 'Out of Stock', 'style' => 'width: 230px'));
echo $form->input('Product.discontinued', 		array('type'=>'checkbox'), array('0'=>'Available','1'=>'Discontinued'), array('label' => 'Discontinued', 'style' => 'width: 230px'));
echo $form->submit();
echo $form->end();
?>