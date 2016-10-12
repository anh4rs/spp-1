<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * kelas controllers class
 *
 * @package     CMS
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Achyar Anshorie
 */
class Kelas extends CI_Controller {

    public function __construct() {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('admin/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('Kelas_model', 'Activity_log_model'));
        $this->load->library('upload');
    }

    // kelas view in list
    public function index($offset = NULL) {
        $this->load->library('pagination');
        // Apply Filter
        // Get $_GET variable
        $f = $this->input->get(NULL, TRUE);

        $data['f'] = $f;

        $params = array();
        // nis
        if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
            $params['kelas_ket'] = $f['n'];
        }

        $paramsPage = $params;
        $params['limit'] = 12;
        $params['offset'] = $offset;
        $data['kelas'] = $this->Kelas_model->get($params);

        $config['per_page'] = 12;
        $config['uri_segment'] = 4;
        $config['base_url'] = site_url('admin/kelas/index');
        $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $config['total_rows'] = count($this->Kelas_model->get($paramsPage));
        $this->pagination->initialize($config);

        $data['title'] = 'Kelas';
        $data['main'] = 'admin/kelas/kelas_list';
        $this->load->view('admin/layout', $data);
    }

    function detail($id = NULL) {
        if ($this->Kelas_model->get(array('id' => $id)) == NULL) {
            redirect('admin/kelas');
        }
        $data['kelas'] = $this->Kelas_model->get(array('id' => $id));
        $data['title'] = 'Detail Kelas';
        $data['main'] = 'admin/kelas/kelas_detail';
        $this->load->view('admin/layout', $data);
    }

    // Add kelas and Update
    public function add($id = NULL) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('kelas_ket', 'Kelas', 'trim|required|xss_clean');   
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
        $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

        if ($_POST AND $this->form_validation->run() == TRUE) {

            if ($this->input->post('kelas_id')) {
                $params['kelas_id'] = $this->input->post('kelas_id');
            } else {
                
            }
            $params['kelas_ket'] = $this->input->post('kelas_ket');
            $params['kelas_tarif'] = $this->input->post('kelas_tarif');
            $status = $this->Kelas_model->add($params);


            // activity log
            $this->Activity_log_model->add(
                    array(
                        'log_date' => date('Y-m-d H:i:s'),
                        'user_id' => $this->session->userdata('user_id'),
                        'log_module' => 'Peserta',
                        'log_action' => $data['operation'],
                        'log_info' => 'ID:' . $status . ';Title:' . $params['kelas_ket']
                    )
            );

            $this->session->set_flashdata('success', $data['operation'] . ' posting berhasil');
            redirect('admin/kelas');
        } else {

            // Edit mode
            if (!is_null($id)) {
                $data['kelas'] = $this->Kelas_model->get(array('id' => $id));
            }
            $data['title'] = $data['operation'] . ' Peserta';
            $data['main'] = 'admin/kelas/kelas_add';
            $this->load->view('admin/layout', $data);
        }
    }

    // Delete kelas
    public function delete($id = NULL) {
        if ($_POST) {
            $this->Kelas_model->delete($this->input->post('del_id'));
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
            redirect('admin/kelas');
        } elseif (!$_POST) {
            $this->session->set_flashdata('delete', 'Delete');
            redirect('admin/kelas/edit/' . $id);
        }
    }
   
}

/* End of file kelas.php */
/* Location: ./application/controllers/admin/kelas.php */
