<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_t_ak_jurnal_create extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();

    $this->load->model('m_t_ak_jurnal_create');
    $this->load->model('m_t_ak_jurnal');
    $this->load->model('m_ak_m_coa');
    $this->load->model('m_ak_m_family');
    $this->load->model('m_ak_m_type');
  }

  public function index()
  {
    $ada_data = '';
    $read_select = $this->m_t_ak_jurnal_create->select();
    foreach ($read_select as $key => $value) {
      $ada_data = $value->ID;
    }
    if ($ada_data == '') {
      $this->read_no_voucer();
    }


    $data = [
      "c_t_ak_jurnal_create" => $this->m_t_ak_jurnal_create->select(),
      "no_akun_option" => $this->m_ak_m_coa->select_no_akun(),
      "c_ak_m_family" => $this->m_ak_m_family->select(),
      "c_ak_m_type" => $this->m_ak_m_type->select(),
      "title" => "Create No Voucer Baru",
      "description" => "Harus isi nomor voucer dulu"
    ];
    $this->render_backend('template/backend/pages/t_ak_jurnal_create', $data);
  }

  public function create_no_voucer()
  {
    $no_voucer_textbox = ($this->input->post("no_voucer_textbox"));

    $this->session->set_userdata('now_no_voucer', $no_voucer_textbox);

    $data = array(
      'NO_VOUCER' => $this->session->userdata('now_no_voucer')
    );

    $this->m_t_ak_jurnal_create->update_all($data, $id);

    redirect('c_t_ak_jurnal_create');
  }


  public function read_no_voucer()
  {
    $read_last_no_voucer = '';
    $read_select = $this->m_t_ak_jurnal->select_no_voucer();
    foreach ($read_select as $key => $value) {
      $read_last_no_voucer = $value->NO_VOUCER;
    }
    $last_no_voucer = intval(substr($read_last_no_voucer, -4)) + 1;

    $now_no_voucer = substr($read_last_no_voucer, 0, -4) . sprintf('%04d', $last_no_voucer);
    $this->session->set_userdata('now_no_voucer_keep', $now_no_voucer);
  }

  public function delete($id)
  {
    $this->m_t_ak_jurnal_create->delete($id);
    $this->session->set_flashdata('notif', '<div class="alert alert-danger icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="icofont icofont-close-line-circled"></i></button><p><strong>Success!</strong> Data Berhasil DIhapus!</p></div>');
    redirect('/c_t_ak_jurnal_create');
  }



  function tambah()
  {

    $date_jurnal_create = ($this->input->post("date"));
    $this->session->set_userdata('date_jurnal_create', $date_jurnal_create);


    $coa_id = intval($this->input->post("coa_id"));


    $debit = intval($this->input->post("debit"));
    $kredit = intval($this->input->post("kredit"));
    $catatan = substr(($this->input->post("catatan")), 0, 200);
    $departemen = substr(($this->input->post("departemen")), 0, 50);
    $no_voucer = $this->session->userdata('now_no_voucer');
    $date = $this->input->post("date");




    $read_select = $this->m_t_ak_jurnal_create->select();
    foreach ($read_select as $key => $value) {
      if ($key == 0) {
        $no_voucer = $value->NO_VOUCER;
      }
    }

    if ($no_voucer != '') 
    {
      $data = array(
        'DATE' => $date,
        'TIME' => date('H:i:s'),
        'CREATED_BY' => $this->session->userdata('username'),
        'UPDATED_BY' => $this->session->userdata('username'),
        'COA_ID' => $coa_id,
        'DEBIT' => $debit,
        'KREDIT' => $kredit,
        'CATATAN' => $catatan,
        'DEPARTEMEN' => $departemen,
        'NO_VOUCER' => $no_voucer

      );

      $this->m_t_ak_jurnal_create->tambah($data);


      $data = array(
        'DATE' => $date
      );

      $this->m_t_ak_jurnal_create->update_all($data);

      $this->session->set_flashdata('notif', '<div class="alert alert-info icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <i class="icofont icofont-close-line-circled"></i></button><p><strong>Data Berhasil Ditambahkan!</strong></p></div>');
    }
    if ($no_voucer == '') {
      $this->session->set_flashdata('notif', '<div class="alert alert-danger icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="icofont icofont-close-line-circled"></i></button><p><strong>GAGAL!</strong> NO VOUCER BELUM DIISI!</p></div>');
    }

    redirect('c_t_ak_jurnal_create');
  }

  function move()
  {
    $this->session->set_userdata('now_no_voucer', '');
    $read_select = $this->m_t_ak_jurnal_create->select();
    foreach ($read_select as $key => $value) {
      if ($key == 0) {
        $created_id = strtotime(date('Y-m-d H:i:s'));
      }
      $data = array(
        'DATE' => $value->DATE,
        'TIME' => $value->TIME,
        'CREATED_BY' => $this->session->userdata('username'),
        'UPDATED_BY' => $this->session->userdata('username'),
        'COA_ID' => $value->COA_ID,
        'DEBIT' => $value->DEBIT,
        'KREDIT' => $value->KREDIT,
        'CATATAN' => $value->CATATAN,
        'DEPARTEMEN' => $value->DEPARTEMEN,
        'NO_VOUCER' => $value->NO_VOUCER,
        'CREATED_ID' => $created_id,
        'CHECKED_ID' => 1,
        'SPECIAL_ID' => 0,
        'COMPANY_ID' => $this->session->userdata('company_id')

      );

      $this->m_t_ak_jurnal->tambah($data);
      $this->update_coa_saldo($value->COA_ID);
      $this->m_t_ak_jurnal_create->delete($value->ID);
    }
    redirect('c_t_ak_jurnal');
  }


  public function update_coa_saldo($coa_id)
  {
    $read_select = $this->m_ak_m_coa->select_coa_id($coa_id);
    foreach ($read_select as $key => $value) {
      $sum_kredit = 0;
      $sum_debit = 0;

      $read_select_1 = $this->m_t_ak_jurnal->select_sum_kredit_detail($coa_id);
      foreach ($read_select_1 as $key_1 => $value_1) {
        $sum_kredit = $value_1->KREDIT;
      }
      $read_select_1 = $this->m_t_ak_jurnal->select_sum_debit_detail($coa_id);
      foreach ($read_select_1 as $key_1 => $value_1) {
        $sum_debit = $value_1->DEBIT;
      }




      if ($value->DB_K_ID == 1) {
        $saldo = $sum_debit - $sum_kredit;
      }
      if ($value->DB_K_ID == 2) {
        $saldo = $sum_kredit - $sum_debit;
      }


      $data = array(
        'SALDO' => $saldo
      );
      $this->m_ak_m_coa->update($data, $coa_id);


      if ($value->NO_AKUN_2 != '' and $value->NO_AKUN_1 != '' and $value->NO_AKUN_3 != '') {
        $read_select_1 = $this->m_ak_m_coa->select_sum_saldo_no_akun_3($value->NO_AKUN_2);
        foreach ($read_select_1 as $key_1 => $value_1) {
          $sum_saldo_parent_2 = $value_1->SALDO;
        }
        $data = array(
          'SALDO' => $sum_saldo_parent_2
        );
        $this->m_ak_m_coa->update_saldo_parent_2($data, $value->NO_AKUN_2);

        $read_select_1 = $this->m_ak_m_coa->select_sum_saldo_no_akun_2($value->NO_AKUN_1);
        foreach ($read_select_1 as $key_1 => $value_1) {
          $sum_saldo_parent_1 = $value_1->SALDO;
        }
        $data = array(
          'SALDO' => $sum_saldo_parent_1
        );
        $this->m_ak_m_coa->update_saldo_parent_1($data, $value->NO_AKUN_1);
      }

      if ($value->NO_AKUN_1 != '' and $value->NO_AKUN_2 == '' and $value->NO_AKUN_3 != '') {
        $read_select_1 = $this->m_ak_m_coa->select_sum_saldo_no_akun_4($value->NO_AKUN_1);
        foreach ($read_select_1 as $key_1 => $value_1) {
          $sum_saldo_parent_1 = $value_1->SALDO;
        }
        $data = array(
          'SALDO' => $sum_saldo_parent_1
        );
        $this->m_ak_m_coa->update_saldo_parent_1($data, $value->NO_AKUN_1);
      }

      if ($value->NO_AKUN_1 != '' and $value->NO_AKUN_2 != '' and $value->NO_AKUN_3 == '') {
        $read_select_1 = $this->m_ak_m_coa->select_sum_saldo_no_akun_5($value->NO_AKUN_1);
        foreach ($read_select_1 as $key_1 => $value_1) {
          $sum_saldo_parent_1 = $value_1->SALDO;
        }
        $data = array(
          'SALDO' => $sum_saldo_parent_1
        );
        $this->m_ak_m_coa->update_saldo_parent_1($data, $value->NO_AKUN_1);
      }
    }
  }


  public function edit_action()
  {
    $id = $this->input->post("id");

    $date = $this->input->post("date");
    $debit = intval($this->input->post("debit"));
    $kredit = intval($this->input->post("kredit"));
    $catatan = ($this->input->post("catatan"));
    $departemen = ($this->input->post("departemen"));
    $no_voucer = $this->session->userdata('now_no_voucer');



    //Dikiri nama kolom pada database, dikanan hasil yang kita tangkap nama formnya.
    $data = array(
      'DATE' => date('Y-m-d'),
      'TIME' => date('H:i:s'),
      'UPDATED_BY' => $this->session->userdata('username'),
      'DEBIT' => $debit,
      'KREDIT' => $kredit,
      'CATATAN' => $catatan,
      'DEPARTEMEN' => $departemen,
      'NO_VOUCER' => $no_voucer,
      'DATE' => $date

    );

    $this->m_t_ak_jurnal_create->update($data, $id);


    $data = array(
      'DATE' => $date
    );

    $this->m_t_ak_jurnal_create->update_all($data);



    $this->session->set_flashdata('notif', '<div class="alert alert-info icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <i class="icofont icofont-close-line-circled"></i></button><p><strong>Data Berhasil Diupdate!</strong></p></div>');
    redirect('/c_t_ak_jurnal_create');
  }
}
