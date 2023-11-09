<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @version : 1.1
 */
class SpaceManagement extends BaseController {

    /**
     * This is default constructor of the class
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('space_management_model');
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
    function spaceListing() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->model('space_management_model');
            $this->load->model('membership_type_model');
            $this->load->model('seats_model');
            $this->load->model('company_model');
            $this->load->model('member_model');

            //get all seat types
            $data['seats'] = $this->seats_model->getAllSeats();
            //get all membership types
            $data['membershipTypes'] = $this->membership_type_model->getAllMembershipTypes();
            //get all company
            $data['companies'] = $this->company_model->getAllCompanies();
            //get all company
            $data['members'] = $this->member_model->getAllMembers();
            //get all plan types
            $data['planTypes'] = $this->membership_type_model->getAllPlanTypes();

            $searchText = $this->input->post('searchText');
            $data['searchText'] = $searchText;

            $this->load->library('pagination');
            $count = $this->space_management_model->spaceListingCount($searchText);
            $returns = $this->paginationCompress("spaceListing/", $count, 10);

            $data['spaceRecords'] = $this->space_management_model->spaceListing($searchText, $returns["page"], $returns["segment"]);
            $this->global['pageTitle'] = 'Wreely : Space Listing';
            $this->loadViews("spaceManagement", $this->global, $data, NULL);
        }
    }

    function spaceFlexible() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->model('space_management_model');
            $this->load->model('membership_type_model');
            $this->load->model('seats_model');
            $this->load->model('company_model');
            $this->load->model('member_model');

            //get all seat types
            $data['seats'] = $this->seats_model->getAllSeats();
            //get all membership types
            $data['membershipTypes'] = $this->membership_type_model->getMembershipTypesBasedOnPlan(PLAN_FLEXIBLE);
            //get all company
            $data['companies'] = $this->company_model->getAllCompanies();
            //get all company
            $data['members'] = $this->member_model->getAllMembers();
            //get all plan types
            $data['planTypes'] = $this->membership_type_model->getAllPlanTypes();

            $searchText = $this->input->post('searchText');
            $data['searchText'] = $searchText;

            $this->load->library('pagination');
            $count = $this->space_management_model->spaceListingCount($searchText, PLAN_FLEXIBLE);
            $returns = $this->paginationCompress("spaceFlexible/", $count, 20);

            $data['spaceRecords'] = $this->space_management_model->spaceListing($searchText, $returns["page"], $returns["segment"], PLAN_FLEXIBLE);
            $this->global['pageTitle'] = 'Wreely : Space Listing';
            $this->loadViews("spaceFlexible", $this->global, $data, NULL);
        }
    }

    function spaceDedicated() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->model('space_management_model');
            $this->load->model('membership_type_model');
            $this->load->model('seats_model');
            $this->load->model('company_model');
            $this->load->model('member_model');

            //get all seat types
            $data['seats'] = $this->seats_model->getAllSeats();
            //get all membership types
            $data['membershipTypes'] = $this->membership_type_model->getMembershipTypesBasedOnPlan(PLAN_DEDICATED);
            //get all company
            $data['companies'] = $this->company_model->getAllCompanies();
            //get all company
            $data['members'] = $this->member_model->getAllMembers();
            //get all plan types
            $data['planTypes'] = $this->membership_type_model->getAllPlanTypes();

            $searchText = $this->input->post('searchText');
            $data['searchText'] = $searchText;

            $this->load->library('pagination');
            $count = $this->space_management_model->spaceListingCount($searchText, PLAN_DEDICATED);
            $returns = $this->paginationCompress("spaceDedicated/", $count, 20);

            $data['spaceRecords'] = $this->space_management_model->spaceListing($searchText, $returns["page"], $returns["segment"], PLAN_DEDICATED);
            $this->global['pageTitle'] = 'Wreely : Space Listing';
            $this->loadViews("spaceDedicated", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to add new user to the system
     */
    function addSpace() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->load->model('space_management_model');

            $seatMembershipTypeId = $this->input->post('seatMembershipTypeId');
            $seatId = $this->input->post('seatId');
            $companyId = $this->input->post('companyId');
            $memberId = $this->input->post('memberId');
            $planTypeId = $this->input->post('planTypeId');

            $renewalDate = $this->input->post('renewalDate');
            $renewalDate = new DateTime($renewalDate);
            $renewalDate = date_format($renewalDate, 'Y-m-d');

            $expiryDate = $this->input->post('expiryDate');
            $expiryDate = new DateTime($expiryDate);
            $expiryDate = date_format($expiryDate, 'Y-m-d');

            $spaceInfo = array('seat_id' => $seatId,'membership_type_id' => $seatMembershipTypeId, 'plan_type' => $planTypeId, 'company_id' => $companyId, 'member_id' => $memberId, 'start_date' => $renewalDate, 'expiry_date' => $expiryDate, 'createdBy' => $this->vendorId, 'createdDtm' => date('Y-m-d H:i:s'));
            $result = $this->space_management_model->addNewSpaceData($spaceInfo);

            if ($result > 0) {
                $this->session->set_flashdata('success', 'New space created successfully');
            } else {
                $this->session->set_flashdata('error', 'Space creation failed');
            }

            if ($planTypeId == PLAN_FLEXIBLE) { //Flexible
              redirect('spaceFlexible');
            } else if ($planTypeId == PLAN_DEDICATED) { //Dedicated
              redirect('spaceDedicated');
            } else {
              redirect('spaceListing');
            }
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists() {
        $userId = $this->input->post("userId");
        $email = $this->input->post("email");

        if (empty($userId)) {
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if (empty($result)) {
            echo("true");
        } else {
            echo("false");
        }
    }

    /**
     * This function is used to edit the space information
     */
    function editSpace() {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('space_management_model');
            $this->load->library('form_validation');

            $spaceId = $this->input->post('spaceId');

            $seatMembershipTypeId = $this->input->post('seatMembershipTypeId');
            $seatId = $this->input->post('seatId');
            $companyId = $this->input->post('companyId');
            $memberId = $this->input->post('memberId');
            $planTypeId = $this->input->post('planTypeId');

            $renewalDate = $this->input->post('renewalDate');
            $renewalDate = new DateTime($renewalDate);
            $renewalDate = date_format($renewalDate, 'Y-m-d');

            $expiryDate = $this->input->post('expiryDate');
            $expiryDate = new DateTime($expiryDate);
            $expiryDate = date_format($expiryDate, 'Y-m-d');

            $spaceInfo = array();
            $spaceInfo = array('seat_id'=>$seatId,
            'plan_type'=>$planTypeId,
            'membership_type_id'=>$seatMembershipTypeId,
            'company_id'=>$companyId,
            'member_id'=>$memberId,
            'start_date'=>$renewalDate,
            'expiry_date'=>$expiryDate,
            'updatedBy'=>$this->vendorId,
            'updatedDtm'=>date('Y-m-d H:i:s'));

            $result = $this->space_management_model->editSpace($spaceInfo, $spaceId);

            if($result == true)
            {
                $this->session->set_flashdata('success', 'Space updated successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'Space updation failed');
            }
            if ($planTypeId == PLAN_FLEXIBLE) { //Flexible
              redirect('spaceFlexible');
            } else if ($planTypeId == PLAN_DEDICATED) { //Dedicated
              redirect('spaceDedicated');
            } else {
              redirect('spaceListing');
            }
         }
    }

    /**
     * This function is used to delete the space using spaceId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteSpace() {
        if ($this->isAdmin() == TRUE) {
            echo(json_encode(array('status' => 'access')));
        } else {

            $spaceId = $this->input->post('spaceId');
            $spaceInfo = array('isDeleted' => 1, 'updatedDtm' => date('Y-m-d H:i:s'), 'expiry_date' => date('Y-m-d H:i:s'));
            $result = $this->space_management_model->deleteSpace($spaceId, $spaceInfo);

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
