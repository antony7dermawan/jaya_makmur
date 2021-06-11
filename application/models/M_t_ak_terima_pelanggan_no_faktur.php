<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_t_ak_terima_pelanggan_no_faktur extends CI_Model {
    
    

public function update($data, $id)
{
    $this->db->where('ID', $id);
    return $this->db->update('T_AK_TERIMA_PELANGGAN_NO_FAKTUR', $data);
    
}



public function select_by_id($id)
  {
    $this->db->select("T_AK_TERIMA_PELANGGAN_NO_FAKTUR.ID");
    $this->db->select("T_AK_TERIMA_PELANGGAN_NO_FAKTUR.FAKTUR_PENJUALAN_ID");
    $this->db->select("T_AK_TERIMA_PELANGGAN_NO_FAKTUR.TOTAL_PENJUALAN");
    $this->db->select("T_AK_FAKTUR_PENJUALAN.PAYMENT_T");

    $this->db->select("T_AK_FAKTUR_PENJUALAN.NO_FAKTUR");
    $this->db->select("T_AK_FAKTUR_PENJUALAN.DATE");
    $this->db->select("T_AK_FAKTUR_PENJUALAN.TOTAL_PEMBAYARAN");


    $this->db->from('T_AK_TERIMA_PELANGGAN_NO_FAKTUR');

    $this->db->join('T_AK_FAKTUR_PENJUALAN', 'T_AK_FAKTUR_PENJUALAN.ID = T_AK_TERIMA_PELANGGAN_NO_FAKTUR.FAKTUR_PENJUALAN_ID', 'left');


    
    $this->db->where('T_AK_TERIMA_PELANGGAN_NO_FAKTUR.ID', $id);



    $akun = $this->db->get ();
    return $akun->result ();
  }

  public function select($id)
  {
    $this->db->select("T_AK_TERIMA_PELANGGAN_NO_FAKTUR.ID");
    $this->db->select("T_AK_TERIMA_PELANGGAN_NO_FAKTUR.FAKTUR_PENJUALAN_ID");
    $this->db->select("T_AK_TERIMA_PELANGGAN_NO_FAKTUR.TOTAL_PENJUALAN");

    
    $this->db->select("T_AK_FAKTUR_PENJUALAN.PAYMENT_T");

    $this->db->select("T_AK_FAKTUR_PENJUALAN.NO_FAKTUR");
    $this->db->select("T_AK_FAKTUR_PENJUALAN.DATE");
    $this->db->select("T_AK_FAKTUR_PENJUALAN.TOTAL_PEMBAYARAN");


    $this->db->from('T_AK_TERIMA_PELANGGAN_NO_FAKTUR');

    $this->db->join('T_AK_FAKTUR_PENJUALAN', 'T_AK_FAKTUR_PENJUALAN.ID = T_AK_TERIMA_PELANGGAN_NO_FAKTUR.FAKTUR_PENJUALAN_ID', 'left');


    
    $this->db->where('T_AK_TERIMA_PELANGGAN_NO_FAKTUR.TERIMA_PELANGGAN_ID', $id);


    $this->db->order_by("ID", "asc");

    $akun = $this->db->get ();
    return $akun->result ();
  }


  public function delete_id($id)
  {
    $this->db->where('FAKTUR_PENJUALAN_ID',$id);
    $this->db->delete('T_AK_FAKTUR_PENJUALAN_RINCIAN');
  }

  public function delete($id)
  {
    $this->db->where('ID',$id);
    $this->db->delete('T_AK_TERIMA_PELANGGAN_NO_FAKTUR');
  }

  function tambah($data)
  {
        $this->db->insert('T_AK_TERIMA_PELANGGAN_NO_FAKTUR', $data);
        return TRUE;
  }

}


