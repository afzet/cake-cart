<?php
/**
 * SVN FILE: $Id: countries.ctp 141 2008-08-19 13:32:57Z jonathan $
 * @copyright		Copyright 2008, Passion Mansion, Inc.
 * @version			$Revision: 141 $
 * Last Modified: $Date: 2008-08-19 08:32:57 -0500 (Tue, 19 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
?>
			<form action="" method="get">
<div class="input text">
<label>Select a Country to ship to</label>
<select onchange="goto_URL(this.form.country)" name="country" class="smaller">
	<option value=""> Select Country</option>
	<?php
	echo '<Pre>'; print_r($countries); die;
	foreach ($countries as $country) {
		echo '<option value="';
		echo $html->url(array('controller' => 'calculator', 'action' => 'add_country', $country['Country']['name']));
		echo '">';
		echo $country['Country']['name'] .'</option>';
	}
	?>
</select>
</div>
		</form>