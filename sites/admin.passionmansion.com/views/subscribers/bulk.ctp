
<h2><a href="/subscribers">Newsletter Subscribers</a> &raquo; Bulk Upload</h2>
<?php
echo $form->create('Subscribers', array('action' => 'bulk_upload','type' => 'file'));
echo $form->input('Newsletter.csv_upload', array('type'=>'file', 'label' => 'Select CSV File', 'style' => 'width: 130px'));
echo $form->submit();
echo $form->end();
?>

<pre>
File format must be in .csv (Comma Seperated Values) 

Format for each each line is as follows:
name,email

<strong style="color: red;">DO NOT INCLUDE ANYTHING ELSE</strong>
</pre>