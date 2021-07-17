<?php
  $level_user_id = $this->session->userdata('level_user_id');
?>

<div class="card">
  <div class="card-header">
    

      <label>Pilih Laporan:</label>

      <select name="pilih_laporan" class='pilih_laporan' id='id_pilih_laporan' placeholder='Pick a state...'>
        <?php

        if($level_user_id == 1)
        {
          echo "<option value='laporan_excel/lap_beli/index/' >Laporan Pembelian</option>";

          echo "<option value='laporan_excel/lap_rb/index/' >Laporan Retur Pembelian</option>";

          echo "<option value='laporan_excel/lap_beli_kredit/index/' >Laporan Pembelian (Hanya Kredit)</option>";

          echo "<option value='laporan_excel/lap_flow_barang_per_item/index/' >Laporan Flow Barang (per Item)</option>";
          echo "<option value='laporan_excel/lap_flow_barang_per_kategori/index/' >Laporan Flow Barang (per Kategori)</option>";
        }
        



        if($level_user_id == 1 or $level_user_id == 10)
        {

          echo "<option value='laporan_excel/lap_jual/index/' >Laporan Penjualan</option>";

          echo "<option value='laporan_excel/lap_rj/index/' >Laporan Retur Penjualan</option>";
          
          echo "<option value='laporan_excel/lap_jual_kredit/index/' >Laporan Penjualan (Hanya Kredit)</option>";

          

          
          echo "<option value='laporan_excel/lap_ranking_sales/index/' >Laporan Ranking Sales</option>";
          echo "<option value='laporan_excel/lap_ranking_pelanggan/index/' >Laporan Ranking Pelanggan</option>";


          echo "<option value='laporan_excel/lap_penjualan_per_sales/index/' >Laporan Penjualan Per Sales</option>";


          echo "<option value='laporan_excel/lap_penjualan_per_pelanggan/index/' >Laporan Penjualan Per Pelanggan</option>";
        }

        ?>
      </select>

      <div class='barang' id='barang'>
        <label>Kode Barang</label>
            <select name="barang_id" class='barang_id' id='barang_id' placeholder='Pick a state...'>
              
              <?php
              foreach ($c_t_m_d_barang as $key => $value) 
              {
                echo "<option value='".$value->BARANG_ID."'>".$value->KODE_BARANG."/".$value->BARANG."/".$value->MERK_BARANG."/".$value->PART_NUMBER."</option>";

              }
              ?>
            </select>
      </div>

      <div class='kategori' id='kategori'>
        <label>Pilih Kategori</label>
            <select name="kategori_id" class='' id='kategori_id' placeholder='Pick a state...'>
            
            <?php
            foreach ($c_t_m_d_kategori as $key => $value) 
            {
                    if($this->session->userdata('master_barang_kategori_id')==$value->ID)
                    {
                      echo "<option value='".$value->ID."' selected>".$value->KATEGORI."</option>";
                    }
                    else
                    {
                      echo "<option value='".$value->ID."'>".$value->KATEGORI."</option>";
                    }
                    

            }
            ?>
          </select>
      </div>

      <div class='sales' id='sales'>
        <label>Pilih Sales</label>
            <select name="sales_id" class='' id='sales_id' placeholder='Pick a state...'>
            
            <?php
            foreach ($c_t_m_d_sales as $key => $value) 
            {
              echo "<option value='".$value->ID."'>".$value->SALES."</option>";
            }
            ?>
          </select>
      </div>


      <div class='pelanggan' id='pelanggan'>
        <label>Pilih Pelanggan</label>
            <select name="pelanggan_id" class='' id='pelanggan_id' placeholder='Pick a state...'>
            
            <?php
            foreach ($c_t_m_d_pelanggan as $key => $value) 
            {
              echo "<option value='".$value->ID."'>".$value->PELANGGAN."</option>";
            }
            ?>
          </select>
      </div>



      <table>
        <tr>
          <th>Periode:</th>
          <th>
            <form action='/action_page.php'>
              <input type='date' class='form-control' id='date_from_laporan' value='<?php echo $this->session->userdata('date_from_select_jurnal'); ?>'>
          </th>
          <th>-</th>
          <th>
            <form action='/action_page.php'>
              <input type='date' class='form-control' id='date_to_laporan' value='<?php echo $this->session->userdata('date_to_select_jurnal'); ?>'>
          </th>
          <th>


            <button type='button' class='btn btn-success' onclick='call_download()'>Download</button>
          </th>
        </tr>
      </table>


    
  </div>

</div>


<?php
//document.getElementById("password_edit").value

?>



<script type="text/javascript">

$(document).ready(function()
{
  $(".pilih_laporan").change(function()
  {
    var pilih_laporan=$(this).val();
    console.log(pilih_laporan);
    
    if(pilih_laporan=="laporan_excel/lap_flow_barang_per_item/index/")
    {
      document.getElementById('barang').style.display = 'block';
      document.getElementById('kategori').style.display = 'none';

      document.getElementById('sales').style.display = 'none';
      document.getElementById('pelanggan').style.display = 'none';
    }

    else if(pilih_laporan=="laporan_excel/lap_flow_barang_per_kategori/index/")
    {
      document.getElementById('barang').style.display = 'none';
      document.getElementById('kategori').style.display = 'block';

      document.getElementById('sales').style.display = 'none';
      document.getElementById('pelanggan').style.display = 'none';
    }


    else if(pilih_laporan=="laporan_excel/lap_penjualan_per_sales/index/")
    {
      document.getElementById('barang').style.display = 'none';
      document.getElementById('kategori').style.display = 'none';

      document.getElementById('sales').style.display = 'block';
      document.getElementById('pelanggan').style.display = 'none';
    }

    else if(pilih_laporan=="laporan_excel/lap_penjualan_per_pelanggan/index/")
    {
      document.getElementById('barang').style.display = 'none';
      document.getElementById('kategori').style.display = 'none';

      document.getElementById('sales').style.display = 'none';
      document.getElementById('pelanggan').style.display = 'block';
    }


    else
    {
      document.getElementById('barang').style.display = 'none';
      document.getElementById('kategori').style.display = 'none';

      document.getElementById('sales').style.display = 'none';
      document.getElementById('pelanggan').style.display = 'none';
    }
    
  });


});



</script>


<script type="text/javascript">
  function call_download() {
    var link_1 = document.getElementById("id_pilih_laporan").value;
    var link_2 = document.getElementById("date_from_laporan").value;
    var link_3 = document.getElementById("date_to_laporan").value;
    var link_4 = parseInt(document.getElementById("barang_id").value);
    var link_5 = parseInt(document.getElementById("kategori_id").value);
    var link_6 = parseInt(document.getElementById("sales_id").value);
    var link_7 = parseInt(document.getElementById("pelanggan_id").value);
    var slash = "/";

    var link = link_1.concat(link_2, slash, link_3, slash, link_4, slash, link_5,slash, link_6,slash, link_7);
    window.open(link);
  }
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select').selectize({
      sortField: 'text'
    });
  });
</script>




<style type="text/css">
  div.searchable {
    width: 90%;
    margin: 0 15px;
  }

  .background-white {
    background-color: white;
  }

  .background-blue {
    background-color: #151B54;
    color: white;
  }

.barang
{
  display: none;
}

.kategori
{
  display: none;
}

.sales
{
  display: none;
}
.pelanggan
{
  display: none;
}

  .searchable input {
    width: 100%;
    height: 25px;
    font-size: 12px;
    padding: 10px;
    -webkit-box-sizing: border-box;
    /* Safari/Chrome, other WebKit */
    -moz-box-sizing: border-box;
    /* Firefox, other Gecko */
    box-sizing: border-box;
    /* Opera/IE 8+ */
    display: block;
    font-weight: 400;
    line-height: 1.6;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    background: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E") no-repeat right .75rem center/8px 10px;
  }

  .searchable ul {
    display: none;
    list-style-type: none;
    background-color: #fff;
    border-radius: 0 0 5px 5px;
    border: 1px solid #add8e6;
    border-top: none;
    max-height: 180px;
    margin: 0;
    overflow-y: scroll;
    overflow-x: hidden;
    padding: 0;
  }

  .searchable ul li {
    padding: 7px 9px;
    border-bottom: 1px solid #e1e1e1;
    cursor: pointer;
    color: #6e6e6e;
  }

  .searchable ul li.selected {
    background-color: #e8e8e8;
    color: #333;
  }
</style>





<style type="text/css">
  .text_red {
    color: red;
  }

  .text_black {
    color: black;
  }
</style>