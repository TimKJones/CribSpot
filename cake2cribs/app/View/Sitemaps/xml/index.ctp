<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" 
  xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" 
  xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">
    <url> 
        <loc><?php echo Router::url('/',true); ?></loc> 
        <changefreq>daily</changefreq> 
        <priority>1.0</priority> 
    </url> 
    <!-- listings -->     
    <?php foreach ($listings as $listing):?> 
    <url> 
        <loc><?php echo Router::url('/'.$listing['url'],true); ?></loc>
            <?php foreach ($listing['Image'] as $image): ?>
                <image:image>
                    <image:loc><?php echo Router::url('/'.$image['image_path'],true); ?></image:loc>
                </image:image>
            <?php endforeach ?>
        <priority>0.8</priority> 
    </url>
    <?php endforeach; ?>
    <?php foreach ($universities as $university):?>
    <url>
        <loc><?php echo Router::url('/' . $university['url'], true); ?></loc>
        <priority>0.8</priority> 
        <?php CakeLog::write('url_university', Router::url('/' . $university['url'], true)); ?>
    </url>
    <?php endforeach; ?>
</urlset> 