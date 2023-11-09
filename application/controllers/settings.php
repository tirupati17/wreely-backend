<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

//require APPPATH . '/libraries/ZohoBooks.php';

class Settings extends BaseController {

    private $userId;
    private $zohoAuthToken;
    private $zohoOrgID;

    /**
     * This is default constructor of the class
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('member_model');
        $this->load->model('company_model');
        $this->isLoggedIn();
        $this->userId = $this->session->userdata('userId');
        $this->load->library('ZohoBooks');

        // Save Zoho Auth token and Organization ID to class-wide variables
    }

    /**
     * This function used to load the first screen of the setting
     */
    public function index() {
        if (class_exists('ZohoBooks')) {
            $this->global['pageTitle'] = 'Wreely : Settings';
            $this->global['zohoAuth'] = false;
            $this->global['quickAuth'] = false;
            $userInfo = $this->user_model->getUserInfo($this->userId);
            $userInfo = json_decode(json_encode($userInfo), true);
            if (count($userInfo)) {
               $userInfo = $userInfo[0];
                //print_r($userInfo);
                if ($userInfo['zohobooks_authtoken'] != '' && $userInfo['zohobooks_organization_id'] != '') {
                    $this->global['zohoAuth'] = true;
                    $this->global['zohobooks_authtoken'] = $userInfo['zohobooks_authtoken'];
                    $this->global['zohobooks_organization_id'] = $userInfo['zohobooks_organization_id'];
                }
                if ($userInfo['quickbooks_authtoken'] != '') {
                    $this->global['quickAuth'] = true;
                }
            }
            /* $zohoBookObject = new $this->zohobooks('9389bc3a6c6ed54e6ceb5bcaea6c4e8d','654927368');
              $orgArray = json_decode($orgObject, true);
              $sliceFromZohoContactsArray = $zohoContactsArray['contacts']; */
            $this->loadViews("settings", $this->global, NULL, NULL);
        } else {
            echo "Class not loaded";
        }
    }

    /**
     * This function gets contacts from Zoho books server using Zohobooks API
     */
    function getZohoBooksContacts($zohoAuthId = '', $zohoOrgId = '') {
        if (class_exists('ZohoBooks')) {
            // Provide Auth token and company id to Zoho books class
            $zohoBookObject = new ZohoBooks($zohoAuthId, $zohoOrgId);
            // Fetch all contacts from Zoho books
            $zohoContactStdObj = $zohoBookObject->allContacts();
            return $zohoContactStdObj;
        } else {
            echo "Class not loaded";
        }
    }

    /**
     * This function gets contact by contact id from Zoho books server using Zohobooks API
     */
    function getZohoBooksContactByContactId($id = '', $zohoAuthId = '', $zohoOrgId = '') {
        if (class_exists('ZohoBooks')) {
            // Provide Auth token and company id to Zoho books class
            $zohoBookObject = new ZohoBooks($zohoAuthId, $zohoOrgId);
            // Fetch all contacts from Zoho books
            $zohoContactStdObj = $zohoBookObject->getContact($id);
            return $zohoContactStdObj;
        } else {
            echo "Class not loaded";
        }
    }

    /**
     * This function gets contact by contact id from Zoho books server using Zohobooks API
     */
    function getOrganizationDetails($zohoAuthId = '') {
        if (class_exists('ZohoBooks')) {
            // Provide Auth token and company id to Zoho books class
            $zohoBookObject = new ZohoBooks($zohoAuthId);
            // Fetch all contacts from Zoho books
            $zohoContactStdObj = $zohoBookObject->getOrganization($zohoAuthId);
            return $zohoContactStdObj;
        } else {
            echo "Class not loaded";
        }
    }

    /**
     * Function to insert company's data to Wreely's database
     */
    function saveUserDataFromZoho() {

        $this->load->model('member_model');

        // If logged in user already has zoho auth id and organization id then take it from database strightaway
        $zohoAuth = $this->input->post('zohoAuth');
        $companyId = '';
        if (!empty($zohoAuth)) {
            $zohoOrgId = $this->input->post('zohoOrgId');
            $userInfo = array(
                'zohobooks_authtoken' => $zohoAuth,
                'zohobooks_organization_id' => $zohoOrgId,
            );

            $updateUserResult = $this->user_model->editUser($userInfo, $this->userId);

            if ($updateUserResult) {
                $zohoContactStdObj = $this->getZohoBooksContacts($zohoAuth, $zohoOrgId);

                $zohoOrganizationObj = $this->getOrganizationDetails($zohoAuth);

                if ($zohoOrganizationObj) {
                    $zohoOrganizationArray = json_decode($zohoOrganizationObj, true);
                    $sliceFromOrganizationArray = $zohoOrganizationArray['organizations'];
                    $arrayOfSelectedOrganizationData = array();
                    foreach ($sliceFromOrganizationArray as $key => $val) {
                        $arrayOfSelectedOrganizationData = array(
                            'contact_person_email_id' => $val['email'],
                            'name' => $val['name'],
                            'contact_member_id' => $val['organization_id'],
                            'contact_person_name' => $val['contact_name'],
                            'status' => 1
                        );

                        // Check if company exists
                        $arrMembers = $this->company_model->checkIfCompanyExistByEmail($val['email']);
                        // If it does exists then don't insert into database
                        if (!$arrMembers) {
                            $companyId = $this->company_model->addNewCompany($arrayOfSelectedOrganizationData);
                            $arrJson = array('status' => 1, 'message' => 'data added succesfully');
                        } else {
                            // Just update
                            $this->company_model->updateCompanyInfo($arrayOfSelectedOrganizationData);
                            $arrJson = array('status' => 1, 'message' => 'data updated succesfully');
                        }
                    }
                }

                if (isset($zohoContactStdObj)) {

                    $zohoContactsArray = json_decode($zohoContactStdObj, true);
                    $sliceFromZohoContactsArray = $zohoContactsArray['contacts'];
                    $arrayOfSelectedData = array();
                    foreach ($sliceFromZohoContactsArray as $key => $val) {
                        $contactDetailsStdObj = $this->getZohoBooksContactByContactId($val['contact_id'], $zohoAuth, $zohoOrgId);
                        $contactDetailsArray = json_decode($contactDetailsStdObj, true);

                        $billingAddressArray = $contactDetailsArray['contact']['billing_address'];
                        $completeAddress = '';
                        if ($this->checkIfAddressArrayNotEmpty($billingAddressArray)) {

                            $completeAddress = $billingAddressArray['attention'] . ', ' . $billingAddressArray['address'] . ', ' . $billingAddressArray['street2'] . ', ' . $billingAddressArray['city'] . ' - ' . $billingAddressArray['zip'] . '. ' . $billingAddressArray['country'] . '.';
                        }

                        $arrayOfSelectedData = array(
                            'email_id' => $val['email'],
                            'contact_no' => $val['mobile'],
                            'address' => $completeAddress,
                            'full_name' => $val['first_name'] . ' ' . $val['last_name'],
                            'status' => 1,
                            'company_id' => $companyId,
                            'updatedDtm' => date('Y-m-d h:i:s')
                        );
                        // Check if company exists
                        $arrMembers = $this->member_model->checkIfMemberExistByEmail($val['email']);
                        $arrMembers = json_decode(json_encode($arrMembers), true);
                        // print_r($arrMembers);
                        // exit;
                        // If it does exists then don't insert into database
                        if (!$arrMembers) {
                            $arrayOfSelectedData['company_id'] = $companyId;
                            $this->member_model->addNewMemberData($arrayOfSelectedData);
                            $arrJson = array('status' => 1, 'message' => 'data added succesfully');
                        } else {
                            // Just update
                            $arrayOfSelectedData['company_id'] = $arrMembers[0]['company_id'];
                            $this->member_model->updateMemberInfo($arrayOfSelectedData);
                            $arrJson = array('status' => 1, 'message' => 'data updated succesfully');
                        }
                    }
                    echo json_encode($arrJson);
                }
                //INSERT INTO `tbl_members` (`id`, `full_name`, `address`, `dob`, `contact_no`, `email_id`, `company_id`, `occupation`, `reference_source`, `membership_plan`, `joining_date`, `payment_date`, `mode_of_payment`, `status`, `createdDtm`, `updatedDtm`) VALUES (NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL)
            }
        }
    }

    function checkIfAddressArrayNotEmpty($addressArray) {
        $cntr = 0;
        $isEmpty = false;
        foreach ($addressArray as $key => $val) {
            if ($key != 'address_id') {
                if ($val != '')
                    $cntr++;
            }
        }
        if ($cntr > 0) {
            $isEmpty = true;
        }
        return $isEmpty;
    }

    function syncFirebase() {
        $isProduction = true;
        if ($isProduction) { //production
            $serviceAccount = ServiceAccount::fromJsonFile(APPPATH.'/third_party/firebase/wreely-819a2-firebase-adminsdk-ii89z-06ea48c609.json');
            $apiKey = 'AIzaSyCGhh8dDBQ-L_q5IgjqOMOv30YbAzA66UM';
            $baseURL = "https://wreely-819a2.firebaseio.com";
        } else {
            $serviceAccount = ServiceAccount::fromJsonFile(APPPATH.'/third_party/firebase/wreely-smoke-firebase-adminsdk-lfol4-ec8504794b.json');
            $apiKey = 'AIzaSyAOs0hFZ69ZKOGmiE6Ze-bqSNFJi841_dY';
            $baseURL = "https://wreely-smoke.firebaseio.com";
        }

        $firebase = (new Factory)
            ->withServiceAccountAndApiKey($serviceAccount, $apiKey)
            ->withDatabaseUri($baseURL)
            ->create();
        $database = $firebase->getDatabase();
        $auth = $firebase->getAuth();

        $userKeys = array();
        $userResult = $this->user_model->getCurrentUsersForFIR();

        foreach ($userResult as $userRow) { //will loop one time only 
            //Insert vendor and get key - START
            $userKey = $this->user_model->checkIfFIRKeyExistForUser($userRow["id"]);
            if ($userKey == NULL) {
              $userData = $database
                    ->getReference('vendors')
                    ->push($userRow);
              //insert newly push key into user table
              $userKey = $userData->getKey();

              //Update mysql vendor - START
              $userInfo["user_fir_key"] = $userKey;
              $userInfo["id"] = $userRow["id"];
              $this->user_model->updateUserInfo($userInfo);
              //Update mysql vendor - END
            }
            //Insert vendor and get key - END

            //company
            $allMemberKeys = array();
            $companyKeys = array();
            $companyResult = $this->company_model->getAllCompaniesForFIR();
            foreach ($companyResult as $companyRow) {
                //Insert company and get key - START
                $companyKey = $this->company_model->checkIfFIRKeyExistForCompany($companyRow["id"]);
                if ($companyKey == NULL) {
                  $companyData = $database
                        ->getReference('companies')
                        ->push($companyRow);
                  $companyKey = $companyData->getKey();

                  //Update mysql company - START
                  $companyInfo["company_fir_key"] = $companyKey;
                  $companyInfo["id"] = $companyRow["id"];
                  $this->company_model->updateCompanyInfo($companyInfo);
                  //Update mysql company - END
                }
                //Insert company and get key - End

                $memberKeys = array();
                $companyMemberResult = $this->member_model->getAllMemberForCompanyId($companyRow["id"]);
                foreach ($companyMemberResult as $companyMemberRow) {
                    //Insert member and get key - START
                    $companyMemberKey = $this->member_model->checkIfFIRKeyExistForMember($companyMemberRow["id"]);
                    if ($companyMemberKey == NULL) {
                        if (filter_var($companyMemberRow["email"], FILTER_VALIDATE_EMAIL)) { //(filter_var($companyMemberRow["email"], FILTER_VALIDATE_EMAIL))
                          $user = $auth->createUserWithEmailAndPassword($companyMemberRow["email"], '123456789');
                          $companyMemberKey = $user->getUid();

                          //Update mysql member - START
                          $memberInfo["member_fir_key"] = $companyMemberKey;
                          $memberInfo["id"] = $companyMemberRow["id"];
                          $this->member_model->updateMemberInfo($memberInfo);
                          //Update mysql member - END
                        }
                    }
                    //Insert member and get key - END

                    //Update firebase member - START
                    $companyMemberRow["vendors"] = array($userKey); //check if key already exist if yes then dont do anything or else add new user key in vendors array
                    $companyMemberRow["company_key"] = $companyKey;

                    $updates = [
                        'users/'.$companyMemberKey => $companyMemberRow
                    ];
                    $companyMemberData = $database
                          ->getReference()
                          ->update($updates);
                    //Update firebase member - End

                    $memberKeys[] = $companyMemberKey;
                    $allMemberKeys[] = $companyMemberKey;
                }
                //Update firebase company - START
                $companyRow["members"] = $memberKeys;
                $companyRow["vendor_key"] = $userKey;

                $updates = [
                    'companies/'.$companyKey => $companyRow
                ];
                $companyData = $database
                      ->getReference()
                      ->update($updates);
                //Update firebase company - END

                $companyKeys[] = $companyKey;
            }
            $userRow["companies"] = $companyKeys;
            $userRow["members"] = $allMemberKeys;

            //Update firebase user - START
            $updates = [
                'vendors/'.$userKey => $userRow
            ];
            $userData = $database
                  ->getReference()
                  ->update($updates);
            //Update firebase user - END
            $userKeys[] = $userKey;
        }
        echo json_encode($userKeys);
    }

    function pageNotFound() {
        $this->global['pageTitle'] = 'Wreely : 404 - Page Not Found';

        $this->loadViews("404", $this->global, NULL, NULL);
    }

}

?>
