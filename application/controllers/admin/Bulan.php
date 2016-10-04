<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * bulan controllers class
 *
 * @package     CMS
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Achyar Anshorie
 */
class Bulan extends CI_Controller {

    public function __construct() {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('admin/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('Bulan_model', 'Activity_log_model'));
        $this->load->library('upload');
    }

    // bulan view in list
    public function index($offset = NULL) {
        $this->load->library('pagination');
        // Apply Filter
        // Get $_GET variable
        $f = $this->input->get(NULL, TRUE);

        $data['f'] = $f;

        $params = array();
        // nis
        if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
            $params['bulan_nama'] = $f['n'];
        }

        $paramsPage = $params;
        $params['limit'] = 12;
        $params['offset'] = $offset;
        $data['bulan'] = $this->Bulan_model->get($params);

        $config['per_page'] = 12;
        $config['uri_segment'] = 4;
        $config['base_url'] = site_url('admin/bulan/index');
        $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $config['total_rows'] = count($this->Bulan_model->get($paramsPage));
        $this->pagination->initialize($config);

        $data['title'] = 'Tahun Ajaran';
        $data['main'] = 'admin/bulan/bulan_list';
        $this->load->view('admin/layout', $data);
    }

    function detail($id = NULL) {
        if ($this->Bulan_model->get(array('id' => $id)) == NULL) {
            redirect('admin/bulan');
        }
        $data['bulan'] = $this->Bulan_model->get(array('id' => $id));
        $data['title'] = 'Detail Tahun Ajaran';
        $data['main'] = 'admin/bulan/bulan_detail';
        $this->load->view('admin/layout', $data);
    }

    // Add bulan and Update
    public function add($id = NULL) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('bulan_nama', 'Tahun Ajaran', 'trim|required|xss_clean');   
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
        $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

        if ($_POST AND $this->form_validation->run() == TRUE) {

            if ($this->input->post('bulan_id')) {
                $params['bulan_id'] = $this->input->post('bulan_id');
            } else {
                
            }
            $params['bulan_nama'] = $this->input->post('bulan_nama');
            $status = $this->Bulan_model->add($params);


            // activity log
            $this->Activity_log_model->add(
                    array(
                        'log_date' => date('Y-m-d H:i:s'),
                        'user_id' => $this->session->userdata('user_id'),
                        'log_module' => 'Peserta',
                        'log_action' => $data['operation'],
                        'log_info' => 'ID:' . $status . ';Title:' . $params['bulan_nama']
                    )
            );

            $this->session->set_flashdata('success', $data['operation'] . ' posting berhasil');
            redirect('admin/bulan');
        } else {

            // Edit mode
            if (!is_null($id)) {
                $data['bulan'] = $this->Bulan_model->get(array('id' => $id));
            }
            $data['title'] = $data['operation'] . ' Peserta';
            $data['main'] = 'admin/bulan/bulan_add';
            $this->load->view('admin/layout', $data);
        }
    }

    // Delete bulan
    public function delete($id = NULL) {
        if ($_POST) {
            $this->Bulan_model->delete($this->input->post('del_id'));
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
            redirect('admin/bulan');
        } elseif (!$_POST) {
            $this->session->set_flashdata('delete', 'Delete');
            redirect('admin/bulan/edit/' . $id);
        }
    }
   
}

/* End of file bulan.php */
/* Location: ./application/controllers/admin/bulan.php */
