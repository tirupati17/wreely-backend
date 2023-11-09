<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Meeting_room_model extends CI_Model
{

    function getMeetingRoomListing() {
        $this->db->select('BaseTbl.id, BaseTbl.name as room_name, BaseTbl.description, BaseTbl.vendor_id, BaseTbl.header_image_url, BaseTbl.start_time, BaseTbl.end_time');
        $this->db->from('tbl_meeting_rooms as BaseTbl');

        $this->db->where('BaseTbl.vendor_id', $this->session->userdata('userId'));
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    function addMeetingRoom($tableInfo) {
        $this->db->trans_start();
        $this->db->insert('tbl_meeting_rooms', $tableInfo);

        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function editMeetingRoom($tableInfo, $id) {
        $this->db->where('id', $id);
        $this->db->update('tbl_meeting_rooms', $tableInfo);

        return TRUE;
    }

    function getMeetingRooms() {
        $this->db->select('id, name, vendor_id, header_image_url');
        $this->db->from('tbl_meeting_rooms');
        $this->db->where('vendor_id', $this->session->userdata('userId'));
        $query = $this->db->get();

        return $query->result();
    }

    function updateMeetingRoom($tableInfo) {
        $this->db->where('id', $tableInfo['id']);
        unset($tableInfo['id']);
        $this->db->update('tbl_meeting_rooms', $tableInfo);
        return TRUE;
    }

    function deleteMeetingRoom($id, $tableInfo) {
        $this->db->where('id', $id);
        $this->db->update('tbl_meeting_rooms', $tableInfo);
        return $this->db->affected_rows();
    }

    function deleteAllMeetingRoom($tableInfo) {
        $this->db->update('tbl_meeting_rooms', $tableInfo);
        return $this->db->affected_rows();
    }

}
