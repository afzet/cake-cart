<?php
/**
 * SVN FILE: $Id: nav_sidebar.ctp 58 2008-08-08 01:51:02Z jonathan $
 *
 * Element Navigation Sidebar View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
?>

<?php echo $this->element('sidebar/rep'); ?>
<?php echo $this->element('sidebar/live_support');?>
<?php echo $this->element('nav_cart'); ?>
<?php echo $this->element('sidebar/categories');?>
<?php echo $this->element('sidebar/newsletter');?>
<?php echo $this->element('sidebar/extras');?>
<?php echo $this->element('sidebar/misc');?>