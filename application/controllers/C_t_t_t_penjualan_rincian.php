<?php
defined('BASEPATH') or exit('No direct script access allowed');


class C_t_t_t_penjualan_rincian extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();

    $this->load->model('m_t_t_t_penjualan');

    $this->load->model('m_t_t_t_penjualan_rincian'); 

    $this->load->model('m_t_t_t_pembelian');
    

    $this->load->model('m_t_m_d_company');
    $this->load->model('m_t_m_d_barang');
    
    $this->load->model('m_t_t_t_pembelian_rincian'); 
    $this->load->model('m_t_ak_faktur_penjualan');
  }

  public function index($penjualan_id)
  {
    $this->session->set_userdata('t_t_t_penjualan_delete_logic', '1');
    
    $this->session->set_userdata('t_m_d_barang_delete_logic', '0');

    $this->session->set_userdata('master_barang_kategori_id', '0');
    $this->session->set_userdata('master_barang_company_id', $this->session->userdata('company_id'));

    $read_select = $this->m_t_t_t_penjualan->select_by_id($penjualan_id);
    foreach ($read_select as $key => $value) 
    {
      $pelanggan_id = $value->PELANGGAN_ID;
      $date_penjualan = $value->DATE;
    }


    $data = [
      
      "c_t_t_t_penjualan_rincian" => $this->m_t_t_t_penjualan_rincian->select($penjualan_id),

      "c_t_t_t_penjualan_by_id" => $this->m_t_t_t_penjualan->select_by_id($penjualan_id),


      "read_suspend" => $this->m_t_ak_faktur_penjualan->select_read_suspend($pelanggan_id,$date_penjualan),




      "c_t_m_d_barang" => $this->m_t_m_d_barang->select(),
      
      "penjualan_id" => $penjualan_id,
      "title" => "Transaksi Penjualan",
      "description" => "form Penjualan"
    ];
    $this->render_backend('template/backend/pages/t_t_t_penjualan_rincian', $data);
  }



  public function delete($id,$penjualan_id)
  {
    $data = array(
        'UPDATED_BY' => $this->session->userdata('username'),
        'MARK_FOR_DELETE' => TRUE
    );
    $this->m_t_t_t_penjualan_rincian->update($data, $id);

    $read_select = $this->m_t_t_t_penjualan_rincian->select_by_id($id);
    foreach ($read_select as $key => $value) 
    {
      $r_qty_jual = $value->QTY;
      $r_barang_id_jual = $value->BARANG_ID;
    }

    






      $vivo_qty = $r_qty_jual;
      $read_select = $this->m_t_t_t_pembelian_rincian->select_sisa_qty($r_barang_id_jual);
      foreach ($read_select as $key => $value) 
      {
        if(($vivo_qty+$value->SISA_QTY) <= $value->QTY)
        {
          $live_sisa_qty = $value->SISA_QTY + $vivo_qty;
          $data = array(
            'SISA_QTY' => $live_sisa_qty
          );
          $this->m_t_t_t_pembelian_rincian->update($data,$value->ID);
          $vivo_qty = 0; 
        }

        if(($vivo_qty+$value->SISA_QTY) > $value->QTY)
        {
          $live_sisa_qty = $value->QTY;
          $data = array(
            'SISA_QTY' => $live_sisa_qty
          );
          $this->m_t_t_t_pembelian_rincian->update($data,$value->ID);
          $vivo_qty = $vivo_qty - $value->SISA_QTY;
        }
      }


    $this->session->set_flashdata('notif', '<div class="alert alert-danger icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="icofont icofont-close-line-circled"></i></button><p><strong>Success!</strong> Data Berhasil DIhapus!</p></div>');

    redirect('c_t_t_t_penjualan_rincian/index/' . $penjualan_id);
  }


 

  function tambah($penjualan_id)
  {
    $barang_id = intval($this->input->post("barang_id"));
    $qty = floatval($this->input->post("qty"));
    $diskon_p_1 = floatval($this->input->post("diskon_p_1"));
    $diskon_p_2 = floatval($this->input->post("diskon_p_2"));
    $diskon_harga = floatval($this->input->post("diskon_harga"));
    $harga_jual = floatval($this->input->post("harga_jual"));

    /* harga db
    $read_select = $this->m_t_m_d_barang->select_by_id($barang_id);
    foreach ($read_select as $key => $value) 
    {
      $harga_jual = $value->HARGA_JUAL;
    }
    */

    $sub_total_1 = ($harga_jual) - (($harga_jual * $diskon_p_1)/100);
    $sub_total_2 = ($sub_total_1) - (($sub_total_1 * $diskon_p_2)/100);
    $sub_total = ($sub_total_2 - $diskon_harga) * $qty;



    $sisa_qty_tt = 0;
    $read_select = $this->m_t_t_t_pembelian_rincian->select_sisa_qty_for_1_barang_id($barang_id);
    foreach ($read_select as $key => $value) 
    {
      $sisa_qty_tt = $value->SUM_SISA_QTY;
    }


    


    

    if($barang_id!=0 and $qty<=$sisa_qty_tt)
    {
      $data = array(
        'PENJUALAN_ID' => $penjualan_id,
        'BARANG_ID' => $barang_id,
        'QTY' => $qty,
        'SISA_QTY' => $qty,


        'DISKON_P_1' => $diskon_p_1,
        'DISKON_P_2' => $diskon_p_2,
        'DISKON_HARGA' => $diskon_harga,

        'HARGA' => $harga_jual,
        'SUB_TOTAL' => $sub_total,
        'SISA_QTY_TT' => $sisa_qty_tt,
        'SPECIAL_CASE_ID' => 0,
        'PEMBELIAN_RINCIAN_ID' => $barang_id,
        
        'CREATED_BY' => $this->session->userdata('username'),
        'UPDATED_BY' => '',
        'MARK_FOR_DELETE' => FALSE,
        'COMPANY_ID' => $this->session->userdata('company_id')
        
      );

      $this->m_t_t_t_penjualan_rincian->tambah($data);

      //..............................................kurangin stok pembelian rincian
      $vivo_qty = $qty;
      $read_select = $this->m_t_t_t_pembelian_rincian->select_sisa_qty($barang_id);
      foreach ($read_select as $key => $value) 
      {
        if($vivo_qty<=$value->SISA_QTY)
        {
          $live_sisa_qty = $value->SISA_QTY - $vivo_qty;
          $data = array(
            'SISA_QTY' => $live_sisa_qty
          );
          $this->m_t_t_t_pembelian_rincian->update($data,$value->ID);
          $vivo_qty = 0;
        }

        if($vivo_qty>$value->SISA_QTY)
        {
          $live_sisa_qty = 0;
          $data = array(
            'SISA_QTY' => $live_sisa_qty
          );
          $this->m_t_t_t_pembelian_rincian->update($data,$value->ID);
          $vivo_qty = $vivo_qty - $value->SISA_QTY;
        }
      }

      //..............................................kurangin stok pembelian rincian end



      //.............................................select barang
      $read_select = $this->m_t_m_d_barang->select_by_id($barang_id);
      foreach ($read_select as $key => $value) 
      {
        $minimum_stok = $value->MINIMUM_STOK;
        $maximum_stok = $value->MAXIMUM_STOK;
      }
      //.............................................select barang

      if(($sisa_qty_tt-$qty)<=$minimum_stok) // stok sisa kena minimum stok
      {
        $inv_int=0;
        $read_select = $this->m_t_t_t_pembelian->select_inv_int();
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

        $live_inv = $inv_po.date('y').sprintf('%010d', $inv_int);

        $read_select = $this->m_t_t_t_pembelian_rincian->select_min_harga_barang($barang_id);
        foreach ($read_select as $key => $value) 
        {
          $harga_minimal = $value->HARGA_MIN;
        }

        $read_select = $this->m_t_t_t_pembelian_rincian->select_min_harga_status($barang_id,$harga_minimal);
        foreach ($read_select as $key => $value) 
        {
          $new_supplier_id = $value->SUPPLIER_ID;
        }


        $today = date('Y-m-d');
        $insert_logic = 0;
        $read_select = $this->m_t_t_t_pembelian->select_inv_po_auto_in_that_day($today,$new_supplier_id);
        foreach ($read_select as $key => $value) 
        {
          $new_qty = $maximum_stok - ($sisa_qty_tt-$qty);
          $new_sub_total = $new_qty * $harga_minimal;
          $insert_logic = 1;

          
          $insert_logic_in = 0;
          $read_select = $this->m_t_t_t_pembelian_rincian->select_barang_id_inside_inv_pembelian($value->ID,$barang_id);
          foreach ($read_select as $key => $value) 
          {
            $insert_logic_in = 1;
          }

          if($insert_logic_in==0)
          {
            $data = array(
              'PEMBELIAN_ID' => $value->ID,
              'BARANG_ID' => $barang_id,
              'QTY' => $new_qty,
              'SISA_QTY_RB' => $new_qty,
              'SISA_QTY' => $new_qty,
              'HARGA' => $harga_minimal,
              'SUB_TOTAL' => $new_sub_total,
              'SISA_QTY_TT' => $sisa_qty_tt,
              'SPECIAL_CASE_ID' => 20, //nol kode barang indent
              'SUPPLIER_ID' => $new_supplier_id,
              'CREATED_BY' => $this->session->userdata('username'),
              'UPDATED_BY' => '',
              'MARK_FOR_DELETE' => FALSE,
              'COMPANY_ID' => $this->session->userdata('company_id')
            );

            $this->m_t_t_t_pembelian_rincian->tambah($data);
          }
            
        }
        
        if($insert_logic==0) //oke untuk insert inv PO AUTO BARU
        {
          $data = array(
            'DATE' => date('Y-m-d'),
            'TIME' => date('H:i:s'),
            'NEW_DATE' => date('Y-m-d'),
            'INV' => $live_inv,
            'INV_INT' => $inv_int,
            'COMPANY_ID' => $this->session->userdata('company_id'),
            'PAYMENT_METHOD_ID' => 2, //kredit
            'SUPPLIER_ID' => $new_supplier_id,
            'KET' => 'PO-AUTO',
            'CREATED_BY' => $this->session->userdata('username'),
            'UPDATED_BY' => '',
            'MARK_FOR_DELETE' => FALSE,
            'PRINTED' => FALSE,
            'INV_SUPPLIER' => '',
            'T_STATUS' => 20, //ini kode po auto
            'TABLE_CODE' => 'PEMBELIAN'
          );

          $this->m_t_t_t_pembelian->tambah($data);

          $new_qty = $maximum_stok - ($sisa_qty_tt-$qty);
          $new_sub_total = $new_qty * $harga_minimal;

          $read_select = $this->m_t_t_t_pembelian->select_inv_po_auto_in_that_day($today,$new_supplier_id);
          foreach ($read_select as $key => $value) 
          {
            $data = array(
              'PEMBELIAN_ID' => $value->ID,
              'BARANG_ID' => $barang_id,
              'QTY' => $new_qty,
              'SISA_QTY_RB' => $new_qty,
              'SISA_QTY' => $new_qty,
              'HARGA' => $harga_minimal,
              'SUB_TOTAL' => $new_sub_total,
              'SISA_QTY_TT' => $sisa_qty_tt,
              'SPECIAL_CASE_ID' => 20, //nol kode barang indent
              'SUPPLIER_ID' => $new_supplier_id,
              'CREATED_BY' => $this->session->userdata('username'),
              'UPDATED_BY' => '',
              'MARK_FOR_DELETE' => FALSE,
              'COMPANY_ID' => $this->session->userdata('company_id')
            );

            $this->m_t_t_t_pembelian_rincian->tambah($data);
          }

          

        }//oke untuk insert inv PO AUTO BARU end
        $this->session->set_flashdata('notif', '<div class="alert alert-info icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <i class="icofont icofont-close-line-circled"></i></button><p><strong>Jumlah Beririsan Dengan Minimum Stok!</strong></p></div>');
      }// stok sisa kena minimum stok end

      if(($sisa_qty_tt-$qty)>$minimum_stok) // stok sisa kena minimum stok
      {
        $this->session->set_flashdata('notif', '<div class="alert alert-info icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <i class="icofont icofont-close-line-circled"></i></button><p><strong>Data Berhasil Ditambahkan!</strong></p></div>');
      }
      
      

      
    }

    else
    {
      $this->session->set_flashdata('notif', '<div class="alert alert-danger icons-alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="icofont icofont-close-line-circled"></i></button><p><strong>Gagal!</strong> Data Tidak Lengkap/Qty > Limit!</p></div>');
    }
    

    
    redirect('c_t_t_t_penjualan_rincian/index/'.$penjualan_id);
  }




}