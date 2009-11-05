<?php
/**
 * SVN FILE: $Id: notify.ctp 58 2008-08-08 01:51:02Z jonathan $
 *
 * Element Email HTML Support Ticket Staff Notifcation Template
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
<strong>--------------------------------------------------------------------
                        NEW SUPPORT TICKET
--------------------------------------------------------------------</strong>

<strong>Ticket #:</strong> <?=$data['Ticket']['id']?>

<strong>Name:</strong>     <?=$data['Ticket']['customer']?>

<strong>Message:</strong>  <?=nl2br($data['Ticket']['body'])?>


<strong>--------------------------------------------------------------------
                        NEW SUPPORT TICKET
--------------------------------------------------------------------</strong>
</pre>