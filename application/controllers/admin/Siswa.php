<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * siswa controllers class
 *
 * @package     CMS
 * @subpackage  Controllers
 * @category    Controllers
 * @author      Achyar Anshorie
 */
class Siswa extends CI_Controller {

    public function __construct() {
        parent::__construct(TRUE);
        if ($this->session->userdata('logged') == NULL) {
            header("Location:" . site_url('admin/auth/login') . "?location=" . urlencode($_SERVER['REQUEST_URI']));
        }
        $this->load->model(array('Siswa_model', 'Activity_log_model'));
        $this->load->library('upload');
    }

    // siswa view in list
    public function index($offset = NULL) {
        $this->load->library('pagination');
        // Apply Filter
        // Get $_GET variable
        $f = $this->input->get(NULL, TRUE);

        $data['f'] = $f;

        $params = array();
        // nis
        if (isset($f['n']) && !empty($f['n']) && $f['n'] != '') {
            $params['siswa_nama'] = $f['n'];
        }

        $paramsPage = $params;
        $params['limit'] = 10;
        $params['offset'] = $offset;
        $data['siswa'] = $this->Siswa_model->get($params);
        $data['siswas'] = $this->Siswa_model->get();

        $config['per_page'] = 10;
        $config['uri_segment'] = 4;
        $config['base_url'] = site_url('admin/siswa/index');
        $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $config['total_rows'] = count($this->Siswa_model->get($paramsPage));
        $this->pagination->initialize($config);

        $data['title'] = 'Peserta';
        $data['main'] = 'admin/siswa/siswa_list';
        $this->load->view('admin/layout', $data);
    }

    function detail($id = NULL) {
        if ($this->Siswa_model->get(array('id' => $id)) == NULL) {
            redirect('admin/siswa');
        }
        $data['siswa'] = $this->Siswa_model->get(array('id' => $id));
        $data['title'] = 'Detail Siswa';
        $data['main'] = 'admin/siswa/siswa_detail';
        $this->load->view('admin/layout', $data);
    }

    // Add siswa and Update
    public function add($id = NULL) {
        $this->load->library('form_validation');
        if (!$this->input->post('siswa_id')) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|xss_clean');
            $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|min_length[6]|matches[password]');
        }
        $this->form_validation->set_rules('siswa_nama', 'Nama Lengkap', 'trim|required|xss_clean');
        $this->form_validation->set_rules('siswa_tmpt_lhr', 'Tempat Lahir', 'trim|required|xss_clean');
        $this->form_validation->set_rules('siswa_tgl_lhr', 'Tanggal Lahir', 'trim|required|xss_clean');
        $this->form_validation->set_rules('siswa_status', 'Status', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
        $data['operation'] = is_null($id) ? 'Tambah' : 'Sunting';

        if ($_POST AND $this->form_validation->run() == TRUE) {

            if ($this->input->post('siswa_id')) {
                $params['siswa_id'] = $this->input->post('siswa_id');
            } else {
                $params['siswa_nis'] = $this->input->post('siswa_nis');
                $params['user_input_date'] = date('Y-m-d H:i:s');
                $params['siswa_password'] = sha1($this->input->post('siswa_password'));
            }
            $params['siswa_nama'] = $this->input->post('siswa_nama');
            $params['siswa_tmpt_lhr'] = $this->input->post('siswa_tmpt_lhr');
            $params['user_last_update'] = date('Y-m-d H:i:s');
            $params['siswa_tgl_lhr'] = $this->input->post('siswa_tgl_lhr');
            $params['siswa_status'] = $this->input->post('siswa_status');
            $status = $this->Siswa_model->add($params);


            // activity log
            $this->Activity_log_model->add(
                    array(
                        'log_date' => date('Y-m-d H:i:s'),
                        'user_id' => $this->session->userdata('user_id'),
                        'log_module' => 'Peserta',
                        'log_action' => $data['operation'],
                        'log_info' => 'ID:' . $status . ';Title:' . $params['siswa_nama']
                    )
            );

            $this->session->set_flashdata('success', $data['operation'] . ' posting berhasil');
            redirect('admin/siswa');
        } else {

            // Edit mode
            if (!is_null($id)) {
                $data['siswa'] = $this->Siswa_model->get(array('id' => $id));
            }
            $data['title'] = $data['operation'] . ' Peserta';
            $data['main'] = 'admin/siswa/siswa_add';
            $this->load->view('admin/layout', $data);
        }
    }

    // Setting Upload File Requied
    function do_upload($name, $createdate, $nis) {
        $config['upload_path'] = FCPATH . 'uploads/';

        $paramsupload = array('date' => $createdate);
        list($date, $time) = explode(' ', $paramsupload['date']);
        list($year, $month, $day) = explode('-', $date);
        $config['upload_path'] = FCPATH . 'uploads/siswa_photo/' . $year . '/' . $month . '/' . $day . '/';

        /* create directory if not exist */
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = '32000';
        $config['file_name'] = $nis;
                $this->upload->initialize($config);

        if (!$this->upload->do_upload($name)) {
            echo $config['upload_path'];
            $this->session->set_flashdata('success', $this->upload->display_errors(''));
            redirect(uri_string());
        }

        $upload_data = $this->upload->data();

        return $upload_data['file_name'];
    }

    function rpw($id = NULL) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('siswa_password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|min_length[6]|matches[siswa_password]');
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>', '</div>');
        if ($_POST AND $this->form_validation->run() == TRUE) {
            $id = $this->input->post('siswa_id');
            $params['password'] = sha1($this->input->post('siswa_password'));
            $status = $this->Siswa_model->change_password($id, $params);

            $this->session->set_flashdata('success', 'Reset password berhasil');
            redirect('admin/siswa');
        } else {
            if ($this->Siswa_model->get(array('id' => $id)) == NULL) {
                redirect('admin/siswa');
            }
            $data['siswa'] = $this->Siswa_model->get(array('id' => $id));
            $data['title'] = $this->lang->line('reset_pass_siswa');
            $data['main'] = 'admin/siswa/change_pass';
            $this->load->view('admin/layout', $data);
        }
    }

    // Delete siswa
    public function delete($id = NULL) {
        if ($_POST) {
            $this->Siswa_model->delete($this->input->post('del_id'));
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
            redirect('admin/siswa');
        } elseif (!$_POST) {
            $this->session->set_flashdata('delete', 'Delete');
            redirect('admin/siswa/edit/' . $id);
        }
    }


function report($id = NULL) {
        $this->load->helper(array('dompdf'));
        if ($id == NULL)
            redirect('admin/siswa');

        $data['siswa'] = $this->Siswa_model->get(array('id' => $id));

        $html = $this->load->view('admin/siswa/siswa_pdf', $data, true);
        $data = pdf_create($html, '', TRUE);
    }
   
}

/* End of file siswa.php */
/* Location: ./application/controllers/admin/siswa.php */
