<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @version : 1.1
 */
class MeetingRoomBooking extends BaseController {

    /**
     * This is default constructor of the class
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('meeting_room_booking_model');
        $this->load->model('meeting_room_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index() {
        $this->global['pageTitle'] = 'Wreely : Dashboard';
        $this->loadViews("dashboard", $this->global, NULL, NULL);
    }

    function meetingRoomBookingsListing() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $data['rooms'] = $this->meeting_room_model->getMeetingRooms();

            $this->global['pageTitle'] = 'Wreely : Meeting Room Bookings';
            $this->loadViews("meetingRoomBookings", $this->global, $data, NULL);
        }
    }

    function meetingRoomBookingsDatatableListing() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->model('meeting_room_booking_model');

            $roomId = $this->input->post('roomId');
            $fromDate = $this->input->post('from');
            $toDate = $this->input->post('to');

            $result = $this->meeting_room_booking_model->getMeetingRoomBookingsListing($roomId, $fromDate, $toDate);
            if ($result > 0) {
                echo(json_encode(array('data' => $result, 'draw' => 1, 'recordsTotal' => 10, 'recordsFiltered' => 20)));
            } else {
                echo(json_encode(array('data' => [], 'draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0)));
            }      
        }
    }

    function pageNotFound() {
        $this->global['pageTitle'] = 'Wreely : 404 - Page Not Found';
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

?>
