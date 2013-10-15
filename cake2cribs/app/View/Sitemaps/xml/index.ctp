<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
      <loc><?php echo Router::url('/pages_sitemap.xml',true); ?></loc>
    </sitemap>
    <?php foreach ($sitemap_urls as $sitemap_url):?> 
    <sitemap>
        <loc><?php echo Router::url('/'.$sitemap_url,true); ?></loc>
    </sitemap>
    <?php endforeach; ?>
</sitemapindex>
