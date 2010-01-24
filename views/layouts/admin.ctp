<?php
/* SVN FILE: $Id: default.ctp 66 2008-08-08 02:31:37Z jonathan $ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.cake.libs.view.templates.layouts
 * @since			CakePHP(tm) v 0.10.0.1076
 * @version			$Revision: 66 $
 * @modifiedby		$LastChangedBy: jonathan $
 * @lastmodified	$Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>
		<?php __('System Administration:'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $html->meta('icon');
		echo $html->css(array('admin/admin','admin/chromestyle'));
	?>		
	<script type="text/javascript" src="/js/admin/jquery-1.2.6.js"></script>
	<script type="text/javascript" src="/js/admin/jquery.jeditable.js"></script>
	<script type="text/javascript" src="/js/admin/application.js"></script>
	<script type="text/javascript" src="/js/admin/chrome.js"></script>
	<script type="text/javascript" charset="utf-8">		function goto_URL(object) { 
			window.location.href = object.options[object.selectedIndex].value; 
		}
		function redirect(object) { 
			window.location.href = object; 
		}
	</script>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>System Administration</h1>
		</div>
		<?php echo $this->element('admin/menu')?>
		<div id="content">
			<?php echo $content_for_layout; ?>


		</div>
		
		<div id="footer">
			Copyright &copy; 2008 Passion Mansion, Inc.
		</div>
	</div>
	<?php echo $cakeDebug; ?>
</body>
</html>
