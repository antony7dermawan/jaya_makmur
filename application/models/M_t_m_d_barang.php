<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_t_m_d_barang extends CI_Model {
    
   

public function update($data, $id)
{
    $this->db->where('ID', $id);
    return $this->db->update('T_M_D_BARANG', $data);
}

public function select_id($id)
{
  $this->db->select('ID');
  $this->db->from('T_M_D_BARANG');
  $this->db->where('BARANG', $id);
  $akun = $this->db->get ();
  return $akun->result ();
}


public function select_by_kategori($kategori_id)
{
  $this->db->select('BARANG_ID');
  $this->db->from('T_M_D_BARANG');
  $this->db->where('KATEGORI_ID', $kategori_id);
  $this->db->where('MARK_FOR_DELETE',FALSE);

  $this->db->where('COMPANY_ID',$this->session->userdata('company_id'));
  $this->db->order_by("BARANG", "asc");
  $akun = $this->db->get ();
  return $akun->result ();
}

public function select_by_id($barang_id)
{
  $this->db->select('*');
  $this->db->from('T_M_D_BARANG');
  $this->db->where('BARANG_ID', $barang_id);
  $this->db->where('COMPANY_ID',$this->session->userdata('company_id'));
  $akun = $this->db->get ();
  return $akun->result ();
}



public function select_existing_barang_id_in_company($barang_id,$company_id)
{
  $this->db->select('*');
  $this->db->from('T_M_D_BARANG');
  $this->db->where('BARANG_ID', $barang_id);
  $this->db->where('COMPANY_ID', $company_id);
  $akun = $this->db->get ();
  return $akun->result ();
}

public function select_by_id_id($id)
{
  $this->db->select('*');
  $this->db->from('T_M_D_BARANG');
  $this->db->where('ID', $id);
  $akun = $this->db->get ();
  return $akun->result ();
}





  public function select()
  {
    $this->db->select('T_M_D_BARANG.ID');
    $this->db->select('T_M_D_BARANG.KODE_BARANG');
    $this->db->select('T_M_D_BARANG.BARANG');
    $this->db->select('T_M_D_BARANG.PART_NUMBER');
    $this->db->select('T_M_D_BARANG.MERK_BARANG');
    $this->db->select('T_M_D_BARANG.POSISI');
    $this->db->select('T_M_D_BARANG.MINIMUM_STOK');
    $this->db->select('T_M_D_BARANG.CREATED_BY');
    $this->db->select('T_M_D_BARANG.UPDATED_BY');
    $this->db->select('T_M_D_BARANG.MARK_FOR_DELETE');
    $this->db->select('T_M_D_BARANG.BARANG_ID');
    $this->db->select('T_M_D_BARANG.HARGA_JUAL');
    $this->db->select('T_M_D_BARANG.MAXIMUM_STOK');

    $this->db->select('T_M_D_KATEGORI.KATEGORI');


    $this->db->select('T_M_D_SATUAN.SATUAN');

    $this->db->select('T_M_D_COMPANY.COMPANY');

    $this->db->select('T_M_D_JENIS_BARANG.JENIS_BARANG');


    $this->db->from('T_M_D_BARANG');


    $this->db->join('T_M_D_JENIS_BARANG', 'T_M_D_JENIS_BARANG.ID = T_M_D_BARANG.JENIS_BARANG_ID', 'left');

    $this->db->join('T_M_D_COMPANY', 'T_M_D_COMPANY.ID = T_M_D_BARANG.COMPANY_ID', 'left');

    $this->db->join('T_M_D_KATEGORI', 'T_M_D_KATEGORI.ID = T_M_D_BARANG.KATEGORI_ID', 'left');

    $this->db->join('T_M_D_SATUAN', 'T_M_D_SATUAN.ID = T_M_D_BARANG.SATUAN_ID', 'left');

    if($this->session->userdata('master_barang_kategori_id')!=0)
    {
      $this->db->where('T_M_D_BARANG.KATEGORI_ID',$this->session->userdata('master_barang_kategori_id'));
    }

    $this->db->where('T_M_D_BARANG.COMPANY_ID',$this->session->userdata('master_barang_company_id'));


    if($this->session->userdata('t_m_d_barang_delete_logic')==0)
    {
      $this->db->where('T_M_D_BARANG.MARK_FOR_DELETE',FALSE);
    }

    
    

    $this->db->order_by("ID", "asc");
    $akun = $this->db->get ();
    return $akun->result ();
  }









  public function select_info_stock()
  {
    $this->db->select('T_M_D_BARANG.ID');
    $this->db->select('T_M_D_BARANG.KODE_BARANG');
    $this->db->select('T_M_D_BARANG.BARANG');
    $this->db->select('T_M_D_BARANG.PART_NUMBER');
    $this->db->select('T_M_D_BARANG.MERK_BARANG');
    $this->db->select('T_M_D_BARANG.POSISI');
    $this->db->select('T_M_D_BARANG.MINIMUM_STOK');
    $this->db->select('T_M_D_BARANG.CREATED_BY');
    $this->db->select('T_M_D_BARANG.UPDATED_BY');
    $this->db->select('T_M_D_BARANG.MARK_FOR_DELETE');
    $this->db->select('T_M_D_BARANG.BARANG_ID');
    $this->db->select('T_M_D_BARANG.HARGA_JUAL');
    $this->db->select('T_M_D_BARANG.MAXIMUM_STOK');

    $this->db->select('T_M_D_KATEGORI.KATEGORI');


    $this->db->select('T_M_D_SATUAN.SATUAN');

    $this->db->select('T_M_D_COMPANY.COMPANY');

    $this->db->select('T_M_D_JENIS_BARANG.JENIS_BARANG');


    $this->db->select('SUM_SISA_QTY');


    $this->db->from('T_M_D_BARANG');


    $this->db->join('T_M_D_JENIS_BARANG', 'T_M_D_JENIS_BARANG.ID = T_M_D_BARANG.JENIS_BARANG_ID', 'left');

    $this->db->join('T_M_D_COMPANY', 'T_M_D_COMPANY.ID = T_M_D_BARANG.COMPANY_ID', 'left');

    $this->db->join('T_M_D_KATEGORI', 'T_M_D_KATEGORI.ID = T_M_D_BARANG.KATEGORI_ID', 'left');

    $this->db->join('T_M_D_SATUAN', 'T_M_D_SATUAN.ID = T_M_D_BARANG.SATUAN_ID', 'left');


    $this->db->join("(select \"BARANG_ID\",sum(\"SISA_QTY\")\"SUM_SISA_QTY\" from \"T_T_T_PEMBELIAN_RINCIAN\" where  \"SPECIAL_CASE_ID\"=0 and \"MARK_FOR_DELETE\"=false and \"COMPANY_ID\"='{$this->session->userdata('master_barang_company_id')}' group by \"BARANG_ID\") as t_sum_1", 'T_M_D_BARANG.BARANG_ID = t_sum_1.BARANG_ID', 'left');


    if($this->session->userdata('master_barang_kategori_id')!=0)
    {
      $this->db->where('T_M_D_BARANG.KATEGORI_ID',$this->session->userdata('master_barang_kategori_id'));
    }

    $this->db->where('T_M_D_BARANG.COMPANY_ID',$this->session->userdata('master_barang_company_id'));



    $this->db->where('T_M_D_BARANG.MARK_FOR_DELETE',FALSE);

    
    

    $this->db->order_by("ID", "asc");
    $akun = $this->db->get ();
    return $akun->result ();
  }





  public function delete($id)
  {
    $this->db->where('ID',$id);
    $this->db->delete('T_M_D_BARANG');
  }

  function tambah($data)
  {
    $this->db->insert('T_M_D_BARANG', $data);
    return TRUE;
  }

}


