<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Event_model extends CI_Model
{
    
    
    function eventListingCount($fromDate, $toDate)
    {
        $this->db->select('BaseTbl.id, BaseTbl.title, COUNT(EventAttendance.id) as total_rsvp, BaseTbl.description, BaseTbl.header_image_url, BaseTbl.vendor_id, BaseTbl.start_time, BaseTbl.end_time');
        $this->db->from('tbl_events as BaseTbl');
        $this->db->join('tbl_events_attendance as EventAttendance', 'EventAttendance.event_id = BaseTbl.id','left');
        if(!empty($fromDate)) {
            $this->db->where('DATE(`start_time`) >=', $fromDate. ' 00:00:00');
        }
        if(!empty($toDate)) {
            $this->db->where('DATE(`start_time`) <=', $toDate. ' 00:00:00');
        }
        $this->db->group_by('BaseTbl.id');
        $this->db->order_by('BaseTbl.start_time', 'DESC');
        $this->db->where('vendor_id', $this->session->userdata('userId'));
        $query = $this->db->get();

        $result = count($query->result());
        return $result;
    }

    function getEventListing($fromDate, $toDate, $page = '', $segment = '') {
        //(SELECT COUNT(*) FROM group_members WHERE member_id = groups.id) AS memberCount
        $this->db->select('BaseTbl.id, BaseTbl.title, COUNT(EventAttendance.id) as total_rsvp, BaseTbl.description, BaseTbl.header_image_url, BaseTbl.vendor_id, BaseTbl.start_time, BaseTbl.end_time');
        $this->db->from('tbl_events as BaseTbl');
        $this->db->join('tbl_events_attendance as EventAttendance', 'EventAttendance.event_id = BaseTbl.id','left');
        if(!empty($fromDate)) {
            $this->db->where('DATE(`start_time`) >=', $fromDate . ' 00:00:00');
        }
        if(!empty($toDate)) {
            $this->db->where('DATE(`start_time`) <=', $toDate. ' 00:00:00');
        }
        $this->db->group_by('BaseTbl.id');
        $this->db->order_by('BaseTbl.start_time', 'DESC');
        $this->db->where('vendor_id', $this->session->userdata('userId'));
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        //print_r($this->db->last_query());

        $result = $query->result();
        return $result;
    }

    function addNewEvent($tableInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_events', $tableInfo);

        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function editEvent($tableInfo, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_events', $tableInfo);

        return TRUE;
    }

    function updateEvent($tableInfo)
    {
        $this->db->where('id', $tableInfo['id']);
        unset($tableInfo['id']);
        $this->db->update('tbl_events', $tableInfo);
        return TRUE;
    }

    function deleteEvent($id, $tableInfo)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_events', $tableInfo);

        return $this->db->affected_rows();
    }

}
