<div class="card">
  <div class="card-header">
    <form action='<?php echo base_url("c_t_ak_faktur_penjualan/date_faktur_penjualan"); ?>' class='no_voucer_area' method="post" id=''>
      <table>
        <tr>
          <th>
            Tanggal Tagihan:
          </th>
          <th>
            <form action='/action_page.php'>
              <input type='date' class='form-control' name='date_faktur_penjualan' value='<?= $this->session->userdata('date_faktur_penjualan') ?>' onchange='this.form.submit();'>
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
            <th>Date</th>
            <th>Pelanggan</th>
            <th>No Faktur</th>
            <th>Penjualan</th>
            <th>Retur Jual</th>
            <th>Total</th>
            <th>PPN</th>
            <th>PPH</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($c_t_ak_faktur_penjualan as $key => $value) {
            echo "<tr>";
            echo "<td>" . ($key + 1) . "</td>";
            echo "<td>" . date('d-m-Y', strtotime($value->DATE)) . " / " . date('H:i', strtotime($value->TIME)) . " / ".$value->CREATED_BY. "</td>";
            echo "<td>" . $value->PELANGGAN . "</td>";
            echo "<td>" . $value->NO_FAKTUR . "</td>";
            #echo "<td>".date('d-m-Y', strtotime($value->DATE))." / ".date('H:i', strtotime($value->TIME))." / ".$value->CREATED_BY."</td>";


            echo "<td>";
            
            echo "Rp" . number_format(round($value->SUM_TOTAL_PENJUALAN)) . "</td>";


            echo "<td>";
            
            echo "Rp" . number_format(round($value->SUM_TOTAL_RETUR_PENJUALAN)) . "</td>";



            $jumlah_akhir = round($value->SUM_TOTAL_PENJUALAN)-round($value->SUM_TOTAL_RETUR_PENJUALAN);

            echo "<td>";
            echo "<a href='" . site_url('c_t_ak_faktur_penjualan_rincian/index/' . $value->ID) . "/" . $value->PELANGGAN_ID . "' ";
            echo "onclick=\"return confirm('Isi Rincian?')\"";
            echo "> <i class='fa fa-search-plus text-c-blue'></i></a> ";
            echo "Rp" . number_format(round($jumlah_akhir)) . "</td>";


            echo "<td>";
            if($value->PPN=='t')
            {
              $enable_ppn = 1;
              echo "10%";
            }
            else
            {
              $enable_ppn = 0;
              echo "0%";
            }
            echo "</td>";


            echo "<td>";
            if($value->PPH=='t')
            {
              echo "0,25%";
            }
            else
            {
              echo "0%";
            }
            echo "</td>";

            echo "<td>";


            if(intval($value->SUM_TOTAL_PENJUALAN)!=0)
            {
              echo "<a href='" . site_url('c_t_ak_faktur_penjualan/update_enable_edit/' . $value->ID) . "/" . intval($value->SUM_TOTAL_PENJUALAN) . "/".$enable_ppn."/" . $value->ENABLE_EDIT . "'"; #/1 ini artinya kena pajak

              

              echo "onclick= 'p_1_" . $key . "()'";
              if ($value->ENABLE_EDIT == 1) {
                echo "> <i class='fa fa-print text-c-black'></i></a> ";
              }
              if ($value->ENABLE_EDIT == 0) {
                echo "> <i class='fa fa-print text-c-green'></i></a> ";

                if($this->session->userdata('level_user_id')==1 and $value->PAYMENT_T==0)
                {
                  echo "<a href='" . site_url('c_t_ak_faktur_penjualan/undo/' . $value->ID) . "' ";
                  echo "onclick=\"return confirm('Apakah kamu yakin ingin memperbaiki data ini?')\"";
                  echo "> <i class='fa fa-refresh f-w-600 f-16 text-c-red'></i></a>";
                }
                if($value->PAYMENT_T>0)
                {
                  echo "Sudah Dibayar";
                }
                
              }


              echo "<script>";
              echo "function p_1_" . $key . "()";
              echo "{";
              echo "window.open('laporan_pdf/c_faktur_penjualan_print/index/" . $value->ID . "/" . $value->PELANGGAN_ID . "');";
              echo "}";
              echo "</script>";


              
            }
            



            if ($value->SUM_TOTAL_PENJUALAN == 0) {
              echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#Modal_Edit' class='btn-edit' data-id='" . $value->ID . "'>";
              echo "<i class='icon feather icon-edit f-w-600 f-16 m-r-15 text-c-green'></i>";
              echo "</a>";



              echo "<a href='" . site_url('c_t_ak_faktur_penjualan/delete/' . $value->ID) . "' ";
              echo "onclick=\"return confirm('Apakah kamu yakin ingin menghapus data ini?')\"";
              echo "> <i class='feather icon-trash-2 f-w-600 f-16 text-c-red'></i></a>";


            }
            echo "</td>";


            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>





<!-- MODAL TAMBAH PEMASUKAN! !-->
<form action="<?php echo base_url('c_t_ak_faktur_penjualan/tambah') ?>" method="post">
  <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"> Tanggal Transaksi
            <form action='/action_page.php'>
              <input type='date' class='form-control' name='date' value='<?= $this->session->userdata('date_faktur_penjualan') ?>'>
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

                  <input type="checkbox" name="ppn" >
                  <label>PPN (10%)</label>
                  
                </fieldset>

              </div><!-- Membungkus Row Kedua !-->


              <div class="col-md-6">

                <fieldset class="form-group">
                  <input type="checkbox" name="pph" >
                  <label>PPH (0.25%)</label>
                  
                </fieldset>
              </div> <!-- Membungkus Row !-->
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
    <form action="<?php echo base_url('c_t_ak_faktur_penjualan/edit_action') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" value="" class="form-control">



          <div class="row">
              <div class="col-md-6">

                <fieldset class="form-group">

                  <input type="checkbox" name="ppn" >
                  <label>PPN (10%)</label>
                  
                </fieldset>

              </div><!-- Membungkus Row Kedua !-->


              <div class="col-md-6">

                <fieldset class="form-group">
                  <input type="checkbox" name="pph" >
                  <label>PPH (0.25%)</label>
                  
                </fieldset>
              </div> <!-- Membungkus Row !-->
            </div>
        </div>


        <div class="modal-footer">
          <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
          <button type="Submit" class="btn btn-primary waves-effect waves-light ">Save changes</button>
        </div>

      </div>


      <script>
        const read_data = <?= json_encode($c_t_ak_faktur_penjualan) ?>;
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
              PPN: ppn,
              PPH: pph
            } = User[0];

            elModalEdit.querySelector("[name=id]").value = ID;


            elModalEdit.querySelector("[name=ppn]").checked = ppn;
            elModalEdit.querySelector("[name=pph]").checked = pph;






          })
        })
      </script>

    </form>
  </div>
</div>



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



<script type="text/javascript">
  function filterFunction(that, event) {
    let container, input, filter, li, input_val;
    container = $(that).closest(".searchable");
    input_val = container.find("input").val().toUpperCase();

    if (["ArrowDown", "ArrowUp", "Enter"].indexOf(event.key) != -1) {
      keyControl(event, container)
    } else {
      li = container.find("ul li");
      li.each(function(i, obj) {
        if ($(this).text().toUpperCase().indexOf(input_val) > -1) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });

      container.find("ul li").removeClass("selected");
      setTimeout(function() {
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

  function onSelect(val) {
    alert(val)
  }

  $(".searchable input").focus(function() {
    $(this).closest(".searchable").find("ul").show();
    $(this).closest(".searchable").find("ul li").show();
  });
  $(".searchable input").blur(function() {
    let that = this;
    setTimeout(function() {
      $(that).closest(".searchable").find("ul").hide();
    }, 300);
  });

  $(document).on('click', '.searchable ul li', function() {
    $(this).closest(".searchable").find("input").val($(this).text()).blur();
    onSelect($(this).text())
  });

  $(".searchable ul li").hover(function() {
    $(this).closest(".searchable").find("ul li.selected").removeClass("selected");
    $(this).addClass("selected");
  });
</script>