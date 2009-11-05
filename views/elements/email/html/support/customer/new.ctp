<?php
/**
 * SVN FILE: $Id: new.ctp 58 2008-08-08 01:51:02Z jonathan $
 *
 * Element Email HTML Support Ticket Customer Notifcation Template
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
?>
<pre>
Thank you for your inquiry. A new ticket has been created.

You wrote:
--------------------------------------------------------------------
Ticket #: <?=$data['Ticket']['id']?>

Name: <?=$data['Ticket']['customer']?>

Message: <?=nl2br($data['Ticket']['body'])?>

-------------------------------------------------------------------- 

Customer Service Department
Passion Mansion
http://www.passionmansion.com
</pre>