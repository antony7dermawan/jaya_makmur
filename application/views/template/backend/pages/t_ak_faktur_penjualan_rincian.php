<div class="card">
  <div class="card-header">
    <?php
    #echo $faktur_penjualan_id;
    #echo $pks_id;

    $today = date('Y-m-d');

    $disabled = '';

    foreach ($c_t_ak_faktur_penjualan as $key => $value) {
      if ($value->ENABLE_EDIT == 0) {
        $disabled = 'disabled';
      }
    }

    ?>
    <form action='<?php echo base_url("c_t_ak_faktur_penjualan_rincian/create_faktur_penjualan/" . $faktur_penjualan_id . "/" . $pelanggan_id); ?>' class='no_voucer_area' method="post" id=''>
      <table>
        <tr>
          <th>
            <a href="<?= base_url("c_t_ak_faktur_penjualan"); ?>" class="btn waves-effect waves-light btn-inverse"><i class="icofont icofont-double-left"></i>Back</a>
          </th>
          <th>
            <form action='/action_page.php'>
              <input type='date' class='form-control' name='date_from_select_penjualan' value='<?php echo $this->session->userdata('date_from_select_penjualan'); ?>' <?= $disabled ?>>
          </th>
          <th>-</th>
          <th>
            <form action='/action_page.php'>
              <input type='date' class='form-control' name='date_to_select_penjualan' value='<?php echo $this->session->userdata('date_to_select_penjualan'); ?>' <?= $disabled ?>>
          </th>
          <th>
            <input type="submit" name="submit_no_voucer" class='btn btn-primary waves-effect waves-light' value="Create" <?= $disabled ?>>
          </th>
        </tr>
      </table>


    </form>
  </div>
  <div class="card-block">
    <!-- Menampilkan notif !-->
    <?= $this->session->flashdata('notif') ?>
    <!-- Tombol untuk menambah data akun !-->

    <div class="table-responsive dt-responsive">
      <table id="dom-jqry" class="table table-striped table-bordered nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th>Keterangan</th>
            <th>Tanggal Kirim</th>
            <th>INV Penjualan</th>

            
            <th>Jumlah Penjualan</th>

            <th>Jumlah Retur Jual</th>
            <th>Total</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($c_t_ak_faktur_penjualan_rincian as $key => $value) {
            echo "<tr>";
            echo "<td>" . ($key + 1) . "</td>";
            echo "<td>" . $value->KETERANGAN . "</td>";
            #echo "<td>".date('d-m-Y', strtotime($value->DATE))."</td>";
            echo "<td>" . $value->DATE . "</td>";
            echo "<td>" . $value->INV . "</td>";
            
            echo "<td>" . number_format(round($value->SUM_SUB_TOTAL)) . "</td>";

            echo "<td>" . number_format(round($value->SUM_RETUR)) . "</td>";


            $jumlah_akhir = round($value->SUM_SUB_TOTAL)-round($value->SUM_RETUR);
            echo "<td>" . number_format(round($jumlah_akhir)) . "</td>";


            echo "<td>";
            if($disabled=='')
            {
              echo "<a href='" . site_url('c_t_ak_faktur_penjualan_rincian/delete/' . $value->ID) . "/" . $value->PENJUALAN_ID ."/" . $faktur_penjualan_id ."/" . $pelanggan_id . "' ";
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
<form action="<?php echo base_url('c_t_ak_faktur_penjualan_rincian/tambah') ?>" method="post">
  <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="row">

            <div class="col-md-6">
              <fieldset class="form-group">
                <label>No Faktur</label>
                <input type='text' class='form-control' placeholder='Input Text' name='no_faktur'>
              </fieldset>
            </div><!-- Membungkus Row Kedua !-->


            <div class="col-md-6">
              <fieldset class="form-group">
                <label>PKS</label>
                <select name="pks_id" class='custom_width' id='select-state' placeholder='Pick a state...'>
                  <?php
                  foreach ($c_t_m_a_pks as $key => $value) {
                    echo "<option value='" . $value->PKS_ID . "'>" . $value->PKS . "</option>";
                  }
                  ?>
                </select>
              </fieldset>
            </div>


          </div> <!-- Membungkus Row !-->
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
    <form action="<?php echo base_url('c_t_ak_faktur_penjualan_rincian/edit_action') ?>" method="post">
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
                <label>No Faktur</label>
                <input type='text' class='form-control' placeholder='Input Text' name='no_faktur'>
              </fieldset>
            </div><!-- Membungkus Row Kedua !-->


            <div class="col-md-6">
              <fieldset class="form-group">
                <label>PKS</label>
                <select name="pks_id" class='custom_width' id='select-state' placeholder='Pick a state...'>
                  <?php
                  foreach ($c_t_m_a_pks as $key => $value) {
                    echo "<option value='" . $value->PKS_ID . "'>" . $value->PKS . "</option>";
                  }
                  ?>
                </select>
              </fieldset>
            </div>

          </div> <!-- Membungkus Row !-->
        </div>


        <div class="modal-footer">
          <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
          <button type="Submit" class="btn btn-primary waves-effect waves-light ">Save changes</button>
        </div>

      </div>


      <script>
        const read_data = <?= json_encode($c_t_ak_faktur_penjualan_rincian) ?>;
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
              NO_FAKTUR: no_faktur,
              PKS_ID: pks_id
            } = User[0];

            elModalEdit.querySelector("[name=id]").value = ID;


            elModalEdit.querySelector("[name=no_faktur]").value = no_faktur;
            elModalEdit.querySelector("[name=pks_id]").value = pks_id;






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