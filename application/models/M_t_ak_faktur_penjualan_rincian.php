<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_t_ak_faktur_penjualan_rincian extends CI_Model {
    
    

public function update($data, $id)
{
    $this->db->where('ID', $id);
    return $this->db->update('T_AK_FAKTUR_PENJUALAN_RINCIAN', $data);
}




  public function select($id)
  {
    $this->db->select("T_AK_FAKTUR_PENJUALAN_RINCIAN.ID");
    $this->db->select("T_AK_FAKTUR_PENJUALAN_RINCIAN.FAKTUR_PENJUALAN_ID");
    $this->db->select("T_AK_FAKTUR_PENJUALAN_RINCIAN.PENJUALAN_ID");
    $this->db->select("T_AK_FAKTUR_PENJUALAN_RINCIAN.CREATED_BY");
    $this->db->select("T_AK_FAKTUR_PENJUALAN_RINCIAN.UPDATED_BY");
    $this->db->select("T_AK_FAKTUR_PENJUALAN_RINCIAN.KETERANGAN");



    $this->db->select("T_T_T_PENJUALAN.DATE");
    $this->db->select("T_T_T_PENJUALAN.TIME");

    $this->db->select("T_T_T_PENJUALAN.INV");

    $this->db->select("T_AK_FAKTUR_PENJUALAN.ENABLE_EDIT");

    $this->db->select("SUM_SUB_TOTAL");

    $this->db->select("SUM_RETUR");

    
    $this->db->from('T_AK_FAKTUR_PENJUALAN_RINCIAN');

    $this->db->join('T_T_T_PENJUALAN', 'T_T_T_PENJUALAN.ID = T_AK_FAKTUR_PENJUALAN_RINCIAN.PENJUALAN_ID', 'left');

    $this->db->join('T_AK_FAKTUR_PENJUALAN', 'T_AK_FAKTUR_PENJUALAN.ID = T_AK_FAKTUR_PENJUALAN_RINCIAN.FAKTUR_PENJUALAN_ID', 'left');


    $this->db->join("(select \"PENJUALAN_ID\",sum(\"SUB_TOTAL\")\"SUM_SUB_TOTAL\" from \"T_T_T_PENJUALAN_RINCIAN\" where \"MARK_FOR_DELETE\"=false group by \"PENJUALAN_ID\") as t_sum_1", 'T_T_T_PENJUALAN.ID = t_sum_1.PENJUALAN_ID', 'left');





    $this->db->join("(select \"T_T_T_RETUR_PENJUALAN\".\"PENJUALAN_ID\",sum(\"T_T_T_RETUR_PENJUALAN_RINCIAN\".\"SUB_TOTAL\")\"SUM_RETUR\" from \"T_T_T_RETUR_PENJUALAN_RINCIAN\" LEFT OUTER JOIN \"T_T_T_RETUR_PENJUALAN\" on \"T_T_T_RETUR_PENJUALAN\".\"ID\"=\"T_T_T_RETUR_PENJUALAN_RINCIAN\".\"RETUR_PENJUALAN_ID\"  where \"T_T_T_RETUR_PENJUALAN_RINCIAN\".\"MARK_FOR_DELETE\"=false group by \"T_T_T_RETUR_PENJUALAN\".\"PENJUALAN_ID\") as t_sum_5", 'T_T_T_PENJUALAN.ID = t_sum_5.PENJUALAN_ID', 'left');

    
    $this->db->where('T_AK_FAKTUR_PENJUALAN_RINCIAN.FAKTUR_PENJUALAN_ID', $id);


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
    $this->db->delete('T_AK_FAKTUR_PENJUALAN_RINCIAN');
  }

  function tambah($data)
  {
        $this->db->insert('T_AK_FAKTUR_PENJUALAN_RINCIAN', $data);
        return TRUE;
  }

}


