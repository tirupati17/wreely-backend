<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Meeting_room_booking_model extends CI_Model
{

    function getMeetingRoomBookingsListing($roomId, $fromDate, $toDate) {
        $this->db->select('BaseTbl.id, COUNT(BaseTbl.booked_by_member_id) as total_slots, BaseTbl.meeting_room_id, Rooms.name as room_name, Members.full_name as member_name, Members.email_id as member_email, BaseTbl.start_time, BaseTbl.end_time');
        $this->db->from('tbl_meeting_room_bookings as BaseTbl');
        $this->db->join('tbl_meeting_rooms as Rooms', 'Rooms.id = BaseTbl.meeting_room_id','left');
        $this->db->join('tbl_members as Members', 'Members.id = BaseTbl.booked_by_member_id','left');
        if($roomId != 0) {
            $this->db->where("BaseTbl.meeting_room_id", $roomId);
        }
        if(!empty($fromDate)) {
            $this->db->where('BaseTbl.start_time >=', $fromDate);
        }
        if(!empty($toDate)) {
            $this->db->where('BaseTbl.start_time <=', $toDate);
        }

        $this->db->order_by('BaseTbl.start_time', 'DESC');
        $this->db->group_by('BaseTbl.booked_by_member_id');
        $this->db->where('vendor_id', $this->session->userdata('userId'));
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    function addNewMeetingRoomBooking($tableInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_meeting_room_bookings', $tableInfo);

        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function editMeetingRoomBooking($tableInfo, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_meeting_room_bookings', $tableInfo);

        return TRUE;
    }

    function getMeetingRoomBooking()
    {
        $this->db->select('*');
        $this->db->from('tbl_meeting_room_bookings');
        $query = $this->db->get();

        return $query->result();
    }

    function updateMeetingRoomBooking($tableInfo)
    {
        $this->db->where('id', $tableInfo['id']);
        unset($tableInfo['id']);
        $this->db->update('tbl_meeting_room_bookings', $tableInfo);
        return TRUE;
    }

    function deleteMeetingRoomBooking($id, $tableInfo)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_meeting_room_bookings', $tableInfo);

        return $this->db->affected_rows();
    }

}
