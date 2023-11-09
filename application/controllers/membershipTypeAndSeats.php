<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @version : 1.1
 */
class MembershipTypeAndSeats extends BaseController {

    /**
     * This is default constructor of the class
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('membership_type_model');
        $this->load->model('seats_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index() {
        $this->global['pageTitle'] = 'Wreely : Dashboard';
        $this->loadViews("dashboard", $this->global, NULL, NULL);
    }

    /**
     * This function is used to load the space list
     */

    function membershipTypeAndSeatListing() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->model('membership_type_model');
            $this->load->model('seats_model');

            //Membership Type
            $membershipTypeSearchText = $this->input->post('membershipTypeSearchText');
            $data['membershipTypeSearchText'] = $membershipTypeSearchText;

            $this->load->library('pagination');
            $membershipTypeCount = $this->membership_type_model->membershipTypeListingCount($membershipTypeSearchText);
            $membershipTypeReturns = $this->paginationCompress("membershipTypeListing/", $membershipTypeCount, 20);

            $data['membershipTypeRecords'] = $this->membership_type_model->membershipTypeListing($membershipTypeSearchText, $membershipTypeReturns["page"], $membershipTypeReturns["segment"]);
            $data['membershipTypes'] = $this->membership_type_model->getAllMembershipTypes();
            $data['planTypes'] = $this->membership_type_model->getAllPlanTypes();

            //Seats
            $seatSearchText = $this->input->post('seatSearchText');
            $data['seatSearchText'] = $seatSearchText;

            $this->load->library('pagination');
            $seatsCount = $this->seats_model->seatListingCount($seatSearchText);
            $seatsReturns = $this->paginationCompress("seatListing/", $seatsCount, 100);

            $data['seatsRecords'] = $this->seats_model->seatListing($seatSearchText, $seatsReturns["page"], $seatsReturns["segment"]);
            $this->global['pageTitle'] = 'Wreely : Membership Type & Seat Listing';
            $this->loadViews("membershipTypeAndSeats", $this->global, $data, NULL);
        }
    }


    /**
     * This function is used to add new user to the system
     */
    function addMembershipType() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->load->model('membership_type_model');

            $name = $this->input->post('membershipName');
            $price = $this->input->post('price');
            $numberOfDays = $this->input->post('numberOfDays');
            $quantity = $this->input->post('quantity');

            $planTypeId = $this->input->post('planTypeId');
            $membershipTypeInfo = array('number_of_day' => $numberOfDays, 'membership_name' => $name,'price' => $price, 'quantity'=>$quantity, 'plan_type_id' => $planTypeId);

            $result = $this->membership_type_model->addNewMembershipTypeData($membershipTypeInfo);

            if ($result > 0) {
                $this->session->set_flashdata('success', 'New seat type created successfully');
            } else {
                $this->session->set_flashdata('error', 'Seat type creation failed');
            }
            redirect('membershipTypeAndSeatListing');
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkMembershipTypeNameExists() {
        $name = $this->input->post("name");
        $result = $this->membership_type_model->checkIfMembershipTypeNameExistByName($name);

        if (empty($result)) {
            echo("true");
        } else {
            echo("false");
        }
    }

    /**
     * This function is used to edit the space information
     */
    function editMembershipType() {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            $this->load->model('membership_type_model');

            $name = $this->input->post('membershipName');
            $price = $this->input->post('price');
            $numberOfDays = $this->input->post('numberOfDays');
            $quantity = $this->input->post('quantity');
            $membershipTypeId = $this->input->post('membershipTypeId');
            $planTypeId = $this->input->post('planTypeId');

            $membershipTypeInfo = array();
            $membershipTypeInfo = array('number_of_day' => $numberOfDays, 'membership_name'=>$name, 'id'=>$membershipTypeId,
                'price'=>$price, 'quantity'=>$quantity, 'plan_type_id' => $planTypeId);

            $result = $this->membership_type_model->updateMemberInfo($membershipTypeInfo);
            if ($result == true)
            {
                $this->session->set_flashdata('success', 'Seat type updated successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'Seat type updation failed');
            }
           redirect('membershipTypeAndSeatListing');
         }
    }

    /**
     * This function is used to delete the space using spaceId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteMembershipType() {
        if ($this->isAdmin() == TRUE) {
            echo(json_encode(array('status' => 'access')));
        } else {
            $membershipTypeId = $this->input->post('membershipTypeId');
            $membershipTypeInfo = array('id' => $membershipTypeId, 'isDeleted' => 1);
            $result = $this->membership_type_model->deleteMembershipType($membershipTypeInfo);
            if ($result > 0) {
                echo(json_encode(array('status' => TRUE)));
            } else {
                echo(json_encode(array('status' => FALSE)));
            }
        }
    }

    /**
     * This function is used to add new user to the system
     */
    function addSeat() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->load->model('seats_model');

            $seatName = $this->input->post('seatName');
            $membershipTypeId = $this->input->post('seatMembershipTypeId');

            $seatInfo = array('seat_name' => $seatName,'membership_type_id' => $membershipTypeId);
            $result = $this->seats_model->addNewSeatData($seatInfo);
            if ($result > 0) {
                $this->session->set_flashdata('success', 'New seat created successfully');
            } else {
                $this->session->set_flashdata('error', 'Seat creation failed');
            }
            redirect('membershipTypeAndSeatListing');
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkSeatNameExists() {
        $name = $this->input->post("name");
        $result = $this->seats_model->checkIfSeatNameExistByName($name);

        if (empty($result)) {
            echo("true");
        } else {
            echo("false");
        }
    }

    /**
     * This function is used to edit the space information
     */
    function editSeat() {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            $this->load->model('seats_model');

            $seatId = $this->input->post('seatId');
            $seatName = $this->input->post('seatName');
            $membershipTypeId = $this->input->post('seatMembershipTypeId');

            $seatInfo = array();
            $seatInfo = array('seat_name'=>$seatName, 'id'=>$seatId, 'membership_type_id'=>$membershipTypeId);

            $result = $this->seats_model->updateSeatInfo($seatInfo);
            if ($result == true)
            {
                $this->session->set_flashdata('success', 'Seat updated successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'Seat updation failed');
            }
            redirect('membershipTypeAndSeatListing');
         }
    }

    /**
     * This function is used to delete the space using spaceId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteSeat() {
        if ($this->isAdmin() == TRUE) {
            echo(json_encode(array('status' => 'access')));
        } else {
            $this->load->model('seats_model');

            $seatId = $this->input->post('seatId');
            $seatInfo = array('id' => $seatId, 'isDeleted' => 1);
            $result = $this->seats_model->deleteSeat($seatInfo);
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

?>
