<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* Posts Model Class
 *
 * @package     CMS
 * @subpackage  Models
 * @category    Models
 * @author      Achyar Anshorie
 */

class Jns_byr_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // Get From Databases
    function get($params = array())
    {
        if(isset($params['id']))
        {
            $this->db->where('jns_byr.jns_byr_id', $params['id']);
        }
        if(isset($params['jns_byr_ket']))
        {
            $this->db->where('jns_byr_ket', $params['jns_byr_ket']);
        }

        if(isset($params['jns_byr_tarif']))
        {
            $this->db->where('jns_byr_tarif', $params['jns_byr_tarif']);
        }
        if(isset($params['date_start']) AND isset($params['date_end']))
        {
            $this->db->where('jns_byr_published_date >=', $params['date_start'] . ' 00:00:00');
            $this->db->where('jns_byr_published_date <=', $params['date_end'] . ' 23:59:59');
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
            $this->db->order_by('jns_byr_id', 'asc');
        }

        $this->db->select('jns_byr.jns_byr_id, jns_byr_ket, jns_byr_tarif');
        $res = $this->db->get('jns_byr');

        if(isset($params['id']) OR (isset($params['limit']) AND $params['limit'] == 1) OR (isset($params['jns_byr_ket'])))
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
        
         if(isset($data['jns_byr_id'])) {
            $this->db->set('jns_byr_id', $data['jns_byr_id']);
        }
        
         if(isset($data['jns_byr_ket'])) {
            $this->db->set('jns_byr_ket', $data['jns_byr_ket']);
        }

        if(isset($data['jns_byr_tarif'])) {
            $this->db->set('jns_byr_tarif', $data['jns_byr_tarif']);
        }
        
        if (isset($data['jns_byr_id'])) {
            $this->db->where('jns_byr_id', $data['jns_byr_id']);
            $this->db->update('jns_byr');
            $id = $data['jns_byr_id'];
        } else {
            $this->db->insert('jns_byr');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }
    
    // Delete to database
    function delete($id) {
        $this->db->where('jns_byr_id', $id);
        $this->db->delete('jns_byr');
    }
        
}
