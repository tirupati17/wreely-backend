<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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
class Seats_model extends CI_Model {

    function seatListingCount($searchText = '') {
        $this->db->select('BaseTbl.id, BaseTbl.seat_name, BaseTbl.membership_type_id');
        $this->db->from('tbl_seats as BaseTbl');
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.seat_name  LIKE '%" . $searchText . "%'
                            OR  BaseTbl.membership_type_id  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.user_id', $this->session->userdata('userId'));
        $query = $this->db->get();

        return count($query->result());
    }

    function seatListing($searchText = '', $page, $segment) {
        $this->db->select('BaseTbl.id, BaseTbl.seat_name, BaseTbl.membership_type_id, MembershipTypeTbl.membership_name');
        $this->db->from('tbl_seats as BaseTbl');
        $this->db->join('tbl_membership_type as MembershipTypeTbl', 'BaseTbl.membership_type_id = MembershipTypeTbl.id', 'left');
        if (!empty($searchText)) {
            $likeCriteria = "(BaseTbl.seat_name  LIKE '%" . $searchText . "%'
                            OR  BaseTbl.membership_name  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.user_id', $this->session->userdata('userId'));
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    function addNewSeatData($seatInfo) {
        $seatInfo['user_id'] = $this->session->userdata('userId');

        $this->db->trans_start();
        $this->db->insert('tbl_seats', $seatInfo);

        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    function getSeatInfo($seatId) {
        $this->db->select('id');
        $this->db->from('tbl_seats');
        $this->db->where('id', $seatId);
        $query = $this->db->get();
        return $query->result();
    }

    function updateSeatInfo($seatInfo) {
        $this->db->where('id', $seatInfo['id']);
        $this->db->update('tbl_seats', $seatInfo);
        return TRUE;
    }

    function checkIfSeatNameExistByName($name) {
        $this->db->select('seat_name');
        $this->db->from('tbl_seats');
        $this->db->where('seat_name', $name);
        $this->db->where('user_id', $this->session->userdata('userId'));
        $query = $this->db->get();
        return $query->result();
    }

    function getUserSeats() {
        $this->db->select('id, seat_name, membership_type_id, isDeleted');
        $this->db->from('tbl_seats');
        $this->db->where('user_id', $this->session->userdata('userId'));
        $query = $this->db->get();
        return $query->result();
    }

    function deleteSeat($seatInfo) {
        $this->db->where('id', $seatInfo['id']);
        $this->db->delete('tbl_seats');

        return $this->db->affected_rows();
    }

    function getAllSeats() {
        $this->db->select('id, seat_name');
        $this->db->from('tbl_seats');
        $this->db->where('user_id', $this->session->userdata('userId'));
        $this->db->where('isDeleted', 0);
        $query = $this->db->get();
        return $query->result();
    }

    function getAvailableSeats() {
        $this->db->select('SeatTbl.id, SeatTbl.user_id, SeatTbl.seat_name, SpaceTbl.seat_id');
        $this->db->from('tbl_seats as SeatTbl');
        $this->db->from('tbl_space` as SpaceTbl');
        $this->db->where('SpaceTbl.seat_id = SeatTbl.id');
        $this->db->where('SpaceTbl.company_id', 0);
        $this->db->group_by('SeatTbl.id');

        $this->db->where('SeatTbl.user_id', $this->session->userdata('userId'));
        $this->db->where('isDeleted', 0);
        $query = $this->db->get();
        return $query->result();
    }
}
