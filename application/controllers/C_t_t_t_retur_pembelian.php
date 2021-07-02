<?php
defined('BASEPATH') or exit('No direct script access allowed');


class C_t_t_t_retur_pembelian extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();

    $this->load->model('m_t_t_t_pembelian');
    $this->load->model('m_t_m_d_company');
    $this->load->model('m_t_t_t_retur_pembelian');
  
  }

  public function index()
  {
    $this->session->set_userdata('t_t_t_retur_pembelian_delete_logic', '1');

    if($this->session->userdata('date_retur_pembelian')=='')
    {
      $date_retur_pembelian = date('Y-m-d');
      $this->session->set_userdata('date_retur_pembelian', $date_retur_pembelian);
    }
    
    $data = [
      "c_t_t_t_retur_pembelian" => $this->m_t_t_t_retur_pembelian->select($this->session->userdata('date_retur_pembelian')),
      "c_t_m_d_company" => $this->m_t_m_d_company->select(),

      "select_inv_pembelian"  => $this->m_t_t_t_pembelian->select_inv_pembelian(),
      "title" => "Transaksi Retur Pembelian",
      "description" => "form Pembelian"
    ];
    $this->render_backend('template/backend/pages/t_t_t_retur_pembelian', $data);
  }


  public function date_retur_pembelian()
  {
    $date_retur_pembelian = ($this->input->post("date_retur_pembelian"));
    $this->session->set_userdata('date_retur_pembelian', $date_retur_pembelian);
    redirect('/c_t_t_t_retur_pembelian');
  }


  public function delete($id)
  {
    $data = array(
        'UPDATED_BY' => $this->session->userdata('username'),
        'MARK_FOR_DELETE' => TRUE
    );
    $this->m_t_t_t_retur_pembelian->update($data, $id);
    $this->session->set_flashdata('notif', '<div class="alert alert-danger icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="icofont icofont-close-line-circled"></i></button><p><strong>Success!</strong> Data Berhasil DIhapus!</p></div>');
    redirect('/c_t_t_t_retur_pembelian');
  }

  public function undo_delete($id)
  {
    $data = array(
        'UPDATED_BY' => $this->session->userdata('username'),
        'MARK_FOR_DELETE' => FALSE
    );
    $this->m_t_t_t_retur_pembelian->update($data, $id);
    
    $this->session->set_flashdata('notif', '<div class="alert alert-info icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <i class="icofont icofont-close-line-circled"></i></button><p><strong>Data Berhasil Dikembalikan!</strong></p></div>');
    redirect('/c_t_t_t_retur_pembelian');
  }

 







  function tambah()
  {
    $pembelian_id = intval($this->input->post("pembelian_id"));
    
    $ket = substr($this->input->post("ket"), 0, 200);
    $date = $this->input->post("date");

    $inv_int = 0;
    $read_select = $this->m_t_t_t_retur_pembelian->select_inv_int();
    foreach ($read_select as $key => $value) 
    {
      $inv_int = intval($value->INV_INT)+1;
    }

    $read_select = $this->m_t_m_d_company->select_by_company_id();
    foreach ($read_select as $key => $value) 
    {
      $inv_pembelian = $value->INV_PEMBELIAN;
      $inv_retur_pembelian = $value->INV_RETUR_PEMBELIAN;
      $inv_penjualan = $value->INV_PENJUALAN;
      $inv_retur_penjualan = $value->INV_RETUR_PENJUALAN;
      $inv_po = $value->INV_PO;
      $inv_pinlok = $value->INV_PINLOK;
    }



    $live_inv = $inv_retur_pembelian.date('y-m').'.'.sprintf('%05d', $inv_int);

    $date_retur_pembelian = $date;
    $this->session->set_userdata('date_retur_pembelian', $date_retur_pembelian);

    if($pembelian_id!=0)
    {
      $data = array(
        'DATE' => $date,
        'TIME' => date('H:i:s'),
        'INV' => $live_inv,
        'INV_INT' => $inv_int,
        'COMPANY_ID' => $this->session->userdata('company_id'),
        'PEMBELIAN_ID' => $pembelian_id,
        'KET' => $ket,
        'CREATED_BY' => $this->session->userdata('username'),
        'UPDATED_BY' => '',
        'MARK_FOR_DELETE' => FALSE,
        'PRINTED' => FALSE,
        'TABLE_CODE' => 'RETUR_PEMBELIAN'
      );

      $this->m_t_t_t_retur_pembelian->tambah($data);

      $this->session->set_flashdata('notif', '<div class="alert alert-info icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <i class="icofont icofont-close-line-circled"></i></button><p><strong>Data Berhasil Ditambahkan!</strong></p></div>');
    }

    else
    {
      $this->session->set_flashdata('notif', '<div class="alert alert-danger icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="icofont icofont-close-line-circled"></i></button><p><strong>Gagal!</strong> Data Tidak Lengkap!</p></div>');
    }
    

    
    redirect('c_t_t_t_retur_pembelian');
  }






  public function edit_action()
  {
    $id = $this->input->post("id");
    $ket = substr($this->input->post("ket"), 0, 200);
    

   
      $data = array(
        'KET' => $ket,
        'UPDATED_BY' => $this->session->userdata('username')
      );
      $this->m_t_t_t_retur_pembelian->update($data, $id);
      $this->session->set_flashdata('notif', '<div class="alert alert-info icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <i class="icofont icofont-close-line-circled"></i></button><p><strong>Data Berhasil Diupdate!</strong></p></div>');
    
    
    redirect('/c_t_t_t_retur_pembelian');
  }
}
