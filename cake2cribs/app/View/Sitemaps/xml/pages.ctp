<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" 
  xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" 
  xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">
    <url> 
        <loc><?php echo Router::url('/',true); ?></loc> 
        <changefreq>daily</changefreq> 
        <priority>1.0</priority> 
    </url>
    <url> 
        <loc><?php echo Router::url('/login',true); ?></loc> 
        <changefreq>yearly</changefreq> 
        <priority>0.5</priority> 
    </url>
    <url> 
        <loc><?php echo Router::url('/signup',true); ?></loc> 
        <changefreq>yearly</changefreq> 
        <priority>0.5</priority> 
    </url>
    <!-- Universities -->
    <?php foreach ($universities as $university):?>
    <url>
        <loc><?php echo Router::url('/' . $university['url'], true); ?></loc>
        <priority>0.8</priority> 
    </url>
    <?php endforeach; ?>
</urlset> 