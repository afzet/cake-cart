<?php 

switch($order_status) {
	case "Completed": $status = 'action_check'; break;
	case "On Hold": $status = 'pause'; break;
	case "Pending":   $status = 'hourglass'; break;
}
echo $html->image('icons/'.$status.'.png', array('alt' => $order_status));
?>
