<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function userListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, Role.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
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
    function userListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, Role.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to get the user roles information
     * @return array $result : This is result of the query
     */
    function getUserRoles()
    {
        $this->db->select('roleId, role');
        $this->db->from('tbl_roles');
        $this->db->where('roleId !=', 1);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $userId : This is user id
     * @return {mixed} $result : This is searched result
     */
    function checkEmailExists($email, $userId = 0)
    {
        $this->db->select("email");
        $this->db->from("tbl_users");
        $this->db->where("email", $email);
        $this->db->where("isDeleted", 0);
        if($userId != 0){
            $this->db->where("userId !=", $userId);
        }
        $query = $this->db->get();

        return $query->result();
    }


    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewUser($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_users', $userInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfo($userId)
    {
        $this->db->select('userId, name, domain_name_reference, vendor_logo_url, email, mobile, roleId, zohobooks_authtoken, zohobooks_organization_id, quickbooks_authtoken');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
        $this->db->where('userId', $userId);
        $query = $this->db->get();
        return $query->result();
    }


    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editUser($userInfo, $userId)
    {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);

        return TRUE;
    }

    function deleteUser($userId, $userInfo)
    {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);

        return $this->db->affected_rows();
    }

    function matchOldPassword($userId, $oldPassword)
    {
        $this->db->select('userId, password');
        $this->db->where('userId', $userId);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get('tbl_users');

        $user = $query->result();

        if(!empty($user)){
            if(verifyHashedPassword($oldPassword, $user[0]->password)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    function changePassword($userId, $userInfo)
    {
        $this->db->where('userId', $userId);
        $this->db->where('isDeleted', 0);
        $this->db->update('tbl_users', $userInfo);

        return $this->db->affected_rows();
    }


    function updateUserInfo($userInfo)
    {
        $this->db->where('userId', $userInfo['id']);
        unset($userInfo['id']);
        $this->db->update('tbl_users', $userInfo);
        return TRUE;
    }

    function getCurrentUsersForFIR()
    {
        $this->db->select("userId as id, email, name, mobile, domain_name_reference, access_token, vendor_logo_url");
        $this->db->from("tbl_users");
        $this->db->where('userId', $this->session->userdata('userId'));
        $query = $this->db->get();
        return $query->result_array();
    }

    function checkIfFIRKeyExistForUser($userId) {
        $this->db->select('user_fir_key');
        $this->db->where('userId', $userId);
        $query = $this->db->get('tbl_users');

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            if (empty($row['user_fir_key'])) {
                return NULL;
            } else {
                return $row['user_fir_key'];
            }
        }
    }
}
