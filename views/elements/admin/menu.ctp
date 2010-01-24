<?php 
if(($active = $session->read('Auth.User.active')) == 1) { ?>
<div class="chromestyle" id="chromemenu">
	<ul>
		<li><a href="/">Home</a></li>
		<li><?php echo $html->link('Settings', array('controller' => 'settings')); ?></li>
		<li><a href="#" rel="users">Users</a></li>
		<li><a href="#" rel="inventory">Inventory</a></li>
		<li><a href="#" rel="faqs">FAQ's</a></li>	
		<li><?php echo $html->link('Orders', array('controller' => 'orders')); ?></li>
		<li><?php echo $html->link('Posts', array('controller' => 'posts')); ?></li>	
		<li><?php echo $html->link('Promo Codes', array('controller' => 'promos')); ?></li>
		<li><?php echo $html->link('Contact Requests', array('controller' => 'contacts')); ?></li>
		<li><a href="#" rel="newsletters">Newsletters</a></li>
		<li><a href="/calculator">Calculator</a></li>	
		<li><?php echo $html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?></li>
	</ul>
</div>


<!--Users drop down menu -->                                                   
<div id="users" class="dropmenudiv">
	<?php echo $html->link('Users', array('controller' => 'users')); ?>
	<?php echo $html->link('Groups', array('controller' => 'groups')); ?>
	<?php echo $html->link('Affiliates', array('controller' => 'affiliates')); ?>
</div>


		
<!--Inventory drop down menu -->                                                   
<div id="inventory" class="dropmenudiv">
	<?php echo $html->link('Categories', array('controller' => 'categories')); ?>
	<?php echo $html->link('Products', array('controller' => 'products')); ?>
</div>

<!--FAQ drop down menu -->                                                   
<div id="faqs" class="dropmenudiv">
	<?php echo $html->link('Categories', array('controller' => 'faq_categories')); ?>
	<?php echo $html->link('Questions', array('controller' => 'faqs')); ?>
</div>

<!--Newsletters drop down menu -->                                                   
<div id="newsletters" class="dropmenudiv">
	<?php echo $html->link('Subscriptions', array('plugin' => 'newsletter', 'controller' => 'subscriptions')); ?>
	<?php echo $html->link('Groups', array('plugin' => 'newsletter', 'controller' => 'groups')); ?>
	<?php echo $html->link('Send Newsletter', array('plugin' => 'newsletter', 'controller' => 'mails')); ?>
</div>


<script type="text/javascript">
	cssdropdown.startchrome("chromemenu")
</script>
<? } ?>