<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @version : 1.1
 */
class Event extends BaseController {

    /**
     * This is default constructor of the class
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('event_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index() {
        $this->global['pageTitle'] = 'Wreely : Dashboard';
        $this->loadViews("dashboard", $this->global, NULL, NULL);
    }

    function eventsListing() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->model('event_model');

            if($this->input->post('from') != '' ){
                $fromDate = $this->input->post('from');
                $toDate = $this->input->post('to');
            }else{
                
                $fromDate = date('Y-m-01'); // hard-coded '01' for first day
                $toDate  = date('Y-m-t');
            }
            
            //echo "this is from date".$fromDate;

            $count = $this->event_model->eventListingCount($fromDate, $toDate);
            
            $returns = $this->paginationCompress("eventsListing/", $count, 10);
            
            $data['eventRecords'] = $this->event_model->getEventListing($fromDate, $toDate, $returns["page"], $returns["segment"]);
            //echo "<pre>";
            //print_r($data['eventRecords']);
            //echo $this->session->userdata['userId'];
            $data['vendor_id'] = $this->session->userdata['userId'];
            
            $this->global['pageTitle'] = 'Wreely : Meeting Room Bookings';
            $this->loadViews("events", $this->global, $data, NULL);
        }
    }

    function eventsDatatableListing() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->model('event_model');

            $fromDate = $this->input->post('from');
            $toDate = $this->input->post('to');

            $this->load->library('pagination');

            $count = $this->event_model->eventListingCount($fromDate, $toDate);

            $returns = $this->paginationCompress("eventsListing/", $count, 10);

            $result = $this->event_model->getEventListing($fromDate, $toDate, $returns["page"], $returns["segment"]);


            if ($result > 0) {
                echo(json_encode(array('data' => $result, 'draw' => 1, 'recordsTotal' => 10, 'recordsFiltered' => 20)));
            } else {
                echo(json_encode(array('data' => [], 'draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0)));
            }
        }
    }

    /**
     * This function is used to edit the member information
     */
    function editEvent() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $userId = $this->input->post('userId');
            $formValues = $this->input->post();
            $ucwordsArr = array('id','title', 'description','vendor_id' ,'start_time', 'end_time');
            foreach ($formValues as $key => $val) {
                if (in_array($key, $ucwordsArr)) {
                    $arrDb[$key] = $val;
                    if ($key == 'start_time' || $key == 'end_time') {
                        $time_timestamp = strtotime($formValues[$key]);
                        $arrDb[$key] = date('Y-m-d h:i:s', $time_timestamp);
                    }
                }
            }
            $eventInfo = $arrDb;
            $this->load->model('event_model');

            $result = $this->event_model->updateEvent($eventInfo);

            if ($result == true) {
                $this->session->set_flashdata('success', 'User updated successfully');
            } else {
                $this->session->set_flashdata('error', 'User updation failed');
            }
            redirect('eventsListing');
        }
    }

    /**
     * This function is used to add new member to the system
     */
    function addEvent() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $formValues = $this->input->post();
            $ucwordsArr = array('title', 'description','vendor_id' ,'start_time', 'end_time');
            foreach ($formValues as $key => $val) {
                if (in_array($key, $ucwordsArr)) {
                    $arrDb[$key] = $val;
                    if ($key == 'start_time' || $key == 'end_time') {
                        $time_timestamp = strtotime($formValues[$key]);
                        $arrDb[$key] = date('Y-m-d h:i:s', $time_timestamp);
                    }
                }
            }

            $eventInfo = $arrDb;
            //$companyInfo["contact_person_email_id"] = strtolower($companyInfo["contact_person_email_id"]);
            $this->load->model('event_model');
            $result = $this->event_model->addNewEvent($eventInfo);

            if ($result > 0) {
                $this->session->set_flashdata('success', 'New event created successfully');
            } else {
                $this->session->set_flashdata('error', 'event creation failed');
            }

            redirect('eventsListing');
        }
    }

    function pageNotFound() {
        $this->global['pageTitle'] = 'Wreely : 404 - Page Not Found';
        $this->loadViews("404", $this->global, NULL, NULL);
    }

}

?>
