<?php 
class SitemapController extends AppController{ 

    var $name = 'Sitemaps'; 
    var $uses = array('Listing', 'Image', 'University'); 
    var $helpers = array('Time');
    var $components = array('RequestHandler'); 

    public function beforeFilter() {
        $this->Auth->allow('index');
    }

    function index () 
    {     
    	//debug logs will destroy xml format, make sure were not in debug mode 
		Configure::write ('debug', 0); 

    	$listings = $this->Listing->find('all', array(
    		'conditions' => array('Listing.visible' => 1)	
    	));

        $universities = $this->University->find('all', array(
            'contains' => array('University')
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

        foreach ($universities as &$university){
            $school_name = str_replace(" ", "_", $university['University']['name']);
            $university['url'] = 'rental/' . $school_name;
        }

    	$this->set('listings', $listings);
        $this->set('universities', $universities);

    	$this->RequestHandler->respondAs('xml');
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