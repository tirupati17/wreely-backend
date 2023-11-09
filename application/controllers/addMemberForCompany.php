<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class AddMemberForCompany extends CI_Controller
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
      $this->load->view('addMemberForCompany');
    }

    //Submit form
    public function addCoworker()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('full_name','Name','trim|required');
        $this->form_validation->set_rules('user_id','User Id','trim|required');
        $this->form_validation->set_rules('company_id','Company Id','trim|required');

        $this->form_validation->set_rules('email_id','Email','trim|required|valid_email|xss_clean|max_length[128]');
        $this->form_validation->set_rules('contact_no','Mobile Number','required|xss_clean');

        if($this->form_validation->run() == FALSE)
        {
            $this->index();
        }
        else
        {
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

            $this->load->model('member_model');
            $result = $this->member_model->addNewMemberForCompany($memberInfo);
            if ($result > 0) {
                echo (json_encode(array('status' => 1)));
            } else {
                echo (json_encode(array('status' => 0)));
            }
          }
     }

     function checkCoworkerEmailExist()
     {
         $this->load->model('member_model');
         $email = $this->input->post("email_id");
         $result = $this->member_model->checkCoworkerEmailExist($email);

         if (empty($result)) {
           echo("true");
         } else {
           echo("false");
         }
     }
}

?>
