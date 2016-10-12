<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * trx_spp controllers class 
 *
 * @package     CMS
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Achyar Anshorie
 */
class Trx_spp extends CI_Controller {

    public function __construct() {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('admin/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('Trx_spp_model', 'Activity_log_model', 'Kelas_model', 'Siswa_model', 'Thn_ajaran_model'));
        $this->load->helper('string');
    }

    // Surat Tarif view in list
    public function index($offset = NULL) {
        $this->load->library('pagination');
        $data['trx_spp'] = $this->Trx_spp_model->get(array('limit' => 10, 'offset' => $offset));
        $config['base_url'] = site_url('admin/trx_spp/index');
        $config['total_rows'] = count($this->Trx_spp_model->get(array('status' => TRUE)));
        $this->pagination->initialize($config);

        $data['title'] = 'Transaksi SPP';
        $data['main'] = 'admin/trx_spp/trx_spp_list';
        $this->load->view('admin/layout', $data);
    }

    function detail($id = NULL) {
        if ($this->Trx_spp_model->get(array('id' => $id)) == NULL) {
            redirect('admin/trx_spp');
        }
        $data['trx_spp'] = $this->Trx_spp_model->get(array('id' => $id));
        
        $data['title'] = 'Transaksi SPP';
        $data['main'] = 'admin/trx_spp/trx_spp_view';
        $this->load->view('admin/layout', $data);
    }

    // Add Surat and Update
    public function add($id = NULL) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('thn_ajaran_id', 'Tahun Ajaran', 'trim|required|xss_clean');                 
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
        $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

        if ($_POST AND $this->form_validation->run() == TRUE) {

            if ($this->input->post('trx_spp_id')) {
                $params['trx_spp_id'] = $this->input->post('trx_spp_id');
            } else {
                
            }

            $params['trx_spp_jul'] = $this->input->post('trx_spp_jul');
            $params['trx_spp_ags'] = $this->input->post('trx_spp_ags');
            $params['trx_spp_sep'] = $this->input->post('trx_spp_sep');
            $params['trx_spp_okt'] = $this->input->post('trx_spp_okt');
            $params['trx_spp_nov'] = $this->input->post('trx_spp_nov');
            $params['trx_spp_des'] = $this->input->post('trx_spp_des');
            $params['trx_spp_jan'] = $this->input->post('trx_spp_jan');
            $params['trx_spp_feb'] = $this->input->post('trx_spp_feb');
            $params['trx_spp_mar'] = $this->input->post('trx_spp_mar');
            $params['trx_spp_apr'] = $this->input->post('trx_spp_apr');
            $params['trx_spp_mei'] = $this->input->post('trx_spp_mei');
            $params['trx_spp_jun'] = $this->input->post('trx_spp_jun');
            $params['kelas_id'] = $this->input->post('kelas_id'); 
            $params['thn_ajaran_id'] = $this->input->post('thn_ajaran_id'); 
            $params['siswa_id'] = $this->input->post('siswa_id');             
            $params['user_id'] = $this->session->userdata('user_id');
            $params['trx_spp_input_update'] = date('Y-m-d H:i:s');            
            $params['trx_spp_last_update'] = date('Y-m-d H:i:s');
            $status = $this->Trx_spp_model->add($params);


            // activity log
            $this->Activity_log_model->add(
                array(
                    'log_date' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->userdata('user_id'),
                    'log_module' => 'Transaksi SPP',
                    'log_action' => $data['operation'],
                    'log_info' => 'ID:'.$status.';Title:NULL' 
                    )
                );

            $this->session->set_flashdata('success', $data['operation'] . ' Transaksi berhasil');
            redirect('admin/trx_spp');
        } else {
            if ($this->input->post('trx_spp_id')) {
                redirect('admin/trx_spp/edit/' . $this->input->post('trx_spp_id'));
            }

            // Edit mode
            if (!is_null($id)) {
                $data['trx_spp'] = $this->Trx_spp_model->get(array('id' => $id));
            }
            $data['kelas'] = $this->Kelas_model->get();   
            $data['thn_ajaran'] = $this->Thn_ajaran_model->get(); 
            $data['siswa'] = $this->Siswa_model->get(); 
            $data['title'] = $data['operation'] . ' Transaksi SPP';
            $data['main'] = 'admin/trx_spp/trx_spp_add';
            $this->load->view('admin/layout', $data);
        }
    }

    // Delete Surat Tarif
    public function delete($id = NULL) {
        if ($_POST) {
            $this->Trx_spp_model->delete($this->input->post('del_id'));
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
            redirect('admin/trx_spp');
        } elseif (!$_POST) {
            $this->session->set_flashdata('delete', 'Delete');
            redirect('admin/trx_spp/edit/' . $id);
        }
    }

}



/* End of file trx_spp.php */
/* Location: ./application/controllers/admin/trx_spp.php */
