<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of company_model
 *
 * @author tirupatibalan
 */
class Company_model extends CI_Model
{
     /**
     * This function is used to get the company listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function companyListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.name, BaseTbl.contact_person_name, BaseTbl.contact_person_number, BaseTbl.contact_person_email_id');
        $this->db->from('tbl_companies as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.contact_person_name  LIKE '%".$searchText."%'
                            OR  BaseTbl.contact_person_number  LIKE '%".$searchText."%'
                            OR  BaseTbl.contact_person_email_id  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.status', 1);
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.user_id', $this->session->userdata('userId'));
        $query = $this->db->get();

        return count($query->result());
    }

    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function companyListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.id, BaseTbl.name, BaseTbl.contact_person_name, BaseTbl.contact_person_number, BaseTbl.contact_person_email_id, BaseTbl.website');
        $this->db->from('tbl_companies as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.contact_person_name  LIKE '%".$searchText."%'
                            OR  BaseTbl.contact_person_number  LIKE '%".$searchText."%'
                            OR  BaseTbl.website  LIKE '%".$searchText."%'
                            OR  BaseTbl.contact_person_email_id  LIKE '%".$searchText."%')";
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


    function getAllCompanyNames()
    {
        $this->db->select('BaseTbl.id, BaseTbl.name');
        $this->db->from('tbl_companies as BaseTbl');
        $this->db->where('BaseTbl.status', 1);
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.user_id', $this->session->userdata('userId'));
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }


    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function checkIfCompanyExists($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.name, BaseTbl.contact_person_name, BaseTbl.contact_person_number, BaseTbl.contact_person_email_id, BaseTbl.website');
        $this->db->from('tbl_companies as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.contact_person_name  LIKE '%".$searchText."%'
                            OR  BaseTbl.contact_person_number  LIKE '%".$searchText."%'
                            OR  BaseTbl.website  LIKE '%".$searchText."%'
                            OR  BaseTbl.contact_person_email_id  LIKE '%".$searchText."%')";
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

        /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewCompany($companyInfo)
    {
        $companyInfo['user_id'] = $this->session->userdata('userId');
        $this->db->trans_start();
        $this->db->insert('tbl_companies', $companyInfo);
        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();
        return $insert_id;
    }

    /**
     * This function used to get company information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getCompanyInfo($companyId)
    {
        $this->db->select('id, name, contact_person_email_id, contact_person_name, contact_person_number, website');
        $this->db->from('tbl_companies');
        $this->db->where('id', $companyId);
        $this->db->where('user_id', $this->session->userdata('userId'));
        $query = $this->db->get();
        return $query->result();
    }

    function updateCompanyInfo($companyInfo)
    {
        $this->db->where('id', $companyInfo['id']);
        $this->db->update('tbl_companies', $companyInfo);
        return TRUE;
    }

    function checkIfCompanyExistByEmail($emailID)
    {
        $this->db->select('id');
        $this->db->from('tbl_companies');
        $this->db->where('contact_person_email_id', $emailID);
        $this->db->where('user_id', $this->session->userdata('userId'));
        $query = $this->db->get();
        return $query->result();
    }

    function getAllCompanies() {
        $this->db->select('id, name, contact_person_email_id, contact_person_name, contact_person_number, website');
        $this->db->from('tbl_companies');
        $this->db->where('isDeleted', 0);
        $this->db->where('user_id', $this->session->userdata('userId'));
        $query = $this->db->get();
        return $query->result();
    }

    function getAllCompaniesForFIR() {
        $this->db->select('id, name, contact_person_email_id, user_id as vendor_id, contact_person_name, contact_person_number, website');
        $this->db->from('tbl_companies');
        $this->db->where('isDeleted', 0);
        $this->db->where('user_id', $this->session->userdata('userId'));
        $query = $this->db->get();
        return $query->result_array();
    }

    function getAllCompaniesBasedOnPlanType($planTypeId) {
        $this->db->select('CompanyTbl.id, CompanyTbl.name, ');
        $this->db->from('tbl_companies as CompanyTbl');
        $this->db->join('tbl_space as Space', 'Space.company_id = CompanyTbl.id', 'left');
        $this->db->where('CompanyTbl.isDeleted', 0);
        $this->db->where('Space.plan_type', $planTypeId);
        $this->db->where('CompanyTbl.user_id', $this->session->userdata('userId'));
        $query = $this->db->get();
        return $query->result();
    }

    function deleteCompanies($companyInfo) {
        $this->db->where('id', $companyInfo['id']);
        $this->db->update('tbl_companies', $companyInfo);

        return $this->db->affected_rows();
    }

    function getCompanyDataForMember($memberId)
    {
        $this->db->select('Company.name as company_name, Company.contact_person_email_id as company_email, Member.id, Member.full_name as member_name');
        $this->db->from('tbl_members as Member');
        $this->db->join('tbl_companies as Company', 'Member.company_id = Company.id','left');
        $this->db->where('Member.id', $memberId);

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function checkIfFIRKeyExistForCompany($companyId) {
        $this->db->select('company_fir_key');
        $this->db->where('id', $companyId);
        $query = $this->db->get('tbl_companies');

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            if (empty($row['company_fir_key'])) {
                return NULL;
            } else {
                return $row['company_fir_key'];
            }
        }
    }
}
