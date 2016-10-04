<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* trx Model Class
 *
 * @package     CMS
 * @subpackage  Models
 * @category    Models
 * @author      Achyar Anshorie
 */

class Trx_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // Get From Databases
    function get($params = array())
    {
        if(isset($params['id']))
        {
            $this->db->where('trx.trx_id', $params['id']);
        }

        if(isset($params['trx_nomor']))
        {
            $this->db->where('trx.trx_nomor', $params['trx_nomor']);
        }

        if(isset($params['siswa_id']))
        {
            $this->db->where('trx.siswa_siswa_id', $params['siswa_id']);
        }

        if(isset($params['siswa_nama']))
        {
            $this->db->where('siswa.siswa_nama', $params['siswa_nama']);
        }

        if(isset($params['siswa_nis']))
        {
            $this->db->where('siswa.siswa_nis', $params['siswa_nis']);
        }
        
        if(isset($params['date_start']) AND isset($params['date_end']))
        {
            $this->db->where('trx_date', $params['date_start']);
            $this->db->or_where('trx_date', $params['date_end']);
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
            $this->db->order_by('trx_id', 'desc');
        }

        $this->db->select('trx.trx_id, trx_nomor, trx_input_date, trx_last_update, thn_ajaran_thn_ajaran_id, bulan_bulan_id, jns_byr_jns_byr_id, trx_kategori,  thn_ajaran_ket, thn_ajaran_status, bulan_nama, 
            user_name, user_full_name,
            jns_byr_kategori, jns_byr_ket, siswa_siswa_id, siswa_nama, siswa_nis, tarif_byr_kategori');
        $this->db->join('thn_ajaran', 'thn_ajaran.thn_ajaran_id = trx.thn_ajaran_thn_ajaran_id', 'left'); 
        $this->db->join('bulan', 'bulan.bulan_id = trx.bulan_bulan_id', 'left'); 
        $this->db->join('jns_byr', 'jns_byr.jns_byr_id = trx.jns_byr_jns_byr_id', 'left');  
        $this->db->join('siswa', 'siswa.siswa_id = trx.siswa_siswa_id', 'left'); 
        $this->db->join('tarif_byr', 'tarif_byr.tarif_byr_id = trx.tarif_byr_tarif_byr_id', 'left'); 
        $this->db->join('user', 'user.user_id = trx.user_user_id', 'left');
        $res = $this->db->get('trx');

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

     if(isset($data['trx_id'])) {
        $this->db->set('trx_id', $data['trx_id']);
    }
    
    if(isset($data['trx_kategori'])) {
        $this->db->set('trx_kategori', $data['trx_kategori']);
    }

    if(isset($data['trx_date'])) {
        $this->db->set('trx_date', $data['trx_date']);
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
    
    if (isset($data['trx_id'])) {
        $this->db->where('trx_id', $data['trx_id']);
        $this->db->update('trx');
        $id = $data['trx_id'];
    } else {
        $this->db->insert('trx');
        $id = $this->db->insert_id();
    }

    $status = $this->db->affected_rows();
    return ($status == 0) ? FALSE : $id;
}

    // Delete to database
function delete($id) {
    $this->db->where('trx_id', $id);
    $this->db->delete('trx');
}

}
