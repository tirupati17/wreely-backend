<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

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
class FlexiAttendance extends BaseController {
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('flexi_attendance_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Wreely : Dashboard';

        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }

     /**
     * This function is used to load the member list
     */
    function flexiAttendanceListing()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('flexi_attendance_model');
            $this->load->model('company_model');
            $this->load->model('member_model');
            $this->load->model('membership_type_model');

            $data['membershipTypes'] = $this->membership_type_model->getAllMembershipTypes();
            $data['companies'] = $this->company_model->getAllCompanies();
            $data['members'] = $this->member_model->getAllMembers();

            $searchText = $this->input->post('searchText');
            $data['searchText'] = $searchText;

            $this->load->library('pagination');
            $count = $this->flexi_attendance_model->flexiAttendanceCount($searchText);

            $returns = $this->paginationCompress ( "flexiAttendanceListing/", $count, 10 );
            $data['flexiAttendanceRecords'] = $this->flexi_attendance_model->flexiAttendanceListing($searchText, $returns["page"], $returns["segment"]);
            $this->global['pageTitle'] = 'Wreely : Flexi Attendance';
            $this->loadViews("flexiAttendance", $this->global, $data, NULL);
        }
    }

    function sendAttendanceReport() {
        $memberId = $this->input->post('memberId');
        $startDate = $this->input->post('startDate');
        $endDate = $this->input->post('endDate');
        $sendAttendanceReport = $this->flexi_attendance_model->sendAttendanceReport($memberId, $startDate, $endDate);
        if ($sendAttendanceReport) {
            echo(json_encode(array('status' => 1)));
        } else {
            echo(json_encode(array('status' => 0)));
        }
    }

    function flexiAttendanceOfMember()
    {
      $memberId = $this->input->post('memberId');
      $fromDate = $this->input->post('from');
      $toDate = $this->input->post('to');

      $flexiMemberDetails = $this->flexi_attendance_model->getFlexiListForMemberId($memberId, $fromDate, $toDate);
      if ($flexiMemberDetails > 0) {
          echo(json_encode(array('data' => $flexiMemberDetails, 'draw' => 1, 'recordsTotal' => 10, 'recordsFiltered' => 20)));
      } else {
          echo(json_encode(array('data' => [], 'draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0)));
      }
    }

    function addFlexiAttendance() {
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
                    $arrDb[$key] = $val;
                }
            }
            $flexiAttendanceInfo = $arrDb;
            $this->load->model('flexi_attendance_model');
            $result = $this->flexi_attendance_model->addNewFlexiAttendance($flexiAttendanceInfo);

            if ($result > 0) {
                $this->session->set_flashdata('success', 'New flexi recorded successfully');
            } else {
                $this->session->set_flashdata('error', 'Flexi creation failed');
            }

            redirect('flexiAttendanceListing');
        }
    }

    function flexiAttendanceMail()
    {
      $memberId = $this->input->post('memberId');
      $emailbody = $this->input->post('emailbody');

      $emailbodyarray = explode(",", $emailbody);
      $body = "";
      foreach($emailbodyarray as $em){
          $body .= "<tr>";
          $body .= $em;
          $body .= "</tr>";
      }

      $body =  "
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
      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

      if (mail($to, $subject, $body,  $headers, 'hello@wreely.com')) {
          echo(json_encode(array('status' => false)));
      } else {
          echo(json_encode(array('status' => true)));
      }
    }
}
