<?php

/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 *
 */
class DbHandler {

    private $conn;

    function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }

    function getHashedPassword($plainPassword) {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }

    public function verifyHashedPassword($plainPassword, $hashedPassword) {
        return password_verify($plainPassword, $hashedPassword) ? true : false;
    }

    /* ------------- `users` table method ------------------ */

    /**
     * Creating new user
     * @param String $name User full name
     * @param String $email User login email id
     * @param String $password User login password
     */
    public function createUser($name, $email, $password) { //Actual vendor registration
        require_once 'PassHash.php';
        $response = array();

        // First check if user already existed in db
        if (!$this->isUserExists($email)) {
            // Generating password hash
            $password_hash = $this->getHashedPassword($password);

            // Generating API key
            $access_token = $this->generateAccessToken();

            // insert query
            $stmt = $this->conn->prepare("INSERT INTO tbl_users (name, email, password, access_token) values(?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $password_hash, $access_token);
            $result = $stmt->execute();
            $stmt->close();

            // Check for successful insertion
            if ($result) {
                // User successfully inserted
                return USER_CREATED_SUCCESSFULLY;
            } else {
                // Failed to create user
                return USER_CREATE_FAILED;
            }
        } else {
            // User with same email already existed in the db
            return USER_ALREADY_EXISTED;
        }
        return $response;
    }

    /**
     * Checking user login
     * @param String $email User login email id
     * @param String $password User login password
     * @return boolean User login status success/fail
     */
    public function checkUserLogin($email, $plain_password) {
        $stmt = $this->conn->prepare("SELECT password, access_token FROM tbl_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $stmt->bind_result($hash_password, $access_token);
            if ($stmt->fetch()) {
              if ($this->verifyHashedPassword($plain_password, $hash_password)) {
                return TRUE;
              } else {
                return FALSE;
              }
            } else {
              return FALSE;
            }
        }
    }

    public function updateMemberFirKey($member_id, $firebase_key) {
        $stmt = $this->conn->prepare("UPDATE tbl_members set member_fir_key = ? WHERE id = ?");
        $stmt->bind_param("ss", $firebase_key, $member_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    public function updateUserDeviceToken($user_id, $deviceToken) {
        $stmt = $this->conn->prepare("UPDATE tbl_users set device_token = ? WHERE userId = ?");
        $stmt->bind_param("ss", $deviceToken, $user_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    public function changeUserPassword($user_id, $newPassword) {
        $password_hash = $this->getHashedPassword($newPassword);
        $stmt = $this->conn->prepare("UPDATE tbl_users set password = ? WHERE userId = ?");
        $stmt->bind_param("ss", $password_hash, $user_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    public function insertRandomCodeInUserAgainstMobile($user_id, $randomCode, $mobileNumber, $countryCode) {
        $stmt = $this->conn->prepare("UPDATE tbl_users set random_code = ?, mobile = ?, country_code = ? WHERE userId = ?");
        $stmt->bind_param("ssss", $randomCode, $mobileNumber, $countryCode, $user_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    public function insertRandomCodeAndNewAccessTokenInMemberAgainstMobile($randomCode, $mobileNumber) {
        $accessToken = $this->getAccessTokenByMobileNumber($mobileNumber);
        if ($accessToken == NULL) {
            $accessToken = $this->generateAccessToken();
            $stmt = $this->conn->prepare("UPDATE tbl_members set random_code = ?, access_token = ? WHERE contact_no = ?");
            $stmt->bind_param("ssi", $randomCode, $accessToken, $mobileNumber);
            $stmt->execute();
            $num_affected_rows = $stmt->affected_rows;
            $stmt->close();
            return $num_affected_rows > 0;
        } else {
            $stmt = $this->conn->prepare("UPDATE tbl_members set random_code = ? WHERE contact_no = ?");
            $stmt->bind_param("si", $randomCode, $mobileNumber);
            $stmt->execute();
            $num_affected_rows = $stmt->affected_rows;
            $stmt->close();
            return $num_affected_rows > 0;
        }
    }

    public function confirmLoginCodeInMember($member_id, $randomCode) {
        if ($this->checkRandomCodeInMember($member_id, $randomCode)) {
            return $this->getAccessTokenByMemberId($member_id);
        } else {
            return NULL;
        }
    }

    public function confirmRandomCodeInUser($user_id, $randomCode) {
        if ($this->checkRandomCodeInUser($user_id, $randomCode)) {
            $stmt = $this->conn->prepare("UPDATE tbl_users set is_mobile_verified = 1 WHERE userId = ?");
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $num_affected_rows = $stmt->affected_rows;
            $stmt->close();
            return $num_affected_rows > 0;
        } else {
            return 0;
        }
    }

    public function checkRandomCodeInMember($member_id, $randomCode) {
        $stmt = $this->conn->prepare("SELECT * from tbl_members WHERE id = ? AND random_code = ?");
        $stmt->bind_param("ss", $member_id, $randomCode);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    public function checkRandomCodeInUser($user_id, $randomCode) {
        $stmt = $this->conn->prepare("SELECT * from tbl_users WHERE userId = ? AND random_code = ?");
        $stmt->bind_param("ss", $user_id, $randomCode);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    /**
     * Checking for duplicate user by email address
     * @param String $email email to check in db
     * @return boolean
     */
    private function isUserExists($email) {
        $stmt = $this->conn->prepare("SELECT userId from tbl_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    public function getVendorDetail($vendorId) {
        $stmt = $this->conn->prepare("SELECT userId, name, email, vendor_logo_url FROM tbl_users WHERE userId = ?");
        $stmt->bind_param("s", $vendorId);
        if ($stmt->execute()) {
            $stmt->bind_result($userId, $name, $email, $vendor_logo_url);
            $user = array();
            if ($stmt->fetch()) {
                $user["vendor_logo_url"] = $vendor_logo_url == NULL ? "" : $vendor_logo_url; //use wreely logo if vendor logo is empty
                $user["id"] = $userId;
                $user["name"] = $name;
                $user["email_id"] = $email;
                $user["total_member"] = 180;//$this->getMembersCountForVendor($userId);
                $stmt->close();
                return $user;
            } else {
                $stmt->close();
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getUserDetailByUserID($userId) {
        $stmt = $this->conn->prepare("SELECT userId, name, email, vendor_logo_url, access_token FROM tbl_users WHERE userId = ?");
        $stmt->bind_param("s", $userId);
        if ($stmt->execute()) {
            $stmt->bind_result($userId, $name, $email, $vendor_logo_url, $access_token);
            $user = array();
            if ($stmt->fetch()) {
                $user["vendor_logo_url"] = $vendor_logo_url == NULL ? "" : $vendor_logo_url; //use wreely logo if vendor logo is empty
                $user["user_id"] = $userId;
                $user["name"] = $name;
                $user["email"] = $email;
                $user["access_token"] = $access_token;
                $stmt->close();
                return $user;
            } else {
                $stmt->close();
                return NULL;
            }
        } else {
            return NULL;
        }
    }
    /**
     * Fetching user by email
     * @param String $email User email id
     */
    public function getUserByEmailOrMobile($emailOrMobile) {
        $stmt = $this->conn->prepare("SELECT userId, name, email, vendor_logo_url, access_token FROM tbl_users WHERE email = ? OR mobile = ?");
        $stmt->bind_param("ss", $emailOrMobile, $emailOrMobile);
        if ($stmt->execute()) {
            // $user = $stmt->get_result()->fetch_assoc();
            $stmt->bind_result($userId, $name, $email, $vendor_logo_url, $access_token);
            $user = array();
            if ($stmt->fetch()) {
                $user["vendor_logo_url"] = $vendor_logo_url == NULL ? "" : $vendor_logo_url; //use wreely logo if vendor logo is empty
                $user["user_id"] = $userId;
                $user["name"] = $name;
                $user["email"] = $email;
                $user["access_token"] = $access_token;
                $stmt->close();
                return $user;
            } else {
                $stmt->close();
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    /**
     * Fetching user api key
     * @param String $user_id user id primary key in user table
     */

    public function getAccessTokenByMobileNumber($mobileNumber) {
        $stmt = $this->conn->prepare("SELECT access_token FROM tbl_members WHERE contact_no = ?");
        $stmt->bind_param("s", $mobileNumber);
        if ($stmt->execute()) {
            $stmt->bind_result($access_token);
            $stmt->fetch();
            $stmt->close();
            return $access_token;
        } else {
            return NULL;
        }
    }

    public function getAccessTokenByMemberId($member_id) {
        $stmt = $this->conn->prepare("SELECT access_token FROM tbl_members WHERE id = ?");
        $stmt->bind_param("s", $member_id);
        if ($stmt->execute()) {
            $stmt->bind_result($access_token);
            $stmt->fetch();
            $stmt->close();
            return $access_token;
        } else {
            return NULL;
        }
    }

    public function getAccessTokenById($user_id) {
        $stmt = $this->conn->prepare("SELECT access_token FROM tbl_users WHERE userId = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $stmt->bind_result($access_token);
            $stmt->fetch();
            $stmt->close();
            return $access_token;
        } else {
            return NULL;
        }
    }

    /**
     * Fetching user id by api key
     * @param String $api_key user api key
     */
    public function getUserId($access_token) {
        $stmt = $this->conn->prepare("SELECT userId FROM tbl_users WHERE access_token = ?");
        $stmt->bind_param("s", $access_token);
        if ($stmt->execute()) {
            $stmt->bind_result($user_id);
            $stmt->fetch();
            $stmt->close();
            return $user_id;
        } else {
            return NULL;
        }
    }

    public function getMemberId($access_token) {
        $stmt = $this->conn->prepare("SELECT id FROM tbl_members WHERE access_token = ?");
        $stmt->bind_param("s", $access_token);
        if ($stmt->execute()) {
            $stmt->bind_result($id);
            $stmt->fetch();
            $stmt->close();
            return $id;
        } else {
            return NULL;
        }
    }

    public function getMemberDetailWithAccessToken($access_token) {
        $memberId = $this->getMemberId($access_token);
        if ($memberId != NULL) {
            return $this->getMemberDetails($memberId);
        } else {
            return NULL;
        }
    }

    public function getMemberDetails($member_id) {
        $stmt = $this->conn->prepare("SELECT MemberTbl.id as member_id, 
        MemberTbl.full_name as member_name, 
        MemberTbl.about_me, 
        MemberTbl.occupation, 
        MemberTbl.website_url, 
        MemberTbl.linkedin_url, 
        MemberTbl.instagram_url, 
        MemberTbl.twitter_url, 
        MemberTbl.facebook_url, 
        MemberTbl.profile_image, 
        MemberTbl.member_fir_key,
        MemberTbl.email_id as member_email_id, 
        MemberTbl.contact_no as member_contact_no, 
        MemberTbl.company_id, 
        CompanyTbl.name as company_name, 
        VendorTbl.name as vendor_name, 
        VendorTbl.userId as vendor_id, 
        VendorTbl.email as vendor_email FROM tbl_members as MemberTbl, tbl_companies as CompanyTbl, tbl_users as VendorTbl WHERE MemberTbl.id = ? AND MemberTbl.company_id = CompanyTbl.id AND MemberTbl.user_id = VendorTbl.userId");
        $stmt->bind_param("s", $member_id);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            foreach ($row as $dict) {
                $response["id"] = $dict["member_id"];
                $response["name"] = $dict["member_name"];
                $response["email_id"] = $dict["member_email_id"];
                $response["contact_no"] = $dict["member_contact_no"];
                $response["company_name"] = $dict["company_name"];
                $response["company_id"] = $dict["company_id"];
                $response["about_me"] = $dict["about_me"];
                $response["member_fir_key"] = $dict["member_fir_key"];
                $response["occupation"] = $dict["occupation"];
                $response["website_url"] = $dict["website_url"];
                $response["linkedin_url"] = $dict["linkedin_url"];
                $response["instagram_url"] = $dict["instagram_url"];
                $response["twitter_url"] = $dict["twitter_url"];
                $response["facebook_url"] = $dict["facebook_url"];
                $response["profile_pic_url"] = $dict["profile_image"];
                $response["membership_type_id"] = $this->getMembershipTypeForMember($response["id"]);
                
                $vendor["name"] = $dict["vendor_name"];
                $vendor["email_id"] = $dict["vendor_email"];
                $vendor["id"] = $dict["vendor_id"];

                $response["vendors"] = array($vendor); //for multiple vendor features 
            }
            return $response;
        } else {
            return NULL;
        }
    }

    public function getMemberByMobileNumber($mobileNumber) {
        $stmt = $this->conn->prepare("SELECT id, user_id, full_name, email_id FROM tbl_members WHERE contact_no = ?");
        $stmt->bind_param("s", $mobileNumber);
        if ($stmt->execute()) {
            $stmt->bind_result($id, $user_id, $full_name, $email_id);
            if ($stmt->fetch()) {
                $member["id"] = $id;
                $member["vendor_id"] = $user_id;
                $member["name"] = $full_name;
                $member["email"] = $email_id;
                $stmt->close();
                return $member;
            } else {
                $stmt->close();
                return NULL;
            }
            return $user_id;
        } else {
            return NULL;
        }
    }

    public function getMemberVendorId($access_token) {
        $stmt = $this->conn->prepare("SELECT user_id FROM tbl_members WHERE access_token = ?");
        $stmt->bind_param("s", $access_token);
        if ($stmt->execute()) {
            $stmt->bind_result($user_id);
            $stmt->fetch();
            $stmt->close();
            return $user_id;
        } else {
            return NULL;
        }
    }

    public function getVendorIdForMember($member_id) {
        $stmt = $this->conn->prepare("SELECT user_id FROM tbl_members WHERE id = ?");
        $stmt->bind_param("s", $member_id);
        if ($stmt->execute()) {
            $stmt->bind_result($user_id);
            $stmt->fetch();
            $stmt->close();
            return $user_id;
        } else {
            return NULL;
        }
    }

    public function isValidMemberAccessToken($access_token) {
        $stmt = $this->conn->prepare("SELECT id from tbl_members WHERE access_token = ?");
        $stmt->bind_param("s", $access_token);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    /**
     * Validating user api key
     * If the api key is there in db, it is a valid key
     * @param String $api_key user api key
     * @return boolean
     */
    public function isValidAccessToken($access_token) {
        $stmt = $this->conn->prepare("SELECT userId from tbl_users WHERE access_token = ?");
        $stmt->bind_param("s", $access_token);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    /**
     * Generating random Unique MD5 String for user Api key
     */
    private function generateAccessToken() {
        return md5(uniqid(rand(), true));
    }

    private function returnListFromStatement($stmt) {
        $listArray = array();
        while ($row = $this->fetch($stmt)) {
            array_push($listArray, $row);
        }
        $stmt->close();
        return $listArray[0];
    }

    private function fetch($result) {
        $array = array();
        if($result instanceof mysqli_stmt)
        {
            $result->store_result();
            $variables = array();
            $data = array();
            $meta = $result->result_metadata();
            while($field = $meta->fetch_field())
                $variables[] = &$data[$field->name]; // pass by reference

            call_user_func_array(array($result, 'bind_result'), $variables);

            $i=0;
            while($result->fetch())
            {
                $array[$i] = array();
                foreach($data as $k=>$v)
                    $array[$i][$k] = $v;
                $i++;
                // don't know why, but when I tried $array[] = $data, I got the same one result in all rows
            }
        }
        elseif($result instanceof mysqli_result)
        {
            while($row = $result->fetch_assoc())
                $array[] = $row;
        }
        return $array;
    }

    /**
    ***************************************************Flexi Attendance********************************************************
    */

    public function createFlexiAttendance($member_id, $signature_base_64, $company_id, $membership_type_id, $vendor_id) {
        $stmt = $this->conn->prepare("INSERT INTO tbl_flexi_attendance (member_id, signature_base_64, company_id, membership_type_id, user_id) values (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $member_id, $signature_base_64, $company_id, $membership_type_id, $vendor_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;

        $lastId = $stmt->insert_id;
        $stmt->close();

        return $lastId;
    }

    public function updateFlexiAttendance() {
        return 0;
    }

    public function getFlexiAttendance() {
        $stmt = $this->conn->prepare("SELECT id  FROM tbl_flexi_attendance WHERE user_id = ?");
        $stmt->bind_param("s", $vendor_id);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $flexi_list_array = array();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $flexi_list_array[] = $response;
            }
            return $flexi_list_array;
        } else {
            return NULL;
        }
    }

    /**
    ***************************************************Enquiries********************************************************
    */

    public function createEnquiry($full_name, $email_id , $contact_no , $occupation , $reason , $reference_source, $how_many_people, $tell_us_more, $vendor_id) {
        $stmt = $this->conn->prepare("INSERT INTO tbl_enquiries (contact_no, email_id, full_name, occupation, reason, reference_source, how_many_people, tell_us_more, user_id) values (?, ?, ?, ?, ?, ? , ?, ?, ?)");
        $stmt->bind_param("sssssssss", $contact_no, $email_id, $full_name, $occupation, $reason, $reference_source, $how_many_people, $tell_us_more, $vendor_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;

        $lastId = $stmt->insert_id;
        $stmt->close();

        return $lastId;
    }

    public function updateEnquiry() {
        return 0;
    }

    public function getEnquiry($user_id) {
        $stmt = $this->conn->prepare("SELECT id FROM tbl_enquiries WHERE user_id = ?");
        $stmt->bind_param("s", $vendor_id);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $enquiry_list_array = array();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $enquiry_list_array[] = $response;
            }
            return $enquiry_list_array;
        } else {
            return NULL;
        }
    }

    /**
    ***************************************************Meeting-Room********************************************************
    */

    public function getMeetingRoomBookingDetails($meetingRoomBookingId) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_meeting_room_bookings WHERE id = ?");
        $stmt->bind_param("s", $meetingRoomBookingId);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $list = array();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $response["meeting_room_id"] = $dict["meeting_room_id"];
                $response["booked_by_member_id"] = $dict["booked_by_member_id"];
                $response["start_time"] = $dict["start_time"];
                $response["end_time"] = $dict["end_time"];
                $response["is_available"] = $dict["is_available"];
                $list[] = $response;
            }
            return count($list) > 0 ? $list[0] : NULL;
        } else {
            return NULL;
        }
    }

    public function getMeetingRoomHistory($vendor_id, $member_id, $start_date, $end_date) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_meeting_room_bookings WHERE booked_by_member_id = ? AND start_time >= ? AND end_time <= ?");
        $stmt->bind_param("sss", $member_id, $start_date, $end_date);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $list_array = array();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $meetingRoom = $this->getMeetingDetails($dict["meeting_room_id"]);
                $response["meeting_room_id"] = $meetingRoom["id"];
                $response["meeting_room_name"] = $meetingRoom["name"];
                $response["booked_by_member_id"] = $dict["booked_by_member_id"];
                $response["start_time"] = $dict["start_time"];
                $response["end_time"] = $dict["end_time"];
                $response["is_available"] = $dict["is_available"];
                $list_array[] = $response;
            }
            return $list_array;
        } else {
            return NULL;
        }
    }

    public function createMeetingRoomBooking($vendor_id, $member_id, $room_id, $start_time, $end_time) {
        if ($this->checkMeetingRoomBookingStatus($room_id, $start_time, $end_time) == 0) {
            $stmt = $this->conn->prepare("INSERT INTO tbl_meeting_room_bookings (meeting_room_id, booked_by_member_id, start_time, end_time, is_available) values (?, ?, ?, ?, 0)");
            $stmt->bind_param("ssss", $room_id, $member_id, $start_time, $end_time);
            $stmt->execute();
            $num_affected_rows = $stmt->affected_rows;
    
            $lastId = $stmt->insert_id;
            $stmt->close();
    
            return $this->getMeetingRoomBookingDetails($lastId);
        } else {
            return 0;
        }
    }

    
    public function checkMeetingRoomBookingStatus($room_id, $start_time, $end_time) {
        $stmt = $this->conn->prepare("SELECT * from tbl_meeting_room_bookings WHERE meeting_room_id = ? AND start_time >= ? AND end_time <= ? AND is_available = 0");
        $stmt->bind_param("sss", $room_id, $start_time, $end_time);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    public function cancelMeetingRoomBooking($booking_id, $member_id) {
        if ($this->checkMeetingRoomBooking($booking_id, $member_id)) {
            $stmt = $this->conn->prepare("DELETE from tbl_meeting_room_bookings WHERE id = ?");
            $stmt->bind_param("s", $booking_id);
            $stmt->execute();
            $num_affected_rows = $stmt->affected_rows;
            $stmt->close();
            return $num_affected_rows > 0;
        } else {
            return 0;
        }
    }

    public function checkMeetingRoomBooking($booking_id, $member_id) {
        $stmt = $this->conn->prepare("SELECT * from tbl_meeting_room_bookings WHERE id = ? AND booked_by_member_id = ?");
        $stmt->bind_param("ss", $booking_id, $member_id);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    public function getMeetingRoomBookings($room_id, $start_date, $end_date) {
        $start_date = explode(' ', $start_date)[0];
        $end_date = explode(' ', $end_date)[0];

        $meetingRoom = $this->getMeetingDetails($room_id);
        $roomStartTime = $meetingRoom["start_time"];
        $roomEndTime = $meetingRoom["end_time"];

        $start_date = $start_date." ".$roomStartTime;
        $end_date = $end_date." ".$roomEndTime;

        $dateFormat = "Y-m-d h:i A";
        $clientDateFormat = "Y-m-d H:i:s";
        //Initial time should be greater than current time and end time should be less than 8 or 9 PM based on workspace setting

        $initialTime = strtotime($start_date);
        $startTime = date($dateFormat, $initialTime);
        $endTime = date($dateFormat, strtotime("+15 minutes", strtotime($startTime)));
        $list_array = array();

        for ($i = 0; $i <= 95; $i++) { // from 00 AM to 12 PM
             if ((strtotime($startTime) >= strtotime($start_date)) && (strtotime($endTime) <= strtotime($end_date))) {
                $bookingRoomData = $this->getMeetingRoomBookingsData($room_id, $startTime, $endTime);
                if ($bookingRoomData == NULL) {
                    $response["id"] = 0;
                    $response["meeting_room_id"] = $room_id;
                    $response["booked_by_member_id"] = 0;
                    $response["start_time"] = date($clientDateFormat, strtotime($startTime));
                    $response["end_time"] = date($clientDateFormat, strtotime($endTime));
                    $response["is_available"] = 1;
                    $list_array[] = $response;
                } else {
                    $list_array[] = $bookingRoomData;
                }
             }

            $startTime = date($dateFormat, strtotime($endTime));
            $endTime = date($dateFormat, strtotime("+15 minutes", strtotime($startTime)));
        }
        return $list_array;
    }

    public function getMeetingRoomBookingsData($room_id, $start_time, $end_time) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_meeting_room_bookings WHERE meeting_room_id = ? AND start_time >= ? AND end_time <= ?");
        $stmt->bind_param("sss", $room_id, $start_time, $end_time);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $list = array();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $response["meeting_room_id"] = $dict["meeting_room_id"];
                $response["booked_by_member_id"] = $dict["booked_by_member_id"];
                $response["start_time"] = $dict["start_time"];
                $response["end_time"] = $dict["end_time"];
                $response["is_available"] = $dict["is_available"];
                $list[] = $response;
            }
            return count($list) > 0 ? $list[0] : NULL;
        } else {
            return NULL;
        }
    }

    public function getMeetingRooms($vendor_id) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_meeting_rooms WHERE vendor_id = ?");
        $stmt->bind_param("s", $vendor_id);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $list_array = array();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $response["name"] = $dict["name"];
                $response["description"] = $dict["description"];
                $response["start_time"] = $dict["start_time"];
                $response["end_time"] = $dict["end_time"];
                $response["images"] = array($dict["header_image_url"]);
                $list_array[] = $response;
            }
            return $list_array;
        } else {
            return NULL;
        }
    }

    public function getMeetingDetails($room_id) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_meeting_rooms WHERE id = ?");
        $stmt->bind_param("s", $room_id);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $list_array = array();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $response["name"] = $dict["name"];
                $response["description"] = $dict["description"];
                $response["start_time"] = $dict["start_time"];
                $response["end_time"] = $dict["end_time"];
                $response["images"] = array($dict["header_image_url"]);
            }
            return $response;
        } else {
            return NULL;
        }
    }

    /**
    ***************************************************MEMBER-WALL********************************************************
    */

    public function submitMemberWallPost($image_url, $post_description, $post_by_member_id, $vendor_id) {
        $stmt = $this->conn->prepare("INSERT INTO tbl_member_wall (image_url, post_description, post_by_member_id, vendor_id) values (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $image_url, $post_description, $post_by_member_id, $vendor_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;

        $lastId = $stmt->insert_id;
        $stmt->close();

        return $lastId;
    }

    public function editMemberWallPost($post_id, $image_url, $post_description, $post_by_member_id, $vendor_id) {
        $stmt = $this->conn->prepare("UPDATE tbl_member_wall set image_url = ?, post_description = ?, post_by_member_id = ?, vendor_id = ?  WHERE post_id = ?");
        $stmt->bind_param("sssss", $image_url, $post_description, $post_by_member_id, $vendor_id, $post_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    public function deleteMemberWallPost($post_id, $post_by_member_id) { //if member_id == post_id then only delete it
        $stmt = $this->conn->prepare("UPDATE tbl_member_wall set is_deleted = 1 WHERE id = ? AND post_by_member_id = ?");
        $stmt->bind_param("ss", $post_id, $post_by_member_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    public function deleteMemberWallComment($comment_id, $comment_by_member_id) {
        $stmt = $this->conn->prepare("DELETE from tbl_member_wall_comment WHERE id = ? AND comment_by_member_id = ?");
        $stmt->bind_param("ss", $comment_id, $comment_by_member_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    public function getMemberWallPost($member_id, $vendor_id, $per_page, $page_number) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_member_wall WHERE vendor_id = ? AND is_deleted = 0 ORDER BY date DESC");
        $stmt->bind_param("s", $vendor_id);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $list_array = array();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $member = $this->getMemberWithMemberId($dict["post_by_member_id"]);
                $response["name"] = $member["name"];
                $response["member_id"] = $member["id"];
                $response["member_profile_image"] = $member["profile_image"];

                $response["status_image"] = $dict["image_url"];
                $response["status_images"] = $dict["image_url"] == "" ? array() : array($dict["image_url"]);
                $response["status_text"] = $dict["post_description"];
                $response["vendor_id"] = $dict["vendor_id"];
                $response["date"] = $dict["date"];
                $likes = $this->getListOfLikesForPost($dict["id"]);
                $comments = $this->getListOfCommentsForPost($dict["id"]);

                $response["is_liked_by_me"] = $this->checkIfAlreadyLiked($dict["id"], $member_id) > 0 ? true : false;
                $response["number_of_likes"] = count($likes);
                $response["number_of_commment"] = count($comments);

                $list_array[] = $response;
            }
            return $list_array;
        } else {
            return NULL;
        }
    }

    public function updateLikeForPost($post_id, $like_by_member_id) {
        if ($this->checkIfAlreadyLiked($post_id, $like_by_member_id) > 0) {
            $stmt = $this->conn->prepare("DELETE from tbl_member_wall_like WHERE post_id = ? AND like_by_member_id = ?");
            $stmt->bind_param("ss", $post_id, $like_by_member_id);
            $stmt->execute();
            $num_affected_rows = $stmt->affected_rows;
            $stmt->close();
            return $num_affected_rows > 0;
        } else {
            $stmt = $this->conn->prepare("INSERT INTO tbl_member_wall_like (post_id, like_by_member_id) values (?, ?)");
            $stmt->bind_param("ss", $post_id, $like_by_member_id);
            $stmt->execute();
            $num_affected_rows = $stmt->affected_rows;
            $lastId = $stmt->insert_id;
            $stmt->close();
            return $lastId;
        }
    }

    public function checkIfAlreadyLiked($post_id, $member_id) {

        $stmt = $this->conn->prepare("SELECT * FROM tbl_member_wall_like WHERE post_id = ? AND like_by_member_id = ?");
        $stmt->bind_param("ss", $post_id, $member_id);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $list_array = array();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $list_array[] = $response;
            }
            return count($list_array);
        } else {
            return 0;
        }
    }

    public function getListOfLikesForPost($post_id) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_member_wall_like WHERE post_id = ?");
        $stmt->bind_param("s", $post_id);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $list_array = array();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $response["post_id"] = $dict["post_id"];
                
                $response["member_id"] = $dict["like_by_member_id"];
                $member = $this->getMemberWithMemberId($dict["like_by_member_id"]);
                $response["member_name"] = $member["name"];
                $response["member_profile_image"] = $member["profile_image"];
                $list_array[] = $response;
            }
            return $list_array;
        } else {
            return array();
        }
    }

    public function submitCommentForPost($post_id, $comment_by_member_id, $comment) {
        $stmt = $this->conn->prepare("INSERT INTO tbl_member_wall_comment (post_id, comment_by_member_id, comment) values (?, ?, ?)");
        $stmt->bind_param("sss", $post_id, $comment_by_member_id, $comment);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;

        $lastId = $stmt->insert_id;
        $stmt->close();

        return $lastId;
    }

    public function getListOfCommentsForPost($post_id) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_member_wall_comment WHERE post_id = ?");
        $stmt->bind_param("s", $post_id);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $list_array = array();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $response["post_id"] = $dict["post_id"];
                $response["date"] = $dict["date"];
                $response["comment"] = $dict["comment"];
                $response["member_id"] = $dict["comment_by_member_id"];

                $member = $this->getMemberWithMemberId($dict["comment_by_member_id"]);
                $response["member_name"] = $member["name"];
                $response["member_profile_image"] = $member["profile_image"];
                $list_array[] = $response;
            }
            return $list_array;
        } else {
            return array();
        }
    }

    /**
     * ****************************************** EVENTS ****************************************
     */

    public function getVendorEvents($vendor_id, $member_id, $per_page, $page_number) {
        $stmt = $this->conn->prepare("SELECT id, title, start_time, end_time, description, vendor_id, header_image_url FROM tbl_events WHERE vendor_id = ?");
        $stmt->bind_param("s", $vendor_id);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $list_array = array();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $response["title"] = $dict["title"];
                $response["start_time"] = $dict["start_time"];
                $response["end_time"] = $dict["end_time"];
                $response["description"] = $dict["description"];
                $response["vendor_id"] = $dict["vendor_id"];
                $response["header_image_url"] = $dict["header_image_url"];
                $response["is_attending"] = $this->getEventAttendStatus($dict["id"], $member_id);
                $response["total_rsvp"] = count($this->getEventAttendee($dict["id"]));
                $response["attendees"] = $this->getEventAttendee($dict["id"]);
                $list_array[] = $response;
            }
            return $list_array;
        } else {
            return NULL;
        }
    }

    public function attendEvent($event_id, $member_id) {
        if ($this->getEventAttendStatus($event_id, $member_id) == FALSE) {
            $stmt = $this->conn->prepare("INSERT INTO tbl_events_attendance (event_id, attendee_member_id) values (?, ?)");
            $stmt->bind_param("ss", $event_id, $member_id);
            $stmt->execute();
            $num_affected_rows = $stmt->affected_rows;
    
            $lastId = $stmt->insert_id;
            $stmt->close();
            return $lastId;
        } else {
            return TRUE;
        }
    }

    public function getEventAttendStatus($event_id, $member_id) {
        $stmt = $this->conn->prepare("SELECT id FROM tbl_events_attendance WHERE event_id = ? AND attendee_member_id = ?");
        $stmt->bind_param("ss", $event_id, $member_id);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $list_array = array();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $list_array[] = $response;
            }
            return count($list_array) > 0 ? TRUE : FALSE;
        } else {
            return FALSE;
        }
    }

    public function unAttendEvent($event_id, $member_id) {
        $stmt = $this->conn->prepare("DELETE from tbl_events_attendance WHERE event_id = ? AND attendee_member_id = ?");
        $stmt->bind_param("ss", $event_id, $member_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    public function getEventAttendee($event_id) {
        $stmt = $this->conn->prepare("SELECT id, event_id, attendee_member_id FROM tbl_events_attendance WHERE event_id = ?");
        $stmt->bind_param("s", $event_id);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $list_array = array();
            foreach ($row as $dict) {
                $member = $this->getMemberWithMemberId($dict["attendee_member_id"]);
                $response["id"] = $member["id"];
                $response["name"] = $member["name"];
                $response["profile_image"] = $member["profile_image"];
                $list_array[] = $response;
            }
            return $list_array;
        } else {
            return [];
        }
    }

    /**
    ***************************************************CO-WORK********************************************************
    */

    public function getMemberFullDetailsWithMemberId($member_id) {
        $stmt = $this->conn->prepare("SELECT MemberTbl.id as member_id, MemberTbl.full_name as member_name, MemberTbl.email_id as member_email_id, MemberTbl.contact_no as member_contact_no, MemberTbl.company_id, CompanyTbl.name as company_name, VendorTbl.name as vendor_name, VendorTbl.email as vendor_email, MAX(FlexiAttendanceTbl.attendance_date) as attendance_date FROM tbl_members as MemberTbl, tbl_companies as CompanyTbl, tbl_users as VendorTbl, tbl_flexi_attendance as FlexiAttendanceTbl WHERE MemberTbl.id = ? AND MemberTbl.company_id = CompanyTbl.id AND MemberTbl.user_id = VendorTbl.userId");
        $stmt->bind_param("s", $member_id);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            foreach ($row as $dict) {
                $response["member_id"] = $dict["member_id"];
                $response["member_name"] = $dict["member_name"];
                $response["member_email_id"] = $dict["member_email_id"];
                $response["member_contact_no"] = $dict["member_contact_no"];
                $response["company_name"] = $dict["company_name"];

                $date = date_create($dict["attendance_date"]);
                $response["attendance_date"] = date_format($date, 'h:i A');

                $response["company_id"] = $dict["company_id"];
                $response["vendor_name"] = $dict["vendor_name"];
                $response["no_of_days_left"] = $this->getNumberOfdaysLeft($response["member_id"]);
                $response["membershipTypeId"] = $this->getMembershipTypeForMember($response["member_id"]);
                $response["vendor_email"] = $dict["vendor_email"];
            }
            return $response;
        } else {
            return NULL;
        }
    }

    public function getNumberOfdaysLeft($member_id) {
      $attendanceCount = $this->getFlexiAttendanceCount($member_id);
      $numberOfDayOfMembershipCount = $this->getNumberOfDaysOfMembershipForMember($member_id);
      return $numberOfDayOfMembershipCount - $attendanceCount;
    }

    public function getMemberWithMemberId($member_id) {
        $stmt = $this->conn->prepare("SELECT id, profile_image, full_name, email_id, contact_no, membership_plan, company_id FROM tbl_members WHERE id = ?");
        $stmt->bind_param("s", $member_id);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();
            $response = array();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $response["name"] = $dict["full_name"];
                $response["contact_no"] = $dict["contact_no"];
                $response["company_id"] = $dict["company_id"];
                $response["profile_image"] = $dict["profile_image"];
                $response["membershipTypeId"] = $this->getMembershipTypeForMember($response["id"]);
                $response["emailId"] = $dict["email_id"];
            }
            return $response;
        } else {
            return NULL;
        }
    }

    public function getMembersCountForVendor($userId) { //for v2
        $stmt = $this->conn->prepare("SELECT id FROM tbl_members WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();
            return count($row);
        } else {
            return 0;
        }
    }

    public function getMembersListForVendor($vendor_id) { //for v2
        $stmt = $this->conn->prepare("SELECT id, about_me, member_fir_key, company_id, profile_image, website_url, linkedin_url, instagram_url, facebook_url, twitter_url, email_id, full_name, contact_no, occupation, membership_plan FROM tbl_members WHERE user_id = ?");
        $stmt->bind_param("s", $vendor_id);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $member_list_array = array();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $response["name"] = $dict["full_name"];
                $response["profile_pic_url"] = $dict["profile_image"];
                $response["contact_no"] = $dict["contact_no"];
                $response["occupation"] = $dict["occupation"];
                $response["about_me"] = $dict["about_me"];
                $response["website_url"] = $dict["website_url"];
                $response["linkedin_url"] = $dict["linkedin_url"];
                $response["instagram_url"] = $dict["instagram_url"];
                $response["twitter_url"] = $dict["twitter_url"];
                $response["facebook_url"] = $dict["facebook_url"];
                $response["member_fir_key"] = $dict["member_fir_key"];
                $response["company_id"] = $dict["company_id"];
                $response["companies"] = $this->getCompanyFullDetailsWithId($dict["company_id"]);
                $response["membership_type_id"] = $this->getMembershipTypeForMember($response["id"]);
                $response["email_id"] = $dict["email_id"];
                $member_list_array[] = $response;
            }
            return $member_list_array;
        } else {
            return NULL;
        }
    }

    public function getMembers($vendor_id) {
        $stmt = $this->conn->prepare("SELECT id, email_id, full_name, contact_no, occupation, membership_plan FROM tbl_members WHERE user_id = ?");
        $stmt->bind_param("s", $vendor_id);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $member_list_array = array();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $response["name"] = $dict["full_name"];
                $response["contactNo"] = $dict["contact_no"];
                $response["occupation"] = $dict["occupation"];
                $response["membershipTypeId"] = $this->getMembershipTypeForMember($response["id"]);
                $response["emailId"] = $dict["email_id"];
                $response["flexiCount"] = $this->getFlexiAttendanceCount($response["id"]);

                $member_list_array[] = $response;
            }
            return $member_list_array;
        } else {
            return NULL;
        }
    }

    public function getCompanyMembers($companyId) {
        $stmt = $this->conn->prepare("SELECT id, email_id, full_name, contact_no, occupation, membership_plan, company_id FROM tbl_members WHERE company_id = ?");
        $stmt->bind_param("s", $companyId);

        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();
            $member_list_array = array();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $response["name"] = $dict["full_name"];
                $response["contactNo"] = $dict["contact_no"];
                $response["occupation"] = $dict["occupation"];
                $response["membershipTypeId"] = $this->getMembershipTypeForMember($response["id"]);
                $response["emailId"] = $dict["email_id"];
                $response["flexiCount"] = $this->getFlexiAttendanceCount($response["id"]);
                $member_list_array[] = $response;
            }
            return $member_list_array;
        } else {
            return NULL;
        }
    }

    public function getCompanyMembersWithId($companyId) {
        $stmt = $this->conn->prepare("SELECT id, email_id, full_name, contact_no, occupation, membership_plan, company_id FROM tbl_members WHERE company_id = ?");
        $stmt->bind_param("s", $companyId);

        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();
            $member_list_array = array();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $response["name"] = $dict["full_name"];
                $response["contact_no"] = $dict["contact_no"];
                $response["occupation"] = $dict["occupation"];
                $response["membership_type_id"] = $this->getMembershipTypeForMember($response["id"]);
                $response["email_id"] = $dict["email_id"];
                $response["flexi_count"] = $this->getFlexiAttendanceCount($response["id"]);
                $member_list_array[] = $response;
            }
            return $member_list_array;
        } else {
            return NULL;
        }
    }

    public function getNumberOfDaysOfMembershipForMember($memberId) {
      $stmt = $this->conn->prepare("SELECT Space.`membership_type_id`, MembershipTypeTbl.number_of_day FROM `tbl_space` as Space, `tbl_membership_type` as MembershipTypeTbl WHERE `Space`.`member_id` = ? AND MembershipTypeTbl.id = Space.membership_type_id AND `Space`.`isDeleted` = 0");
      $stmt->bind_param("s", $memberId);
      if ($stmt->execute()) {
          $row = $this->fetch($stmt);
          $stmt->close();
          $number_of_day = 0;
          foreach ($row as $dict) {
              $number_of_day = $dict["number_of_day"];
          }
          return $number_of_day;
      } else {
          return 0;
      }
    }

    public function getMembershipTypeForMember($memberId) {
      $stmt = $this->conn->prepare("SELECT Space.`membership_type_id`, MembershipTypeTbl.number_of_day FROM `tbl_space` as Space, `tbl_membership_type` as MembershipTypeTbl WHERE `Space`.`member_id` = ? AND MembershipTypeTbl.id = Space.membership_type_id AND `Space`.`isDeleted` = 0");
      $stmt->bind_param("s", $memberId);
      if ($stmt->execute()) {
          $row = $this->fetch($stmt);
          $stmt->close();
          $membershipTypeId = 0;
          foreach ($row as $dict) {
              $membershipTypeId = $dict["membership_type_id"];
          }
          return $membershipTypeId;
      } else {
          return 0;
      }
    }

    public function getMember($memberId) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_members WHERE id = ?");
        $stmt->bind_param("s", $memberId);

        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $response["name"] = $dict["full_name"];
                $response["email_id"] = $dict["email_id"];
                $response["access_token"] = $dict["access_token"];
            }
            return $response;
        } else {
            return NULL;
        }
    }

    public function getMemberDetailsWithId($memberId) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_members WHERE id = ?");
        $stmt->bind_param("s", $memberId);

        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();
            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $response["name"] = $dict["full_name"];
                $response["contactNo"] = $dict["contact_no"];
                $response["occupation"] = $dict["occupation"];
                $response["membershipTypeId"] = $dict["membership_plan"];
                $response["emailId"] = $dict["email_id"];
                $response["flexiCount"] = $this->getFlexiAttendanceCount($response["id"]);
            }
            return $response;
        } else {
            return NULL;
        }
    }
    public function getCompanyDetailsWithId($companyId) {
        $stmt = $this->conn->prepare("SELECT id, name FROM tbl_companies WHERE id = ?");
        $stmt->bind_param("s", $companyId);

        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            foreach ($row as $dict) {
                $response["id"] = $dict["id"];
                $response["name"] = $dict["name"];
            }
            return $response;
        } else {
            return NULL;
        }
    }

    public function getFlexiAttendanceCount($memberId) {
      $stmt = $this->conn->prepare("SELECT `Flexi`.`member_id`, COUNT(`Flexi`.`member_id`) as flexiCount FROM tbl_flexi_attendance as Flexi, `tbl_space` as Space WHERE `Flexi`.`attendance_date` >= `Space`.`start_date` AND `Flexi`.`attendance_date` <= `Space`.`expiry_date` AND `Flexi`.`member_id` = ? AND `Space`.`member_id` = ? GROUP BY `Flexi`.`member_id`");
      $stmt->bind_param("ss", $memberId, $memberId);
      if ($stmt->execute()) {
          $row = $this->fetch($stmt);
          $stmt->close();
          $count = 0;
          foreach ($row as $dict) {
              $count = $dict["flexiCount"];
          }
          return $count;
      } else {
          return 0;
      }
    }

    public function getCompanies($vendor_id) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_companies WHERE user_id = ? AND isDeleted = 0");
        $stmt->bind_param("s", $vendor_id);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $companies_list_array = array();
            foreach ($row as $dict) {
                $companies_list_array[] = $dict;
            }
            return $companies_list_array;
        } else {
            return NULL;
        }
    }

    public function getCompanyFullDetailsWithId($companyId) {
        $stmt = $this->conn->prepare("SELECT * FROM tbl_companies WHERE id = ?");
        $stmt->bind_param("s", $companyId);
        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $companies_list_array = array();
            foreach ($row as $dict) {
                $companies_list_array[] = $dict;
            }
            return $companies_list_array;
        } else {
            return NULL;
        }
    }

    /**
    ***************************************************STRIPE-PAYMENT********************************************************
    */

    private function getPayment($payment_id) {
        $stmt = $this->conn->prepare("SELECT * FROM PAYMENT WHERE id = ?");
        $stmt->bind_param("s", $payment_id);

        if ($stmt->execute()) {
            $row = $this->fetch($stmt);
            $stmt->close();

            $list_array = array();
            foreach ($row as $dict) {
                $response = $dict;
            }
            return $response;
        } else {
            return NULL;
        }
    }

    private function updatePaymentWithPaymentDetails($stripe_token, $amount, $currency_code, $payment_id) {
        $stmt = $this->conn->prepare("UPDATE PAYMENT set stripe_token = ?, amount = ?, currency_code = ? WHERE id = ?");
        $stmt->bind_param("ssss", $stripe_token, $amount, $currency_code, $payment_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;

        $lastId = $stmt->update_id;
        $stmt->close();
        return $lastId == 0 ? NULL : $lastId;
    }

    private function insertPaymentWithPaymentDetails($book_by_user_id, $stripe_token, $amount, $currency_code) {
        $stmt = $this->conn->prepare("INSERT INTO PAYMENT (user_id, stripe_token, amount, currency_code) values (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $booked_by_user_id, $stripe_token, $amount, $currency_code);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;

        $lastId = $stmt->insert_id;
        $stmt->close();
        return $lastId == 0 ? NULL : $lastId;
    }

    public function chargeUserCard($amount, $stripe_token) {
        \Stripe\Stripe::setApiKey(STRIPE_KEY);
        $error = '';
        $success = '';
        try {
            \Stripe\Charge::create(array("amount" => $amount,
                                        "currency" => "euro",
                                        "card" => $stripe_token));
            return TRUE;
        } catch(\Stripe\Error\Card $e) {
          // Since it's a decline, \Stripe\Error\Card will be caught
          $body = $e->getJsonBody();
          $err  = $body['error'];

          print('Status is:' . $e->getHttpStatus() . "\n");
          print('Type is:' . $err['type'] . "\n");
          print('Code is:' . $err['code'] . "\n");
          // param is '' in this case
          print('Param is:' . $err['param'] . "\n");
          print('Message is:' . $err['message'] . "\n");
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            $error = $e->getMessage();
            return $error;
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            $error = $e->getMessage();
            return $error;
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $error = $e->getMessage();
            return $error;
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            $error = $e->getMessage();
            return $error;
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $error = $e->getMessage();
            return $error;
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $error = $e->getMessage();
            return $error;
        }
    }

    public function saveStripeCard($credit_card_number, $exp_month, $exp_year, $amount, $currency_type) {
        \Stripe\Stripe::setApiKey(STRIPE_KEY);
        $myCard = array('number' => $credit_card_number, 'exp_month' => $exp_month, 'exp_year' => $exp_year);
        $charge = \Stripe\Charge::create(array('card' => $myCard, 'amount' => $amount, 'currency' => $currency_type));
        echo $charge;
    }
}

?>
