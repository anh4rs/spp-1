<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* Posts Model Class
 *
 * @package     CMS
 * @subpackage  Models
 * @category    Models
 * @author      Achyar Anshorie
 */

class Siswa_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // Get From Databases
    function get($params = array())
    {
        if(isset($params['id']))
        {
            $this->db->where('siswa.siswa_id', $params['id']);
        }
        if(isset($params['siswa_nis']))
        {
            $this->db->where('siswa_nis', $params['siswa_nis']);
        }
        
        if(isset($params['password']))
        {
            $this->db->where('siswa_password', $params['password']);
        }
        if(isset($params['siswa_nama']))
        {
            $this->db->where('siswa_nama', $params['siswa_nama']);
        }
        if(isset($params['status']))
        {
            $this->db->where('siswa_status', $params['status']);
        }
        if(isset($params['date_start']) AND isset($params['date_end']))
        {
            $this->db->where('siswa_published_date >=', $params['date_start'] . ' 00:00:00');
            $this->db->where('siswa_published_date <=', $params['date_end'] . ' 23:59:59');
        }

        if(isset($params['limit']))
        {
            if(!isset($params['offset']))
            {
                $params['offset'] = NULL;
            }

            $this->db->limit($params['limit'], $params['offset']);
        }

        if(isset($params['order_by']))
        {
            $this->db->order_by($params['order_by'], 'asc');
        }
        else
        {
            $this->db->order_by('siswa_last_update', 'desc');
        }

        $this->db->select('siswa.siswa_id, siswa_nis, siswa_password, siswa_nama,
            siswa_tmpt_lhr, siswa_tgl_lhr, siswa_status, siswa_input_date, siswa_last_update');
        $res = $this->db->get('siswa');

        if(isset($params['id']) OR (isset($params['limit']) AND $params['limit'] == 1) OR (isset($params['siswa_nis'])))
        {
            return $res->row_array();
        }
        else
        {
            return $res->result_array();
        }
    }

    // Add and update to database
    function add($data = array()) {
        
         if(isset($data['siswa_id'])) {
            $this->db->set('siswa_id', $data['siswa_id']);
        }
        
         if(isset($data['siswa_nis'])) {
            $this->db->set('siswa_nis', $data['siswa_nis']);
        }
        
         if(isset($data['password'])) {
            $this->db->set('siswa_password', $data['password']);
        }
        
         if(isset($data['siswa_nama'])) {
            $this->db->set('siswa_nama', $data['siswa_nama']);
        }
        
         if(isset($data['siswa_tmpt_lhr'])) {
            $this->db->set('siswa_tmpt_lhr', $data['siswa_tmpt_lhr']);
        }
        
         if(isset($data['siswa_tgl_lhr'])) {
            $this->db->set('siswa_tgl_lhr', $data['siswa_tgl_lhr']);
        }
        
         if(isset($data['siswa_status'])) {
            $this->db->set('siswa_status', $data['siswa_status']);
        }
        
         if(isset($data['siswa_input_date'])) {
            $this->db->set('siswa_input_date', $data['siswa_input_date']);
        }
       
         if(isset($data['siswa_last_update'])) {
            $this->db->set('siswa_last_update', $data['siswa_last_update']);
        }
        
        if (isset($data['siswa_id'])) {
            $this->db->where('siswa_id', $data['siswa_id']);
            $this->db->update('siswa');
            $id = $data['siswa_id'];
        } else {
            $this->db->insert('siswa');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }
    
    // Delete to database
    function delete($id) {
        $this->db->where('siswa_id', $id);
        $this->db->delete('siswa');
    }
    
    function change_password($id, $params) {
        $this->db->where('siswa_id', $id);
        $this->db->update('siswa', $params);
    }
    
}
