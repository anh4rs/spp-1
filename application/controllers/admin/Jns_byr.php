<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * jns_byr controllers class
 *
 * @package     CMS
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Achyar Anshorie
 */
class Jns_byr extends CI_Controller {

    public function __construct() {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('admin/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('Jns_byr_model', 'Activity_log_model'));
        $this->load->library('upload');
    }

    // jns_byr view in list
    public function index($offset = NULL) {
        $this->load->library('pagination');
        // Apply Filter
        // Get $_GET variable
        $f = $this->input->get(NULL, TRUE);

        $data['f'] = $f;

        $params = array();
        // nis
        if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
            $params['jns_byr_kategori'] = $f['n'];
        }

        $paramsPage = $params;
        $params['limit'] = 12;
        $params['offset'] = $offset;
        $data['jns_byr'] = $this->Jns_byr_model->get($params);

        $config['per_page'] = 12;
        $config['uri_segment'] = 4;
        $config['base_url'] = site_url('admin/jns_byr/index');
        $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $config['total_rows'] = count($this->Jns_byr_model->get($paramsPage));
        $this->pagination->initialize($config);

        $data['title'] = 'Jenis Pembayaran';
        $data['main'] = 'admin/jns_byr/jns_byr_list';
        $this->load->view('admin/layout', $data);
    }

    function detail($id = NULL) {
        if ($this->Jns_byr_model->get(array('id' => $id)) == NULL) {
            redirect('admin/jns_byr');
        }
        $data['jns_byr'] = $this->Jns_byr_model->get(array('id' => $id));
        $data['title'] = 'Detail Jenis Bayar';
        $data['main'] = 'admin/jns_byr/jns_byr_detail';
        $this->load->view('admin/layout', $data);
    }

    // Add jns_byr and Update
    public function add($id = NULL) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('jns_byr_kategori', 'Jenis Bayar', 'trim|required|xss_clean');  
        $this->form_validation->set_rules('jns_byr_ket', 'Kategori', 'trim|required|xss_clean');   
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
        $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

        if ($_POST AND $this->form_validation->run() == TRUE) {

            if ($this->input->post('jns_byr_id')) {
                $params['jns_byr_id'] = $this->input->post('jns_byr_id');
            } else {
                
            }
            $params['jns_byr_kategori'] = $this->input->post('jns_byr_kategori');
            $params['jns_byr_ket'] = $this->input->post('jns_byr_ket');
            $status = $this->Jns_byr_model->add($params);


            // activity log
            $this->Activity_log_model->add(
                    array(
                        'log_date' => date('Y-m-d H:i:s'),
                        'user_id' => $this->session->userdata('user_id'),
                        'log_module' => 'Peserta',
                        'log_action' => $data['operation'],
                        'log_info' => 'ID:' . $status . ';Title:' . $params['jns_byr_ket']
                    )
            );

            $this->session->set_flashdata('success', $data['operation'] . ' posting berhasil');
            redirect('admin/jns_byr');
        } else {

            // Edit mode
            if (!is_null($id)) {
                $data['jns_byr'] = $this->Jns_byr_model->get(array('id' => $id));
            }
            $data['title'] = $data['operation'] . ' Jenis Bayar';
            $data['main'] = 'admin/jns_byr/jns_byr_add';
            $this->load->view('admin/layout', $data);
        }
    }

    // Delete jns_byr
    public function delete($id = NULL) {
        if ($_POST) {
            $this->Jns_byr_model->delete($this->input->post('del_id'));
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
            redirect('admin/jns_byr');
        } elseif (!$_POST) {
            $this->session->set_flashdata('delete', 'Delete');
            redirect('admin/jns_byr/edit/' . $id);
        }
    }
   
}

/* End of file jns_byr.php */
/* Location: ./application/controllers/admin/jns_byr.php */
