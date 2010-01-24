<?php
/**
 * SVN FILE: $Id: confirmation.ctp 302 2008-08-31 06:45:39Z jonathan $
 *
 * Element Email HTML Newsletter Confirmation Template
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 302 $
 * Last Modified: $Date: 2008-08-31 02:45:39 -0400 (Sun, 31 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
?>
<pre>
Sign-up Date: <?=date('m-d-Y');?>


--------------------------------------------------------------------
Full Name: <?=$data['Affiliate']['contact_name']?>

Customer Number: <?=$data['Affiliate']['customer_code']?>

Affiliate Code: <?=$data['Affiliate']['aff_code']?>

Password: <?=$data['Affiliate']['password']?>

Validation Code: <?=$data['Affiliate']['verification']?>

-------------------------------------------------------------------- 

Please visit now https://passionmansion.com/affiliates/confirm in order to verify your account.

To log in go to http://www.passionmansion.com/affiliates.html
 
If you have any further questions please go to http://www.passionmansion.com and then click on "contact us"
</pre>