<?php
/**
 * SVN FILE: $Id: default.ctp 494 2008-09-05 01:43:06Z jonathan $
 *
 * Default Layout
 *
 * @package pmCart
 * @author Jonathan Bradley <jonathan@passionmansion.com>
 * @copyright Copyright 2008, Passion Mansion, Inc.
 * @version $Revision: 494 $
 * Last Modified: $Date: 2008-09-04 21:43:06 -0400 (Thu, 04 Sep 2008) $
 * Modified By: $LastChangedBy: jonathan $
 */
 ?>
                                                        
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
        <head>
                <title><?=$title_for_layout;?></title>
                <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
                <meta name="description" content="<?=$session->read('Settings.description');?>" />
                <meta name="keywords" content="<?=$session->read('Settings.keywords');?>" />
                <meta name="robots" content="index, follow" />
                <meta name="robots" content="all" />
                <meta name="msvalidate.01" content="1F63A1CE80D05C2E4E103C323134EB01" />
                <link rel="alternate" type="application/rss+xml" title="Passion Mansion's Most Popular Products Feed" href="https://passionmansion.com/rss/recent.xml" />
                <link rel="stylesheet" href="/css/style.css" type="text/css" media="screen" title="no title" charset="utf-8" />
                <link rel="stylesheet" href="/css/navigation.css" type="text/css" media="screen" title="no title" charset="utf-8" />
                <link rel="stylesheet" media="all" type="text/css" href="/css/flyout.css" />
                <link href="/favicon.ico" type="image/x-icon" rel="icon"/>
                <link href="/favicon.ico" type="image/x-icon" rel="shortcut icon"/>
		<script type="text/javascript" charset="utf-8" src="/js/application.js"></script>	
		
	</head>
	<body>
		<div class="twitter"><a href="http://twitter.com/passionmansion" target="_blank" rel="external"><img border="0" src="/img/twitter.png"/></a></div>
		<table id="main" width="940" cellspacing="0" cellpadding="0" border="0" style="text-align: center">
			<tr>
				<td style="background: url(/img/web_logo.gif) no-repeat; height: 99px; text-align: right">
					<?=$html->link($html->image('banners/passion-parties.png'),'mailto:anthony@passionmansion.com?subject=Host Passion Party',null,null,false)?>
				</td>				
			</tr>
			<tr>
				<td>
					<?php echo $this->renderElement('nav_top'); ?>
				</td>
			</tr>
			<tr>
				
				<td>
					<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td valign="top" style="width: 194px">
								<?php 
									echo $this->renderElement('nav_cart');
									if (!empty($searched)) echo $this->renderElement('sidebar/recent_search');
									echo $this->renderElement('nav_sidebar'); 
								?>
							</td>
							<td style="width: 10px" >&nbsp;</td>
							<td valign="top">		
							<?php echo $content_for_layout; ?>
							</td>
						</tr>
					</table>
					
				</td>
			</tr>
			<tr><td style="height: 20px;">&nbsp;</td></tr>
			<tr><td style="height: 20px;">&nbsp;</td></tr>
			<tr>
				<td style="text-align: center" class="smaller">	
					<div class="smallest" style="color: #808080; width: 700px; margin: 0 auto;">
          	We strongly support parental controls on the Internet. These web pages are not intended to 
          	be viewed by minors. If you are a parent and you want to block this site, please contact one 
          	of the following: RSAC Cyber Patrol Safesurf SurfWatch Websense SmartAlex. 
  					<br /><br />
  					<?	
  					echo $html->link('Terms of Use Agreement', '/faqs/view/26/Terms_Of_Use_Agreement').' | ';
  					echo $html->link('Privacy Policy','/faqs/view/27/Privacy_Policy').' | ';
  					echo $html->link('Press','/press').' | ';
  					echo $html->link('Advertising', '/docs/advertise').' | ';
  					// echo $html->link('Affiliates', '/affiliates').' | ';
  					echo $html->link('Sitemap', '/docs/sitemap');
  					?>
  					<br /><br />
  					<?=$session->read('Settings.copyright');?>
  					<br /><br />
  					<?=$html->link('18 U.S.C Section 2257 Compliance Notice', '/docs/2257');?>
					</div>
					<div style="display: none;">
					</div>
					<br /><br />
				</td>
			</tr>
		</table>		
    <div style="display:none">
		<script type="text/javascript">
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
			<script type="text/javascript">
			try {
			var pageTracker = _gat._getTracker("UA-4225920-1");
			pageTracker._trackPageview();
			} catch(err) {}
		</script>
    </div>
  </body>
</html>


