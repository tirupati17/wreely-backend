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
class Member_model extends CI_Model {

    /**
     * This function is used to get the member listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function memberListingCount($searchText = '') {
      $this->db->select('BaseTbl.id, BaseTbl.full_name, Companies.name , BaseTbl.address, BaseTbl.dob, BaseTbl.company_id, BaseTbl.reference_source, BaseTbl.contact_no, BaseTbl.email_id, BaseTbl.occupation');
      $this->db->from('tbl_members as BaseTbl');
      // $this->db->join('tbl_space as Space', 'BaseTbl.id = Space.member_id','left');
      // $this->db->join('tbl_membership_type as MembershipTbl', 'MembershipTbl.id = Space.membership_type_id','left');
      $this->db->join('tbl_companies as Companies', 'Companies.id = BaseTbl.company_id','left');

        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.full_name  LIKE '%" . $searchText . "%'
                            OR  BaseTbl.contact_no  LIKE '%" . $searchText . "%'
                            OR  BaseTbl.email_id  LIKE '%" . $searchText . "%'
                            OR  BaseTbl.occupation  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.status', 1);
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
    function memberListing($searchText = '', $page, $segment) {
        $this->db->select('BaseTbl.id, BaseTbl.full_name, Companies.name , BaseTbl.address, BaseTbl.dob, BaseTbl.company_id, BaseTbl.reference_source, BaseTbl.contact_no, BaseTbl.email_id, BaseTbl.occupation');
        $this->db->from('tbl_members as BaseTbl');
        // $this->db->join('tbl_space as Space', 'BaseTbl.id = Space.member_id','left');
        // $this->db->join('tbl_membership_type as MembershipTbl', 'MembershipTbl.id = Space.membership_type_id','left');
        $this->db->join('tbl_companies as Companies', 'Companies.id = BaseTbl.company_id','left');
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.full_name  LIKE '%" . $searchText . "%'
                            OR  BaseTbl.contact_no  LIKE '%" . $searchText . "%'
                            OR  BaseTbl.email_id  LIKE '%" . $searchText . "%'
                            OR  BaseTbl.occupation  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.status', 1);
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.user_id', $this->session->userdata('userId'));
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    function getSingleMemberById($id = '') {
        $this->db->select('BaseTbl.id as id, BaseTbl.contact_no as contact_no, BaseTbl.company_id as company_id, BaseTbl.user_id as vendor_id, BaseTbl.full_name as full_name, BaseTbl.email_id as email, BaseTbl.occupation as occupation');
        //$this->db->select('BaseTbl.id, BaseTbl.full_name, BaseTbl.address, BaseTbl.dob, BaseTbl.company_id, BaseTbl.membership_plan, BaseTbl.reference_source, BaseTbl.contact_no, BaseTbl.email_id, BaseTbl.occupation');
        $this->db->from('tbl_members as BaseTbl');
        
        $whereCriteria = "(BaseTbl.id =  $id )";
        $this->db->where($whereCriteria);

        $this->db->where('BaseTbl.status', 1);
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.user_id', $this->session->userdata('userId'));
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to add new member to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewMemberData($userInfo) {
        $userInfo['user_id'] = $this->session->userdata('userId');

        $this->db->trans_start();
        $this->db->insert('tbl_members', $userInfo);

        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function addNewMemberForCompany($memberInfo) {
        $this->db->trans_start();
        $this->db->insert('tbl_members', $memberInfo);

        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function checkCoworkerEmailExist($email)
    {
        $this->db->select('id');
        $this->db->where('email_id', strtolower($email));
        $this->db->where('isDeleted', 0);
        $query = $this->db->get('tbl_members');

        if ($query->num_rows() > 0){
            return true;
        } else {
            return false;
        }
    }

    /**
     * This function used to get member information by id
     * @param number $memberId : This is user id
     * @return array $result : This is user information
     */
    function getMemberInfo($memberId) {
        $this->db->select('id');
        $this->db->from('tbl_members');
        $this->db->where('id', $memberId);
        $query = $this->db->get();

        return $query->result();
    }

    function updateMemberInfo($userInfo) {
        $this->db->where('id', $userInfo['id']);
        $this->db->update('tbl_members', $userInfo);
        return TRUE;
    }

    function checkIfMemberExistByEmail($emailID) {
        $this->db->select('company_id');
        $this->db->from('tbl_members');
        $this->db->where('email_id', $emailID);
        $query = $this->db->get();

        return $query->result();
    }

    function getAllMembers() {
        $this->db->select('BaseTbl.id as id, BaseTbl.contact_no as contact_no, BaseTbl.full_name as full_name, BaseTbl.email_id as email, BaseTbl.occupation as occupation');
        $this->db->from('tbl_members as BaseTbl');
        $this->db->where('isDeleted', 0);
        $this->db->where('user_id', $this->session->userdata('userId'));
        $query = $this->db->get();

        return $query->result();
    }

    function getAllMembersForFIR() {
        $this->db->select('BaseTbl.id as id, BaseTbl.contact_no as contact_no, BaseTbl.full_name as full_name, BaseTbl.email_id as email, BaseTbl.occupation as occupation');
        $this->db->from('tbl_members as BaseTbl');
        $this->db->where('isDeleted', 0);
        $this->db->where('user_id', $this->session->userdata('userId'));
        $query = $this->db->get();
        return $query->result_array();
    }

    function getAllMemberForCompanyId($companyId) {
        $this->db->select('BaseTbl.id as id, BaseTbl.contact_no as contact_no, BaseTbl.company_id as company_id, BaseTbl.user_id as vendor_id, BaseTbl.full_name as full_name, BaseTbl.email_id as email, BaseTbl.occupation as occupation');
        $this->db->from('tbl_members as BaseTbl');
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.user_id', $this->session->userdata('userId'));
        $this->db->where('BaseTbl.company_id', $companyId);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getMemberPlans() {
        $this->db->select('id,membership_name');
        $this->db->from('tbl_membership_type');
        $this->db->where('user_id', $this->session->userdata('userId'));
        $query = $this->db->get();

        return $query->result();
    }

    function getCompanyMembersById($id = '') {
        $this->db->select('BaseTbl.id as id, BaseTbl.contact_no as contact_no, BaseTbl.full_name as full_name, BaseTbl.email_id as email, BaseTbl.occupation as occupation, BaseTbl.reference_source as reference');
        $this->db->from(' tbl_members as BaseTbl');
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.company_id', $id);

        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to delete the member information
     * @param number $memberId : This is member id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteMember($memberId, $memberInfo) {
        $this->db->where('id', $memberId);
        $this->db->update('tbl_members', $memberInfo);

        return $this->db->affected_rows();
    }

    function deleteCompanies($memberInfo) {
        $this->db->where('id', $memberInfo['id']);
        $this->db->update('tbl_members', $memberInfo);

        return $this->db->affected_rows();
    }

    /**
     * This function is used to delete the member using memberId
     * @return boolean $result : TRUE / FALSE
     */
    function getMemberByCompanyId($id) {
        $this->db->select('BaseTbl.id as id,  BaseTbl.full_name as full_name');
        $this->db->from('tbl_members as BaseTbl');
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.company_id', $id);

        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    function checkIfFIRKeyExistForMember($memberId) {
        $this->db->select('member_fir_key');
        $this->db->where('id', $memberId);
        $query = $this->db->get('tbl_members');

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            if (empty($row['member_fir_key'])) {
                return NULL;
            } else {
                return $row['member_fir_key'];
            }
        }
    }
}
