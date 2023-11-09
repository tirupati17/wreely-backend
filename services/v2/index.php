<?php

require_once '../include/DbHandler.php';
require_once '../include/PassHash.php';
require '.././vendor/autoload.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

/**
 * Adding Middle Layer to authenticate every request
 * Checking if the request has valid api key in the 'Authorization' header
 */


/*
 * ------------------------WREELY - COMMUNITY PLATFORM START ------------------------
 */

$member_id = NULL;
function authenticate(\Slim\Route $route) {
    // Getting request headers
    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();
    $access_token = $app->request()->get('access_token') == NULL ? $app->request()->post('access_token') : $app->request()->get('access_token');
    if ($access_token == NULL) {
        $bodyData = isValidJson($app->request()->getBody());
        $access_token = isset($bodyData->access_token) ? $bodyData->access_token : NULL;
    }

    if ($access_token != NULL) {
        $db = new DbHandler();

        // validating access token
        if (!$db->isValidMemberAccessToken($access_token)) {
            $response["error"] = true;
            $response["message"] = "Access Denied. Invalid access token.";
            echoResponse(401, $response);
            $app->stop();
        } else {
            global $member_id;            
            $member_id = $db->getMemberId($access_token);
        }
    } else {
        $response["error"] = $access_token;
        $response["message"] = "Access token is missing";
        echoResponse(400, $response);
        $app->stop();
    }
}

$app->post('/member/login', function() use ($app) {
    //check and parse valid json
    $bodyData = isValidJson($app->request->getBody());

    // reading valid post params from json otherwise return empty string
    $mobileNumber = isset($bodyData->mobile_number) ? $bodyData->mobile_number : "";
    $countryCode = isset($bodyData->country_code) ? $bodyData->country_code : "";

    //check for required parameter
    verifyRequiredJsonFields($arrayName = array('mobile_number' => $mobileNumber, 'country_code' => $countryCode));
    $response = array();

    $db = new DbHandler();
    $member = $db->getMemberByMobileNumber($mobileNumber);
    if ($member != NULL) {
        if (sendRandomCode($mobileNumber, $countryCode, $member["id"], 3) != NULL) {
            $response["error"] = false;
            $response["member_id"] = $member["id"];
            $response["message"] = "Login code sent successfully";
            echoResponse(200, $response);
        } else {
            $response["error"] = true;
            $response["message"] = "Failed to send code. Please try again";
            echoResponse(400, $response);
        }
    } else {
        $response["error"] = true;
        $response["message"] = "Oop's, looks like there is no such mobile number exist in our platform. Please contact your workspace community manager.";
        echoResponse(400, $response);
    }
});

$app->post('/member/login/confirmation', function() use ($app) {
    //check and parse valid json
    $bodyData = isValidJson($app->request->getBody());

    //read valid post params from json otherwise return empty string
    $login_code = isset($bodyData->login_code) ? $bodyData->login_code : "";
    $member_id = isset($bodyData->member_id) ? $bodyData->member_id : "";

    //check for required parameter
    verifyRequiredJsonFields($arrayName = array('login_code' => $login_code, 'member_id' => $member_id));
    $response = array();

    $db = new DbHandler();

    //confirm randomcode inside user
    $access_token = $db->confirmLoginCodeInMember($member_id, $login_code);

    if ($access_token != NULL) {
        $response["access_token"] = $access_token;
        echoResponse(200, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "Invalid login code. Please try again.";
        echoResponse(400, $response);
    }
});

$app->get('/member', 'authenticate', function() use ($app) {
    global $member_id;
    $db = new DbHandler();

    $member = $db->getMemberDetails($member_id);

    $response["user"] = $member;
    echoResponse(200, $response);
});

$app->get('/member/:member_id', 'authenticate', function($member_id) use ($app) {
    $db = new DbHandler();
    $member = $db->getMemberDetails($member_id);

    $response["member"] = $member;
    echoResponse(200, $response);
});

$app->get('/member/update/firebase/firebase_key=:firebase_key', 'authenticate', function($firebase_key) use ($app) {

    //check for required parameter
    verifyRequiredJsonFields($arrayName = array('firebase_key' => $firebase_key));
    $response = array();

    global $member_id;
    $db = new DbHandler();
    $isValid = $db->updateMemberFirKey($member_id, $firebase_key);

    if ($isValid != NULL) {
        $response["error"] = false;
        $response["message"] = "Firebase key successfully updated.";
        echoResponse(200, $response);
    } else {
        $response["error"] = true;
        $response['message'] = "An error occurred. Please try again";
        echoResponse(400, $response);
    }
});

$app->post('/member/update', function() use ($app) {
    global $member_id;
    $db = new DbHandler();

    $member = $db->getMemberDetails($member_id);

    $response["user"] = $member;
    echoResponse(200, $response);
});

$app->get('/validate/session', 'authenticate', function() use ($app) {
    global $member_id;
    $db = new DbHandler();
    $member = $db->getMemberDetails($member_id);
    
    if ($member != NULL) {
        $response["error"] = false;
        echoResponse(200, $response);
    } else {
        $response["error"] = true;
        echoResponse(401, $response); //Invalid session
    }
});

$app->get('/vendors', 'authenticate', function() use ($app) {
    global $member_id;
    $db = new DbHandler();
    $vendor_id = $db->getVendorIdForMember($member_id);
    $vendors = $db->getVendorDetail($vendor_id);
    
    $response["vendors"] = array($vendors) ? array($vendors) : array();
    echoResponse(200, $response);
});

$app->get('/:vendor_id/companies', 'authenticate', function($vendor_id) use ($app) {
    $db = new DbHandler();
    $companies = $db->getCompanies($vendor_id);

    $response["companies"] = $companies ? $companies : array();
    echoResponse(200, $response);
});

$app->get('/:vendor_id/companies/:company_id/members', 'authenticate', function($vendor_id, $company_id) use ($app) {
    $db = new DbHandler();
    $members = $db->getCompanyMembersWithId($company_id);

    $response["members"] = $members ? $members : array();
    echoResponse(200, $response);
});

$app->get('/:vendor_id/members', 'authenticate', function($vendor_id) use ($app) {
    $db = new DbHandler();
    $members = $db->getMembersListForVendor($vendor_id);

    $response["members"] = $members ? $members : array();
    echoResponse(200, $response);
});

$app->get('/:vendor_id/meetingrooms', 'authenticate', function($vendor_id) use ($app) {
    $db = new DbHandler();
    $result = $db->getMeetingRooms($vendor_id);

    $response["meeting_rooms"] = $result ? $result : array();
    echoResponse(200, $response);
});

$app->get('/:vendor_id/meetingroom/bookings/room_id=:room_id&start_date=:start_date&end_date=:end_date', 'authenticate', function($vendor_id, $room_id, $start_date, $end_date) use ($app) {

    //check for required parameter
    verifyRequiredJsonFields($arrayName = array('room_id' => $room_id, 'start_date' => $start_date, 'end_date' => $end_date));

    $db = new DbHandler();
    $result = $db->getMeetingRoomBookings($room_id, $start_date, $end_date);

    $response["meeting_room_bookings"] = $result ? $result : array();
    echoResponse(200, $response);
});

$app->get('/:vendor_id/meetingroom/bookings/cancel/booking_id=:booking_id', 'authenticate', function($vendor_id, $booking_id) use ($app) {

    //check for required parameter
    verifyRequiredJsonFields($arrayName = array('booking_id' => $booking_id));

    global $member_id;
    $db = new DbHandler();
    $result = $db->cancelMeetingRoomBooking($booking_id, $member_id);

    if ($result == 0) {
        $response["error"] = true;
        $response["message"] = "Oop's sorry, Looks like there is no such bookings by you.";
        echoResponse(400, $response);
    } else {
        $response["error"] = false;
        echoResponse(200, $response);
    }
});

$app->post('/:vendor_id/meetingroom/bookings', 'authenticate', function($vendor_id) use ($app) {

    //check and parse valid json
    $bodyData = isValidJson($app->request->getBody());

    //read valid post params from json otherwise return empty string
    $room_id = isset($bodyData->room_id) ? $bodyData->room_id : "";
    $start_time = isset($bodyData->start_time) ? $bodyData->start_time : "";
    $end_time = isset($bodyData->end_time) ? $bodyData->end_time : "";

    //check for required parameter
    verifyRequiredJsonFields($arrayName = array('room_id' => $room_id, 'start_time' => $start_time, 'end_time' => $end_time));

    global $member_id;
    $db = new DbHandler();
    $result = $db->createMeetingRoomBooking($vendor_id, $member_id, $room_id, $start_time, $end_time);

    if ($result == NULL) {
        $response["error"] = true;
        $response["message"] = "Oop's sorry, Meeting room booked by someone else.";
        echoResponse(400, $response);
    } else {
        $first_day_this_month = date('Y-m-01 H:i:s');
        $last_day_this_month  = date('Y-m-t H:i:s');
        $start_time_timestamp = date('Y-m-d H:i:s', strtotime($start_time));
        $end_time_timestamp = date('Y-m-d H:i:s', strtotime($end_time));        

        $count_for_this_month = count($db->getMeetingRoomHistory($vendor_id, $member_id, $first_day_this_month, $last_day_this_month));
        $member_data = $db->getMemberDetailsWithId($member_id);
        $vendor_data = $db->getUserDetailByUserID($vendor_id);
        $meeting_room_details = $db->getMeetingDetails($room_id);

        $meeting_room_name = $meeting_room_details["name"];
        $vendor_name = $vendor_data["name"];
        $name = $member_data["name"];
        $email = $member_data["emailId"];
        $subject = "Meeting room bookings via Wreely";
        $default_message = "You have booked meeting room [".$meeting_room_name."] at ".$vendor_name." from ".$start_time." to ".$end_time.".";
        $google_calendar_link = make_google_calendar_link($subject, $start_time_timestamp, $end_time_timestamp, "Mumbai", $default_message);
        $calendar_link_text = "\n\nAdd reminder to your google calendar ".$google_calendar_link;
        
        $first_paragraph = $default_message;
        $second_paragraph = "";$calendar_link_text;

        $tags = "MeetingRooms";
        sendDeafultMailViaPepipost($name, $email, $subject,  $first_paragraph, $second_paragraph, $vendor_data, $tags);

        $response["error"] = false;
        $response["mail_message"] = $first_paragraph.$second_paragraph;
        $response["meeting_room_bookings"] = array($result);
        echoResponse(200, $response);
    }
});

$app->get('/:vendor_id/meetingroom/bookings/history/start_date=:start_date&end_date=:end_date', 'authenticate', function($vendor_id, $start_date, $end_date) use ($app) {
    global $member_id;
    $db = new DbHandler();
    $result = $db->getMeetingRoomHistory($vendor_id, $member_id, $start_date, $end_date);

    $response["meeting_rooms_history"] = $result ? $result : array();
    echoResponse(200, $response);
});

$app->get('/:vendor_id/events/per_page=:per_page&page_number=:page_number', 'authenticate', function($vendor_id, $per_page, $page_number) use ($app) {
    global $member_id;
    $db = new DbHandler();
    $result = $db->getVendorEvents($vendor_id, $member_id, $per_page, $page_number);

    $response["events"] = $result ? $result : array();
    echoResponse(200, $response);
});

$app->get('/events/attend/event_id=:event_id', 'authenticate', function($event_id) use ($app) { //should use delete and solve access token problem
    global $member_id;
    $db = new DbHandler();
    $result = $db->attendEvent($event_id, $member_id);

    $response["error"] = false;
    echoResponse(200, $response);
});

$app->get('/events/unattend/event_id=:event_id', 'authenticate', function($event_id) use ($app) { //should use delete and solve access token problem
    global $member_id;
    $db = new DbHandler();
    $result = $db->unAttendEvent($event_id, $member_id);

    $response["error"] = false;
    echoResponse(200, $response);
});

$app->get('/workspaces/nearby/lat=:lat&lon=:lon&rad=:rad', function($lat, $lon, $rad) use ($app) { //should use delete and solve access token problem
    $url = "https://www.coworker.com/api/nearbyspaces/format/json?lat=".$lat."&lon=".$lon."&rad=".$rad;

    $response = fetchData($url);
    echoResponse(200, json_decode($response));
});

$app->post('/:vendor_id/memberwalls', 'authenticate', function($vendor_id) use ($app) { //for edit change post to put

    //check and parse valid json
    $bodyData = isValidJson($app->request->getBody());

    //read valid post params from json otherwise return empty string
    $image_url = isset($bodyData->image_url) ? $bodyData->image_url : "";
    $post_description = isset($bodyData->post_description) ? $bodyData->post_description : "";
    $post_by_member_id = isset($bodyData->post_by_member_id) ? $bodyData->post_by_member_id : "";

    //check for required parameter
    if ($image_url == "" && $post_description == "") {
        verifyRequiredJsonFields($arrayName = array('post_description' => $post_description, 'post_by_member_id' => $post_by_member_id));
    }

    $db = new DbHandler();
    $result = $db->submitMemberWallPost($image_url, $post_description, $post_by_member_id, $vendor_id);

    if ($result == NULL) {
        $response["error"] = true;
        $response["message"] = "Oop's sorry, Something went wrong.";
        echoResponse(400, $response);
    } else {
        $response["error"] = false;
        $response["member_walls"] = array($result);
        echoResponse(200, $response);
    }
});

$app->get('/:vendor_id/memberwalls/per_page=:per_page&page_number=:page_number', 'authenticate', function($vendor_id, $per_page, $page_number) use ($app) {
    global $member_id;

    $db = new DbHandler();
    $result = $db->getMemberWallPost($member_id, $vendor_id, $per_page, $page_number);

    $response["member_walls"] = $result ? $result : array();
    echoResponse(200, $response);
});

$app->get('/:vendor_id/memberwalls/delete/post_id=:post_id', 'authenticate', function($vendor_id, $post_id) use ($app) { //should use delete and solve access token problem
    global $member_id;
    $db = new DbHandler();
    $result = $db->deleteMemberWallPost($post_id, $member_id);

    $response["error"] = false;
    echoResponse(200, $response);
});

$app->get('/:vendor_id/memberwalls/comments/delete/comment_id=:comment_id', 'authenticate', function($vendor_id, $comment_id) use ($app) { //should use delete and solve access token problem
    global $member_id;
    $db = new DbHandler();
    
    $result = $db->deleteMemberWallComment($comment_id, $member_id);

    $response["error"] = false;
    echoResponse(200, $response);
});

$app->get('/:vendor_id/memberwalls/likes/post_id=:post_id&per_page=:per_page&page_number=:page_number', 'authenticate', function($vendor_id, $post_id, $per_page, $page_number) use ($app) {
    $db = new DbHandler();
    $result = $db->getListOfLikesForPost($post_id);

    $response["member_wall_likes"] = $result ? $result : array();
    echoResponse(200, $response);
});

$app->get('/:vendor_id/memberwalls/comments/post_id=:post_id&per_page=:per_page&page_number=:page_number', 'authenticate', function($vendor_id, $post_id, $per_page, $page_number) use ($app) {
    $db = new DbHandler();
    $result = $db->getListOfCommentsForPost($post_id);

    $response["member_wall_comments"] = $result ? $result : array();
    echoResponse(200, $response);
});

$app->post('/:vendor_id/memberwalls/likes', 'authenticate', function($vendor_id) use ($app) { //for edit change post to put

    //check and parse valid json
    $bodyData = isValidJson($app->request->getBody());

    //read valid post params from json otherwise return empty string
    $post_id = isset($bodyData->post_id) ? $bodyData->post_id : "";

    //check for required parameter
    verifyRequiredJsonFields($arrayName = array('post_id' => $post_id));

    global $member_id;
    $db = new DbHandler();
    $result = $db->updateLikeForPost($post_id, $member_id);

    if ($result == 0) {
        $response["error"] = true;
        $response["message"] = "Oop's sorry, Something went wrong.";
        echoResponse(400, $response);
    } else {
        $response["error"] = false;
        echoResponse(200, $response);
    }
});

$app->post('/:vendor_id/memberwalls/comments', 'authenticate', function($vendor_id) use ($app) { //for edit change post to put

    //check and parse valid json
    $bodyData = isValidJson($app->request->getBody());

    //read valid post params from json otherwise return empty string
    $post_id = isset($bodyData->post_id) ? $bodyData->post_id : "";
    $comment = isset($bodyData->comment) ? $bodyData->comment : "";

    //check for required parameter
    verifyRequiredJsonFields($arrayName = array('post_id' => $post_id, 'comment' => $comment));

    global $member_id;
    $db = new DbHandler();
    $result = $db->submitCommentForPost($post_id, $member_id, $comment);

    if ($result == 0) {
        $response["error"] = true;
        $response["message"] = "Oop's sorry, Something went wrong.";
        echoResponse(400, $response);
    } else {
        $response["error"] = false;
        $response["member_wall_comments"] = array($result);
        echoResponse(200, $response);
    }
});

/*
 * ------------------------WREELY - COMMUNITY PLATFORM END ------------------------
 */

/****************************** COMMON USER AUTHENTICATION FLOW START   *****************************/

// $app->post('/user/registration', function() use ($app) {
//     //check and parse valid json
//     $bodyData = isValidJson($app->request->getBody());

//     //read valid post params from json otherwise return empty string
//     $name = isset($bodyData->name) ? $bodyData->name : "";
//     $email = isset($bodyData->email) ? $bodyData->email : "";
//     $password = isset($bodyData->password) ? $bodyData->password : "";

//     //check for required parameter
//     verifyRequiredJsonFields($arrayName = array('name' => $name, 'email' => $email, 'password' => $password));

//     $response = array();
//     $isFacebook = 0;

//     // validating email address
//     validateEmail($email);

//     $db = new DbHandler();
//     $res = $db->createUser($name, $email, $password);

//     if ($res == USER_CREATED_SUCCESSFULLY) {
//         //Fetch user from database
//         $user = $db->getUserByEmailOrMobile($email);

//         $response["error"] = false;
//         $response["message"] = "You are successfully registered.";
//         $response["result"] = $user;
//         echoResponse(200, $response);
//     } else if ($res == USER_CREATE_FAILED) {
//         $response["error"] = true;
//         $response["message"] = "Oops! An error occurred while registering";
//         echoResponse(400, $response);
//     } else if ($res == USER_ALREADY_EXISTED) {
//         $response["error"] = true;
//         $response["message"] = "Sorry, this email already existed";
//         echoResponse(400, $response);
//     }
//     // echo json response
// });

// $app->get('/user', 'authenticate', function() use ($app) {
//     global $vendor_id;
//     $db = new DbHandler();

//     $user =  $db->getUserDetailByUserID($vendor_id);
//     if ($user != NULL) {
//         $response["user"] = $user;
//         echoResponse(200, $response);
//     } else {
//         $response['message'] = 'Invalid access_token';
//         echoResponse(400, $response);
//     }
// });

// $app->post('/user/forgotpass/mobile', function() use ($app) {
//     //check and parse valid json
//     $bodyData = isValidJson($app->request->getBody());

//     // reading valid post params from json otherwise return empty string
//     $mobileNumber = isset($bodyData->mobile_number) ? $bodyData->mobile_number : "";

//     //check for required parameter
//     verifyRequiredJsonFields($arrayName = array('mobile' => $mobileNumber));
//     $response = array();

//     $db = new DbHandler();
//     $user = $db->getUserByEmailOrMobile($mobileNumber);
//     if ($user != NULL) {
//             $user_id = $user['userId'];
//             $result = sendRandomCode($mobileNumber, $user["country_code"], $user_id, 2);
//         if ($result != NULL) {
//             $response["error"] = false;
//             $response["message"] = "Password reset code sent successfully";
//             $response["result"] = $user;
//             echoResponse(200, $response);
//         } else {
//             $response["error"] = true;
//             $response["message"] = "Failed to send code. Please try again";
//             echoResponse(400, $response);
//         }
//     } else {
//         // unknown error occurred
//         $response['error'] = true;
//         $response['message'] = "Mobile number does not exist.";
//         echoResponse(400, $response);
//     }
// });

// $app->post('/user/forgotpass/confirmation', 'authenticate', function() use ($app) {
//     //check and parse valid json
//     $bodyData = isValidJson($app->request->getBody());

//     // reading valid post params from json otherwise return empty string
//     $randomCode = isset($bodyData->random_code) ? $bodyData->random_code : "";

//     //check for required parameter
//     verifyRequiredJsonFields($arrayName = array('random_code' => $randomCode));
//     $response = array();

//     global $user_id;
//     $db = new DbHandler();
//     //confirm randomcode inside user
//     $isValid = $db->checkRandomCodeInUser($user_id, $randomCode);

//     if ($isValid != NULL) {
//         $response["error"] = false;
//         $response["message"] = "Successfully confirmed.";
//         echoResponse(200, $response);
//     } else {
//         $response["error"] = true;
//         $response["message"] = "Invalid random code. Please try again.";
//         echoResponse(400, $response);
//     }
// });

// $app->post('/user/forgotpass/change', 'authenticate', function() use ($app) {
//     //check and parse valid json
//     $bodyData = isValidJson($app->request->getBody());

//     // reading valid post params from json otherwise return empty string
//     $newPassword = isset($bodyData->new_password) ? $bodyData->new_password : "";

//     //check for required parameter
//     verifyRequiredJsonFields($arrayName = array('new_password' => $newPassword));
//     $response = array();

//     global $user_id;
//     $db = new DbHandler();
//     //confirm randomcode inside user
//     $isValid = $db->changeUserPassword($user_id, $newPassword);

//     if ($isValid != NULL) {
//         $response["error"] = false;
//         $response["message"] = "Successfully changed.";
//         echoResponse(200, $response);
//     } else {
//         $response["error"] = true;
//         $response['message'] = "An error occurred. Please try again";
//         echoResponse(400, $response);
//     }
// });

// $app->post('/user/devicetoken', 'authenticate', function() use ($app) {
//     //check and parse valid json
//     $bodyData = isValidJson($app->request->getBody());

//     // reading valid post params from json otherwise return empty string
//     $deviceToken = isset($bodyData->device_token) ? $bodyData->device_token : "";

//     //check for required parameter
//     verifyRequiredJsonFields($arrayName = array('device_token' => $deviceToken));
//     $response = array();

//     global $user_id;
//     $db = new DbHandler();
//     //confirm randomcode inside user
//     $isValid = $db->updateUserDeviceToken($user_id, $deviceToken);

//     if ($isValid != NULL) {
//         $response["error"] = false;
//         $response["message"] = "Device token successfully updated.";
//         echoResponse(200, $response);
//     } else {
//         $response["error"] = true;
//         $response['message'] = "An error occurred. Please try again";
//         echoResponse(400, $response);
//     }
// });

// $app->post('/user/mobile/verification', 'authenticate', function() use ($app) {
//     //check and parse valid json
//     $bodyData = isValidJson($app->request->getBody());

//     // reading valid post params from json otherwise return empty string
//     $mobileNumber = isset($bodyData->mobile_number) ? $bodyData->mobile_number : "";
//     $countryCode = isset($bodyData->country_code) ? $bodyData->country_code : "";

//     //check for required parameter
//     verifyRequiredJsonFields($arrayName = array('mobile' => $mobileNumber, 'country_code' => $countryCode));
//     $response = array();

//     global $user_id;
//     $db = new DbHandler();
//     $user = $db->getUserByVerifiedMobile($mobileNumber);
//     if ($user != NULL) {
//             $response["error"] = true;
//             $response["message"] = "Mobile number already exist.";
//             echoResponse(400, $response);
//     } else {
//         if (sendRandomCode($mobileNumber, $countryCode, $user_id, 1) != NULL) {
//             $response["error"] = false;
//             $response["message"] = "Verification code sent successfully";
//             echoResponse(200, $response);
//         } else {
//             $response["error"] = true;
//             $response["message"] = "Failed to send code. Please try again";
//             echoResponse(400, $response);
//         }
//     }
// });

// $app->post('/user/mobile/confirmation', 'authenticate', function() use ($app) {
//     //check and parse valid json
//     $bodyData = isValidJson($app->request->getBody());

//     //read valid post params from json otherwise return empty string
//     $randomCode = isset($bodyData->random_code) ? $bodyData->random_code : "";

//     //check for required parameter
//     verifyRequiredJsonFields($arrayName = array('random_code' => $randomCode));
//     $response = array();

//     global $user_id;
//     $db = new DbHandler();

//     //confirm randomcode inside user
//     $isValid = $db->confirmRandomCodeInUser($user_id, $randomCode);

//     if ($isValid != 0) {
//         $response["error"] = false;
//         $response["message"] = "Successfully confirmed.";
//         echoResponse(200, $response);
//     } else {
//         $response["error"] = true;
//         $response["message"] = "Invalid random code. Please try again.";
//         echoResponse(400, $response);
//     }
// });

// $app->post('user/upload/images', function() use ($app) {
//     //check and parse valid json
//     $bodyData = isValidJson($app->request->getBody());

//     // reading valid post params from json otherwise return empty string
//     $image_data = isset($bodyData->image_data) ? $bodyData->image_data : "";

//     //check for required parameter
//     verifyRequiredJsonFields($arrayName = array('vendor_logo_data' => $image_data));
//     $response = array();

//     $baseurl = "http://wreely/";
//     $baseimageurl = $baseurl."services/images/";
//     $uploaddir = realpath('../images'). '/';

//     $files = array();
//     $uploadfilename = uniqid().".jpg";
//     $uploadfile = $uploaddir . $uploadfilename;

//     $data = base64_decode($image_data);
//     file_force_contents($uploadfile, $data);
//     $file_url = $baseimageurl.$uploadfilename;

//     if ($file_url != NULL) {
//         $response["file_url"] = $file_url;
//         echoResponse(200, $response);
//     } else {
//         $response["message"] = "Oop's something went wrong.";
//         echoResponse(400, $response);
//     }
// });

/****************************** COMMON USER AUTHENTICATION FLOW END   *****************************/


/*
 * ------------------------FUNCTIONS------------------------
 */

function fetchData($url) {
    $ch = curl_init ($url) ;
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1) ;
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1) ;
    $res = curl_exec ($ch) ;
    curl_close ($ch) ;
    return ($res) ;
}

function file_force_contents($filename, $data, $flags = 0){
    if(!is_dir(dirname($filename)))
        mkdir(dirname($filename).'/', 0777, TRUE);
    return file_put_contents($filename, $data,$flags);
}

function sendRandomCode($mobileNumber, $countryCode, $user_id, $type) {
    $db = new DbHandler();
    //Insert randomcode inside db
    $digits = 5;
    $randomCode = rand(pow(10, $digits-1), pow(10, $digits)-1);

    $isInserted = NULL;
    if ($type == 1) { //Registration
        $isInserted = $db->insertRandomCodeInUserAgainstMobile($user_id, $randomCode, $mobileNumber, $countryCode);
        $message = "Your verification code is ".$randomCode;
    } else if ($type == 2) { //Password reset
        $isInserted = $db->insertRandomCodeInUserAgainstMobile($user_id, $randomCode, $mobileNumber, $countryCode);
        $message = "Your password reset code is ".$randomCode;
    } else if ($type == 3) { //Login code
        $isInserted = $db->insertRandomCodeAndNewAccessTokenInMemberAgainstMobile($randomCode, $mobileNumber);
        $message = "Your wreely login code is ".$randomCode;
    }
    if ($isInserted != NULL) {
        return sendViaMsg91($countryCode, $mobileNumber, $message);
    } else {
        return NULL;
    }
}

function sendViaMsg91($countryCode, $mobileNumber, $message) {
    $validPhoneNumber = $countryCode.$mobileNumber;
    $auth_key = "224731AHOERtgUl5b407128";
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "http://api.msg91.com/api/sendhttp.php?sender=MSGIND&route=4&mobiles=".$validPhoneNumber."&authkey=".$auth_key."&country=0&message=".$message,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
        return NULL;
    } else {
        return $response;
    }
}

function sendTwilioMessage($countryCode, $mobileNumber, $message) {
    $validPhoneNumber = $countryCode.$mobileNumber;
    $sid    = TwilioAccountSID;
    $token  = TwilioAuthToken;
    $twilio = new Client($sid, $token);

    $twillioMessage = $twilio->messages->create($validPhoneNumber, // to
                           array(
                               "body" => $message,
                               "from" => TwilioPhoneNumberTest
                           )
                  );
    print($twillioMessage->sid);
    return $twillioMessage->sid;
}

function sendNexmoMessage($countryCode, $mobileNumber, $message) {
    $validPhoneNumber = $countryCode.$mobileNumber;

    $basic  = new \Nexmo\Client\Credentials\Basic(NEXMO_API_KEY, NEXMO_API_SECRET);
    $client = new \Nexmo\Client($basic);

    $nexmoMessage = $client->message()->send([
        'to' => $validPhoneNumber,
        'from' => 'Wreely',
        'text' => $message
    ]);

    return $nexmoMessage;
}

function sendTextLocalMsg($countryCode, $mobileNumber, $message) {
    $validPhoneNumber = $countryCode.$mobileNumber;

    // Authorisation details.
	$username = "tirupati.balan@gmail.com";
	$hash = "3fe453554c89d48f11893e56a7ef662c703c25f4dc3cb50d21a6160d6a7977a6";

	// Config variables. Consult http://api.textlocal.in/docs for more info.
	$test = "0";

	// Data for text message. This is the text message data.
	$sender = "WREELY"; // This is who the message appears to be from.
	$numbers = $validPhoneNumber; // A single number or a comma-seperated list of numbers
	$message = $message;
	// 612 chars or less
	// A single number or a comma-seperated list of numbers
	$message = urlencode($message);
	$data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
	$ch = curl_init('http://api.textlocal.in/send/?');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch); // This is the result from the API
    curl_close($ch);
    return $result;    
}

function sendViaBulksmsgateway($countryCode, $mobileNumber, $message) {
    $validPhoneNumber = $countryCode.$mobileNumber;

    $username="tirupatibalan"; 
    $password="123456789"; 
    $message=$message;

    $sender="WREELY"; //ex:INVITE
    $mobile_number=$validPhoneNumber;
    $url="login.bulksmsgateway.in/sendmessage.php?user=".urlencode($username)."&password=".urlencode($password)."&mobile=".urlencode($mobile_number)."&sender=".urlencode($sender)."&message=".urlencode($message)."&type=".urlencode('3'); 
    //$url="login.bulksmsgateway.in/sendmessage.php?user=.urlencode($username)."&password=".urlencode($password)."&mobile=".urlencode($mobile_number)."&message=".urlencode($message)."&sender=".urlencode($sender)."&type=".urlencode('3');

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $curl_scraped_page = curl_exec($ch);
    curl_close($ch); 

    return $curl_scraped_page;
}

function sendViaKarix($countryCode, $mobileNumber, $message) {
    $validPhoneNumber = $countryCode.$mobileNumber;

    // Configure HTTP basic authorization: basicAuth
    $config = new \Swagger\Client\Configuration();
    $config->setUsername('d7da5f9c-0e2e-4959-986a-7c0073cfff39');
    $config->setPassword('ebad861a-6ddd-437b-a186-21a6f3b2d605');
    $apiInstance = new Swagger\Client\Api\MessageApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
    );
    $api_version = "1.0"; // string | API Version. If not specified your pinned verison is used.
    $message = new \Swagger\Client\Model\CreateMessage(); // \Swagger\Client\Model\CreateAccount | Subaccount object
    date_default_timezone_set('UTC');
    $message->setDestination([$validPhoneNumber]);
    $message->setSource("441224085206");
    $message->setText($message);
    try {
        $result = $apiInstance->sendMessage($api_version, $message);
        print_r($result);
        return 1;
    } catch (Exception $e) {
        echo 'Exception when calling MessageApi->createMessage: ', $e->getMessage(), PHP_EOL;
        return 0;
    }
}

/**
 * Check if string is valid json
 */
function isValidJson($string) {
    json_decode($string);
    if (json_last_error() == JSON_ERROR_NONE && strlen(trim($string)) != 0) {
        return json_decode($string);
    } else {
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Invalid json in post.';
        echoResponse(400, $response);
        $app->stop();
    }
}

/**
 * Verifying required params posted or not
 */
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoResponse(400, $response);
        $app->stop();
    }
}

function verifyRequiredJsonFields($required_fields) {
    $error = false;
    $error_fields = "";
    foreach ($required_fields as $key => $value) {
        if (!isset($value) || strlen(trim($value)) <= 0) {
            $error = true;
            $error_fields .= $key . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required post parameter ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoResponse(400, $response);
        $app->stop();
    }
}

function make_google_calendar_link($name, $begin, $end, $location, $details) {
	$params = array('&dates=', '/', '&details=', '&location=', '&sf=true&output=xml');
	$url = 'https://www.google.com/calendar/render?action=TEMPLATE&text=';
	$arg_list = func_get_args();
    for ($i = 0; $i < count($arg_list); $i++) {
    	$current = $arg_list[$i];
    	if(is_int($current)) {
    		$t = new DateTime('@' . $current, new DateTimeZone('UTC'));
    		$current = $t->format('Ymd\THis\Z');
    		unset($t);
    	}
    	else {
    		$current = urlencode($current);
    	}
    	$url .= (string) $current . $params[$i];
    }
    return $url;
}

/**
 * Validating email address
 */
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
        echoResponse(400, $response);
        $app->stop();
    }
}

/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoResponse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

function getUserProfileFromFacebookWithAccessToken($access_token) {
    $fb = new Facebook\Facebook([
        'app_id' => 'FACEBOOK_APP_ID',
        'app_secret' => '06011b4bba7c212b7feafe24e9922909',
        'default_graph_version' => 'v2.4',
        'default_access_token' => $access_token, // optional
    ]);

    // Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
    //   $helper = $fb->getRedirectLoginHelper();
    //   $helper = $fb->getJavaScriptHelper();
    //   $helper = $fb->getCanvasHelper();
    //   $helper = $fb->getPageTabHelper();
    $customResponse = array();
    try {
        // Get the Facebook\GraphNodes\GraphUser object for the current user.
        // If you provided a 'default_access_token', the '{access-token}' is optional.
        $customResponse["error"] = false;
        $response = $fb->get('/me?fields=id,name,email,birthday,gender', $access_token);

        $me = $response->getGraphUser();
        $customResponse["graph_user"] = $me;
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        $customResponse["error"] = true;
        $customResponse["message"] = 'Graph returned an error: ' . $e->getMessage();
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        $customResponse["error"] = true;
        $customResponse["message"] = 'Facebook SDK returned an error: ' . $e->getMessage();
    }

    return $customResponse;
}

/*
 * ------------------------PEPIPOST------------------------
 */

    function sendDeafultMailViaPepipost($name, $email, $subject, $first_paragraph, $second_paragraph, $vendor_data, $tags) {
        $vendor_email = $vendor_data["email"];
        $vendor_name = $vendor_data["name"];
        $vendor_id = $vendor_data["user_id"];
        $vendor_logo_mini_image = $vendor_data["vendor_logo_url"];

        $data["api_key"] = "8ddab8b710bc724c25a1fff71d164b89";

        $email_details["fromname"] = $vendor_name.' via Wreely';
        $email_details["subject"] = $subject;
        $email_details["from"] = "no_reply@wreely.com";
        $email_details["replytoid"] = "hello@wreely.com";
        $data["email_details"] = $email_details;

        $data["tags"] = $tags;

        $settings["bcc"] = "tirupati.balan@gmail.com";
        $settings["template"] = "7393";
        $data["settings"] = $settings;

        $data["recipients"] = array($vendor_email);

        $attributes["FNAME"] = array($name);
        $attributes["UMESSAGE"] = array($first_paragraph);
        $attributes["SMESSAGE"] = array($second_paragraph);
        $attributes["UEMAIL"] = array($email);
        $attributes["HIMAGE"] = array($vendor_logo_mini_image);
        $attributes["MC_PREVIEW_TEXT"] = array("");
        $data["attributes"] = $attributes;               

        $data_string = json_encode($data);                                                                                   
                                                                                                                            
        $ch = curl_init('https://api.pepipost.com/api/web.send.json');                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($data_string))                                                                       
        );                                                                                                                                                                                                                                          
        $result = curl_exec($ch);
    }

 function sendEnquiryMailViaPepipost($name, $contact, $email, $reference_source, $visit_reason, $how_many, $tell_us_more, $vendor_data) {
      $vendor_email = $vendor_data["email"];
      $vendor_name = $vendor_data["name"];
      $vendor_id = $vendor_data["user_id"];
      $vendor_logo_mini_image = $vendor_data["vendor_logo_url"];

      $data["api_key"] = "8ddab8b710bc724c25a1fff71d164b89";

      $email_details["fromname"] = $vendor_name.' via Wreely';
      $email_details["subject"] = "Hello, new member enquiry received!!";
      $email_details["from"] = "no_reply@wreely.com";
      $email_details["replytoid"] = "hello@wreely.com";
      $data["email_details"] = $email_details;

      $data["tags"] = "Enquiry";

      $settings["bcc"] = "tirupati.balan@gmail.com";
      $settings["template"] = "6527";
      $data["settings"] = $settings;

      $data["recipients"] = array($vendor_email);

      $attributes["FNAME"] = array($name);
      $attributes["CONTACT"] = array($contact);
      $attributes["UEMAIL"] = array($email);
      $attributes["REFERENCESOURCE"] = array($reference_source);
      $attributes["VISITREASON"] = array($visit_reason);
      $attributes["HOWMANY"] = array($how_many);
      $attributes["HIMAGE"] = array($vendor_logo_mini_image);
      $attributes["TELLUSMORE"] = array($tell_us_more);
      $attributes["MC_PREVIEW_TEXT"] = array("");
      $data["attributes"] = $attributes;               

      $data_string = json_encode($data);                                                                                   
                                                                                                                            
      $ch = curl_init('https://api.pepipost.com/api/web.send.json');                                                                      
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($data_string))                                                                       
      );                                                                                                                                                                                                                                          
      $result = curl_exec($ch);
}

/*
 * ------------------------MANDRILL------------------------
 */

function sendEnquiryMail($name, $contact, $email, $reference_source, $visit_reason, $how_many, $tell_us_more, $vendor_data) {
     $vendor_email = $vendor_data["email"];
     $vendor_name = $vendor_data["name"];
     $vendor_id = $vendor_data["user_id"];
     $vendor_logo_mini_image = $vendor_data["vendor_logo_url"];
     try {
        $mandrill = new Mandrill(MANDRILL_KEY);
        $template_name = 'SendEnquiry';
        $template_content = null;
        $message = array(
            'subject' => 'Hello, new member enquiry received!!',
            'from_email' => 'no_reply@wreely.com',
            'from_name' => $vendor_name.' via Wreely',
            'to' => array(
                array(
                    'email' => $vendor_email,
                    'type' => 'to'
                ),
                array(
                    'email' => 'tirupati.balan@gmail.com',
                    'type' => 'to'
                )
            ),
            'important' => true,
            'merge' => true,
            'global_merge_vars' => array(
                array(
                    'name' => 'FNAME',
                    'content' => $name
                ),
                array(
                    'name' => 'CONTACT',
                    'content' => $contact
                ),
                array(
                    'name' => 'EMAIL',
                    'content' => $email
                ),
                array(
                    'name' => 'REFERENCESOURCE',
                    'content' => $reference_source
                ),
                array(
                    'name' => 'VISITREASON',
                    'content' => $visit_reason
                ),
                array(
                    'name' => 'HOWMANY',
                    'content' => $how_many
                ),
                array(
                    'name' => 'HIMAGE',
                    'content' => $vendor_logo_mini_image
                ),
                array(
                    'name' => 'TELLUSMORE',
                    'content' => $tell_us_more
                )
            ),
            'tags' => array('template-sendenquiry')
        );
        $async = true;
        $mandrill->messages->sendTemplate($template_name, $template_content, $message, $async);
      } catch(Mandrill_Error $e) {
        //Add Logs
      }
}

function sendAttendanceReportToMember($member_name, $start_date, $end_date, $company_name, $receiver_email, $attachment_encoded, $vendor_data) {
    $vendor_email = $vendor_data["email"];
    $vendor_name = $vendor_data["name"];
    $vendor_id = $vendor_data["user_id"];
    $vendor_logo_mini_image = $vendor_data["vendor_logo_url"];
     try {
        $fileName = $start_date.'_'.$end_date.'.pdf';
        $mandrill = new Mandrill('EP30QIWuZFoaFE_zRlRB_g');
        $template_name = 'AttendanceReportToMember';
        $template_content = null;
        $message = array(
            'subject' => 'Flexi Attendance Report - '.$vendor_name,
            'from_email' => 'no_reply@wreely.com',
            'from_name' => $vendor_name.' via Wreely',
            'to' => array(
                array(
                    'email' => $vendor_email,
                    'type' => 'to'
                ),
                array(
                    'email' => 'tirupati.balan@gmail.com',
                    'type' => 'to'
                )
            ),
            'important' => true,
            'merge' => true,
            'global_merge_vars' => array(
                array(
                    'name' => 'CNAME',
                    'content' => $company_name
                ),
                array(
                    'name' => 'MNAME',
                    'content' => $member_name
                ),
                array(
                    'name' => 'STARTDATE',
                    'content' => $start_date
                ),
                array(
                    'name' => 'ENDDATE',
                    'content' => $end_date
                ),
                array(
                    'name' => 'HIMAGE',
                    'content' => $vendor_logo_mini_image
                ),
                array(
                    'name' => 'VNAME',
                    'content' => $vendor_name
                )
            ),
            "attachments" => array(
                array(
                    'content' => $attachment_encoded,
                    'type' => "application/pdf",
                    'name' => $fileName
                  )
            ),
            'tags' => array('AttendanceReportToMember')
        );
        $async = false;
        return $mandrill->messages->sendTemplate($template_name, $template_content, $message, $async);
      } catch(Mandrill_Error $e) {
        //Add Logs
      }
}

function sendDailyAttendanceStatusToMember($member_name, $vendor_name, $checkin_time, $receiver_email, $no_of_days_left, $vendor_data) {
        $vendor_email = $vendor_data["email"];
        $vendor_name = $vendor_data["name"];
        $vendor_id = $vendor_data["user_id"];
        $vendor_logo_mini_image = $vendor_data["vendor_logo_url"];
     try {
        $mandrill = new Mandrill('EP30QIWuZFoaFE_zRlRB_g');
        $template_name = 'DailyReminder';
        $template_content = null;
        $message = array(
            'subject' => 'Flexi Attendance Status - '.$vendor_name,
            'from_email' => 'no_reply@wreely.com',
            'from_name' => $vendor_name.' via Wreely',
            'to' => array(
                array(
                    'email' => $vendor_email,
                    'type' => 'to'
                ),
                array(
                    'email' => $receiver_email,
                    'type' => 'bcc'
                )
            ),
            'important' => true,
            'merge' => true,
            'global_merge_vars' => array(
                array(
                    'name' => 'MNAME',
                    'content' => $member_name
                ),
                array(
                    'name' => 'CTIME',
                    'content' => $checkin_time
                ),
                array(
                    'name' => 'VNAME',
                    'content' => $vendor_name
                ),
                array(
                    'name' => 'HIMAGE',
                    'content' => $vendor_logo_mini_image
                ),
                array(
                    'name' => 'NOOFDAYSLEFT',
                    'content' => $no_of_days_left
                )
            ),
            'tags' => array('DailyReminder')
        );
        $async = true;
        $mandrill->messages->sendTemplate($template_name, $template_content, $message, $async);
      } catch(Mandrill_Error $e) {

      }
      return 1;
}

$app->run();
?>
