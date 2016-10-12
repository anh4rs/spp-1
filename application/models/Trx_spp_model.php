<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* trx_spp Model Class
 *
 * @package     CMS
 * @subpackage  Models
 * @category    Models
 * @author      Achyar Anshorie
 */

class Trx_spp_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // Get From Databases
    function get($params = array())
    {
        if(isset($params['id']))
        {
            $this->db->where('trx_spp.trx_spp_id', $params['id']);
        }
        
        if(isset($params['thn_ajaran_id']))
        {
            $this->db->where('thn_ajaran_thn_ajaran_id', $params['thn_ajaran_id']);
        }
        
        if(isset($params['siswa_id']))
        {
            $this->db->where('siswa_siswa_id', $params['siswa_id']);
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
            $this->db->order_by('trx_spp_last_update', 'desc');
        }

        $this->db->select('trx_spp.trx_spp_id, trx_spp_jul, trx_spp_ags, trx_spp_sep, trx_spp_okt, trx_spp_nov, trx_spp_des,
            trx_spp_jan, trx_spp_feb, trx_spp_mar, trx_spp_apr, trx_spp_mei, trx_spp_jun,
            trx_spp_input_date, trx_spp_last_update');
        $this->db->select('trx_spp.user_user_id, user_name');
        $this->db->select('thn_ajaran_thn_ajaran_id, thn_ajaran_ket');
        $this->db->select('siswa_siswa_id, siswa_nama, siswa_nis');
        $this->db->select('kelas_id, kelas_ket, kelas_tarif');
        
        $this->db->join('user', 'user.user_id = trx_spp.user_user_id', 'left');
        $this->db->join('thn_ajaran', 'thn_ajaran.thn_ajaran_id = trx_spp.thn_ajaran_thn_ajaran_id', 'left');
        $this->db->join('siswa', 'siswa.siswa_id = trx_spp.siswa_siswa_id', 'left');
        $this->db->join('kelas', 'kelas.kelas_id = trx_spp.kelas_kelas_id', 'left');
        $res = $this->db->get('trx_spp');

        if(isset($params['id']))
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
        
         if(isset($data['trx_spp_id'])) {
            $this->db->set('trx_spp_id', $data['trx_spp_id']);
        }
        
         if(isset($data['thn_ajaran_id'])) {
            $this->db->set('thn_ajaran_thn_ajaran_id', $data['thn_ajaran_id']);
        }

        if(isset($data['kelas'])) {
            $this->db->set('kelas_kelas_id', $data['kelas_id']);
        }
        
         if(isset($data['trx_spp_jul'])) {
            $this->db->set('trx_spp_jul', $data['trx_spp_jul']);
        }

        if(isset($data['trx_spp_ags'])) {
            $this->db->set('trx_spp_ags', $data['trx_spp_ags']);
        }

        if(isset($data['trx_spp_sep'])) {
            $this->db->set('trx_spp_sep', $data['trx_spp_sep']);
        }

        if(isset($data['trx_spp_okt'])) {
            $this->db->set('trx_spp_okt', $data['trx_spp_okt']);
        }

        if(isset($data['trx_spp_nov'])) {
            $this->db->set('trx_spp_nov', $data['trx_spp_nov']);
        }

        if(isset($data['trx_spp_des'])) {
            $this->db->set('trx_spp_des', $data['trx_spp_des']);
        }

        if(isset($data['trx_spp_jan'])) {
            $this->db->set('trx_spp_jan', $data['trx_spp_jan']);
        }

        if(isset($data['trx_spp_feb'])) {
            $this->db->set('trx_spp_feb', $data['trx_spp_feb']);
        }

        if(isset($data['trx_spp_mar'])) {
            $this->db->set('trx_spp_mar', $data['trx_spp_mar']);
        }

        if(isset($data['trx_spp_apr'])) {
            $this->db->set('trx_spp_apr', $data['trx_spp_apr']);
        }

        if(isset($data['trx_spp_mei'])) {
            $this->db->set('trx_spp_mei', $data['trx_spp_mei']);
        }

        if(isset($data['trx_spp_jun'])) {
            $this->db->set('trx_spp_jun', $data['trx_spp_jun']);
        }
        
         if(isset($data['siswa_id'])) {
            $this->db->set('siswa_siswa_id', $data['siswa_id']);
        }
        
         if(isset($data['trx_spp_input_date'])) {
            $this->db->set('trx_spp_input_date', $data['trx_spp_input_date']);
        }
        
         if(isset($data['trx_spp_last_update'])) {
            $this->db->set('trx_spp_last_update', $data['trx_spp_last_update']);
        }
        
         if(isset($data['user_id'])) {
            $this->db->set('user_user_id', $data['user_id']);
        }
        
        if (isset($data['trx_spp_id'])) {
            $this->db->where('trx_spp_id', $data['trx_spp_id']);
            $this->db->update('trx_spp');
            $id = $data['trx_spp_id'];
        } else {
            $this->db->insert('trx_spp');
            $id = $this->db->insert_id();
        }

        $status = $this->db->affected_rows();
        return ($status == 0) ? FALSE : $id;
    }
    
    // Delete to database
    function delete($id) {
        $this->db->where('trx_spp_id', $id);
        $this->db->delete('trx_spp');
    }
    
}
