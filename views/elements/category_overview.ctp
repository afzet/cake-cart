<?php
/**
 * SVN FILE: $Id: category_overview.ctp 97 2008-08-19 09:06:49Z jonathan $
 *
 * Element Category Overview View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 97 $
 * Last Modified: $Date: 2008-08-19 05:06:49 -0400 (Tue, 19 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
?>
<table border="0" cellspacing="5" cellpadding="5" style="border: 0;" width="100%">
	<tr>
		<td class="smaller" width="100">Quick Jump:</td>
		<td colspan="12" class="smaller">
			
			 
			
			<form method="get" action="">
				<select name="movies" onChange="goto_URL(this.form.movies)">
                    <option value="">Select Category</option>
					<?
					if($category['Category']['parent_id'] == 0) $id = $category['Category']['id'];
					else $id = $category['Category']['parent_id'];
					
					echo '<option value="/category/view/'.$id.'/all">All</option>';
					
					foreach($cats as $cat) 
					{
						$cat_name = urlencode(str_replace(array('%',', ','& ','- '),'',htmlspecialchars_decode($cat['Category']['name'])));
						echo '<option value="/category/view/'.$cat['Category']['id'].'/'.$cat_name.'">
						'.$cat['Category']['name'].'
						</option>';
					}
					?>
				</select>
				<br/>
			</form>
		</td>
		<td width="15" />
	</tr>
</table>
<br />

<?=$this->renderElement('pagnation');?>
<?=$this->renderElement('product_view', array('data' => $data));?>
<?=$this->renderElement('pagnation');?>

