<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @version : 1.1
 */
class User extends BaseController {

    /**
     * This is default constructor of the class
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('company_model');
        $this->load->model('member_model');
        $this->load->model('enquiries_model');
        $this->load->model('enquiries_model');
        $this->load->model('space_management_model');
        $this->load->model('seats_model');
        $this->load->model('staff_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index() {
        $this->global['pageTitle'] = 'Wreely : Dashboard';
        $companyCount = $this->company_model->getAllCompanies();
        $membersCount = $this->member_model->getAllMembers();
        $enquiriesCount = $this->enquiries_model->getAllEnquiries();
        $flexiCount = $this->space_management_model->getAllPlans(PLAN_FLEXIBLE);
        $dedicatedCount = $this->space_management_model->getAllPlans(PLAN_DEDICATED);
        $seatsCount = $this->seats_model->getAllSeats();

        $data['companyCount'] = count($companyCount);
        $data['membersCount'] = count($membersCount);
        $data['enquiriesCount'] = count($enquiriesCount);
        $data['flexiCount'] = count($flexiCount);
        $data['dedicatedCount'] = count($dedicatedCount);
        $data['seatsCount'] = count($seatsCount);

        $this->loadViews("dashboard", $this->global, $data, NULL);
    }

    /**
     * This function is used to load the user list
     */
    function userListing() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->model('user_model');

            $searchText = $this->input->post('searchText');
            $data['searchText'] = $searchText;

            $this->load->library('pagination');

            $count = $this->user_model->userListingCount($searchText);

            $returns = $this->paginationCompress("userListing/", $count, 10);
            $data['userRecords'] = $this->user_model->userListing($searchText, $returns["page"], $returns["segment"]);
            $this->global['pageTitle'] = 'Wreely : User Listing';
            $this->loadViews("users", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the staff list
     */
    function staffListing($mode = '') {
        $userId = $this->session->userdata['userId'];
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $searchText = $this->input->post('searchText');
            $data['searchText'] = $searchText;
            $this->load->library('pagination');
            $count = $this->staff_model->userListingCount($searchText, $userId);
            $returns = $this->paginationCompress("userListing/", $count, 10);
            $data['userRecords'] = $this->staff_model->userListing($searchText, $returns["page"], $returns["segment"], $userId);
            $data['mode'] = $mode;
            $this->global['pageTitle'] = 'Wreely : User Listing';
            $this->loadViews("staffs", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function addNewStaff() {


        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            // Validations
            $this->form_validation->set_rules('name', 'Full Name', 'trim|required|max_length[128]|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|max_length[128]');
            $this->form_validation->set_rules('password', 'Password', 'matches[cpassword]|max_length[20]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'matches[password]|max_length[20]');
            //$this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]|xss_clean');
            
            //Posting data
            $name = $this->input->post("name");
            $email = $this->input->post("email");
            $mobile = $this->input->post("mobile");
            $password = $this->input->post("password");
            
            print_r($this->form_validation->run());
            exit;
            
            if ($this->form_validation->run() == FALSE) {
                $mode = "add";
                $this->staffListing($mode);
            } else {
                $staffDataArray = array(
                    'name' => $name,
                    'mobile' => $mobile,
                    'email' => $email,
                    'password' => getHashedPassword($password),
                    'parent_id' => $this->session->userdata('userId')
                );

                $emailMobileExists = $this->staff_model->checkIfEmailORMobileExists($email, $mobile);
                
                if(!$emailMobileExists) {
                    $result = $this->staff_model->addNewStaffData($staffDataArray);
                    $this->global['pageTitle'] = 'Wreely : Add New Member';
                    $data = array();
                    redirect('staffListing');
                }
            }
        }
    }

    /**
     * This function is used to load the add new form
     */
    function addNew() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->model('user_model');
            $data['roles'] = $this->user_model->getUserRoles();

            $this->global['pageTitle'] = 'Wreely : Add New User';

            $this->loadViews("addNew", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to add new user to the system
     */
    function addNewUser() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|max_length[128]');
            $this->form_validation->set_rules('password', 'Password', 'required|max_length[20]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]|max_length[20]');
            $this->form_validation->set_rules('role', 'Role', 'trim|required|numeric');
            $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|xss_clean');

            if ($this->form_validation->run() == FALSE) {
                $this->addNew();
            } else {
                $name = ucwords(strtolower($this->input->post('fname')));
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                $roleId = $this->input->post('role');
                $mobile = $this->input->post('mobile');

                $userInfo = array('email' => $email, 'password' => getHashedPassword($password), 'roleId' => $roleId, 'name' => $name,
                    'mobile' => $mobile, 'createdBy' => $this->vendorId, 'createdDtm' => date('Y-m-d H:i:s'));

                $this->load->model('user_model');
                $result = $this->user_model->addNewUser($userInfo);

                if ($result > 0) {
                    $this->session->set_flashdata('success', 'New User created successfully');
                } else {
                    $this->session->set_flashdata('error', 'User creation failed');
                }

                redirect('addNew');
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
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOld($userId = NULL) {
        if ($this->isAdmin() == TRUE || $userId == 1) {
            $this->loadThis();
        } else {
            if ($userId == null) {
                redirect('userListing');
            }

            $data['roles'] = $this->user_model->getUserRoles();
            $data['userInfo'] = $this->user_model->getUserInfo($userId);

            $this->global['pageTitle'] = 'Wreely : Edit User';

            $this->loadViews("editOld", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to edit the user information
     */
    function editUser() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $userId = $this->input->post('userId');

            $this->form_validation->set_rules('fname', 'Full Name', 'trim|required|max_length[128]|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|max_length[128]');
            $this->form_validation->set_rules('password', 'Password', 'matches[cpassword]|max_length[20]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'matches[password]|max_length[20]');
            $this->form_validation->set_rules('role', 'Role', 'trim|required|numeric');
            $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|min_length[10]|xss_clean');

            if ($this->form_validation->run() == FALSE) {
                $this->editOld($userId);
            } else {
                $name = ucwords(strtolower($this->input->post('fname')));
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                $roleId = $this->input->post('role');
                $mobile = $this->input->post('mobile');

                $userInfo = array();

                if (empty($password)) {
                    $userInfo = array('email' => $email, 'roleId' => $roleId, 'name' => $name,
                        'mobile' => $mobile, 'updatedBy' => $this->vendorId, 'updatedDtm' => date('Y-m-d H:i:s'));
                } else {
                    $userInfo = array('email' => $email, 'password' => getHashedPassword($password), 'roleId' => $roleId,
                        'name' => ucwords($name), 'mobile' => $mobile, 'updatedBy' => $this->vendorId,
                        'updatedDtm' => date('Y-m-d H:i:s'));
                }

                $result = $this->user_model->editUser($userInfo, $userId);

                if ($result == true) {
                    $this->session->set_flashdata('success', 'User updated successfully');
                } else {
                    $this->session->set_flashdata('error', 'User updation failed');
                }

                redirect('userListing');
            }
        }
    }

    /**
     * This function is used to edit the member information
     */
    function editStaff() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $userId = $this->input->post('userId');
            $formValues = $this->input->post();
            foreach ($formValues as $key => $val) {
                $consider = true;
                if (0 === strpos($key, 'cmb_')) {
                    $consider = false;
                }
                if (trim($val) != '' && $consider) {
                    if ($key == 'full_name') {
                        $val = ucwords(strtolower($val));
                    }
                    $arrDb[$key] = $val;
                }
            }

            $arrDb['createdDtm'] = date('Y-m-d H:i:s');
            $arrDb['updatedDtm'] = date('Y-m-d H:i:s');
            $memberInfo = $arrDb;
            $memberInfo["email_id"] = strtolower($memberInfo["email_id"]);
            $userInfo = array();

            $this->load->model('member_model');

            $result = $this->member_model->updateMemberInfo($memberInfo);

            if ($result == true) {
                $this->session->set_flashdata('success', 'User updated successfully');
            } else {
                $this->session->set_flashdata('error', 'User updation failed');
            }

            redirect('memberListing');
            //}
        }
    }

    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser() {
        if ($this->isAdmin() == TRUE) {
            echo(json_encode(array('status' => 'access')));
        } else {
            $userId = $this->input->post('userId');
            $userInfo = array('isDeleted' => 1, 'updatedBy' => $this->vendorId, 'updatedDtm' => date('Y-m-d H:i:s'));

            $result = $this->user_model->deleteUser($userId, $userInfo);

            if ($result > 0) {
                echo(json_encode(array('status' => TRUE)));
            } else {
                echo(json_encode(array('status' => FALSE)));
            }
        }
    }

    /**
     * This function is used to load the change password screen
     */
    function loadChangePass() {
        $this->global['pageTitle'] = 'Wreely : Change Password';

        $this->loadViews("changePassword", $this->global, NULL, NULL);
    }

    /**
     * This function is used to change the password of the user
     */
    function changePassword() {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('oldPassword', 'Old password', 'required|max_length[20]');
        $this->form_validation->set_rules('newPassword', 'New password', 'required|max_length[20]');
        $this->form_validation->set_rules('cNewPassword', 'Confirm new password', 'required|matches[newPassword]|max_length[20]');

        if ($this->form_validation->run() == FALSE) {
            $this->loadChangePass();
        } else {
            $oldPassword = $this->input->post('oldPassword');
            $newPassword = $this->input->post('newPassword');

            $resultPas = $this->user_model->matchOldPassword($this->vendorId, $oldPassword);

            if (empty($resultPas)) {
                $this->session->set_flashdata('nomatch', 'Your old password not correct');
                redirect('loadChangePass');
            } else {
                $usersData = array('password' => getHashedPassword($newPassword), 'updatedBy' => $this->vendorId,
                    'updatedDtm' => date('Y-m-d H:i:s'));

                $result = $this->user_model->changePassword($this->vendorId, $usersData);

                if ($result > 0) {
                    $this->session->set_flashdata('success', 'Password updation successful');
                } else {
                    $this->session->set_flashdata('error', 'Password updation failed');
                }

                redirect('loadChangePass');
            }
        }
    }

    function pageNotFound() {
        $this->global['pageTitle'] = 'Wreely : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }

}

?>
