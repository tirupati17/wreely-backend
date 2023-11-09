<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Space_management_model extends CI_Model {

    /**
     * This function is used to get the space listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function spaceListingCount($searchText = '', $planTypeId = '') {
        $this->db->select('BaseTbl.id, BaseTbl.seat_id, BaseTbl.membership_type_id, BaseTbl.company_id, BaseTbl.member_id, BaseTbl.start_date, BaseTbl.expiry_date, Seats.seat_name, Member.full_name as member_name, Company.name as company_name, MType.membership_name, BaseTbl.plan_type as plan_id, PlanTbl.plan_name');
        $this->db->from('tbl_space as BaseTbl');
        $this->db->join('tbl_seats as Seats', 'Seats.id = BaseTbl.seat_id', 'left');
        $this->db->join('tbl_plan_type as PlanTbl', 'BaseTbl.plan_type = PlanTbl.id', 'left');
        $this->db->join('tbl_membership_type as MType', 'MType.id = BaseTbl.membership_type_id', 'left');
        $this->db->join('tbl_companies as Company', 'Company.id = BaseTbl.company_id', 'left');
        $this->db->join('tbl_members as Member', 'Member.id = BaseTbl.member_id', 'left');
        // $this->db->join('tbl_members as Members', 'Members.id = BaseTbl.member_id', 'left');
        if (!empty($searchText)) {
            $likeCriteria = "(Seats.seat_name  LIKE '%" . $searchText . "%'
                            OR  MType.membership_name  LIKE '%" . $searchText . "%'
                            OR  PlanTbl.plan_name  LIKE '%" . $searchText . "%'
                            OR  Company.name  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.plan_type', $planTypeId);
        $this->db->where('BaseTbl.user_id', $this->session->userdata('userId'));
        $query = $this->db->get();

        return count($query->result());
    }

    /**
     * This function is used to get the space listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function spaceListing($searchText = '', $page = '', $segment = '', $planTypeId = '') {
      $this->db->select('BaseTbl.id, BaseTbl.seat_id, BaseTbl.membership_type_id, BaseTbl.company_id, BaseTbl.member_id, BaseTbl.start_date, BaseTbl.expiry_date, Seats.seat_name, Member.full_name as member_name, Company.name as company_name, MType.membership_name, BaseTbl.plan_type as plan_id, PlanTbl.plan_name');
      $this->db->from('tbl_space as BaseTbl');
      $this->db->join('tbl_seats as Seats', 'Seats.id = BaseTbl.seat_id', 'left');
      $this->db->join('tbl_plan_type as PlanTbl', 'BaseTbl.plan_type = PlanTbl.id', 'left');
      $this->db->join('tbl_membership_type as MType', 'MType.id = BaseTbl.membership_type_id', 'left');
      $this->db->join('tbl_companies as Company', 'Company.id = BaseTbl.company_id', 'left');
      $this->db->join('tbl_members as Member', 'Member.id = BaseTbl.member_id', 'left');
        if (!empty($searchText)) {
            $likeCriteria = "(Seats.seat_name  LIKE '%" . $searchText . "%'
                            OR  MType.membership_name  LIKE '%" . $searchText . "%'
                            OR  Company.name  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        if (!empty($spaceId)) {
            //$this->db->where('BaseTbl.id',$spaceId);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.plan_type', $planTypeId);
        $this->db->where('BaseTbl.user_id', $this->session->userdata('userId'));
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        //echo $this->db->last_query();
        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewSpaceData($spaceInfo) {
        $spaceInfo['user_id'] = $this->session->userdata('userId');

        $this->db->trans_start();
        $this->db->insert('tbl_space', $spaceInfo);

        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getSpaceInfo($spaceId) {
        $this->db->select('id, seat_id, membership_type_id, member_id, createdDtm, updatedDtm, isDeleted');
        $this->db->from('tbl_space');
        $this->db->where('isDeleted', 0);
        $this->db->where('user_id', $this->session->userdata('userId'));
        $this->db->where('id', $spaceId);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to update the space information
     * @param array $spaceInfo : This is space updated information
     * @param number $spaceId : This is space id
     */
    function editSpace($spaceInfo, $spaceId) {
        $this->db->where('id', $spaceId);
        $this->db->update('tbl_space', $spaceInfo);

        return TRUE;
    }

    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteSpace($spaceId, $spaceInfo) {
        $this->db->where('id', $spaceId);
        $this->db->delete('tbl_space');

        return $this->db->affected_rows();
    }

    function getAllPlans($planType) {
        $this->db->select('id');
        $this->db->from('tbl_space');
        $this->db->where('isDeleted', 0);
        $this->db->where('user_id', $this->session->userdata('userId'));
        $this->db->where('plan_type', $planType);
        $query = $this->db->get();
        return $query->result();
    }
}
