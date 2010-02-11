<?php
/**
 * SVN FILE: $Id: new_rss.ctp 58 2008-08-08 01:51:02Z jonathan $
 *
 * Products What's New RSS View
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 58 $
 * Last Modified: $Date: 2008-08-07 21:51:02 -0400 (Thu, 07 Aug 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
 ?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
  <channel>
    <title>Passion Mansion</title>
    <link>http://www.sextoycompanies.net/</link>
    <description>20 Newest Products</description>
    <language>en-us</language>
    
    <pubDate><?=date("D, j M Y H:i:s", gmmktime()) . ' GMT';?></pubDate>
	    <?=$time->nice($time->gmt()) . ' GMT';?>
	  <docs>http://blogs.law.harvard.edu/tech/rss</docs>
    <? 
    foreach ($products as $product) 
    { 
    	$product_name 	= $product['Product']['product_name'];
    	$product_price 	= $product['Product']['product_price'];
    	$product_desc 	= $text->truncate($product['Product']['product_desc'], 75);
    	$product_id		 	= $product['Product']['id'];
    	$product_url	 	= urlencode($product_name);
    	$product_date 	= $time->nice($product['Product']['product_date']) . ' GMT';
    	
    	echo '
		    <item>
		      <title>'.$product_name.' - $'.$product_price.'</title>
		      <link>http://www.sextoycompany.net/product_info/'.$product_id.'</link>
		      <description>'.$product_desc.'</description>
		      '.$product_date.'
		       <pubDate>'.$product_date.'</pubDate>
		      <guid>http://www.sextoycompany.net/product_info/'.$product_id.'/'.$product_url.'</guid>
		    </item>
		    	';
    } 
    ?>
  </channel>

</rss>