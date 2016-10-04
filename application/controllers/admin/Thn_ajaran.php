<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * thn_ajaran controllers class
 *
 * @package     CMS
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Achyar Anshorie
 */
class thn_ajaran extends CI_Controller {

    public function __construct() {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('admin/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('Thn_ajaran_model', 'Activity_log_model'));
        $this->load->library('upload');
    }

    // thn_ajaran view in list
    public function index($offset = NULL) {
        $this->load->library('pagination');
        // Apply Filter
        // Get $_GET variable
        $f = $this->input->get(NULL, TRUE);

        $data['f'] = $f;

        $params = array();
        // nis
        if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
            $params['thn_ajaran_ket'] = $f['n'];
        }

        $paramsPage = $params;
        $params['limit'] = 10;
        $params['offset'] = $offset;
        $data['thn_ajaran'] = $this->Thn_ajaran_model->get($params);

        $config['per_page'] = 10;
        $config['uri_segment'] = 4;
        $config['base_url'] = site_url('admin/thn_ajaran/index');
        $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $config['total_rows'] = count($this->Thn_ajaran_model->get($paramsPage));
        $this->pagination->initialize($config);

        $data['title'] = 'Tahun Ajaran';
        $data['main'] = 'admin/thn_ajaran/thn_ajaran_list';
        $this->load->view('admin/layout', $data);
    }

    function detail($id = NULL) {
        if ($this->Thn_ajaran_model->get(array('id' => $id)) == NULL) {
            redirect('admin/thn_ajaran');
        }
        $data['thn_ajaran'] = $this->Thn_ajaran_model->get(array('id' => $id));
        $data['title'] = 'Detail Tahun Ajaran';
        $data['main'] = 'admin/thn_ajaran/thn_ajaran_detail';
        $this->load->view('admin/layout', $data);
    }

    // Add thn_ajaran and Update
    public function add($id = NULL) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('thn_ajaran_ket', 'Tahun Ajaran', 'trim|required|xss_clean');  
        $this->form_validation->set_rules('thn_ajaran_status', 'Status', 'trim|required|xss_clean');      
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
        $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

        if ($_POST AND $this->form_validation->run() == TRUE) {

            if ($this->input->post('thn_ajaran_id')) {
                $params['thn_ajaran_id'] = $this->input->post('thn_ajaran_id');
            } else {
                
            }
            $params['thn_ajaran_ket'] = $this->input->post('thn_ajaran_ket');
            $params['thn_ajaran_status'] = $this->input->post('thn_ajaran_status');
            $status = $this->Thn_ajaran_model->add($params);


            // activity log
            $this->Activity_log_model->add(
                    array(
                        'log_date' => date('Y-m-d H:i:s'),
                        'user_id' => $this->session->userdata('user_id'),
                        'log_module' => 'Peserta',
                        'log_action' => $data['operation'],
                        'log_info' => 'ID:' . $status . ';Title:' . $params['thn_ajaran_ket']
                    )
            );

            $this->session->set_flashdata('success', $data['operation'] . ' posting berhasil');
            redirect('admin/thn_ajaran');
        } else {

            // Edit mode
            if (!is_null($id)) {
                $data['thn_ajaran'] = $this->Thn_ajaran_model->get(array('id' => $id));
            }
            $data['title'] = $data['operation'] . ' Peserta';
            $data['main'] = 'admin/thn_ajaran/thn_ajaran_add';
            $this->load->view('admin/layout', $data);
        }
    }

    // Delete thn_ajaran
    public function delete($id = NULL) {
        if ($_POST) {
            $this->Thn_ajaran_model->delete($this->input->post('del_id'));
            // activity log
            $this->Activity_log_model->add(
                    array(
                        'log_date' => date('Y-m-d H:i:s'),
                        'user_id' => $this->session->userdata('user_id'),
                        'log_module' => 'Peserta',
                        'log_action' => 'Hapus',
                        'log_info' => 'ID:' . $this->input->post('del_id') . ';Title:' . $this->input->post('del_name')
                    )
            );
            $this->session->set_flashdata('success', 'Hapus posting berhasil');
            redirect('admin/thn_ajaran');
        } elseif (!$_POST) {
            $this->session->set_flashdata('delete', 'Delete');
            redirect('admin/thn_ajaran/edit/' . $id);
        }
    }
   
}

/* End of file thn_ajaran.php */
/* Location: ./application/controllers/admin/thn_ajaran.php */
