<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * tarif_byr controllers class 
 *
 * @package     CMS
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Achyar Anshorie
 */
class Tarif_byr extends CI_Controller {

    public function __construct() {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('admin/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('Tarif_byr_model', 'Activity_log_model', 'Bulan_model', 'Jns_byr_model', 'Thn_ajaran_model'));
        $this->load->helper('string');
    }

    // Surat Tarif view in list
    public function index($offset = NULL) {
        $this->load->library('pagination');
        $data['tarif_byr'] = $this->Tarif_byr_model->get(array('limit' => 10, 'offset' => $offset));
        $config['base_url'] = site_url('admin/tarif_byr/index');
        $config['total_rows'] = count($this->Tarif_byr_model->get(array('status' => TRUE)));
        $this->pagination->initialize($config);

        $data['title'] = 'Set Tarif Pembayaran';
        $data['main'] = 'admin/tarif_byr/tarif_byr_list';
        $this->load->view('admin/layout', $data);
    }

    function detail($id = NULL) {
        if ($this->Tarif_byr_model->get(array('id' => $id)) == NULL) {
            redirect('admin/tarif_byr');
        }
        $data['tarif_byr'] = $this->Tarif_byr_model->get(array('id' => $id));
        
        $data['title'] = 'Set Tarif Pembayaran';
        $data['main'] = 'admin/tarif_byr/tarif_byr_view';
        $this->load->view('admin/layout', $data);
    }

    // Add Surat and Update
    public function add($id = NULL) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tarif_byr_kategori', 'Set Tarif', 'trim|required|xss_clean');                 
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
        $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

        if ($_POST AND $this->form_validation->run() == TRUE) {

            if ($this->input->post('tarif_byr_id')) {
                $params['tarif_byr_id'] = $this->input->post('tarif_byr_id');
            } else {
                $lastnumber = $this->Tarif_byr_model->get(array('limit' => 1, 'order_by' => 'tarif_byr_id'));
                $num = $lastnumber['tarif_byr_number'];
                $params['tarif_byr_number'] = sprintf('%04d', $num + 01);
                $params['tarif_byr_input_date'] = date('Y-m-d H:i:s');
                
            }

            $params['tarif_byr_kategori'] = $this->input->post('tarif_byr_kategori');
            $params['bulan_id'] = $this->input->post('bulan_id'); 
            $params['thn_ajaran_id'] = $this->input->post('thn_ajaran_id'); 
            $params['jns_byr_id'] = $this->input->post('jns_byr_id');             
            $params['user_id'] = $this->session->userdata('user_id');            
            $params['tarif_byr_last_update'] = date('Y-m-d H:i:s');
            $status = $this->Tarif_byr_model->add($params);


            // activity log
            $this->Activity_log_model->add(
                array(
                    'log_date' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('user_id'),
                    'log_module' => 'Set Tarif Pembayaran',
                    'log_action' => $data['operation'],
                    'log_info' => 'ID:'.$status.';Title:NULL' 
                    )
                );

            $this->session->set_flashdata('success', $data['operation'] . ' Tarif berhasil');
            redirect('admin/tarif_byr');
        } else {
            if ($this->input->post('tarif_byr_id')) {
                redirect('admin/tarif_byr/edit/' . $this->input->post('tarif_byr_id'));
            }

            // Edit mode
            if (!is_null($id)) {
                $data['tarif_byr'] = $this->Tarif_byr_model->get(array('id' => $id));
            }
            $data['bulan'] = $this->Bulan_model->get();   
            $data['thn_ajaran'] = $this->Thn_ajaran_model->get(); 
            $data['jns_byr'] = $this->Jns_byr_model->get(); 
            $data['title'] = $data['operation'] . ' Set Tarif Pembayaran';
            $data['main'] = 'admin/tarif_byr/tarif_byr_add';
            $this->load->view('admin/layout', $data);
        }
    }

    // Delete Surat Tarif
    public function delete($id = NULL) {
        if ($_POST) {
            $this->Tarif_byr_model->delete($this->input->post('del_id'));
            // activity log
            $this->Activity_log_model->add(
                array(
                    'log_date' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('user_id'),
                    'log_module' => 'Surat Tarif',
                    'log_action' => 'Hapus',
                    'log_info' => 'ID:' . $this->input->post('del_id') . ';Title:' . $this->input->post('del_name')
                    )
                );
            $this->session->set_flashdata('success', 'Hapus Surat Tarif berhasil');
            redirect('admin/tarif_byr');
        } elseif (!$_POST) {
            $this->session->set_flashdata('delete', 'Delete');
            redirect('admin/tarif_byr/edit/' . $id);
        }
    }

}



/* End of file tarif_byr.php */
/* Location: ./application/controllers/admin/tarif_byr.php */
