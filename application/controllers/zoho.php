<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';


class Zoho extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->library('zohobooks');
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        if (class_exists('zohobooks'))
        {
            $zohoObject = new $this->zohobooks('952538f8df7168bcbfbc29185e949a99','654927368');
            $zObj =$zohoObject->allContacts();
            echo "<pre>";
            print_r(json_decode($zObj));
        } else {
            echo "Class not loaded";
        }
    }

    function pageNotFound()
    {
        $this->global['pageTitle'] = 'Wreely : 404 - Page Not Found';
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

?>
