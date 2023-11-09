<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of enquiry
 *
 * @author tirupatibalan
 */
class Enquiry extends BaseController {
        /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('enquiries_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
      $this->global['pageTitle'] = 'Wreely : Dashboard';
      $this->loadViews("dashboard", $this->global, NULL, NULL);
    }

     /**
     * This function is used to load the member list
     */
    function enquiriesListing()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('enquiries_model');

            $searchText = $this->input->post('searchText');
            $data['searchText'] = $searchText;

            $this->load->library('pagination');

            $count = $this->enquiries_model->enquiriesListingCount($searchText);

            $returns = $this->paginationCompress ( "enquiriesListing/", $count, 10 );

            $data['enquiriesRecords'] = $this->enquiries_model->enquiriesListing($searchText, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = 'Wreely : Enquiries Listing';

            $this->loadViews("enquiry", $this->global, $data, NULL);
        }
    }


    /**
     * This function is used load enquiries edit information
     * @param number $enquiryId : Optional : This is enquiry id
     */
    function editOldEnquiry($enquiryId = NULL) {
      if ($enquiryId == null) {
          redirect('enquiryListing');
      }
      $this->load->model('enquiries_model');

      $data['enquiryInfo'] = $this->enquiries_model->getEnquiryInfo($enquiryId);

      $this->global['pageTitle'] = 'Wreely : Edit Enquiry';

      $this->loadViews("editOldEnquiry", $this->global, $data, NULL);
    }

    /**
     * This function is used to edit the enquiry information
     */
    function editEnquiry() {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('enquiries_model');
            $this->load->library('form_validation');

            $enquiryId = $this->input->post('enquiryId');
            if($this->form_validation->run() != FALSE) //Later perform validation
            {
                $this->editOldEnquiry($enquiryId);
            }
            else
            {
                $full_name = $this->input->post('full_name');
                $contact_no = $this->input->post('contact_no');
                $email_id = $this->input->post('email_id');
                $occupation = $this->input->post('occupation');
                $reference_source = $this->input->post('reference_source');
                $how_many = $this->input->post('how_many');
                $reason = $this->input->post('reason');
                $tell_us_more = $this->input->post('tell_us_more');

                //Admin Changes related to person enquiry
                $notes = $this->input->post('notes');

                $enquiryInfo = array();
                $enquiryInfo = array('full_name'=>$full_name,
                'contact_no'=>$contact_no,
                'email_id'=>$email_id,
                'occupation'=>$occupation,
                'reference_source'=>$reference_source,
                'how_many_people'=>$how_many,
                'reason'=>$reason,
                'notes'=>$notes,
                'tell_us_more'=>$tell_us_more,
                'updatedDtm'=>date('Y-m-d H:i:s'));

                $result = $this->enquiries_model->editEnquiry($enquiryInfo, $enquiryId);

                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Enquiry updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Enquiry updation failed');
                }
                redirect('enquiriesListing');
             }
         }
    }

    /**
     * This function is used to delete the enquiry using enquiryId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteEnquiry() {
        if ($this->isAdmin() == TRUE) {
            echo(json_encode(array('status' => 'access')));
        } else {

            $enquiryId = $this->input->post('enquiryId');
            $enquiryInfo = array('isDeleted' => 1, 'updatedDtm' => date('Y-m-d H:i:s'));

            $result = $this->enquiries_model->deleteEnquiry($enquiryId, $enquiryInfo);

            if ($result > 0) {
                echo(json_encode(array('status' => TRUE)));
            } else {
                echo(json_encode(array('status' => FALSE)));
            }
        }
    }

    function pageNotFound() {
        $this->global['pageTitle'] = 'Wreely : 404 - Page Not Found';
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}
