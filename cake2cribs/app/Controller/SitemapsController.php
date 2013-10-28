<?php 
class SitemapsController extends AppController{ 

    var $name = 'Sitemaps'; 
    var $uses = array('Listing', 'Image', 'University'); 
    var $helpers = array('Time');
    var $components = array('RequestHandler'); 
    private $LISTING_IDS_PER_SITEMAP = 500;

    public function beforeFilter() {
        $this->Auth->allow('index');
        $this->Auth->allow('listings');
        $this->Auth->allow('pages');
    }

    function index () 
    {     
    	//debug logs will destroy xml format, make sure were not in debug mode 
		Configure::write ('debug', 0); 
    	$maxQuery = $this->Listing->find('all', array(
            'fields' => array('MAX(Listing.listing_id) AS listing_id', '*'), 
        ));
        $max_listing_id = 25000;
        if (array_key_exists(0, $maxQuery) && array_key_exists(0, $maxQuery[0])
            && array_key_exists('listing_id', $maxQuery[0][0]))
            $max_listing_id = $maxQuery[0][0]['listing_id'];

        /* Generate sitemap urls for listings */
        $number_sitemaps = intval(ceil($max_listing_id / $this->LISTING_IDS_PER_SITEMAP));
        $sitemap_urls = array();

        for ($i = 0; $i < $number_sitemaps; $i++){
            $url = 'sitemaps/listings/'.$i.'.xml';
            array_push($sitemap_urls, $url);
        }   
        

        $this->set('sitemap_urls', $sitemap_urls);
    	$this->RequestHandler->respondAs('xml');
    }

    /*
    Generates the sitemap for listing_ids in the range: 
    [$this->LISTING_IDS_PER_SITEMAP * $sitemap_index, 
    $this->LISTING_IDS_PER_SITEMAP * $sitemap_index + $this->LISTING_IDS_PER_SITEMAP)
    */
    public function listings($sitemap_index=0)
    {
        //debug logs will destroy xml format, make sure were not in debug mode 
        Configure::write ('debug', 0); 

        $listing_id_min = $this->LISTING_IDS_PER_SITEMAP * $sitemap_index;
        $listing_id_max = $listing_id_min + $this->LISTING_IDS_PER_SITEMAP;
        $listings = $this->Listing->find('all', array(
            'conditions' => array(
                'Listing.listing_id >= ' => $listing_id_min,
                'Listing.listing_id < ' => $listing_id_max,
            )
        ));

        /* Set image paths to be the lrg_ version */
        $this->_setImagePathsForView($listings);

        foreach ($listings as &$listing){
            $full_address = $listing["Marker"]["street_address"];
            $full_address .= " " . $listing["Marker"]["city"];
            $full_address .= " " . $listing["Marker"]["state"];
            $full_address .= " " . $listing["Marker"]["zip"];
            $full_address = str_replace(" ", "-", $full_address);
            $listing['url'] = 'listing/' . $listing['Listing']['listing_id'] . '/' . $full_address;
        }

        $this->set('listings', $listings);
        $this->RequestHandler->respondAs('xml');
    }

    /* 
    Includes pages for:
    - universities
    - signup/login
    */
    public function pages()
    {
        $universities = $this->University->find('all', array(
            'contain' => array()
        ));

        foreach ($universities as &$university){
            $school_name = str_replace(" ", "_", $university['University']['name']);
            $university['url'] = 'university/' . $school_name;
        }

        $this->set('universities', $universities);
    } 

    /*
    Takes in an array of Image objects.
    Updates their image_paths by pre-pending prefix to their filenames.
    */
    private function _setImagePathsForView(&$listings, $prefix='lrg_')
    {
        foreach ($listings as &$listing){
            if (array_key_exists('Image', $listing)){
                foreach ($listing['Image'] as &$image){
                    if (array_key_exists('image_path', $image)) {
                        $fileName = $prefix.$this->_getFileNameFromPath($image['image_path']);
                        $directory = $this->_getDeepestDirectoryFromPath($image['image_path']);
                        $image['image_path'] = $directory.'/'.$fileName;
                    }
                }
            }
        }
    }

    /*
    Returns the file name given the full path to the file
    */
    private function _getFileNameFromPath($image_path)
    {
        return substr($image_path, strrpos($image_path, '/') + 1);
    }

    /*
    Returns the deepest directory from a given path.
    */
    private function _getDeepestDirectoryFromPath($path)
    {
        return substr($path, 0, strrpos($path, '/'));
    }
} 
?> 