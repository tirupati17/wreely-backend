<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of enquiries_model
 *
 * @author tirupatibalan
 */
class Enquiries_model extends CI_Model
{
     /**
     * This function is used to get the enquiries listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function enquiriesListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.full_name, BaseTbl.email_id, BaseTbl.occupation, BaseTbl.contact_no, BaseTbl.reference_source, BaseTbl.reason, BaseTbl.notes');
        $this->db->from('tbl_enquiries as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.full_name  LIKE '%".$searchText."%'
                            OR  BaseTbl.contact_no  LIKE '%".$searchText."%'
                            OR  BaseTbl.reference_source  LIKE '%".$searchText."%'
                            OR  BaseTbl.notes  LIKE '%".$searchText."%'
                            OR  BaseTbl.reason  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.user_id', $this->session->userdata('userId'));
        $query = $this->db->get();
        return count($query->result());
    }

    /**
     * This function is used to get the enquiries listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function enquiriesListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.id, BaseTbl.full_name, BaseTbl.email_id, BaseTbl.occupation, BaseTbl.contact_no, BaseTbl.reference_source, BaseTbl.reason, BaseTbl.how_many_people, BaseTbl.tell_us_more, BaseTbl.notes, BaseTbl.createdDtm');
        $this->db->from('tbl_enquiries as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.full_name  LIKE '%".$searchText."%'
                            OR  BaseTbl.contact_no  LIKE '%".$searchText."%'
                            OR  BaseTbl.reference_source  LIKE '%".$searchText."%'
                            OR  BaseTbl.notes  LIKE '%".$searchText."%'
                            OR  BaseTbl.reason  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.user_id', $this->session->userdata('userId'));
        $this->db->order_by('createdDtm', 'desc');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to add new enquiry to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewEnquiryData($enquiryInfo) {
        $enquiryInfo['user_id'] = $this->session->userdata('userId');

        $this->db->trans_start();
        $this->db->insert('tbl_enquiries', $enquiryInfo);

        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function used to get enquiry information by id
     * @param number $enquiryId : This is enquiry id
     * @return array $result : This is enquiry information
     */
    function getEnquiryInfo($enquiryId) {
        $this->db->select('id, full_name, email_id, occupation, contact_no, reference_source, reason, how_many_people, createdDtm, updatedDtm, isDeleted, tell_us_more, notes');
        $this->db->from('tbl_enquiries');
        $this->db->where('isDeleted', 0);
        $this->db->where('user_id', $this->session->userdata('userId'));
        $this->db->where('id', $enquiryId);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to update the enquiry information
     * @param array $enquiryInfo : This is enquiry updated information
     * @param number $enquiryId : This is enquiry id
     */
    function editEnquiry($enquiryInfo, $enquiryId) {
        $this->db->where('id', $enquiryId);
        $this->db->update('tbl_enquiries', $enquiryInfo);

        return TRUE;
    }

    /**
     * This function is used to delete the enquiry information
     * @param number $enquiryId : This is enquiry id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteEnquiry($enquiryId, $enquiryInfo) {
        $this->db->where('id', $enquiryId);
        $this->db->update('tbl_enquiries', $enquiryInfo);

        return $this->db->affected_rows();
    }

    function getAllEnquiries() {
        $this->db->select('id');
        $this->db->from('tbl_enquiries');
        $this->db->where('isDeleted', 0);
        $this->db->where('user_id', $this->session->userdata('userId'));
        $query = $this->db->get();
        return $query->result();
    }
}
