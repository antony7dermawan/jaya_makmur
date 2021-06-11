<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_t_ak_terima_pelanggan_no_faktur extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();

    $this->load->model('m_t_ak_terima_pelanggan_no_faktur');
    $this->load->model('m_t_ak_terima_pelanggan');
    $this->load->model('m_t_ak_faktur_penjualan');
  }


  public function index($id, $pks_id)
  {
    $data = [
      "c_t_ak_terima_pelanggan_no_faktur" => $this->m_t_ak_terima_pelanggan_no_faktur->select($id),
      "c_t_ak_terima_pelanggan" => $this->m_t_ak_terima_pelanggan-> select_by_id($id),
      "terima_pelanggan_id" => $id,
      "select_no_faktur" => $this->m_t_ak_faktur_penjualan->select_no_faktur(),
      "pks_id" => $pks_id,
      "title" => "Rincian No Faktur",
      "description" => "Isi Rincian Nomor Faktur"
    ];
    $this->render_backend('template/backend/pages/t_ak_terima_pelanggan_no_faktur', $data);
  }




  public function delete($id, $terima_pelanggan_id,$pks_id)
  {
    $read_select = $this->m_t_ak_terima_pelanggan_no_faktur->select_by_id($id);
    foreach ($read_select as $key => $value) 
    {
      $payment_t = intval($value->PAYMENT_T);
    }
    //if($payment_t==0)
    //{
      $this->m_t_ak_terima_pelanggan_no_faktur->delete($id);
      $this->session->set_flashdata('notif', '<div class="alert alert-danger icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="icofont icofont-close-line-circled"></i></button><p><strong>Success!</strong> Data Berhasil DIhapus!</p></div>');
   // }

    /*
    if($payment_t>0)
    {
      $this->session->set_flashdata('notif', '<div class="alert alert-danger icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="icofont icofont-close-line-circled"></i></button><p><strong>Gagal!</strong> Data Sudah Dibayar!</p></div>');
    }
    */
    
    redirect('/c_t_ak_terima_pelanggan_no_faktur/index/'.$terima_pelanggan_id.'/'.$pks_id);
  }




  function tambah($terima_pelanggan_id, $pks_id)
  {
    $faktur_penjualan_id = intval($this->input->post("faktur_penjualan_id"));
    $read_select = $this->m_t_ak_faktur_penjualan->select_by_id($faktur_penjualan_id);
    foreach ($read_select as $key => $value) {
      $sum_total_penjualan = round($value->SUM_TOTAL_PENJUALAN);

      $sum_total_retur_penjualan = round($value->SUM_TOTAL_RETUR_PENJUALAN);

      $ppn = $value->PPN;
      $pph = $value->PPH;
    }

    $total_all = $sum_total_penjualan-$sum_total_retur_penjualan;
    if($ppn=='t')
    {
      $total_all = $total_all * 1.1;
    }

    $data = array(
      'FAKTUR_PENJUALAN_ID' => $faktur_penjualan_id,
      'TERIMA_PELANGGAN_ID' => $terima_pelanggan_id,
      'CREATED_BY' => $this->session->userdata('username'),
      'UPDATED_BY' => $this->session->userdata('username'),
      'TOTAL_PENJUALAN' => $total_all
    );

    $this->m_t_ak_terima_pelanggan_no_faktur->tambah($data);

    $this->session->set_flashdata('notif', '<div class="alert alert-info icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <i class="icofont icofont-close-line-circled"></i></button><p><strong>Data Berhasil Ditambahkan!</strong></p></div>');
    redirect('c_t_ak_terima_pelanggan_no_faktur/index/' . $terima_pelanggan_id . '/' . $pks_id);
  }
}
