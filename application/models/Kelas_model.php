<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
* Posts Model Class
 *
 * @package     CMS
 * @subpackage  Models
 * @category    Models
 * @author      Achyar Anshorie
 */

class Kelas_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // Get From Databases
    function get($params = array())
    {
        if(isset($params['id']))
        {
            $this->db->where('kelas.kelas_id', $params['id']);
        }
        if(isset($params['kelas_ket']))
        {
            $this->db->where('kelas_ket', $params['kelas_ket']);
        }
        if(isset($params['date_start']) AND isset($params['date_end']))
        {
            $this->db->where('kelas_published_date >=', $params['date_start'] . ' 00:00:00');
            $this->db->where('kelas_published_date <=', $params['date_end'] . ' 23:59:59');
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
            $this->db->order_by('kelas_id', 'asc');
        }

        $this->db->select('kelas.kelas_id, kelas_ket, kelas_tarif');
        $res = $this->db->get('kelas');

        if(isset($params['id']) OR (isset($params['limit']) AND $params['limit'] == 1) OR (isset($params['kelas_ket'])))
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

       if(isset($data['kelas_id'])) {
        $this->db->set('kelas_id', $data['kelas_id']);
    }

    if(isset($data['kelas_ket'])) {
        $this->db->set('kelas_ket', $data['kelas_ket']);
    }

    if(isset($data['kelas_tarif'])) {
        $this->db->set('kelas_tarif', $data['kelas_tarif']);
    }

    if (isset($data['kelas_id'])) {
        $this->db->where('kelas_id', $data['kelas_id']);
        $this->db->update('kelas');
        $id = $data['kelas_id'];
    } else {
        $this->db->insert('kelas');
        $id = $this->db->insert_id();
    }

    $status = $this->db->affected_rows();
    return ($status == 0) ? FALSE : $id;
}

    // Delete to database
function delete($id) {
    $this->db->where('kelas_id', $id);
    $this->db->delete('kelas');
}

}
