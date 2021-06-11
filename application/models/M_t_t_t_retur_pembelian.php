<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_t_t_t_retur_pembelian extends CI_Model {
    
    
public function update($data, $id)
{
    $this->db->where('ID', $id);
    return $this->db->update('T_T_T_RETUR_PEMBELIAN', $data);
}

public function select_range_date($from_date,$to_date)
  {
    $this->db->select("T_T_T_RETUR_PEMBELIAN.ID");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.DATE");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.TIME");

    $this->db->select("T_T_T_RETUR_PEMBELIAN.INV");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.INV_INT");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.COMPANY_ID");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.PEMBELIAN_ID");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.CREATED_BY");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.UPDATED_BY");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.MARK_FOR_DELETE");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.KET");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.PRINTED");


    $this->db->select("T_T_T_PEMBELIAN.INV as INV_PEMBELIAN");


    $this->db->select("SUM_SUB_TOTAL");
    $this->db->select("T_M_D_COMPANY.COMPANY");
    $this->db->select("T_M_D_SUPPLIER.SUPPLIER");



    $this->db->select("T_T_T_PEMBELIAN.INV_SUPPLIER");

    
    $this->db->select("T_M_D_PAYMENT_METHOD.PAYMENT_METHOD");


    $this->db->from('T_T_T_RETUR_PEMBELIAN');


    $this->db->join('T_T_T_PEMBELIAN', 'T_T_T_PEMBELIAN.ID = T_T_T_RETUR_PEMBELIAN.PEMBELIAN_ID', 'left');


    $this->db->join('T_M_D_SUPPLIER', 'T_M_D_SUPPLIER.ID = T_T_T_PEMBELIAN.SUPPLIER_ID', 'left');


    $this->db->join('T_M_D_PAYMENT_METHOD', 'T_M_D_PAYMENT_METHOD.ID = T_T_T_PEMBELIAN.PAYMENT_METHOD_ID', 'left');

    $this->db->join("(select \"RETUR_PEMBELIAN_ID\",sum(\"SUB_TOTAL\")\"SUM_SUB_TOTAL\" from \"T_T_T_RETUR_PEMBELIAN_RINCIAN\" where \"MARK_FOR_DELETE\"=false group by \"RETUR_PEMBELIAN_ID\") as t_sum_1", 'T_T_T_RETUR_PEMBELIAN.ID = t_sum_1.RETUR_PEMBELIAN_ID', 'left');

    $this->db->join('T_M_D_COMPANY', 'T_M_D_COMPANY.ID = T_T_T_RETUR_PEMBELIAN.COMPANY_ID', 'left');
    
    if($this->session->userdata('t_t_t_retur_pembelian_delete_logic')==0)
    {
      $this->db->where('T_T_T_RETUR_PEMBELIAN.MARK_FOR_DELETE',FALSE);
    }

    $this->db->where("T_T_T_RETUR_PEMBELIAN.DATE<='{$to_date}' and T_T_T_RETUR_PEMBELIAN.DATE>='{$from_date}'");

    
    $this->db->where("T_T_T_RETUR_PEMBELIAN.COMPANY_ID={$this->session->userdata('company_id')}");
    $this->db->order_by("ID", "desc");

    $akun = $this->db->get ();
    return $akun->result ();
  }


  public function select($date_retur_pembelian)
  {
    $this->db->select("T_T_T_RETUR_PEMBELIAN.ID");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.DATE");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.TIME");

    $this->db->select("T_T_T_RETUR_PEMBELIAN.INV");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.INV_INT");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.COMPANY_ID");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.PEMBELIAN_ID");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.CREATED_BY");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.UPDATED_BY");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.MARK_FOR_DELETE");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.KET");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.PRINTED");


    $this->db->select("T_T_T_PEMBELIAN.INV as INV_PEMBELIAN");


    $this->db->select("SUM_SUB_TOTAL");
    $this->db->select("T_M_D_COMPANY.COMPANY");
   


    $this->db->from('T_T_T_RETUR_PEMBELIAN');


    $this->db->join('T_T_T_PEMBELIAN', 'T_T_T_PEMBELIAN.ID = T_T_T_RETUR_PEMBELIAN.PEMBELIAN_ID', 'left');


    


    $this->db->join("(select \"RETUR_PEMBELIAN_ID\",sum(\"SUB_TOTAL\")\"SUM_SUB_TOTAL\" from \"T_T_T_RETUR_PEMBELIAN_RINCIAN\" where \"MARK_FOR_DELETE\"=false group by \"RETUR_PEMBELIAN_ID\") as t_sum_1", 'T_T_T_RETUR_PEMBELIAN.ID = t_sum_1.RETUR_PEMBELIAN_ID', 'left');

    $this->db->join('T_M_D_COMPANY', 'T_M_D_COMPANY.ID = T_T_T_RETUR_PEMBELIAN.COMPANY_ID', 'left');
    
    $date_before = date('Y-m-d',(strtotime ( '-30 day' , strtotime ( $date_retur_pembelian) ) ));

    $this->db->where("T_T_T_RETUR_PEMBELIAN.DATE<='{$date_retur_pembelian}' and T_T_T_RETUR_PEMBELIAN.DATE>='{$date_before}'");

    
    $this->db->where("T_T_T_RETUR_PEMBELIAN.COMPANY_ID={$this->session->userdata('company_id')}");
    $this->db->order_by("ID", "desc");

    $akun = $this->db->get ();
    return $akun->result ();
  }












  public function select_by_id($id)
  {
    $this->db->select("T_T_T_RETUR_PEMBELIAN.ID");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.DATE");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.TIME");

    $this->db->select("T_T_T_RETUR_PEMBELIAN.INV");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.INV_INT");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.COMPANY_ID");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.PEMBELIAN_ID");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.CREATED_BY");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.UPDATED_BY");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.MARK_FOR_DELETE");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.KET");
    $this->db->select("T_T_T_RETUR_PEMBELIAN.PRINTED");


    $this->db->select("T_T_T_PEMBELIAN.INV as INV_PEMBELIAN");


    $this->db->select("SUM_SUB_TOTAL");
    $this->db->select("T_M_D_COMPANY.COMPANY");

    $this->db->select("T_M_D_SUPPLIER.SUPPLIER");



    $this->db->select("T_T_T_PEMBELIAN.INV_SUPPLIER");

    
    $this->db->select("T_M_D_PAYMENT_METHOD.PAYMENT_METHOD");


    $this->db->from('T_T_T_RETUR_PEMBELIAN');


    $this->db->join('T_T_T_PEMBELIAN', 'T_T_T_PEMBELIAN.ID = T_T_T_RETUR_PEMBELIAN.PEMBELIAN_ID', 'left');


    $this->db->join('T_M_D_SUPPLIER', 'T_M_D_SUPPLIER.ID = T_T_T_PEMBELIAN.SUPPLIER_ID', 'left');


    $this->db->join('T_M_D_PAYMENT_METHOD', 'T_M_D_PAYMENT_METHOD.ID = T_T_T_PEMBELIAN.PAYMENT_METHOD_ID', 'left');



    $this->db->join('T_M_D_COMPANY', 'T_M_D_COMPANY.ID = T_T_T_RETUR_PEMBELIAN.COMPANY_ID', 'left');

    $this->db->join("(select \"RETUR_PEMBELIAN_ID\",sum(\"SUB_TOTAL\")\"SUM_SUB_TOTAL\" from \"T_T_T_RETUR_PEMBELIAN_RINCIAN\" where \"MARK_FOR_DELETE\"=false group by \"RETUR_PEMBELIAN_ID\") as t_sum_1", 'T_T_T_RETUR_PEMBELIAN.ID = t_sum_1.RETUR_PEMBELIAN_ID', 'left');

    

    
    $this->db->where('T_T_T_RETUR_PEMBELIAN.ID',$id);
    $this->db->where("T_T_T_RETUR_PEMBELIAN.COMPANY_ID={$this->session->userdata('company_id')}");

    

    $akun = $this->db->get ();
    return $akun->result ();
  }


  public function select_inv_int()
  {
    $this_year = date('Y-m').'-01';
    $this->db->limit(1);
    $this->db->select("INV_INT");
    $this->db->from('T_T_T_RETUR_PEMBELIAN');
    $this->db->where("COMPANY_ID={$this->session->userdata('company_id')}");
    $this->db->where("DATE>='{$this_year}'");
    $this->db->order_by("ID", "desc");

    $akun = $this->db->get ();
    return $akun->result ();
  }

   




  public function delete($id)
  {
    $this->db->where('ID',$id);
    $this->db->delete('T_T_T_RETUR_PEMBELIAN');
  }

  function tambah($data)
  {
        $this->db->insert('T_T_T_RETUR_PEMBELIAN', $data);
        return TRUE;
  }

}


