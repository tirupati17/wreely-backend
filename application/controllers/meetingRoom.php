<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class MeetingRoom extends BaseController {

    /**
     * This is default constructor of the class
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('meeting_room_model');
        $this->isLoggedIn();
    }

    public function index() {
        $this->global['pageTitle'] = 'Wreely : Dashboard';
        $this->loadViews("dashboard", $this->global, NULL, NULL);
    }

    function meetingRoomListing() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'Wreely : Meeting Room Bookings';
            $data['vendor_id'] = $this->session->userdata['userId'];
            $this->loadViews("meetingRoomList", $this->global, $data, NULL);
        }
    }

    function meetingRoomDatatableListing() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->model('meeting_room_model');
            $result = $this->meeting_room_model->getMeetingRoomListing();
            if ($result > 0) {
                echo(json_encode(array('data' => $result, 'draw' => 1, 'recordsTotal' => 10, 'recordsFiltered' => 20)));
            } else {
                echo(json_encode(array('data' => [], 'draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0)));
            }      
        }
    }

    function editMeetingRoom() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $userId = $this->input->post('userId');
            $formValues = $this->input->post();
            $ucwordsArr = array('name', 'vendor_id', 'header_image_url', 'description', 'start_time', 'end_time');
            foreach ($formValues as $key => $val) {
                if ($key == "meetingRoomId") {
                    $arrDb["id"] = $val;
                }
                if (in_array($key, $ucwordsArr)) {
                    $arrDb[$key] = $val;
                    if ($key == 'start_time' || $key == 'end_time') {
                        $time_timestamp = strtotime($formValues[$key]);
                        $arrDb[$key] = date('h:i A', $time_timestamp);
                    }
                }
            }
            $objInfo = $arrDb;
            $this->load->model('meeting_room_model');
            $result = $this->meeting_room_model->updateMeetingRoom($objInfo);

            if ($result == true) {
                echo(json_encode(array('status' => TRUE)));
            } else {
                echo(json_encode(array('status' => FALSE)));
            }
        }
    }

    function addMeetingRoom() {
        if ($this->isAdmin() == TRUE) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $formValues = $this->input->post();
            $ucwordsArr = array('name', 'vendor_id', 'header_image_url', 'description', 'start_time', 'end_time');
            foreach ($formValues as $key => $val) {
                if (in_array($key, $ucwordsArr)) {
                    $arrDb[$key] = $val;
                    if ($key == 'start_time' || $key == 'end_time') {
                        $time_timestamp = strtotime($formValues[$key]);
                        $arrDb[$key] = date('h:i A', $time_timestamp);
                    }
                }
            }

            $objInfo = $arrDb;
            $this->load->model('meeting_room_model');
            $result = $this->meeting_room_model->addMeetingRoom($objInfo);

            if ($result == true) {
                echo(json_encode(array('status' => TRUE)));
            } else {
                echo(json_encode(array('status' => FALSE)));
            }
        }
    }

    function deleteMeetingRoom() {
        if ($this->isAdmin() == TRUE) {
            echo(json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('meetingRoomId');
            $objInfo = array('isDeleted' => 1);

            $result = $this->meeting_room_model->deleteMeetingRoom($id, $objInfo);

            if ($result > 0) {
                echo(json_encode(array('status' => TRUE)));
            } else {
                echo(json_encode(array('status' => FALSE)));
            }
        }
    }

    function deleteAllMeetingRoom() {
        if ($this->isAdmin() == TRUE) {
            echo(json_encode(array('status' => 'access')));
        } else {
            $objInfo = array('isDeleted' => 1);
            $result = $this->meeting_room_model->deleteAllMeetingRoom($objInfo);

            if ($result > 0) {
                echo(json_encode(array('status' => TRUE)));
            } else {
                echo(json_encode(array('status' => FALSE)));
            }
        }
    }


    function pageNotFound() {
        $this->global['pageTitle'] = 'Wreely : 404 - Page Not Found';
        $this->loadViews("404", $this->global, NULL, NULL);
    }
}

?>
