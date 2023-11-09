<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of interiorLayout
 *
 * @author tirupatibalan
 */
class InteriorLayout extends BaseController 
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('interiorLayout_model');
        $this->isLoggedIn();
    }
    
    /**
     * This function used to load the first screen of the setting
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Wreely : Interior Layout';
        $this->loadViews("interiorLayout", $this->global, NULL , NULL);
    }
}
