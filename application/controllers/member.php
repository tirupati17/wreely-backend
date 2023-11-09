<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of member
 *
 * @author tirupatibalan
 */
class Member extends BaseController {

    /**
     * This is default constructor of the class
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('member_model');
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
     * This function is used to load the member list
     */
    function memberListing() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->model('member_model');
            $this->load->model('company_model');

            $searchText = $this->input->post('searchText');
            $data['searchText'] = $searchText;

            $this->load->library('pagination');

            $count = $this->member_model->memberListingCount($searchText);
            $allCompanies = $this->company_model->getAllCompanyNames();

            if (count($allCompanies)) {
                if (is_object($allCompanies[0])) {
                    $allCompanies = json_decode(json_encode($allCompanies), True);
                }
            }

            $returns = $this->paginationCompress("memberListing/", $count, 10);
            $data['companyNames'] = json_encode($allCompanies);
            $data['memberRecords'] = $this->member_model->memberListing($searchText, $returns["page"], $returns["segment"]);
            $this->global['pageTitle'] = 'Wreely : Member Listing';

            $this->loadViews("member", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the member list
     */
    function memberdetails($id = '') {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->model('member_model');
            $memberData = $this->member_model->getSingleMemberById($id);

            if (is_object($memberData[0])) {
                $memberData = json_decode(json_encode($memberData), True);
            }
            $data['data'] = $memberData;
            $this->load->view("jsonView", $data);
        }
    }

    function createFirebaseUser($memberInfo) {
        $serviceAccount = ServiceAccount::fromJsonFile(APPPATH.'/third_party/firebase/wreely-819a2-firebase-adminsdk-ii89z-06ea48c609.json');
        $apiKey = 'AIzaSyCGhh8dDBQ-L_q5IgjqOMOv30YbAzA66UM';
        $baseURL = "https://wreely-819a2.firebaseio.com";

        $firebase = (new Factory)
            ->withServiceAccountAndApiKey($serviceAccount, $apiKey)
            ->withDatabaseUri($baseURL)
            ->create();
        $database = $firebase->getDatabase();
        $auth = $firebase->getAuth();

        if (filter_var($memberInfo["email"], FILTER_VALIDATE_EMAIL)) { 
            $user = $auth->createUserWithEmailAndPassword($memberInfo["email"], "123456789");
            $memberKey = $user->getUid();

            //Update mysql member - START
            $memberInfo["member_fir_key"] = $memberKey;
            $this->member_model->updateMemberInfo($memberInfo);

            $updates = [
                'users/'.$memberKey => $memberInfo
            ];
            $result = $database
                  ->getReference()
                  ->update($updates);

            return $result;
            //Update mysql member - END
        } else {
            return NULL;
        }
        //Update firebase member - E
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists() {
        $email = $this->input->post("email");
        $result = $this->user_model->checkIfMemberExistByEmail($email);

        if (empty($result)) {
            echo("true");
        } else {
            echo("false");
        }
    }

    /**
     * This function is used to add new member to the system
     */
    function addMember() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $formValues = $this->input->post();

            foreach ($formValues as $key => $val) {
                $consider = true;
                if (0 === strpos($key, 'cmb_')) {
                    $consider = false;
                }
                if (trim($val) != '' && $consider) {
                    if ($val != 'full_name') {
                        $val = ucwords(strtolower($val));
                    }
                    $arrDb[$key] = $val;
                }
            }

            $arrDb['createdDtm'] = date('Y-m-d H:i:s');
            $arrDb['updatedDtm'] = date('Y-m-d H:i:s');

            $memberInfo = $arrDb;
            $memberInfo["email_id"] = strtolower($memberInfo["email_id"]);

            $this->load->model('member_model');
            $result = $this->member_model->addNewMemberData($memberInfo);

            if ($result > 0) {
                //$member = $this->member_model->getSingleMemberById($result);
                //$this->createFirebaseUser($member);
                $this->session->set_flashdata('success', 'New Member created successfully');
            } else {
                $this->session->set_flashdata('error', 'Member creation failed');
            }

            redirect('memberListing');
        }
    }

    /**
     * This function is used load member edit information
     * @param number $memberId : Optional : This is member id
     */
    function editLoadMember($memberId = NULL) {
        if ($this->isAdmin() == TRUE || $memberId == 1) {
            $this->loadThis();
        } else {
            if ($memberId == null) {
                redirect('memberListing');
            }

            $data['getMemberPlans'] = $this->member_model->getMemberPlans();
            $data['memberInfo'] = $this->member_model->getMemberInfo($memberId);

            $this->global['pageTitle'] = 'Wreely : Edit Member';

            $this->loadViews("editLoadMember", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to edit the member information
     */
    function editMember() {
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
     * This function is used to delete the member using memberId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteMember() {
        if ($this->isAdmin() == TRUE) {
            echo(json_encode(array('status' => 'access')));
        } else {
            $memberId = $this->input->post('memberId');
            $memberInfo = array('isDeleted' => 1, 'updatedBy' => $this->vendorId, 'updatedDtm' => date('Y-m-d H:i:s'));

            $result = $this->member_model->deleteMember($memberId, $memberInfo);

            if ($result > 0) {
                echo(json_encode(array('status' => TRUE)));
            } else {
                echo(json_encode(array('status' => FALSE)));
            }
        }
    }

    function companyMemberDetailsMail() {
        $memberId = $this->input->post('memberId');
        $emailbody = $this->input->post('emailbody');

        $emailbodyarray = explode(",", $emailbody);
        $body = "";
        foreach ($emailbodyarray as $em) {
            $body .= "<tr>";
            $body .= $em;
            $body .= "</tr>";
        }

        $body = "
              <table>
                  <thead>
                      <tr>
                        <th>
                        Company
                        </th>
                        <th>
                        Name
                        </th>
                        <th>
                        Attendance Date
                        </th>
                      </tr>
                  <thead>
                  <tbody>
                      {$body}
                  </tbody>
              </table>
      ";

        $to = 'tirupati.balan@gmail.com';
        $subject = 'SUBJECT';
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        if (mail($to, $subject, $body, $headers, 'hello@wreely.com')) {
            echo(json_encode(array('status' => false)));
        } else {
            echo(json_encode(array('status' => true)));
        }
    }

    /**
     * This function is used to delete the member using memberId
     * @return boolean $result : TRUE / FALSE
     */
    function getCompanyMembersById() {

        $this->load->model('member_model');
        $companyId = $this->input->post('id');
//        $fromDate = $this->input->post('from');
//        $toDate = $this->input->post('to');

        $companyMemberDetails = $this->member_model->getCompanyMembersById($companyId);
        if ($companyMemberDetails > 0) {
            echo(json_encode(array('data' => $companyMemberDetails, 'draw' => 1, 'recordsTotal' => 10, 'recordsFiltered' => 20)));
        } else {
            echo(json_encode(array('data' => [], 'draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0)));
        }
    }

    /**
     * This function is used to delete the member using memberId
     * @return boolean $result : TRUE / FALSE
     */
    function getMembersByCompanyId($id) {
        
        $this->load->model('member_model');
        //$companyId = $this->input->post('id');
        $companyMemberDetails = $this->member_model->getMemberByCompanyId($id);
        echo json_encode($companyMemberDetails);
    }

}
