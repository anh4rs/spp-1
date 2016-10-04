<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* Posts Model Class
 *
 * @package     CMS
 * @subpackage  Models
 * @category    Models
 * @author      Achyar Anshorie
 */

class Bulan_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // Get From Databases
    function get($params = array())
    {
        if(isset($params['id']))
        {
            $this->db->where('bulan.bulan_id', $params['id']);
        }
        if(isset($params['bulan_nama']))
        {
            $this->db->where('bulan_nama', $params['bulan_nama']);
        }
        if(isset($params['date_start']) AND isset($params['date_end']))
        {
            $this->db->where('bulan_published_date >=', $params['date_start'] . ' 00:00:00');
            $this->db->where('bulan_published_date <=', $params['date_end'] . ' 23:59:59');
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
            $this->db->order_by('bulan_id', 'asc');
        }

        $this->db->select('bulan.bulan_id, bulan_nama');
        $res = $this->db->get('bulan');

        if(isset($params['id']) OR (isset($params['limit']) AND $params['limit'] == 1) OR (isset($params['bulan_nama'])))
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
        
         if(isset($data['bulan_id'])) {
            $this->db->set('bulan_id', $data['bulan_id']);
        }
        
         if(isset($data['bulan_nama'])) {
            $this->db->set('bulan_nama', $data['bulan_nama']);
        }
        
        if (isset($data['bulan_id'])) {
            $this->db->where('bulan_id', $data['bulan_id']);
            $this->db->update('bulan');
            $id = $data['bulan_id'];
        } else {
            $this->db->insert('bulan');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }
    
    // Delete to database
    function delete($id) {
        $this->db->where('bulan_id', $id);
        $this->db->delete('bulan');
    }
        
}
