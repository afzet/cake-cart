<?php
/**
 * SVN FILE: $Id: view.ctp 167 2008-08-26 17:42:10Z jonathan $
 *
 * Products View View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 167 $
 * Last Modified: $Date: 2008-08-26 13:42:10 -0400 (Tue, 26 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */

 $product = $data['product']['Product'];
 ?>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td valign="top">
			<table cellspacing="0" cellpadding="0" border="0">				
				<tbody>
				<tr>
					<td width="12"/>
						<td valign="top">
							<table cellspacing="0" cellpadding="0" border="0">
								<tbody>
									<tr valign="top">
										<td width="100%" align="left">
											<span class="t14"><?=$product['product_name']?></span><br/>
											<span class="orange">Product #: <?=$product['product_code']?></span>
										</td>			
									</tr>			
								</tbody>		
							</table>
							
							<br/>
								<?=nl2br($product['product_desc'])?><br/>
								<br/>
								<table width="100%" cellspacing="3" cellpadding="0" border="0">		
		              <tbody>
		              	<?
		            		if (!empty($product['product_manf']))  {
		            			echo '<tr>
				              	<td valign="top" class="smaller"><strong>Manufacturer:</strong></td>
				              	<td valign="top" class="smaller">
				              	<a href="/product/search/field:product_manf/mainkeyword:'.$product['product_manf'].'">
				              		'.$product['product_manf'].'</a>
				              	</td>               		
			            		</tr>';
		            		}
		            		if (!empty($product['product_length']))  {
		            			echo '<tr>
				              	<td valign="top" class="smaller"><strong>Length:</strong></td>
				              	<td valign="top" class="smaller">
				              	'.$product['product_length'].'"
				              	</td>               		
			            		</tr>';
		            		}
		            		
		            		if (!empty($product['product_width']))  {
		            			echo '<tr>
				              	<td valign="top" class="smaller"><strong>Girth:</strong></td>
				              	<td valign="top" class="smaller">
				              	'.$product['product_width'].'"
				              	</td>               		
			            		</tr>';
		            		}
		            		
		            		if (!empty($product['product_circum']))  {
		            			echo '<tr>
				              	<td valign="top" class="smaller"><strong>Circumfrence:</strong></td>
				              	<td valign="top" class="smaller">
				              	'.$product['product_circum'].'"
				              	</td>               		
			            		</tr>';
		            		}
		            		
		            		if (!empty($product['product_size']))  {
		            			echo '<tr>
				              	<td valign="top" class="smaller"><strong>Size:</strong></td>
				              	<td valign="top" class="smaller">
				              	<a href="/product/search/field:product_size/mainkeyword:'.$product['product_size'].'">
				              		'.$product['product_size'].'</a>
				              	</td>               		
			            		</tr>';
		            		}
		            		
		            		if (!empty($product['product_power']))  {
		            			echo '<tr>
				              	<td valign="top" class="smaller"><strong>Powered By:</strong></td>
				              	<td valign="top" class="smaller">'.str_replace(' | ',', ',$product['product_power']).'</td>              		
			            		</tr>';
		            		}
		            		
		            		if (!empty($product['product_star']))  {
		            			echo '<tr>
				              	<td valign="top" class="smaller"><strong>Star:</strong></td>
				              	<td valign="top" class="smaller">';
		            			$field = explode(' | ', $product['product_star']);
		            			$str = '';
		            			foreach ($field as $value) {
		            				$str .= '
		            					<a href="/product/search/field:product_star/mainkeyword:'.$value.'">'.ucwords($value).'</a>, ';
		            			}
		            			echo substr($str, 0, -2).'</td></tr>';
		            		}
		            		
		            		if (!empty($product['product_shape']))  {
		            			echo '<tr>
				              	<td valign="top" class="smaller"><strong>Shape:</strong></td>
				              	<td valign="top" class="smaller">';
		            			$field = explode(' | ', $product['product_shape']);
		            			$str = '';
		            			foreach ($field as $value) {
		            				$str .= '
		            					<a href="/product/search/field:product_shape/mainkeyword:'.$value.'">'.ucwords($value).'</a>, ';
		            			}
		            			echo substr($str, 0, -2).'</td></tr>';
		            		}
		            		
		            		if (!empty($product['product_material']))  {
		            			echo '<tr>
				              	<td valign="top" class="smaller"><strong>Material:</strong></td>
				              	<td valign="top" class="smaller">';
		            			$field = explode(' | ', $product['product_material']);
		            			$str = '';
		            			foreach ($field as $value) {
		            				$str .= '
		            					<a href="/product/search/field:product_material/mainkeyword:'.$value.'">'.ucwords($value).'</a>, ';
		            			}
		            			echo substr($str, 0, -2).'</td></tr>';
		            		}
		            		
		            		if (!empty($product['product_body']))  {
		            			echo '<tr>
				              	<td valign="top" class="smaller"><strong>For Body Part:</strong></td>
				              	<td valign="top" class="smaller">';
		            			$field = explode(' | ', $product['product_body']);
		            			$str = '';
		            			foreach ($field as $value) {
		            				$str .= '
		            					<a href="/product/search/field:product_body/mainkeyword:'.$value.'">'.ucwords($value).'</a>, ';
		            			}
		            			echo substr($str, 0, -2).'</td></tr>';
		            		}
		            		
		            		if (!empty($product['product_powered']))  {
		            			echo '<tr>
				              	<td valign="top" class="smaller"><strong>Powered By:</strong></td>
				              	<td valign="top" class="smaller">'.ucwords(strtolower(str_replace(' | ',', ',$product['product_powered']))).'</td>              		
			            		</tr>';
		            		}
		            		
		            		if (!empty($product['product_color']))  {
		            			echo '<tr>
				              	<td valign="top" class="smaller"><strong>Color:</strong></td>
				              	<td valign="top" class="smaller">';
		            			$field = explode(' | ', $product['product_color']);
		            			$str = '';
		            			foreach ($field as $value) {
		            				$str .= '
		            					<a href="/product/search/field:product_color/mainkeyword:'.$value.'">'.ucwords($value).'</a>, ';
		            			}
		            			echo substr($str, 0, -2).'</td></tr>';
		            		}
		            		
		            		if (!empty($product['product_vibe']))  {
		            			echo '<tr>
				              	<td valign="top" class="smaller"><strong>Vibration:</strong></td>
				              	<td valign="top" class="smaller">';
		            			$field = explode(' | ', $product['product_vibe']);
		            			$str = '';
		            			foreach ($field as $value) {
		            				$str .= '
		            					<a href="/product/search/field:product_vibe/mainkeyword:'.$value.'">'.ucwords($value).'</a>, ';
		            			}
		            			echo substr($str, 0, -2).'</td></tr>';
		            		}
		            		
		            		if (!empty($product['product_features']))  {
		            			echo '<tr>
				              	<td valign="top" class="smaller"><strong>Features:</strong></td>
				              	<td valign="top" class="smaller">';
		            			$field = explode(' | ', $product['product_features']);
		            			$str = '';
		            			foreach ($field as $value) {
		            				$str .= '
		            					<a href="/product/search/field:product_features/mainkeyword:'.$value.'">'.ucwords($value).'</a>, ';
		            			}
		            			echo substr($str, 0, -2).'</td></tr>';
		            		}
		            		?>
		            		  </tbody>            
		          </table><p>&nbsp;</p>
		          <span style="font-size:10px">
		            Special* New Get 15 free minutes of <a href="http://vod.sextoytv.com/index.php?a=passionmansion" target="_blank">pay per minute adult videos</a> online with one purchase of any product.
		          </span>
          
          <br/>
          <br />

					<table width="100%" cellspacing="0" cellpadding="0" border="0">
					
						<tbody>
						<tr>
							<td width="49%" valign="bottom" class="orange">
								<? if ($product['out_of_stock']==0) { ?> 										
									<a href="/cart/add/<?=$product['id'];?>">
									<img style="vertical-align: middle;" alt="" src="/img/buttons/cart.gif" border="0" />
									</a>
								<? } else { ?>
									<img style="vertical-align: middle;" alt="" src="/img/buttons/outofstock.gif" border="0" />
								<? } ?>
							</td>
							<td width="1%" align="right" class="small" />
							<td width="50%" valign="top" align="right" class="t14">
								$<?=number_format($product['product_price'],2);?> 
							</td>
						</tr>
						<tr>
							<td height="6" colspan="3"/><td height="6" colspan="3"/>
						</tr>
						</tbody>
					</table>
					<br />
					<span class="smaller">
					<strong>Share This:</strong> 

					<? 
					$url = 'http://passionmansion.com/product_info/'.$product['id'];
					$title   = urlencode($product['product_name']);
					$media   = $product['product_thumb'];
					
					?>
					<a href="http://www.myspace.com/Modules/PostTo/Pages/?u=<?=$url?>&amp;c=<?=$title?>" target="_blank" />
						<img src="/img/social/myspace.gif" alt="MySpace" />
					</a>
					<a href="http://facebook.com/share.php?url=<?=$url?>" target="_blank" />
						<img src="/img/social/facebook.gif" alt="Facebook" />
					</a>
					<a href="http://digg.com/submit?phase=2&amp;url=<?=$url?>&amp;title=<?=$title?>&amp;media=<?=$media?>" target="_blank" />
						<img src="/img/social/digg.gif" alt="Digg" />
					</a>
					<a href="http://del.icio.us/post?url=<?=$url?>&amp;title=<?=$title?>" target="_blank" />
						<img src="/img/social/delicious.gif" alt="Del.icio.us" />
					</a>
					<a href="http://stumbleupon.com/submit?url=<?=$url?>&amp;title=<?=$title?>" target="_blank" />
						<img src="/img/social/stumbleupon.gif" alt="Facebook" />
					</a>
					<a href="http://cgi.fark.com/cgi/fark/farkit.pl?h=<?=$title?>&amp;u=<?=$url?>" target="_blank" />
						<img src="/img/social/fark.gif" alt="Fark" />
					</a>
					<a href="http://reddit.com/submit?url=<?=$url?>&amp;title=<?=$title?>" target="_blank" />
						<img src="/img/social/readit.gif" alt="Fark" />
					</a>
					<a href="http://view.nowpublic.com/?src=<?=$url?>" target="_blank" />
						<img src="/img/social/nowpublic.gif" alt="Now Public" />
					</a>
					<a href="javascript:(function(){var d=document;var s=d.createElement('script');s.type='text/javascript';s.src='http://www.kaboodle.com/zg/addbutton.js';d.getElementsByTagName('head')[0].appendChild(s);})()"> <img src="http://www.kaboodle.com/ht/mk/img/kicon-sm.gif" width="16" height="16" border="0" alt="Add To Kaboodle" /></a>
						<br style="clear: left;" />
					</div>
					</span>
		</td>

	  </tr>
  </table>
	  
  </td>
  <td width="10"/>
  <td width="1" />
  <td width="10"/>
  <td valign="top" style="text-align: center" width="200" class="smaller">
  	<img src="<?=$product['product_image'];?>" alt="<?=$product['product_name'];?>" id="prod_img" border="0" /> 
  	
  	<? if($product['product_thumb2']!='') { ?>
  		<br/><br/>
  		<a href="#" onclick="document.getElementById('prod_img').src='/img/items/<?=$product['id'];?>_1.jpg'">Image 1</a>
  		&nbsp;&nbsp; 
  		<a href="#" onclick="document.getElementById('prod_img').src='/img/items/<?=$product['id'];?>_2.jpg'">Image 2</a>
  	<? } ?>
  	<br/><br/>
  	<br/>
	</td>


</tr>
<tr><td>&nbsp;</td></tr>
<tr>
	<td colspan="5" style="padding-left: 10px;">
		<strong>Recommended Products</strong><br />
		<?=$this->renderElement('product_view', array('data' => $data['recommended']));?>
	</td>
</tr>
<? if (empty($data['product']['Rating']) == false) { ?>
<tr>
	<td colspan="5" style="padding-left: 10px;">
		<table border="0" cellspacing="5" cellpadding="5" width="90%" align="center">
			<tr><td style="border-bottom: 1px dotted #c0c0c0;"><strong>User Reviews and Comments</strong><br /><br /><td></tr>
			<?
			foreach ($data['product']['Rating'] as $rating) {
				echo '<tr>
					<td style="padding-top: 4px; border-bottom: 1px dotted #c0c0c0;">
					<img src="/img/ratings/'.$rating['rating'].'.gif" /> <br />
					'.$rating['comment'].' <br /> <br />
					</td>
				</tr>';
			}
			?>
		</table>
	</td>
</tr>
<? } ?>
</table>
