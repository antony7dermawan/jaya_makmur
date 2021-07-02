<?php
defined('BASEPATH') or exit('No direct script access allowed');


class C_t_ak_terima_pelanggan extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();

    $this->load->model('m_t_ak_terima_pelanggan');
    $this->load->model('m_t_ak_faktur_penjualan');
    $this->load->model('m_t_t_t_penjualan');


    $this->load->model('m_t_m_d_company');
    $this->load->model('m_t_m_d_pelanggan');
    
    $this->load->model('m_ak_m_coa');
    $this->load->model('m_t_ak_terima_pelanggan_print_setting');
    $this->load->model('m_t_ak_jurnal');
    $this->load->model('m_t_ak_terima_pelanggan_metode_bayar');
    $this->load->model('m_t_ak_terima_pelanggan_diskon');


    
  }

  public function index()
  {


    $this->session->set_userdata('t_m_d_pelanggan_delete_logic', '0');
    
    if($this->session->userdata('date_terima_pelanggan')=='')
    {
      $date_terima_pelanggan = date('Y-m-d');
      $this->session->set_userdata('date_terima_pelanggan', $date_terima_pelanggan);
    }

    $data = [
      "c_t_ak_terima_pelanggan" => $this->m_t_ak_terima_pelanggan->select($this->session->userdata('date_terima_pelanggan')),
     
      "c_t_m_d_pelanggan" => $this->m_t_m_d_pelanggan->select(),
     
      "title" => "Transaksi Terima Pelanggan",
      "description" => "form terima pelanggan"
    ];
    $this->render_backend('template/backend/pages/t_ak_terima_pelanggan', $data);
  }


  public function undo($id)
  {

    $data = array(
      'ENABLE_EDIT' => 1
    );
    $this->m_t_ak_terima_pelanggan->update($data, $id);
    
    $read_select = $this->m_t_ak_terima_pelanggan->select_by_id($id);
    foreach ($read_select as $key => $value) 
    {
      $no_form = $value->NO_FORM;
    }
    $this->m_t_ak_jurnal->delete_no_voucer($no_form);

    $this->session->set_flashdata('notif', '<div class="alert alert-danger icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="icofont icofont-close-line-circled"></i></button><p><strong>Success!</strong> Data Berhasil Dibatalkan!</p></div>');
    redirect('/c_t_ak_terima_pelanggan');
  }

  public function delete($id)
  {
    $this->m_t_ak_terima_pelanggan->delete($id);
    $this->session->set_flashdata('notif', '<div class="alert alert-danger icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="icofont icofont-close-line-circled"></i></button><p><strong>Success!</strong> Data Berhasil DIhapus!</p></div>');
    redirect('/c_t_ak_terima_pelanggan');
  }

  public function date_terima_pelanggan()
  {
    $date_terima_pelanggan = ($this->input->post("date_terima_pelanggan"));
    $this->session->set_userdata('date_terima_pelanggan', $date_terima_pelanggan);
    redirect('/c_t_ak_terima_pelanggan');
  }


  function update_enable_edit($id, $sum_total_penjualan, $sum_jumlah, $sum_diskon, $enable_edit,$sum_adm_bank,$sum_payment_t)
  {
    $read_select = $this->m_t_ak_terima_pelanggan->select_by_id($id);
    foreach ($read_select as $key => $value) 
    {
      $no_form = $value->NO_FORM;
      $date_move = $value->DATE;
      $time_move = $value->TIME;
    }

    if ($enable_edit == 1) 
    {
      $created_id = strtotime(date('Y-m-d H:i:s'));
      $coa_id = 0;

      // metode bayar 
      
      $sum_all_payment = 0;
      $read_select = $this->m_t_ak_terima_pelanggan_metode_bayar->select($id);
      foreach ($read_select as $key => $value) 
      {
        $coa_id = $value->COA_ID;
        $jumlah_per_bank = $value->JUMLAH;

        $read_select_in = $this->m_ak_m_coa->select_coa_id($coa_id);
        foreach ($read_select_in as $key_in => $value_in) 
        {
          $db_k_id = $value_in->DB_K_ID;
          if ($db_k_id == 1) #kode 1 debit / 2 kredit
          {
            $data = array(
              'DATE' => $date_move,
              'TIME' => $time_move,
              'CREATED_BY' => $this->session->userdata('username'),
              'UPDATED_BY' => $this->session->userdata('username'),
              'COA_ID' => $coa_id,
              'DEBIT' => round($jumlah_per_bank),
              'KREDIT' => 0,
              'CATATAN' => 'Pembayaran TBS : ' . $no_form,
              'DEPARTEMEN' => '0',
              'NO_VOUCER' => $no_form,
              'CREATED_ID' => $created_id,
              'CHECKED_ID' => 1,
              'SPECIAL_ID' => 0
            );
          }
          if ($db_k_id == 2) #kode 1 debit / 2 kredit
          {
            $data = array(
              'DATE' => $date_move,
              'TIME' => $time_move,
              'CREATED_BY' => $this->session->userdata('username'),
              'UPDATED_BY' => $this->session->userdata('username'),
              'COA_ID' => $coa_id,
              'DEBIT' => 0,
              'KREDIT' => round($jumlah_per_bank),
              'CATATAN' => 'Pembayaran TBS : ' . $no_form,
              'DEPARTEMEN' => '0',
              'NO_VOUCER' => $no_form,
              'CREATED_ID' => $created_id,
              'CHECKED_ID' => 1,
              'SPECIAL_ID' => 0
            );
          }
          $this->m_t_ak_jurnal->tambah($data);
          $sum_all_payment = floatval($sum_all_payment) + floatval($jumlah_per_bank);
        }
      }

      #.....................................................................................done 


       // diskon
      
      $sum_all_diskon = 0;
      $read_select = $this->m_t_ak_terima_pelanggan_diskon->select($id);
      foreach ($read_select as $key => $value) 
      {
        $coa_id = $value->COA_ID;
        $jumlah_per_diskon = $value->JUMLAH;

        $read_select_in = $this->m_ak_m_coa->select_coa_id($coa_id);
        foreach ($read_select_in as $key_in => $value_in) 
        {
          $db_k_id = $value_in->DB_K_ID;
          if ($db_k_id == 1) #kode 1 debit / 2 kredit
          {
            $data = array(
              'DATE' => $date_move,
              'TIME' => $time_move,
              'CREATED_BY' => $this->session->userdata('username'),
              'UPDATED_BY' => $this->session->userdata('username'),
              'COA_ID' => $coa_id,
              'DEBIT' => round($jumlah_per_diskon),
              'KREDIT' => 0,
              'CATATAN' => 'Pembayaran TBS : ' . $no_form,
              'DEPARTEMEN' => '0',
              'NO_VOUCER' => $no_form,
              'CREATED_ID' => $created_id,
              'CHECKED_ID' => 1,
              'SPECIAL_ID' => 0
            );
          }
          if ($db_k_id == 2) #kode 1 debit / 2 kredit
          {
            $data = array(
              'DATE' => $date_move,
              'TIME' => $time_move,
              'CREATED_BY' => $this->session->userdata('username'),
              'UPDATED_BY' => $this->session->userdata('username'),
              'COA_ID' => $coa_id,
              'DEBIT' => 0,
              'KREDIT' => round($jumlah_per_diskon),
              'CATATAN' => 'Pembayaran TBS : ' . $no_form,
              'DEPARTEMEN' => '0',
              'NO_VOUCER' => $no_form,
              'CREATED_ID' => $created_id,
              'CHECKED_ID' => 1,
              'SPECIAL_ID' => 0
            );
          }
          $this->m_t_ak_jurnal->tambah($data);
          $sum_all_diskon = floatval($sum_all_diskon) + floatval($jumlah_per_diskon);
        }
      }

      #.....................................................................................done 




      /*
      $coa_diskon = 0;
      $read_select = $this->m_t_ak_terima_pelanggan_print_setting->select_id(6);
      foreach ($read_select as $key => $value) {
        $setting_value = $value->SETTING_VALUE;
      }
      $read_select = $this->m_ak_m_coa->read_coa_id_from_no_akun($setting_value);
      foreach ($read_select as $key => $value) {
        $coa_diskon = $value->ID;
        $db_k_id = $value->DB_K_ID;
      }

      if ($db_k_id == 1) #kode 1 debit / 2 kredit
      {
        $data = array(
          'DATE' => date('Y-m-d'),
          'TIME' => date('H:i:s'),
          'CREATED_BY' => $this->session->userdata('username'),
          'UPDATED_BY' => $this->session->userdata('username'),
          'COA_ID' => $coa_diskon,
          'DEBIT' => intval($sum_diskon),
          'KREDIT' => 0,
          'CATATAN' => 'FAKTUR PENJUALAN : ' . $no_form,
          'DEPARTEMEN' => '0',
          'NO_VOUCER' => $no_form,
          'CREATED_ID' => $created_id
        );
      }
      if ($db_k_id == 2) #kode 1 debit / 2 kredit
      {
        $data = array(
          'DATE' => date('Y-m-d'),
          'TIME' => date('H:i:s'),
          'CREATED_BY' => $this->session->userdata('username'),
          'UPDATED_BY' => $this->session->userdata('username'),
          'COA_ID' => $coa_diskon,
          'DEBIT' => 0,
          'KREDIT' => intval($sum_diskon),
          'CATATAN' => 'FAKTUR PENJUALAN : ' . $no_form,
          'DEPARTEMEN' => '0',
          'NO_VOUCER' => $no_form,
          'CREATED_ID' => $created_id
        );
      }
      $this->m_t_ak_jurnal->tambah($data);
      #.....................................................................................done
      */


/*   ...........................................................ini pph22 yg auto ganti manual input di diskon
      $coa_pasal_22 = 0;
      $read_select = $this->m_t_ak_terima_pelanggan_print_setting->select_id(6);
      foreach ($read_select as $key => $value) {
        $setting_value = $value->SETTING_VALUE;
      }
      $read_select = $this->m_ak_m_coa->read_coa_id_from_no_akun($setting_value);
      foreach ($read_select as $key => $value) {
        $coa_pasal_22 = $value->ID;
        $db_k_id = $value->DB_K_ID;
      }

      $sum_total_penjualan = $sum_total_penjualan-($sum_payment_t-$sum_jumlah);

      $nilai_pasal_22 = intval(0.25 * floatval($sum_total_penjualan))/100;

      if ($db_k_id == 1) #kode 1 debit / 2 kredit
      {
        $data = array(
          'DATE' => $this->session->userdata('date_terima_pelanggan'),
          'TIME' => date('H:i:s'),
          'CREATED_BY' => $this->session->userdata('username'),
          'UPDATED_BY' => $this->session->userdata('username'),
          'COA_ID' => $coa_pasal_22,
          'DEBIT' => intval($nilai_pasal_22),
          'KREDIT' => 0,
          'CATATAN' => 'Pembayaran TBS : ' . $no_form,
          'DEPARTEMEN' => '0',
          'NO_VOUCER' => $no_form,
          'CREATED_ID' => $created_id,
          'CHECKED_ID' => 1,
          'SPECIAL_ID' => 0
        );
      }
      if ($db_k_id == 2) #kode 1 debit / 2 kredit
      {
        $data = array(
          'DATE' => $this->session->userdata('date_terima_pelanggan'),
          'TIME' => date('H:i:s'),
          'CREATED_BY' => $this->session->userdata('username'),
          'UPDATED_BY' => $this->session->userdata('username'),
          'COA_ID' => $coa_pasal_22,
          'DEBIT' => 0,
          'KREDIT' => intval($nilai_pasal_22),
          'CATATAN' => 'Pembayaran TBS : ' . $no_form,
          'DEPARTEMEN' => '0',
          'NO_VOUCER' => $no_form,
          'CREATED_ID' => $created_id,
          'CHECKED_ID' => 1,
          'SPECIAL_ID' => 0
        );
      }
      $this->m_t_ak_jurnal->tambah($data);
      #.....................................................................................done

*/$nilai_pasal_22=0;



      $coa_id_beban_adm_bank = 0;
      $read_select = $this->m_t_ak_terima_pelanggan_print_setting->select_id(7);
      foreach ($read_select as $key => $value) {
        $setting_value = $value->SETTING_VALUE;
      }
      $read_select = $this->m_ak_m_coa->read_coa_id_from_no_akun($setting_value);
      foreach ($read_select as $key => $value) {
        $coa_id_beban_adm_bank = $value->ID;
        $db_k_id = $value->DB_K_ID;
      }
      $total_adm_bank = floatval($sum_adm_bank);
      if ($db_k_id == 1) #kode 1 debit / 2 kredit
      {
        $data = array(
          'DATE' => $date_move,
          'TIME' => $time_move,
          'CREATED_BY' => $this->session->userdata('username'),
          'UPDATED_BY' => $this->session->userdata('username'),
          'COA_ID' => $coa_id_beban_adm_bank,
          'DEBIT' => round($total_adm_bank),
          'KREDIT' => 0,
          'CATATAN' => 'Pembayaran TBS : ' . $no_form,
          'DEPARTEMEN' => '0',
          'NO_VOUCER' => $no_form,
          'CREATED_ID' => $created_id,
          'CHECKED_ID' => 1,
          'SPECIAL_ID' => 0
        );
      }
      if ($db_k_id == 2) #kode 1 debit / 2 kredit
      {
        $data = array(
          'DATE' => $date_move,
          'TIME' => $time_move,
          'CREATED_BY' => $this->session->userdata('username'),
          'UPDATED_BY' => $this->session->userdata('username'),
          'COA_ID' => $coa_id_beban_adm_bank,
          'DEBIT' => 0,
          'KREDIT' => round($total_adm_bank),
          'CATATAN' => 'Pembayaran TBS : ' . $no_form,
          'DEPARTEMEN' => '0',
          'NO_VOUCER' => $no_form,
          'CREATED_ID' => $created_id,
          'CHECKED_ID' => 1,
          'SPECIAL_ID' => 0
        );
      }
      $this->m_t_ak_jurnal->tambah($data);
      #.....................................................................................done 




      $coa_id_beban_selisih_pembulatan = 0;
      $read_select = $this->m_t_ak_terima_pelanggan_print_setting->select_id(8);
      foreach ($read_select as $key => $value) {
        $setting_value = $value->SETTING_VALUE;
      }
      $read_select = $this->m_ak_m_coa->read_coa_id_from_no_akun($setting_value);
      foreach ($read_select as $key => $value) {
        $coa_id_beban_selisih_pembulatan = $value->ID;
        $db_k_id = $value->DB_K_ID;
      }


      $total_transaksi = floatval($sum_all_payment) + floatval($nilai_pasal_22) + floatval($total_adm_bank) +  floatval($sum_all_diskon);

      $up_total_transaksi = ceil($total_transaksi);




      $total_beban_selisih_pembulatan = floatval($up_total_transaksi) - floatval($total_transaksi);
      if ($db_k_id == 1) #kode 1 debit / 2 kredit
      {
        $data = array(
          'DATE' => $date_move,
          'TIME' => $time_move,
          'CREATED_BY' => $this->session->userdata('username'),
          'UPDATED_BY' => $this->session->userdata('username'),
          'COA_ID' => $coa_id_beban_selisih_pembulatan,
          'DEBIT' => round($total_beban_selisih_pembulatan),
          'KREDIT' => 0,
          'CATATAN' => 'Pembayaran TBS : ' . $no_form,
          'DEPARTEMEN' => '0',
          'NO_VOUCER' => $no_form,
          'CREATED_ID' => $created_id,
          'CHECKED_ID' => 1,
          'SPECIAL_ID' => 0
        );
      }
      if ($db_k_id == 2) #kode 1 debit / 2 kredit
      {
        $data = array(
          'DATE' => $date_move,
          'TIME' => $time_move,
          'CREATED_BY' => $this->session->userdata('username'),
          'UPDATED_BY' => $this->session->userdata('username'),
          'COA_ID' => $coa_id_beban_selisih_pembulatan,
          'DEBIT' => 0,
          'KREDIT' => round($total_beban_selisih_pembulatan),
          'CATATAN' => 'Pembayaran TBS : ' . $no_form,
          'DEPARTEMEN' => '0',
          'NO_VOUCER' => $no_form,
          'CREATED_ID' => $created_id,
          'CHECKED_ID' => 1,
          'SPECIAL_ID' => 0
        );
      }
      $this->m_t_ak_jurnal->tambah($data);
      #.....................................................................................done 





      $coa_piutang_dagang = 0;
      $read_select = $this->m_t_ak_terima_pelanggan_print_setting->select_id(9);
      foreach ($read_select as $key => $value) {
        $setting_value = $value->SETTING_VALUE;
      }
      $read_select = $this->m_ak_m_coa->read_coa_id_from_no_akun($setting_value);
      foreach ($read_select as $key => $value) {
        $coa_piutang_dagang = $value->ID;
        $db_k_id = $value->DB_K_ID;
      }





      $total_piutang_dagang = floatval($up_total_transaksi);

      /*
      if ($db_k_id == 1) #kode 1 debit / 2 kredit
      {
        $data = array(
          'DATE' => date('Y-m-d'),
          'TIME' => date('H:i:s'),
          'CREATED_BY' => $this->session->userdata('username'),
          'UPDATED_BY' => $this->session->userdata('username'),
          'COA_ID' => $coa_piutang_dagang,
          'DEBIT' => intval($total_piutang_dagang),
          'KREDIT' => 0,
          'CATATAN' => 'Pembayaran TBS : ' . $no_form,
          'DEPARTEMEN' => '0',
          'NO_VOUCER' => $no_form,
          'CREATED_ID' => $created_id
        );
      }
      */
      
        $data = array(
          'DATE' => $date_move,
          'TIME' => $time_move,
          'CREATED_BY' => $this->session->userdata('username'),
          'UPDATED_BY' => $this->session->userdata('username'),
          'COA_ID' => $coa_piutang_dagang,
          'DEBIT' => 0,
          'KREDIT' => round($total_piutang_dagang),
          'CATATAN' => 'Pembayaran TBS : ' . $no_form,
          'DEPARTEMEN' => '0',
          'NO_VOUCER' => $no_form,
          'CREATED_ID' => $created_id,
          'CHECKED_ID' => 1,
          'SPECIAL_ID' => 0
        );
      
      $this->m_t_ak_jurnal->tambah($data);
      #.....................................................................................done 




    }

    $data = array(
      'ENABLE_EDIT' => 0
    );

    $this->m_t_ak_terima_pelanggan->update($data, $id);

    $this->session->set_flashdata('notif', "<div class='alert alert-info icons-alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'> <i class='icofont icofont-close-line-circled'></i></button><p><strong>Jurnal Berhasil Dibuat</strong></p></div>");


    #$this->render_backend('template/backend/pages/laporan_pdf/faktur_penjualan_print/3', $data);
    #redirect('/laporan_pdf/faktur_penjualan_print/3');
    redirect('c_t_ak_terima_pelanggan');
  }






  function tambah()
  {
    $pelanggan_id = intval($this->input->post("pelanggan_id"));
    $keterangan = substr($this->input->post("ket"), 0, 200);

    

    $no_form = substr($this->input->post("no_form"), 0, 50);
    $date = ($this->input->post("date"));


    $date_terima_pelanggan = $date;
    $this->session->set_userdata('date_terima_pelanggan', $date_terima_pelanggan);



    if($pelanggan_id!=0)
    {
      $logic_no_form = 0;

      $inv_int = 0;
      $read_select = $this->m_t_ak_terima_pelanggan->select_inv_int();
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

      $live_inv = $inv_terima_pelanggan.date('y-m').'.'.sprintf('%05d', $inv_int);



      $read_select = $this->m_t_ak_terima_pelanggan->read_no_form($live_inv);
      foreach ($read_select as $key => $value) 
      {
        $logic_no_form = 1;
        $this->session->set_flashdata('notif', '<div class="alert alert-danger icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="icofont icofont-close-line-circled"></i></button><p><strong>Gagal!</strong> No Form Sudah Digunakan!</p></div>');
      }
      if($logic_no_form == 0)
      {
        $data = array(
          'DATE' => $date,
          'TIME' => date('H:i:s'),
          'KET' => $keterangan,
          'CREATED_BY' => $this->session->userdata('username'),
          'UPDATED_BY' => $this->session->userdata('username'),
          'ENABLE_EDIT' => 1,
          'NO_FORM' => $live_inv,
          'PELANGGAN_ID' => $pelanggan_id,
          'INV_INT' =>$inv_int,
          'COMPANY_ID' => $this->session->userdata('company_id')
        );

        $this->m_t_ak_terima_pelanggan->tambah($data);

        $this->session->set_flashdata('notif', '<div class="alert alert-info icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <i class="icofont icofont-close-line-circled"></i></button><p><strong>Data Berhasil Ditambahkan!</strong></p></div>');
      }

      
    }
    if($pelanggan_id==0)
    {
      $this->session->set_flashdata('notif', '<div class="alert alert-danger icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="icofont icofont-close-line-circled"></i></button><p><strong>Gagal!</strong> No Faktur Tidak Boleh Kosong!</p></div>');
    }
    
    redirect('c_t_ak_terima_pelanggan');
  }






  public function edit_action()
  {
    $id = $this->input->post("id");
    $no_form = $this->input->post("no_form");



    //Dikiri nama kolom pada database, dikanan hasil yang kita tangkap nama formnya.
    $data = array(
      'NO_FORM' => $no_form
    );
    $this->m_t_ak_terima_pelanggan->update($data, $id);
    $this->session->set_flashdata('notif', '<div class="alert alert-info icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <i class="icofont icofont-close-line-circled"></i></button><p><strong>Data Berhasil Diupdate!</strong></p></div>');
    redirect('/c_t_ak_terima_pelanggan');
  }
}
