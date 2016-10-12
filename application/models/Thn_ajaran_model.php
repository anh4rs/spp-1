<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* Posts Model Class
 *
 * @package     CMS
 * @subpackage  Models
 * @category    Models
 * @author      Achyar Anshorie
 */

class Thn_ajaran_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // Get From Databases
    function get($params = array())
    {
        if(isset($params['id']))
        {
            $this->db->where('thn_ajaran.thn_ajaran_id', $params['id']);
        }
        if(isset($params['thn_ajaran_ket']))
        {
            $this->db->where('thn_ajaran_ket', $params['thn_ajaran_ket']);
        }
        if(isset($params['thn_ajaran_budget']))
        {
            $this->db->where('thn_ajaran_budget', $params['thn_ajaran_budget']);
        }
        if(isset($params['status']))
        {
            $this->db->where('thn_ajaran_status', $params['status']);
        }
        if(isset($params['date_start']) AND isset($params['date_end']))
        {
            $this->db->where('thn_ajaran_published_date >=', $params['date_start'] . ' 00:00:00');
            $this->db->where('thn_ajaran_published_date <=', $params['date_end'] . ' 23:59:59');
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
            $this->db->order_by('thn_ajaran_ket', 'desc');
        }

        $this->db->select('thn_ajaran.thn_ajaran_id, thn_ajaran_ket, thn_ajaran_budget, thn_ajaran_status');
        $res = $this->db->get('thn_ajaran');

        if(isset($params['id']) OR (isset($params['limit']) AND $params['limit'] == 1) OR (isset($params['thn_ajaran_ket'])))
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
        
         if(isset($data['thn_ajaran_id'])) {
            $this->db->set('thn_ajaran_id', $data['thn_ajaran_id']);
        }
        
         if(isset($data['thn_ajaran_ket'])) {
            $this->db->set('thn_ajaran_ket', $data['thn_ajaran_ket']);
        }

        if(isset($data['thn_ajaran_budget'])) {
            $this->db->set('thn_ajaran_budget', $data['thn_ajaran_budget']);
        }
        
         if(isset($data['thn_ajaran_status'])) {
            $this->db->set('thn_ajaran_status', $data['thn_ajaran_status']);
        }
        
        if (isset($data['thn_ajaran_id'])) {
            $this->db->where('thn_ajaran_id', $data['thn_ajaran_id']);
            $this->db->update('thn_ajaran');
            $id = $data['thn_ajaran_id'];
        } else {
            $this->db->insert('thn_ajaran');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }
    
    // Delete to database
    function delete($id) {
        $this->db->where('thn_ajaran_id', $id);
        $this->db->delete('thn_ajaran');
    }
    
    function change_password($id, $params) {
        $this->db->where('thn_ajaran_id', $id);
        $this->db->update('thn_ajaran', $params);
    }
    
}
