<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of member_model
 *
 * @author tirupatibalan
 */

class Flexi_attendance_model extends CI_Model {

    /**
     * This function is used to get the member listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function flexiAttendanceCount($searchText = '') {
        $this->db->select('BaseTbl.member_id, Companies.name, Members.full_name, Membership_type.number_of_day, Membership_type.membership_name, MAX(BaseTbl.attendance_date) as attendance_date, BaseTbl.signature_base_64, BaseTbl.company_id, BaseTbl.membership_type_id, COUNT(Members.id) as attendance_count');
        $this->db->from('tbl_flexi_attendance as BaseTbl');
        $this->db->join('tbl_space as Space', 'BaseTbl.attendance_date >= Space.start_date AND BaseTbl.attendance_date <= Space.expiry_date', 'left');
        $this->db->join('tbl_companies as Companies', 'Companies.id = BaseTbl.company_id','left');
        $this->db->join('tbl_members as Members', 'Members.id = BaseTbl.member_id','left');
        $this->db->join('tbl_membership_type as Membership_type', 'Membership_type.id = Space.membership_type_id','left');
        $this->db->group_by('Members.id');
        $this->db->order_by('attendance_count', 'asc');
        $this->db->where('Space.plan_type', PLAN_FLEXIBLE);
        $this->db->where('BaseTbl.member_id = Space.member_id');

        if (!empty($searchText)) {
            $likeCriteria = "(attendance_date  LIKE '%" . $searchText . "%'
                            OR  Companies.name  LIKE '%" . $searchText . "%'
                            OR  Members.full_name  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $query = $this->db->get();
        $this->db->where('BaseTbl.user_id', $this->session->userdata('userId'));

        return count($query->result());
    }

    /**
     * This function is used to get the member listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function flexiAttendanceListing($searchText = '', $page, $segment) {
        $this->db->select('BaseTbl.member_id, Companies.name, Members.full_name, Membership_type.number_of_day, Membership_type.membership_name, MAX(BaseTbl.attendance_date) as attendance_date, BaseTbl.signature_base_64, BaseTbl.company_id, BaseTbl.membership_type_id, COUNT(Members.id) as attendance_count, Space.start_date, Space.expiry_date');
        $this->db->from('tbl_flexi_attendance as BaseTbl');
        $this->db->join('tbl_space as Space', 'BaseTbl.attendance_date >= Space.start_date AND BaseTbl.attendance_date <= Space.expiry_date', 'BaseTbl.member_id = Space.member_id', 'left');
        $this->db->join('tbl_companies as Companies', 'Companies.id = BaseTbl.company_id','left');
        $this->db->join('tbl_members as Members', 'Members.id = BaseTbl.member_id','left');
        $this->db->join('tbl_membership_type as Membership_type', 'Membership_type.id = Space.membership_type_id','left');
        $this->db->group_by('Members.id');
        $this->db->order_by('Companies.name');
        $this->db->where('Space.plan_type', PLAN_FLEXIBLE);
        $this->db->where('BaseTbl.member_id = Space.member_id');
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.attendance_date  LIKE '%" . $searchText . "%'
                            OR  Companies.name  LIKE '%" . $searchText . "%'
                            OR  Members.full_name  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.user_id', $this->session->userdata('userId'));
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    function getFlexiListForMemberId($memberId, $fromDate, $toDate)
    {
      $this->db->select('BaseTbl.id, BaseTbl.member_id, Companies.name, Members.full_name, Membership_type.number_of_day, Membership_type.membership_name, BaseTbl.attendance_date as attendance_date, BaseTbl.signature_base_64, BaseTbl.company_id, BaseTbl.membership_type_id');
      $this->db->from('tbl_flexi_attendance as BaseTbl');
      $this->db->join('tbl_companies as Companies', 'Companies.id = BaseTbl.company_id','left');
      $this->db->join('tbl_members as Members', 'Members.id = BaseTbl.member_id','left');
      $this->db->join('tbl_membership_type as Membership_type', 'Membership_type.id = BaseTbl.membership_type_id','left');
      $this->db->order_by('attendance_date', 'asc');
      $this->db->where('BaseTbl.member_id', $memberId);
      $this->db->where('BaseTbl.attendance_date >=', $fromDate);
      $this->db->where('BaseTbl.attendance_date <=', $toDate);

      $query = $this->db->get();
      $result = $query->result();
      return $result;
    }

    function getFlexiListForMemberIdForAttendance($memberId, $fromDate, $toDate)
    {
      $this->db->select('Companies.name, Members.full_name, BaseTbl.attendance_date as attendance_date, Membership_type.membership_name');
      $this->db->from('tbl_flexi_attendance as BaseTbl');
      $this->db->join('tbl_companies as Companies', 'Companies.id = BaseTbl.company_id','left');
      $this->db->join('tbl_members as Members', 'Members.id = BaseTbl.member_id','left');
      $this->db->join('tbl_membership_type as Membership_type', 'Membership_type.id = BaseTbl.membership_type_id','left');
      $this->db->order_by('attendance_date', 'asc');
      $this->db->where('BaseTbl.member_id', $memberId);
      $this->db->where('BaseTbl.attendance_date >=', $fromDate);
      $this->db->where('BaseTbl.attendance_date <=', $toDate);

      $query = $this->db->get();
      $result = $query->result();
      return $result;
    }

    function getRenewalExpiryDateOfMember($memberId)
    {
      $this->db->select('Space.start_date, Space.expiry_date');
      $this->db->from('tbl_space as Space');
      $this->db->where('Space.member_id', $memberId);

      $query = $this->db->get();
      $result = $query->result();
      return $result;
    }

    function addNewFlexiAttendance($flexiAttendanceInfo)
    {
        $flexiAttendanceInfo['user_id'] = $this->session->userdata('userId');
        $this->db->trans_start();
        $this->db->insert('tbl_flexi_attendance', $flexiAttendanceInfo);
        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();
        return $insert_id;
    }

    function updateCompanyInfo($flexiAttendanceInfo)
    {
        $this->db->where('id', $flexiAttendanceInfo['id']);
        $this->db->update('tbl_flexi_attendance', $flexiAttendanceInfo);
        return TRUE;
    }
}
