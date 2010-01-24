<div class="user">
<?php echo $form->create('User', array('action' => 'login'));?>
	<fieldset>
 		<legend>User Login</legend>
	<?php
		echo $form->input('User.username');
		echo $form->input('User.password');
		echo $form->input('remember_me', array('label' => 'Remember Me', 'type' => 'checkbox'));
	?>
	</fieldset>
<?php echo $form->end('Sign In');?>
</div>
