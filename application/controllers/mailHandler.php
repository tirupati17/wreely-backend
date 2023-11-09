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

 class PDF extends FPDF
 {
     function Footer()
     {
         // Go to 1.5 cm from bottom
         $this->SetY(-15);
         // Select Arial italic 8
         $this->SetFont('Arial','I',8);
         // Print centered page number
         $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
     }

     function Header()
     {
         // Select Arial bold 15
         $this->SetFont('Arial','B',15);
         // Move to the middle
         $this->Cell(40);
         // Framed title
         $this->Cell(100,10,'Flexi Attendance Report',1,0,'C');
         // Line break
         $this->Ln(20);
     }
 }

class MailHandler extends BaseController {
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('flexi_attendance_model');
        $this->load->model('company_model');
        $this->load->model('user_model');
        $this->isLoggedIn();
    }

    function returnTabularPDF($header, $result) {
        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',12);
        foreach($header as $heading) {
            $pdf->Cell(45,8,$heading,1);
        }
        foreach($result as $row) {
          $pdf->SetFont('Arial','',12);
          $pdf->Ln();
          foreach($row as $column)
            $pdf->Cell(45,8,$column,1);
        }
        return $pdf->Output('', 'S');
    }

    function sendAttendanceReport() {
        $this->load->model('company_model');

        $memberId = $this->input->post('memberId');
        $startDate = $this->input->post('startDate');
        $endDate = $this->input->post('endDate');

        $result = $this->flexi_attendance_model->getFlexiListForMemberIdForAttendance($memberId, $startDate, $endDate);
        $header = array('Company', 'Member', 'Attendance Date', 'Membership');

        $pdfdoc = $this->returnTabularPDF($header, $result);
        $attachmentEncoded = chunk_split(base64_encode($pdfdoc));

        $vendorData = array();
        $vendorData["vendorName"] = $this->session->userdata('name');
        $vendorData["vendorId"] = $this->session->userdata('userId');
        $vendorData["vendorDomainNameReference"] = $this->session->userdata('domainNameReference');
        $vendorData["vendorLogoUrl"] = $this->session->userdata('vendorLogoUrl');
        $vendorData["vendorEmail"] = $this->session->userdata('vendorEmail');

        $data = $this->company_model->getCompanyDataForMember($memberId);
        if (count($data)) {
          $companyName = $data[0]["company_name"];
          $memberName = $data[0]["member_name"];
          $companyFounderEmail = $data[0]["company_email"];
          $this->sendAttendanceReportToMember($memberName, $startDate, $endDate, $companyName, $companyFounderEmail, $attachmentEncoded, $vendorData);
          echo (json_encode(array('status' => 1)));
        } else {
          echo (json_encode(array('status' => 0)));
        }
    }

    function sendCoworkerFillUpForm() {
      $companyId = $this->input->post('companyId');

      $vendorData = array();
      $vendorData["vendorName"] = $this->session->userdata('name');
      $vendorData["vendorId"] = $this->session->userdata('userId');
      $vendorData["vendorDomainNameReference"] = $this->session->userdata('domainNameReference');
      $vendorData["vendorLogoUrl"] = $this->session->userdata('vendorLogoUrl');
      $vendorData["vendorEmail"] = $this->session->userdata('vendorEmail');

      $data = $this->company_model->getCompanyInfo($companyId);
      if (count($data)) {
        $companyName = $data[0]->name;
        $companyFounderEmail = $data[0]->contact_person_email_id;
        $endpointPath = $vendorData["vendorDomainNameReference"].'/addCoworker?d=';

        $getParameter = array('companyId' => $companyId, 'vendorId' => $vendorData["vendorId"], 'companyName' => $companyName, 'vendorName' => $vendorData["vendorName"], 'vendorDomainNameReference' => $vendorData["vendorDomainNameReference"]);
        $link = $endpointPath.base64_encode(json_encode($getParameter));

        $this->sendCoworkerFillUpFormToCompanyOwner($companyName, $companyFounderEmail, $link, $vendorData);
        echo (json_encode(array('status' => 1)));
      } else {
        echo (json_encode(array('status' => 0)));
      }
    }

    function sendCoworkerFillUpFormToCompanyOwner($companyName, $companyFounderEmail, $link, $vendorData) {
        $vendorEmail = $vendorData["vendorEmail"];
        $vendorName = $vendorData["vendorName"];
        $vendorId = $vendorData["vendorId"];
        $vendorLogoMiniImage = $vendorData["vendorLogoUrl"];
         try {
            $mandrill = new Mandrill(MANDRILL_KEY);
            $templateName = 'CoworkerFillUpForm';
            $templateContent = null;
            $message = array(
                'subject' => 'Coworker Fillup Form - '.$vendorName,
                'from_email' => 'no_reply@wreely.com',
                'from_name' => $vendorName.' via Wreely',
                'to' => array(
                    array(
                        'email' => $companyFounderEmail,
                        'type' => 'to'
                    ),
                    array(
                        'email' => $vendorEmail,
                        'type' => 'bcc'
                    ),
                    array(
                        'email' => 'tirupati.balan@gmail.com',
                        'type' => 'bcc'
                    )
                ),
                'important' => true,
                'merge' => true,
                'global_merge_vars' => array(
                    array(
                        'name' => 'CNAME',
                        'content' => $companyName
                    ),
                    array(
                        'name' => 'FLINK',
                        'content' => $link
                    ),
                    array(
                        'name' => 'HIMAGE',
                        'content' => $vendorLogoMiniImage
                    ),
                    array(
                        'name' => 'VNAME',
                        'content' => $vendorName
                    )
                ),
                'tags' => array('CoworkerFillUpForm')
            );
            $async = false;
            return $mandrill->messages->sendTemplate($templateName, $templateContent, $message, $async);
          } catch(Mandrill_Error $e) {
            return 0;
          }
    }

    function sendAttendanceReportToMember($memberName, $startDate, $endDate, $companyName, $companyFounderEmail, $attachmentEncoded, $vendorData) {
            $vendorEmail = $vendorData["vendorEmail"];
            $vendorName = $vendorData["vendorName"];
            $vendorId = $vendorData["vendorId"];
            $vendorLogoMiniImage = $vendorData["vendorLogoUrl"];
         try {
            $fileName = $startDate.'_'.$endDate.'.pdf';
            $mandrill = new Mandrill(MANDRILL_KEY);
            $templateName = 'AttendanceReportToMember';
            $templateContent = null;
            $message = array(
                'subject' => 'Flexi Attendance Report - '.$vendorName,
                'from_email' => 'no_reply@wreely.com',
                'from_name' => $vendorName.' via Wreely',
                'to' => array(
                    array(
                        'email' => $companyFounderEmail,
                        'type' => 'to'
                    ),
                    array(
                        'email' => $vendorEmail,
                        'type' => 'bcc'
                    ),
                    array(
                        'email' => 'tirupati.balan@gmail.com',
                        'type' => 'bcc'
                    )
                ),
                'important' => true,
                'merge' => true,
                'global_merge_vars' => array(
                    array(
                        'name' => 'CNAME',
                        'content' => $companyName
                    ),
                    array(
                        'name' => 'MNAME',
                        'content' => $memberName
                    ),
                    array(
                        'name' => 'STARTDATE',
                        'content' => $startDate
                    ),
                    array(
                        'name' => 'ENDDATE',
                        'content' => $endDate
                    ),
                    array(
                        'name' => 'HIMAGE',
                        'content' => $vendorLogoMiniImage
                    ),
                    array(
                        'name' => 'VNAME',
                        'content' => $vendorName
                    )
                ),
                "attachments" => array(
                    array(
                        'content' => $attachmentEncoded,
                        'type' => "application/pdf",
                        'name' => $fileName
                      )
                ),
                'tags' => array('AttendanceReportToMember')
            );
            $async = false;
            $mandrill->messages->sendTemplate($templateName, $templateContent, $message, $async);
          } catch(Mandrill_Error $e) {

          }
    }
}
