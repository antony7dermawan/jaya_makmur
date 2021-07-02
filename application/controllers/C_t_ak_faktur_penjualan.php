<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_t_ak_faktur_penjualan extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();


    $this->load->model('m_t_ak_faktur_penjualan');
    $this->load->model('m_t_t_t_penjualan');



    $this->load->model('m_t_m_d_company');
    $this->load->model('m_t_m_d_pelanggan');
    $this->load->model('m_ak_m_coa');
    $this->load->model('m_t_ak_faktur_penjualan_print_setting');
    $this->load->model('m_t_ak_jurnal');
  }

  public function index()
  {
    $this->session->set_userdata('t_m_d_pelanggan_delete_logic', '0');

    if($this->session->userdata('date_faktur_penjualan')=='')
    {
      $date_faktur_penjualan = date('Y-m-d');
      $this->session->set_userdata('date_faktur_penjualan', $date_faktur_penjualan);
    }
    
    $data = [
      "c_t_ak_faktur_penjualan" => $this->m_t_ak_faktur_penjualan->select($this->session->userdata('date_faktur_penjualan')),

      "c_t_m_d_pelanggan" => $this->m_t_m_d_pelanggan->select(),

      "title" => "Faktur Penjualan",
      "description" => "Membuat Tagihan ke Pelanggan"
    ];
    $this->render_backend('template/backend/pages/t_ak_faktur_penjualan', $data);
  }

  public function undo($id)
  {

    $data = array(
      'ENABLE_EDIT' => 1
    );
    $this->m_t_ak_faktur_penjualan->update($data, $id);
    $read_select = $this->m_t_ak_faktur_penjualan->select_by_id($id);
    foreach ($read_select as $key => $value) 
    {
      $no_faktur=$value->NO_FAKTUR;
    }
    $this->m_t_ak_jurnal->delete_no_voucer($no_faktur);

    $this->session->set_flashdata('notif', '<div class="alert alert-danger icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="icofont icofont-close-line-circled"></i></button><p><strong>Success!</strong> Data Berhasil Dibatalkan!</p></div>');
    redirect('/c_t_ak_faktur_penjualan');
  }


  public function delete($id)
  {
    $this->m_t_ak_faktur_penjualan->delete($id);
    $this->session->set_flashdata('notif', '<div class="alert alert-danger icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="icofont icofont-close-line-circled"></i></button><p><strong>Success!</strong> Data Berhasil Dihapus!</p></div>');
    redirect('/c_t_ak_faktur_penjualan');
  }

  public function date_faktur_penjualan()
  {
    $date_faktur_penjualan = ($this->input->post("date_faktur_penjualan"));
    $this->session->set_userdata('date_faktur_penjualan', $date_faktur_penjualan);
    redirect('/c_t_ak_faktur_penjualan');
  }
  

  function update_enable_edit($id,$sum_total_penjualan,$ppn_logic,$enable_edit)
  {
    $read_select = $this->m_t_ak_faktur_penjualan->select_by_id($id);
    foreach ($read_select as $key => $value) 
    {
      $no_faktur=$value->NO_FAKTUR;
      $date_move = $value->DATE;
      $time_move = $value->TIME;
    }


    
    if($enable_edit==1)
    {
      $created_id = strtotime(date('Y-m-d H:i:s'));
      $coa_id = 0;
      $coa_id_dpp = 'taik';
      $read_select = $this->m_t_ak_faktur_penjualan_print_setting->select_id(1);
      foreach ($read_select as $key => $value) 
      {
        $setting_value=$value->SETTING_VALUE;
      }
      $read_select = $this->m_ak_m_coa->read_coa_id_from_no_akun($setting_value);
      foreach ($read_select as $key => $value) 
      {
        $coa_id_dpp=$value->ID;
        $db_k_id=$value->DB_K_ID;
      }

      if($db_k_id==1)#kode 1 debit / 2 kredit
      {
        $data = array(
        'DATE' => $date_move,
        'TIME' => $time_move,
        'CREATED_BY' => $this->session->userdata('username'),
        'UPDATED_BY' => $this->session->userdata('username'),
        'COA_ID' => $coa_id_dpp,
        'DEBIT' => intval($sum_total_penjualan),
        'KREDIT' => 0,
        'CATATAN' => 'FAKTUR PENJUALAN : '.$no_faktur,
        'DEPARTEMEN' => '0',
        'NO_VOUCER' => $no_faktur,
        'CREATED_ID' => $created_id,
        'CHECKED_ID' => 1,
        'SPECIAL_ID' => 0,
        'COMPANY_ID' => $this->session->userdata('company_id')
        );
      }
      if($db_k_id==2)#kode 1 debit / 2 kredit
      {
        $data = array(
        'DATE' => $date_move,
        'TIME' => $time_move,
        'CREATED_BY' => $this->session->userdata('username'),
        'UPDATED_BY' => $this->session->userdata('username'),
        'COA_ID' => $coa_id_dpp,
        'DEBIT' => 0,
        'KREDIT' => intval($sum_total_penjualan),
        'CATATAN' => 'FAKTUR PENJUALAN : '.$no_faktur,
        'DEPARTEMEN' => '0',
        'NO_VOUCER' => $no_faktur,
        'CREATED_ID' => $created_id,
        'CHECKED_ID' => 1,
        'SPECIAL_ID' => 0,
        'COMPANY_ID' => $this->session->userdata('company_id')
        );
      }
      $this->m_t_ak_jurnal->tambah($data);
      #.....................................................................................done jurnal dpp
      $coa_id_ppn = 0;
      $read_select = $this->m_t_ak_faktur_penjualan_print_setting->select_id(2);
      foreach ($read_select as $key => $value) 
      {
        $setting_value=$value->SETTING_VALUE;
      }
      $read_select = $this->m_ak_m_coa->read_coa_id_from_no_akun($setting_value);
      foreach ($read_select as $key => $value) 
      {
        $coa_id_ppn=$value->ID;
        $db_k_id=$value->DB_K_ID;
      }
      $ppn=0;
      if($ppn_logic==1)
      {
        $ppn = (intval($sum_total_penjualan) * 10)/100;
      }
      if($db_k_id==1)#kode 1 debit / 2 kredit
      {
        $data = array(
        'DATE' => $date_move,
        'TIME' => $time_move,
        'CREATED_BY' => $this->session->userdata('username'),
        'UPDATED_BY' => $this->session->userdata('username'),
        'COA_ID' => $coa_id_ppn,
        'DEBIT' => intval($ppn),
        'KREDIT' => 0,
        'CATATAN' => 'FAKTUR PENJUALAN : '.$no_faktur,
        'DEPARTEMEN' => '0',
        'NO_VOUCER' => $no_faktur,
        'CREATED_ID' => $created_id,
        'CHECKED_ID' => 1,
        'SPECIAL_ID' => 0,
        'COMPANY_ID' => $this->session->userdata('company_id')
        );
      }
      if($db_k_id==2)#kode 1 debit / 2 kredit
      {
        $data = array(
        'DATE' => $date_move,
        'TIME' => $time_move,
        'CREATED_BY' => $this->session->userdata('username'),
        'UPDATED_BY' => $this->session->userdata('username'),
        'COA_ID' => $coa_id_ppn,
        'DEBIT' => 0,
        'KREDIT' => intval($ppn),
        'CATATAN' => 'FAKTUR PENJUALAN : '.$no_faktur,
        'DEPARTEMEN' => '0',
        'NO_VOUCER' => $no_faktur,
        'CREATED_ID' => $created_id,
        'CHECKED_ID' => 1,
        'SPECIAL_ID' => 0,
        'COMPANY_ID' => $this->session->userdata('company_id')
        );
      }
      $this->m_t_ak_jurnal->tambah($data);
      #.....................................................................................done jurnal dpp

      $coa_id_piutang_dagang = 0;
      $read_select = $this->m_t_ak_faktur_penjualan_print_setting->select_id(3);
      foreach ($read_select as $key => $value) 
      {
        $setting_value=$value->SETTING_VALUE;
      }
      $read_select = $this->m_ak_m_coa->read_coa_id_from_no_akun($setting_value);
      foreach ($read_select as $key => $value) 
      {
        $coa_id_piutang_dagang=$value->ID;
        $db_k_id=$value->DB_K_ID;
      }
      $piutang_dagang = intval($sum_total_penjualan) + intval($ppn);
      if($db_k_id==1)#kode 1 debit / 2 kredit
      {
        $data = array(
        'DATE' => $date_move,
        'TIME' => $time_move,
        'CREATED_BY' => $this->session->userdata('username'),
        'UPDATED_BY' => $this->session->userdata('username'),
        'COA_ID' => $coa_id_piutang_dagang,
        'DEBIT' => intval($piutang_dagang),
        'KREDIT' => 0,
        'CATATAN' => 'FAKTUR PENJUALAN : '.$no_faktur,
        'DEPARTEMEN' => '0',
        'NO_VOUCER' => $no_faktur,
        'CREATED_ID' => $created_id,
        'CHECKED_ID' => 1,
        'SPECIAL_ID' => 0,
        'COMPANY_ID' => $this->session->userdata('company_id')
        );
      }
      if($db_k_id==2)#kode 1 debit / 2 kredit
      {
        $data = array(
        'DATE' => $date_move,
        'TIME' => $time_move,
        'CREATED_BY' => $this->session->userdata('username'),
        'UPDATED_BY' => $this->session->userdata('username'),
        'COA_ID' => $coa_id_piutang_dagang,
        'DEBIT' => 0,
        'KREDIT' => intval($piutang_dagang),
        'CATATAN' => 'FAKTUR PENJUALAN : '.$no_faktur,
        'DEPARTEMEN' => '0',
        'NO_VOUCER' => $no_faktur,
        'CREATED_ID' => $created_id,
        'CHECKED_ID' => 1,
        'SPECIAL_ID' => 0,
        'COMPANY_ID' => $this->session->userdata('company_id')
        );
      }
      $this->m_t_ak_jurnal->tambah($data);
      #.....................................................................................done jurnal dpp
    }

    $data = array(
      'ENABLE_EDIT' => 0
    );

    $this->m_t_ak_faktur_penjualan->update($data, $id);

    $this->session->set_flashdata('notif', "<div class='alert alert-info icons-alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'> <i class='icofont icofont-close-line-circled'></i></button><p><strong>Data Berhasil Masuk Jurnal</strong></p></div>");

    
    #$this->render_backend('template/backend/pages/laporan_pdf/faktur_penjualan_print/3', $data);
    #redirect('/laporan_pdf/faktur_penjualan_print/3');
    redirect('c_t_ak_faktur_penjualan');
  }

  function tambah()
  {
    $pelanggan_id = intval($this->input->post("pelanggan_id"));
    $keterangan = '';


    $ppn = $this->input->post("ppn");
    $pph = $this->input->post("pph");

    if($ppn==null)
    {
      $ppn = false;
    }
    if($pph==null)
    {
      $pph = false;
    }

    $date = ($this->input->post("date"));

    $date_faktur_penjualan = $date;
    $this->session->set_userdata('date_faktur_penjualan', $date_faktur_penjualan);


    if($pelanggan_id!=0)
    {

      $logic_no_faktur = 0;
      

      $inv_int = 0;
      $read_select = $this->m_t_ak_faktur_penjualan->select_inv_int();
      foreach ($read_select as $key => $value) 
      {
        $inv_int = intval($value->INV_INT)+1;
      }

      $read_select = $this->m_t_m_d_company->select_by_company_id();
      foreach ($read_select as $key => $value) 
      {
        $inv_faktur_penjualan = $value->INV_FAKTUR_PENJUALAN;
        $inv_terima_pelanggan = $value->INV_TERIMA_PELANGGAN;
      }

      $live_inv = $inv_faktur_penjualan.date('y-m').'.'.sprintf('%05d', $inv_int);






      if($logic_no_faktur == 0)
      {
        $data = array(
          'DATE' => $date,
          'TIME' => date('H:i:s'),
          'PELANGGAN_ID' => $pelanggan_id,
          'CREATED_BY' => $this->session->userdata('username'),
          'UPDATED_BY' => $this->session->userdata('username'),
          'KETERANGAN' => $keterangan,
          'NO_FAKTUR' => $live_inv,
          'ENABLE_EDIT' => 1,
          'TOTAL_PEMBAYARAN' => 0,
          'PAYMENT_T' =>0,
          'INV_INT' =>$inv_int,
          'COMPANY_ID' => $this->session->userdata('company_id'),
          'PPN' => $ppn,
          'PPH' => $pph
          
        );

        $this->m_t_ak_faktur_penjualan->tambah($data);

        $this->session->set_flashdata('notif', '<div class="alert alert-info icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <i class="icofont icofont-close-line-circled"></i></button><p><strong>Data Berhasil Ditambahkan!</strong></p></div>');
      }
      
    }
    if($pelanggan_id==0)
    {
      $this->session->set_flashdata('notif', '<div class="alert alert-danger icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="icofont icofont-close-line-circled"></i></button><p><strong>Gagal!</strong> Pelanggan Tidak Boleh Kosong!</p></div>');
    }
    
    redirect('c_t_ak_faktur_penjualan');
  }






  public function edit_action()
  {
    $id = $this->input->post("id");
    $ppn = $this->input->post("ppn");
    $pph = $this->input->post("pph");


    if($ppn==null)
    {
      $ppn = false;
    }
    if($pph==null)
    {
      $pph = false;
    }

//Dikiri nama kolom pada database, dikanan hasil yang kita tangkap nama formnya.
    $data = array(
      'PPN' => $ppn,
      'PPH' => $pph
    );
    $this->m_t_ak_faktur_penjualan->update($data, $id);
    $this->session->set_flashdata('notif', '<div class="alert alert-info icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <i class="icofont icofont-close-line-circled"></i></button><p><strong>Data Berhasil Diupdate!</strong></p></div>');
    redirect('/c_t_ak_faktur_penjualan');
  }

}
