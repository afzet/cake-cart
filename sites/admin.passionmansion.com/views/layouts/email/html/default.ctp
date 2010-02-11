<?php
/**
 * SVN FILE: $Id: default.ctp 66 2008-08-08 02:31:37Z jonathan $
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 66 $
 * Last Modified: $Date: 2008-08-07 21:31:37 -0500 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Order Confirmation</title>
		<style type="text/css" media="screen">
			* { padding: 0; margin: 0; }
			body { margin: 0px; font-size: 10px; font-family: verdana;   }
			table.tracking { background: #fff;border-right:0;clear: both;color: #333;margin-bottom: 10px;width: 100%; }
			th { background: #f2f2f2;border:1px solid #bbb;border-top: 1px solid #fff;border-left: 1px solid #fff;text-align: center; }
			th a { background:#f2f2f2;display: block;padding: 2px 4px;text-decoration: none; }
			th a:hover { background: #ccc;color: #333;text-decoration: none; }
			td { padding: 10px;padding: 4px; }
			table.tracking tr td { background: #fff;padding: 4px;vertical-align: top;text-align:center; }
			#main { margin: 0 auto; border: 0px; padding: 5px; width: 726px }
			.info p { margin-bottom: 10px }
			ol { padding: 10px 0; }
			li { margin-left: 40px; padding: 5px; }
		</style>
	</head>
	<body>

		<table border="0" cellspacing="0" cellpadding="0" id="main">
			<tr><td><img src="http://beta.passionmansion.com/img/email/header.gif" alt="passionmansion.com" /></td></tr>
			<tr>
				<td class="info">
					<?php echo $content_for_layout; ?>
				<td>
			</tr>
			<tr><td><img src="http://beta.passionmansion.com/img/email/footer.gif" alt="let us help you find your passion" /></td></tr>
		</table>
	</body>
</html>