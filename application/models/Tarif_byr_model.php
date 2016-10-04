<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* tarif_byr Model Class
 *
 * @package     CMS
 * @subpackage  Models
 * @category    Models
 * @author      Achyar Anshorie
 */

class Tarif_byr_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // Get From Databases
    function get($params = array())
    {
        if(isset($params['id']))
        {
            $this->db->where('tarif_byr.tarif_byr_id', $params['id']);
        }
        
        if(isset($params['date_start']) AND isset($params['date_end']))
        {
            $this->db->where('tarif_byr_date', $params['date_start']);
            $this->db->or_where('tarif_byr_date', $params['date_end']);
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
            $this->db->order_by($params['order_by'], 'desc');
        }
        else
        {
            $this->db->order_by('tarif_byr_id', 'desc');
        }

        $this->db->select('tarif_byr.tarif_byr_id, thn_ajaran_thn_ajaran_id, bulan_bulan_id, jns_byr_jns_byr_id, tarif_byr_kategori,  thn_ajaran_ket, thn_ajaran_status, bulan_nama, jns_byr_kategori, jns_byr_ket');
        $this->db->join('thn_ajaran', 'thn_ajaran.thn_ajaran_id = tarif_byr.thn_ajaran_thn_ajaran_id', 'left'); 
        $this->db->join('bulan', 'bulan.bulan_id = tarif_byr.bulan_bulan_id', 'left'); 
        $this->db->join('jns_byr', 'jns_byr.jns_byr_id = tarif_byr.jns_byr_jns_byr_id', 'left');   
        $res = $this->db->get('tarif_byr');

        if(isset($params['id']) OR (isset($params['limit']) AND $params['limit']==1))
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
        
       if(isset($data['tarif_byr_id'])) {
        $this->db->set('tarif_byr_id', $data['tarif_byr_id']);
    }
    
    if(isset($data['tarif_byr_kategori'])) {
        $this->db->set('tarif_byr_kategori', $data['tarif_byr_kategori']);
    }

    if(isset($data['tarif_byr_date'])) {
        $this->db->set('tarif_byr_date', $data['tarif_byr_date']);
    }
    
    if(isset($data['bulan_id'])) {
        $this->db->set('bulan_bulan_id', $data['bulan_id']);
    }
    
    if(isset($data['thn_ajaran_id'])) {
        $this->db->set('thn_ajaran_thn_ajaran_id', $data['thn_ajaran_id']);
    }        
    
    if(isset($data['jns_byr_id'])) {
        $this->db->set('jns_byr_jns_byr_id', $data['jns_byr_id']);
    }
    
    if (isset($data['tarif_byr_id'])) {
        $this->db->where('tarif_byr_id', $data['tarif_byr_id']);
        $this->db->update('tarif_byr');
        $id = $data['tarif_byr_id'];
    } else {
        $this->db->insert('tarif_byr');
        $id = $this->db->insert_id();
    }

    $status = $this->db->affected_rows();
    return ($status == 0) ? FALSE : $id;
}

    // Delete to database
function delete($id) {
    $this->db->where('tarif_byr_id', $id);
    $this->db->delete('tarif_byr');
}

}
