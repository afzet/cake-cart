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

<?php echo$this->element('frontend/modules/livezilla/live_support');?>
<?php echo$this->element('frontend/blocks/categories', array('navCats' => $navCats));?>
<?php echo$this->element('frontend/blocks/subscribe', array('plugin' => 'newsletter'));?>
<?php echo$this->element('frontend/blocks/extras');?>
<?php echo$this->element('frontend/blocks/misc');?>