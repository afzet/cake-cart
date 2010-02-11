
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc><?php echo Router::url('/',true); ?></loc>
		<changefreq>daily</changefreq>
		<priority>1.0</priority>
	</url>
	<!-- static pages -->	
	<?php foreach ($products as $product):?>
	<url>
		<loc><?php echo Router::url('/'. $dojo->sitemap($product),true); ?></loc>
		<lastmod><?php echo date('Y-m-d\TH:m:s\Z'); ?></lastmod>
		<priority>0.8</priority>
	</url>
	<?php endforeach; ?>
</urlset>

