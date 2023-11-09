<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Company (CompanyController)
 * Company Class to control all user related operations.
 * @version : 1.1
 */

class Company extends BaseController {
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('company_model');
        $this->isLoggedIn();
    }

     /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Wreely : Companies';

        $this->loadViews("company", $this->global, NULL , NULL);
    }

     /**
     * This function is used to load the company list
     */
    function companyListing()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('company_model');

            $searchText = $this->input->post('searchText');
            $data['searchText'] = $searchText;

            $this->load->library('pagination');

            $count = $this->company_model->companyListingCount($searchText);

            $returns = $this->paginationCompress ( "companyListing/", $count, 10 );

            $data['companyRecords'] = $this->company_model->companyListing($searchText, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = 'Wreely : Company Listing';

            $this->loadViews("company", $this->global, $data, NULL);
        }
    }

     /**
     * This function is used to edit the member information
     */
    function editCompany() {
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
                    if ($key == 'contact_person_email_id') {
                        $val = strtolower($val);
                    }
                    $arrDb[$key] = $val;
                }
            }
            $companyInfo = $arrDb;
            $this->load->model('company_model');

            $result = $this->company_model->updateCompanyInfo($companyInfo);

            if ($result == true) {
                $this->session->set_flashdata('success', 'User updated successfully');
            } else {
                $this->session->set_flashdata('error', 'User updation failed');
            }
            redirect('companyListing');
        }
    }

    /**
     * This function is used to add new member to the system
     */
    function addCompany() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $formValues = $this->input->post();
            $ucwordsArr = array('name','contact_person_name');

            foreach ($formValues as $key => $val) {
                $consider = true;
                if (0 === strpos($key, 'cmb_')) {
                    $consider = false;
                }
                if (trim($val) != '' && $consider) {
                    if (in_array($key, $ucwordsArr)) {
                        $val = ucwords(strtolower($val));
                    }
                    $arrDb[$key] = $val;
                }
            }

            $companyInfo = $arrDb;
            $companyInfo["contact_person_email_id"] = strtolower($companyInfo["contact_person_email_id"]);

            $this->load->model('company_model');
            $result = $this->company_model->addNewCompany($companyInfo);

            if ($result > 0) {
                $this->session->set_flashdata('success', 'New member created successfully');
            } else {
                $this->session->set_flashdata('error', 'Member creation failed');
            }

            redirect('companyListing');
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

    function deleteCompany() {
        if ($this->isAdmin() == TRUE) {
            echo(json_encode(array('status' => 'access')));
        } else {
            $companyId = $this->input->post('companyId');
            $companyInfo = array('id' => $companyId, 'isDeleted' => 1);
            $result = $this->company_model->deleteCompanies($companyInfo);
            if ($result > 0) {
                echo(json_encode(array('status' => TRUE)));
            } else {
                echo(json_encode(array('status' => FALSE)));
            }
        }
    }
}
