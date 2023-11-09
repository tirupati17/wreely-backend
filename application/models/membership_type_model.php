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
class Membership_type_model extends CI_Model {

    /**
     * This function is used to get the member listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function membershipTypeListingCount($searchText = '') {
      $this->db->select('BaseTbl.id, BaseTbl.number_of_day, BaseTbl.membership_name, BaseTbl.price, BaseTbl.quantity, PlanTbl.plan_name, BaseTbl.plan_type_id as plan_id');
      $this->db->from('tbl_membership_type as BaseTbl');
      $this->db->join('tbl_plan_type as PlanTbl', 'BaseTbl.plan_type_id = PlanTbl.id', 'left');
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.membership_name  LIKE '%" . $searchText . "%'
                            OR  BaseTbl.price  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.user_id', $this->session->userdata('userId'));
        $query = $this->db->get();

        return count($query->result());
    }

    /**
     * This function is used to get the member listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function membershipTypeListing($searchText = '', $page, $segment) {
      $this->db->select('BaseTbl.id, BaseTbl.number_of_day, BaseTbl.membership_name, BaseTbl.price, BaseTbl.quantity, PlanTbl.plan_name, BaseTbl.plan_type_id as plan_id');
      $this->db->from('tbl_membership_type as BaseTbl');
      $this->db->join('tbl_plan_type as PlanTbl', 'BaseTbl.plan_type_id = PlanTbl.id', 'left');
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.membership_name  LIKE '%" . $searchText . "%'
                            OR  BaseTbl.price  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
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
    function addNewMembershipTypeData($membershipTypeInfo) {
        $membershipTypeInfo['user_id'] = $this->session->userdata('userId');

        $this->db->trans_start();
        $this->db->insert('tbl_membership_type', $membershipTypeInfo);

        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    /**
     * This function used to get member information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getMembershipTypeInfo($membershipTypeId) {
        $this->db->select('id');
        $this->db->from('tbl_membership_type');
        $this->db->where('id', $membershipTypeId);
        $query = $this->db->get();

        return $query->result();
    }

    function updateMemberInfo($membershipTypeInfo) {
        $this->db->where('id', $membershipTypeInfo['id']);
        $this->db->update('tbl_membership_type', $membershipTypeInfo);
        return TRUE;
    }

    function checkIfMembershipTypeNameExistByName($name) {
        $this->db->select('name');
        $this->db->from('tbl_membership_type');
        $this->db->where('name', $name);
        $this->db->where('user_id', $this->session->userdata('userId'));
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteMembershipType($membershipTypeInfo) {
        $this->db->where('id', $membershipTypeInfo['id']);
        $this->db->delete('tbl_membership_type');

        return $this->db->affected_rows();
    }

    /**
     * This function is used to get the user seats information
     * @return array $result : This is result of the query
     */
    function getAllMembershipTypes() {
        $this->db->select('id, membership_name, price, quantity, isDeleted');
        $this->db->from('tbl_membership_type');
        $this->db->where('isDeleted', 0);
        $this->db->where('user_id', $this->session->userdata('userId'));
        $query = $this->db->get();

        return $query->result();
    }

    function getMembershipTypesBasedOnPlan($planTypeId) {
        $this->db->select('id, membership_name, price, quantity, isDeleted');
        $this->db->from('tbl_membership_type');
        $this->db->where('isDeleted', 0);
        $this->db->where('plan_type_id', $planTypeId);
        $this->db->where('user_id', $this->session->userdata('userId'));
        $query = $this->db->get();

        return $query->result();
    }

    function getAllPlanTypes() {
        $this->db->select('id, plan_name');
        $this->db->from('tbl_plan_type');
        $query = $this->db->get();

        return $query->result();
    }

}
