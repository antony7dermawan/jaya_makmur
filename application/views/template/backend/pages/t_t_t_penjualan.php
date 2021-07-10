<div class="card">
  <div class="card-header">
    <form action='<?php echo base_url("c_t_t_t_penjualan/date_penjualan"); ?>' class='no_voucer_area' method="post" id=''>
      <table>
        <tr>
          <th>
            Tanggal Transaksi:
          </th>
          <th>
            <form action='/action_page.php'>
              <input type='date' class='form-control' name='date_penjualan' value='<?= $this->session->userdata('date_penjualan') ?>' onchange='this.form.submit();'>
          </th>
        </tr>
      </table>



    </form>
  </div>
  <div class="card-block">
    <!-- Menampilkan notif !-->
    <?= $this->session->flashdata('notif') ?>
    <!-- Tombol untuk menambah data akun !-->
    <button data-toggle="modal" data-target="#addModal" class="btn btn-success waves-effect waves-light">New Data</button>

    <div class="table-responsive dt-responsive">
      <table id="dom-jqry" class="table table-striped table-bordered nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th>INV</th>
            <th>Date</th>
            <th>Ket</th>
            <th>Pelanggan</th>
            <th>Payment Method</th>
            <th>Total</th>

            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($c_t_t_t_penjualan as $key => $value) {
            if($value->MARK_FOR_DELETE == 'f')
            {
              echo "<tr>";
              echo "<td>" . ($key + 1) . "</td>";
              echo "<td>" . $value->INV_HEAD.$value->INV . "</td>";
              echo "<td>" . date('d-m-Y', strtotime($value->DATE)) . " / " . date('H:i', strtotime($value->TIME)) . "</td>";
              echo "<td>" . $value->KET . "</td>";
              echo "<td>" . $value->PELANGGAN . "</td>";
              echo "<td>" . $value->PAYMENT_METHOD . "</td>";



              //satu button
              echo "<td>";
              echo "<a href='" . site_url('c_t_t_t_penjualan_rincian/index/' . $value->ID) . "' ";
              echo "onclick=\"return confirm('Lanjut?')\"";
              echo "> <i class='fa fa-search-plus text-c-blue'></i></a> ";
              echo " Rp" . number_format(intval($value->SUM_SUB_TOTAL)) . "</td>";
              //satu button

              
              echo "<td>";


              if (intval($value->SUM_SUB_TOTAL) != 0)
              {
                echo "<a "; #/1 ini artinya kena pajak

                echo "onclick= 'p_1_" . $key . "()'";
                if ($value->PRINTED == 'f') {
                  echo "> <i class='fa fa-print text-c-black'></i></a> ";
                }
                if ($value->PRINTED == 't') {
                  echo "> <i class='fa fa-print text-c-green'></i></a> ";
                  
                }

                echo "<script>";
                echo "function p_1_" . $key . "()";
                echo "{";
                echo "window.open('laporan_pdf/c_t_t_t_penjualan_print/index/" . $value->ID . "');";
                echo "}";
                echo "</script>";





                echo "<a "; #/1 ini artinya kena pajak

                echo "onclick= 'p_2_" . $key . "()'";
                if ($value->PRINTED == 'f') {
                  echo "> <i class='fa fa-print text-c-blue'></i></a> ";
                }
                if ($value->PRINTED == 't') {
                  echo "> <i class='fa fa-print text-c-green'></i></a> ";
                  
                }

                echo "<script>";
                echo "function p_2_" . $key . "()";
                echo "{";
                echo "window.open('laporan_pdf/c_t_t_t_penjualan2_print/index/" . $value->ID . "');";
                echo "}";
                echo "</script>";


                if($value->ENABLE_EDIT==0)
                {
                  echo "<a class='fa text-c-green'>Sudah Ditagih</a>";
                }
              }
              

                echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#Modal_Edit' class='btn-edit' data-id='" . $value->ID . "'>";
                echo "<i class='icon feather icon-edit f-w-600 f-16 m-r-15 text-c-green'></i>";
                echo "</a>";

              if ($value->SUM_SUB_TOTAL == 0) {
                

                echo "<a href='" . site_url('c_t_t_t_penjualan/delete/' . $value->ID) . "' ";

                echo "onclick=\"return confirm('Apakah kamu yakin ingin menghapus data ini?')\"";


                echo "> <i class='feather icon-trash-2 f-w-600 f-16 text-c-red'></i></a>";
              }
              echo "</td>";


              echo "</tr>";
            }









            if($value->MARK_FOR_DELETE == 't')
            {
              echo "<tr>";
              echo "<td><s>" . ($key + 1) . "</s></td>";
              echo "<td><s>" . $value->INV . "</td>";
              echo "<td><s>" . date('d-m-Y', strtotime($value->DATE)) . " / " . date('H:i', strtotime($value->TIME)) . "</s></td>";
              echo "<td><s>" . $value->KET . "</s></td>";
              echo "<td><s>" . $value->PELANGGAN . "</s></td>";
              echo "<td><s>" . $value->PAYMENT_METHOD . "</s></td>";



              //satu button
              echo "<td><s>";
              echo "<a href='" . site_url('c_t_t_t_penjualan_rincian/index/' . $value->ID) . "' ";
              echo "onclick=\"return confirm('Lanjut?')\"";
              echo "> <i class='fa fa-search-plus text-c-blue'></i></a> ";
              echo " Rp" . number_format(intval($value->SUM_SUB_TOTAL)) . "</s></td>";
              //satu button

              
              echo "<td>";

              echo "<a href='".site_url('c_t_t_t_penjualan/undo_delete/' . $value->ID)."' ";
              ?>
              onclick="return confirm('Apakah kamu yakin ingin mengembalikan data ini?')"
              <?php
              echo "> <i class='fa fa-refresh f-w-600 f-16 text-c-red'></i></a>";

              echo ' '.$value->UPDATED_BY;
          

              echo "</td>";


              echo "</tr>";
            }

          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>









<!-- MODAL TAMBAH PEMASUKAN! !-->
<form action="<?php echo base_url('c_t_t_t_penjualan/tambah') ?>" method="post" id='add_data'>
  <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tanggal Transaksi:
            <form action='/action_page.php'>
              <input type='date' class='form-control' name='date' value='<?= $this->session->userdata('date_penjualan') ?>'>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          
          
        <div class="form-group">
              <label>Pelanggan</label>
              <select name="pelanggan_id" class='custom_width' id='select-state' placeholder='Pick a state...'>
              <?php
              foreach ($c_t_m_d_pelanggan as $key => $value) 
              {
                echo "<option value='".$value->ID."'>".$value->PELANGGAN."</option>";

              }
              ?>
              </select>
        </div>

        <div class="row">
          <div class="col-md-6">

            <fieldset class="form-group">
              <label>Sales</label>
              <select name="sales_id" class='custom_width' id='select-state' placeholder='Pick a state...'>
              <?php
              foreach ($c_t_m_d_sales as $key => $value) 
              {
                echo "<option value='".$value->ID."'>".$value->SALES."</option>";

              }
              ?>
              </select>
            </fieldset>

          </div><!-- Membungkus Row Kedua !-->


          <div class="col-md-6">

            <fieldset class="form-group">
              <label>Payment Method</label>
              <select name="payment_method_id" class='custom_width' id='select-state' placeholder='Pick a state...'>
              <?php
              foreach ($c_t_m_d_payment_method as $key => $value) 
              {
                echo "<option value='".$value->ID."'>".$value->PAYMENT_METHOD."</option>";

              }
              ?>
              </select>
          </div> <!-- Membungkus Row !-->
        </div>

        <div class="row">
          <div class="col-md-6">

            <fieldset class="form-group">
              <label>No Polisi</label>
              <select name="no_polisi_id" class='custom_width' id='select-state' placeholder='Pick a state...'>
              <?php
              foreach ($c_t_m_d_no_polisi as $key => $value) 
              {
                echo "<option value='".$value->ID."'>".$value->NO_POLISI."</option>";

              }
              ?>
              </select>
            </fieldset>

          </div><!-- Membungkus Row Kedua !-->


          <div class="col-md-6">

            <fieldset class="form-group">
              <label>Supir</label>
              <select name="supir_id" class='custom_width' id='select-state' placeholder='Pick a state...'>
              <?php
              foreach ($c_t_m_d_supir as $key => $value) 
              {
                echo "<option value='".$value->ID."'>".$value->SUPIR."</option>";

              }
              ?>
              </select>
          </div> <!-- Membungkus Row !-->
        </div>


        <div class="row">
          <div class="col-md-6">

            <fieldset class="form-group">
              <label>Inv Head</label>
              <input type='text' class='form-control' placeholder='Input Text' name='inv_head'>
            </fieldset>

          </div><!-- Membungkus Row Kedua !-->


          <div class="col-md-6">

            <fieldset class="form-group">
              <label>Lokasi</label>
              <select name="lokasi_id" class='custom_width' id='select-state' placeholder='Pick a state...'>
              <?php
              foreach ($c_t_m_d_lokasi as $key => $value) 
              {
                echo "<option value='".$value->ID."'>".$value->LOKASI."</option>";

              }
              ?>
              </select>
          </div> <!-- Membungkus Row !-->
        </div>


        <div class="form-group">
              <label>Keterangan</label>
              <textarea rows='4' cols='20' name='ket' id='' form='add_data' class='form-control'></textarea>
        </div>


        



        </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
            <button type="Submit" class="btn btn-primary waves-effect waves-light ">Save changes</button>
          </div>



      </div>
    </div>
  </div>
</form>
<!-- MODAL TAMBAH PEMASUKAN SELESAI !-->









<!-- MODAL EDIT AKUN !-->
<div class="modal fade" id="Modal_Edit" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form action="<?php echo base_url('c_t_t_t_penjualan/edit_action') ?>" method="post" autocomplete="off" id='edit_data'>
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        
        <div class="modal-body">
        <input type="hidden" name="id" value="" class="form-control">


        <div class="form-group">
              <label>Pelanggan</label>
              <div class="searchable">
                  <input type="text" name='pelanggan' placeholder="search" onkeyup="filterFunction(this,event)">
                  <ul>
                    <?php
                    foreach ($c_t_m_d_pelanggan as $key => $value) 
                    {
                      echo "<li>".$value->PELANGGAN."</li>";
                    }
                    ?>
                  </ul>
              </div>
        </div>


        <div class="row">
          <div class="col-md-6">

            <fieldset class="form-group">
              <label>Sales</label>


              <div class="searchable">
                  <input type="text" name='sales' placeholder="search" onkeyup="filterFunction(this,event)">
                  <ul>
                    <?php
                    foreach ($c_t_m_d_sales as $key => $value) 
                    {
                      echo "<li>".$value->SALES."</li>";
                    }
                    ?>
                  </ul>
              </div>
            </fieldset>

          </div><!-- Membungkus Row Kedua !-->


          <div class="col-md-6">

            <fieldset class="form-group">
              <label>Payment Method</label>


              <div class="searchable">
                  <input type="text" name='payment_method' placeholder="search" onkeyup="filterFunction(this,event)">
                  <ul>
                    <?php
                    foreach ($c_t_m_d_payment_method as $key => $value) 
                    {
                      echo "<li>".$value->PAYMENT_METHOD."</li>";
                    }
                    ?>
                  </ul>
              </div>
            </fieldset>
          </div> <!-- Membungkus Row !-->
        </div>



        <div class="row">
          <div class="col-md-6">

            <fieldset class="form-group">
              <label>No Polisi</label>


              <div class="searchable">
                  <input type="text" name='no_polisi' placeholder="search" onkeyup="filterFunction(this,event)">
                  <ul>
                    <?php
                    foreach ($c_t_m_d_no_polisi as $key => $value) 
                    {
                      echo "<li>".$value->NO_POLISI."</li>";
                    }
                    ?>
                  </ul>
              </div>
            </fieldset>

          </div><!-- Membungkus Row Kedua !-->


          <div class="col-md-6">

            <fieldset class="form-group">
              <label>Supir</label>


              <div class="searchable">
                  <input type="text" name='supir' placeholder="search" onkeyup="filterFunction(this,event)">
                  <ul>
                    <?php
                    foreach ($c_t_m_d_supir as $key => $value) 
                    {
                      echo "<li>".$value->SUPIR."</li>";
                    }
                    ?>
                  </ul>
              </div>
            </fieldset>
          </div> <!-- Membungkus Row !-->
        </div>


        <div class="row">
          <div class="col-md-6">

            <fieldset class="form-group">
              <label>Inv Head</label>
              <input type='text' class='form-control' placeholder='Input Text' name='inv_head'>
            </fieldset>

          </div><!-- Membungkus Row Kedua !-->


          <div class="col-md-6">

            <fieldset class="form-group">
              <label>Lokasi</label>
              <div class="searchable">
                  <input type="text" name='lokasi' placeholder="search" onkeyup="filterFunction(this,event)">
                  <ul>
                    <?php
                    foreach ($c_t_m_d_lokasi as $key => $value) 
                    {
                      echo "<li>".$value->LOKASI."</li>";
                    }
                    ?>
                  </ul>
              </div>
          </div> <!-- Membungkus Row !-->
        </div>


        <div class="form-group">
              <label>Keterangan</label>
              <textarea rows='4' cols='20' name='ket' id='' form='edit_data' class='form-control'></textarea>
        </div>


        


          <div class="modal-footer">
            <div class="created_form">
              Created By : <a name='created_by'></a>
              <br>
              Updated By : <a name='updated_by'></a>
            </div>
            <style type="text/css">
              .created_form
              {
                float: left;
                margin right: : 20px;
                font-size: 10px;
              }
            </style>
            <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
            <button type="Submit" class="btn btn-primary waves-effect waves-light ">Save changes</button>
          </div>

        </div>


<script>
  const read_data = <?= json_encode($c_t_t_t_penjualan) ?>;
  console.log(read_data);
  let elModalEdit = document.querySelector("#Modal_Edit");
  console.log(elModalEdit);
  let elBtnEdits = document.querySelectorAll(".btn-edit");
  [...elBtnEdits].forEach(edit => {
    edit.addEventListener("click", (e) => {
      let id = edit.getAttribute("data-id");
      let User = read_data.filter(user => {
        if (user.ID == id)
          return user;
      });
      const {
        ID,
        KET : ket,
        PELANGGAN : pelanggan,
        PAYMENT_METHOD : payment_method,
        NO_POLISI : no_polisi,
        SUPIR : supir,
        SALES : sales,
        UPDATED_BY : updated_by,
        CREATED_BY : created_by,
        LOKASI : lokasi,
        INV_HEAD : inv_head
      } = User[0];

      elModalEdit.querySelector("[name=id]").value = ID;
      
      elModalEdit.querySelector("[name=no_polisi]").value = no_polisi;
      elModalEdit.querySelector("[name=supir]").value = supir;
      elModalEdit.querySelector("[name=sales]").value = sales;

      elModalEdit.querySelector("[name=pelanggan]").value = pelanggan;
      elModalEdit.querySelector("[name=payment_method]").value = payment_method;
      elModalEdit.querySelector("[name=ket]").value = ket;
      elModalEdit.querySelector("[name=updated_by]").text = updated_by;
      elModalEdit.querySelector("[name=created_by]").text = created_by;
      elModalEdit.querySelector("[name=lokasi]").value = lokasi;
      elModalEdit.querySelector("[name=inv_head]").value = inv_head;

  



    })
  })
</script>

    </form>
  </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {
      $('select').selectize({
          sortField: 'text'
      });
  });
</script>



<style type="text/css">
    div.searchable {
    width: 100%;
    
}

.searchable input {
    width: 100%;
    height: 30px;
    font-size: 14px;
    padding: 10px;
    -webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
    -moz-box-sizing: border-box; /* Firefox, other Gecko */
    box-sizing: border-box; /* Opera/IE 8+ */
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



<script type="text/javascript">
    
    function filterFunction(that, event) {
    let container, input, filter, li, input_val;
    container = $(that).closest(".searchable");
    input_val = container.find("input").val().toUpperCase();

    if (["ArrowDown", "ArrowUp", "Enter"].indexOf(event.key) != -1) {
        keyControl(event, container)
    } else {
        li = container.find("ul li");
        li.each(function (i, obj) {
            if ($(this).text().toUpperCase().indexOf(input_val) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

        container.find("ul li").removeClass("selected");
        setTimeout(function () {
            container.find("ul li:visible").first().addClass("selected");
        }, 100)
    }
}

function keyControl(e, container) {
    if (e.key == "ArrowDown") {

        if (container.find("ul li").hasClass("selected")) {
            if (container.find("ul li:visible").index(container.find("ul li.selected")) + 1 < container.find("ul li:visible").length) {
                container.find("ul li.selected").removeClass("selected").nextAll().not('[style*="display: none"]').first().addClass("selected");
            }

        } else {
            container.find("ul li:first-child").addClass("selected");
        }

    } else if (e.key == "ArrowUp") {

        if (container.find("ul li:visible").index(container.find("ul li.selected")) > 0) {
            container.find("ul li.selected").removeClass("selected").prevAll().not('[style*="display: none"]').first().addClass("selected");
        }
    } else if (e.key == "Enter") {
        container.find("input").val(container.find("ul li.selected").text()).blur();
        onSelect(container.find("ul li.selected").text())
    }

    container.find("ul li.selected")[0].scrollIntoView({
        behavior: "smooth",
    });
}



$(".searchable input").focus(function () {
    $(this).closest(".searchable").find("ul").show();
    $(this).closest(".searchable").find("ul li").show();
});
$(".searchable input").blur(function () {
    let that = this;
    setTimeout(function () {
        $(that).closest(".searchable").find("ul").hide();
    }, 300);
});

$(document).on('click', '.searchable ul li', function () {
    $(this).closest(".searchable").find("input").val($(this).text()).blur();
    onSelect($(this).text())
});

$(".searchable ul li").hover(function () {
    $(this).closest(".searchable").find("ul li.selected").removeClass("selected");
    $(this).addClass("selected");
});
</script>