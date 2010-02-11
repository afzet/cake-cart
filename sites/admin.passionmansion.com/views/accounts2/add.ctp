
<h2><a href="/accounts">Accounts</a> &raquo; Add</h2>
<?php
echo $form->create('Account', array('action' => 'add'));
echo $form->input('Account.website');
echo $form->input('Account.username');
echo $form->input('Account.acct_password');
echo $form->submit();
echo $form->end();
?>