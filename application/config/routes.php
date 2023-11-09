<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "login";
$route['404_override'] = 'error';


/*********** USER DEFINED ROUTES *******************/

//Setting
$route['syncFirebase'] = 'settings/syncFirebase';

//Space
$route['spaceListing'] = 'spaceManagement/spaceListing';
$route['spaceFlexible'] = 'spaceManagement/spaceFlexible';
$route['spaceDedicated'] = 'spaceManagement/spaceDedicated';

$route['spaceFlexible/(:num)'] = 'spaceManagement/spaceFlexible/$1';
$route['spaceDedicated/(:num)'] = 'spaceManagement/spaceDedicated/$1';
$route['spaceListing/(:num)'] = 'spaceManagement/spaceListing/$1';
$route['addNewSpace'] = "spaceManagement/addNewSpace"; //in use to load add new form
$route['addSpace'] = "spaceManagement/addSpace"; //in use to submit new data in database
$route['editOldSpace'] = "spaceManagement/editOldSpace";
$route['editOldSpace/(:num)'] = "spaceManagement/editOldSpace/$1"; //in use to load seperate form with data based on id
$route['editSpace'] = "spaceManagement/editSpace"; //in use to submit edited data in database
$route['deleteSpace'] = "spaceManagement/deleteSpace";

//MembershipType
$route['membershipTypeAndSeatListing'] = 'membershipTypeAndSeats/membershipTypeAndSeatListing';
$route['membershipTypeAndSeatListing/(:num)'] = 'membershipTypeAndSeats/membershipTypeAndSeatListing/$1';
$route['editMembershipType'] = "membershipTypeAndSeats/editMembershipType";
$route['deleteMembershipType'] = "membershipTypeAndSeats/deleteMembershipType";
$route['addMembershipType'] = "membershipTypeAndSeats/addMembershipType"; //in use to submit new data in database

//Seats
$route['editSeat'] = "membershipTypeAndSeats/editSeat"; //in use to submit edited data in database
$route['deleteSeat'] = "membershipTypeAndSeats/deleteSeat";
$route['addSeat'] = "membershipTypeAndSeats/addSeat"; //in use to submit new data in database

//Member
$route['memberListing'] = 'member/memberListing';
$route['memberListing/(:num)'] = 'member/memberListing/$1';
$route['addNewMember'] = "member/addNewMember";
$route['editMember/(:num)'] = "member/editMember/$1";
$route['memberdetails/(:num)'] = "member/memberdetails/$1";
$route['deleteMember'] = "member/deleteMember";

//Staff
$route['staffListing'] = 'user/staffListing';
$route['memberListing/(:num)'] = 'member/memberListing/$1';

//Events
$route['eventsListing'] = 'event/eventsListing';
$route['eventsListing/(:num)'] = 'event/eventsListing/$1';
$route['eventsDatatableListing'] = 'event/eventsDatatableListing';

//MeetingRoom
$route['meetingRoomListing'] = 'meetingRoom/meetingRoomListing';
$route['meetingRoomDatatableListing'] = 'meetingRoom/meetingRoomDatatableListing';
$route['editMeetingRoom'] = "meetingRoom/editMeetingRoom";
$route['deleteMeetingRoom'] = "meetingRoom/deleteMeetingRoom";
$route['addMeetingRoom'] = "meetingRoom/addMeetingRoom";
$route['deleteAllMeetingRoom'] = "meetingRoom/deleteAllMeetingRoom";

//MeetingRoomBookings
$route['meetingRoomBookingsListing'] = 'meetingRoomBooking/meetingRoomBookingsListing';
$route['meetingRoomBookingsDatatableListing'] = 'meetingRoomBooking/meetingRoomBookingsDatatableListing';

//Outside Member Entry
$route['addCoworker'] = 'addMemberForCompany/addCoworker';
$route['checkCoworkerEmailExist'] = "addMemberForCompany/checkCoworkerEmailExist";

//Company
$route['companyListing'] = 'company/companyListing';
$route['companyListing/(:num)'] = 'company/companyListing/$1';
$route['addNewCompany'] = "company/addNewMember";
$route['editCompany/(:num)'] = "company/editCompany/$1";
$route['companydetails/(:num)'] = "company/companydetails/$1";
$route['deleteCompany'] = "company/deleteCompany";

//FlexiAttendance
$route['flexiAttendanceListing'] = 'flexiAttendance/flexiAttendanceListing';
$route['flexiAttendanceListing/(:num)'] = 'flexiAttendance/flexiAttendanceListing/$1';
$route['flexiAttendanceOfMember'] = 'flexiAttendance/flexiAttendanceOfMember';
$route['flexiAttendanceMail'] = 'flexiAttendance/flexiAttendanceMail';

//MailHandler
$route['sendAttendanceReport'] = 'mailHandler/sendAttendanceReport';
$route['sendCoworkerFillUpForm'] = 'mailHandler/sendCoworkerFillUpForm';

//Enquiry
$route['enquiriesListing'] = 'enquiry/enquiriesListing';
$route['enquiriesListing/(:num)'] = 'enquiry/enquiriesListing/$1';
$route['editOldEnquiry'] = "enquiry/editOldEnquiry";
$route['editOldEnquiry/(:num)'] = "enquiry/editOldEnquiry/$1";
$route['editEnquiry'] = "enquiry/editEnquiry";
$route['deleteEnquiry'] = "enquiry/deleteEnquiry";

//User
$route['loginMe'] = 'login/loginMe';
$route['dashboard'] = 'user';
$route['logout'] = 'user/logout';
$route['userListing'] = 'user/userListing';
$route['userListing/(:num)'] = "user/userListing/$1";
$route['addNew'] = "user/addNew";
$route['addNewUser'] = "user/addNewUser";
$route['editOld'] = "user/editOld";
$route['editOld/(:num)'] = "user/editOld/$1";
$route['editUser'] = "user/editUser";
$route['deleteUser'] = "user/deleteUser";
$route['loadChangePass'] = "user/loadChangePass";
$route['changePassword'] = "user/changePassword";
$route['pageNotFound'] = "user/pageNotFound";
$route['checkEmailExists'] = "user/checkEmailExists";
$route['forgotPassword'] = "login/forgotPassword";
$route['resetPasswordUser'] = "login/resetPasswordUser";
$route['resetPasswordConfirmUser'] = "login/resetPasswordConfirmUser";
$route['resetPasswordConfirmUser/(:any)'] = "login/resetPasswordConfirmUser/$1";
$route['resetPasswordConfirmUser/(:any)/(:any)'] = "login/resetPasswordConfirmUser/$1/$2";
$route['createPasswordUser'] = "login/createPasswordUser";

/* End of file routes.php */
/* Location: ./application/config/routes.php */
