<?php
defined('BASEPATH') or exit('No direct script access allowed');

class c_t_ak_jurnal_print extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();

    $this->load->model('m_t_ak_jurnal');
    $this->load->model('m_t_ak_jurnal_edit');
    $this->load->model('m_ak_m_coa');
    $this->load->model('m_ak_m_family');
    $this->load->model('m_ak_m_type');


    
  }

  public function index($created_id)
  {
    $pdf = new \TCPDF();
    $pdf->SetPrintHeader(false);
    $pdf->SetPrintFooter(false);
    $pdf->AddPage('P', 'mm', 'A4');
    $pdf->SetAutoPageBreak(true, 0);
 
        // Add Header
    
    #.............................paper head


    $pdf->SetFont('','B',12);
    $pdf->Cell(90, 9, "PT. JO PERDANA AGRI TECHNOLOGY", 0, 0, 'L');

    $pdf->SetFont('','B',18);
    $pdf->Cell(90, 9, "Bukti Jurnal", 0, 1, 'R');


    $pdf->SetFont('','',10);


    $read_select = $this->m_t_ak_jurnal->select_created_id($created_id);
    foreach ($read_select as $key => $value) 
    {
      $no_akun_1[$key]=$value->NO_AKUN_1;
      $no_akun_2[$key]=$value->NO_AKUN_2;
      $no_akun_3[$key]=$value->NO_AKUN_3;
      $nama_akun[$key]=$value->NAMA_AKUN;
      $debit[$key]=$value->DEBIT;
      $kredit[$key]=$value->KREDIT;
      $catatan[$key]=$value->CATATAN;
      $no_voucer=$value->NO_VOUCER;
      $tanggal=date('d-m-Y', strtotime($value->DATE));

                if($value->NO_AKUN_3!='')
                {
                  $no_akun[$key]=$value->NO_AKUN_3;
                }
                elseif($value->NO_AKUN_2!='')
                {
                  $no_akun[$key]=$value->NO_AKUN_2;
                }
                else
                {
                  $no_akun[$key]=$value->NO_AKUN_1;
                }
    }
    $total_akun = $key;



    $pdf->Cell(30, 6, "No Voucer", 1, 0, 'C');
    $pdf->Cell(60, 6, $no_voucer, 1, 1, 'L');
    $pdf->Cell(30, 6, "Tanggal", 1, 0, 'C');
    $pdf->Cell(60, 6, $tanggal, 1, 1, 'L');


    $pdf->Cell(30, 1, "", 0, 1, 'C');



    $pdf->Cell(25, 6, "No. Akun:", 1, 0, 'C');
    $pdf->Cell(50, 6, "Nama Akun", 1, 0, 'C');
    $pdf->Cell(30, 6, "Debit", 1, 0, 'C');
    $pdf->Cell(30, 6, "Kredit", 1, 0, 'C');
    $pdf->Cell(50, 6, "Catatan", 1, 1, 'C');

    $total_debit=0;
    $total_kredit=0;
    $total_baris_1_bon = 6;

    $total_kertas = 1;

    for($i=0;$i<=$total_akun;$i++)
    {
      $rmd=(float)($i/$total_baris_1_bon);
      $rmd=($rmd-(int)$rmd)*$total_baris_1_bon;


      $rmd2=(float)($total_kertas/2);
      $rmd2=($rmd2-(int)$rmd2)*2;


      if($i>=$total_baris_1_bon and $rmd==0 and $rmd2==0 and $total_kertas>0)
      {
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->AddPage('P', 'mm', 'A4');
        $total_kertas = $total_kertas +1;

        $pdf->SetFont('','B',12);
        $pdf->Cell(90, 11, "PT. JO PERDANA AGRI TECHNOLOGY", 0, 0, 'L');
        $pdf->SetFont('','B',18);
        $pdf->Cell(90, 11, "Bukti Jurnal", 0, 1, 'R');
        $pdf->SetFont('','',12);

        $pdf->Cell(30, 6, "No Voucer", 1, 0, 'C');
        $pdf->Cell(40, 6, $no_voucer, 1, 1, 'L');
        $pdf->Cell(30, 6, "Tanggal", 1, 0, 'C');
        $pdf->Cell(40, 6, $tanggal, 1, 1, 'L');


        $pdf->Cell(30, 1, "", 0, 1, 'C');

        $pdf->Cell(25, 6, "No. Akun:", 1, 0, 'C');
        $pdf->Cell(50, 6, "Nama Akun", 1, 0, 'C');
        $pdf->Cell(30, 6, "Debit", 1, 0, 'C');
        $pdf->Cell(30, 6, "Kredit", 1, 0, 'C');
        $pdf->Cell(50, 6, "Catatan", 1, 1, 'C');

      }



      if($i>=$total_baris_1_bon and $rmd==0 and $rmd2==1)
      {

        $pdf->Cell(40, 60, '', '0', 1, 'L');

        $total_kertas = $total_kertas +1;
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        


        $pdf->SetFont('','B',12);
        $pdf->Cell(90, 11, "PT. JO PERDANA AGRI TECHNOLOGY", 0, 0, 'L');
        $pdf->SetFont('','B',18);
        $pdf->Cell(90, 11, "Bukti Jurnal", 0, 1, 'R');
        $pdf->SetFont('','',12);

        $pdf->Cell(30, 6, "No Voucer", 1, 0, 'C');
        $pdf->Cell(40, 6, $no_voucer, 1, 1, 'L');
        $pdf->Cell(30, 6, "Tanggal", 1, 0, 'C');
        $pdf->Cell(40, 6, $tanggal, 1, 1, 'L');


        $pdf->Cell(30, 1, "", 0, 1, 'C');

        $pdf->Cell(25, 6, "No. Akun:", 1, 0, 'C');
        $pdf->Cell(50, 6, "Nama Akun", 1, 0, 'C');
        $pdf->Cell(30, 6, "Debit", 1, 0, 'C');
        $pdf->Cell(30, 6, "Kredit", 1, 0, 'C');
        $pdf->Cell(50, 6, "Catatan", 1, 1, 'C');

      }
      $pdf->SetFont('','',8);

      $pdf->MultiCell(25, 8, $no_akun[$i], 'L', 'L',0,0);
      $pdf->MultiCell(50, 8, substr($nama_akun[$i], 0, 60), 'L', 'L',0,0);
      $pdf->MultiCell(30, 8, number_format(intval($debit[$i])), 'L', 'C',0,0);
      $pdf->MultiCell(30, 8, number_format(intval($kredit[$i])), 'L', 'C',0,0);
      $pdf->MultiCell(50, 8, substr($catatan[$i], 0, 60), 'L', 'L',0,0);
      $pdf->Cell(0.01, 8, "", 'L', 1, 'C');

      $total_debit=$total_debit+intval($debit[$i]);
      $total_kredit=$total_kredit+intval($kredit[$i]);

    }

    if($total_baris_1_bon>$i)
    {
      for($x=0;$x<($total_baris_1_bon-$i);$x++)
      {
        $pdf->Cell(25, 8, "", 'L', 0, 'C');
        $pdf->Cell(50, 8, "", 'L', 0, 'R');
        $pdf->Cell(30, 8, "", 'L', 0, 'C');
        $pdf->Cell(30, 8, "", 'L', 0, 'C');
        $pdf->Cell(50, 8, "", 'L', 0, 'C');
        $pdf->Cell(0.01, 8, "", 'L', 1, 'C');
      }
    }
    if($total_baris_1_bon<$i)
    {
      $rmd=(float)($i/$total_baris_1_bon);
      $rmd=($rmd-(int)$rmd)*$total_baris_1_bon;
      for($x=0;$x<($total_baris_1_bon-$rmd);$x++)
      {
        $pdf->Cell(25, 8, "", 'L', 0, 'C');
        $pdf->Cell(50, 8, "", 'L', 0, 'R');
        $pdf->Cell(30, 8, "", 'L', 0, 'C');
        $pdf->Cell(30, 8, "", 'L', 0, 'C');
        $pdf->Cell(50, 8, "", 'L', 0, 'C');
        $pdf->Cell(0.01, 8, "", 'L', 1, 'C');
      }
    }

    $pdf->Cell(25, 6, "", 'T', 0, 'C');
    $pdf->Cell(50, 6, "Total", 1, 0, 'R');
    $pdf->Cell(30, 6, number_format(intval($total_debit)), 1, 0, 'C');
    $pdf->Cell(30, 6, number_format(intval($total_kredit)), 1, 0, 'C');
    $pdf->Cell(50, 6, "", 'T', 1, 'C');

    $pdf->Cell(1, 1, "", 0, 1, 'C');

    $pdf->Cell(25, 6, "Terbilang", 0, 0, 'R');
    $pdf->MultiCell(160, 6,'#'.ucwords($this->terbilang($total_debit)).' Rupiah#', 1, 'L',0,1);
    $pdf->Cell(25, 6, "Keterangan", 0, 0, 'R');
    $pdf->MultiCell(160, 6,$catatan[$total_akun], 1, 'L',0,1);

        $pdf->Cell(40, 6, "Dibuat:", 0, 0, 'C');
        $pdf->Cell(5, 6, "", 0, 0, 'C');
        $pdf->Cell(40, 6, "Diperiksa:", 0, 0, 'C');
        $pdf->Cell(5, 6, "", 0, 0, 'C');
        $pdf->Cell(40, 6, "Disetujui Oleh:", 0, 0, 'C');
        $pdf->Cell(5, 6, "", 0, 0, 'C');
        $pdf->Cell(40, 6, "Diketahui Oleh:", 0, 1, 'C');

        $pdf->Cell(40, 12, "", 0, 0, 'C');
        $pdf->Cell(5, 12, "", 0, 0, 'C');
        $pdf->Cell(40, 12, "", 0, 0, 'C');
        $pdf->Cell(5, 12, "", 0, 0, 'C');
        $pdf->Cell(40, 12, "", 0, 0, 'C');
        $pdf->Cell(5, 12, "", 0, 0, 'C');
        $pdf->Cell(40, 12, "", 0, 1, 'C');

        $pdf->Cell(40, 6, "Tgl:", 'T', 0, 'C');
        $pdf->Cell(5, 6, "", 0, 0, 'C');
        $pdf->Cell(40, 6, "Tgl:", 'T', 0, 'C');
        $pdf->Cell(5, 6, "", 0, 0, 'C');
        $pdf->Cell(40, 6, "Tgl:", 'T', 0, 'C');
        $pdf->Cell(5, 6, "", 0, 0, 'C');
        $pdf->Cell(40, 6, "Tgl:", 'T', 1, 'C');






    $pdf->Output("terima_pelanggan".$no_voucer.".pdf");
  }



  
function penyebut($nilai) {
    $nilai = abs($nilai);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 12) {
      $temp = " ". $huruf[$nilai];
    } else if ($nilai <20) {
      $temp = $this->penyebut($nilai - 10). " belas";
    } else if ($nilai < 100) {
      $temp = $this->penyebut($nilai/10)." puluh". $this->penyebut($nilai % 10);
    } else if ($nilai < 200) {
      $temp = " seratus" . $this->penyebut($nilai - 100);
    } else if ($nilai < 1000) {
      $temp = $this->penyebut($nilai/100) . " ratus" . $this->penyebut($nilai % 100);
    } else if ($nilai < 2000) {
      $temp = " seribu" . $this->penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
      $temp = $this->penyebut($nilai/1000) . " ribu" . $this->penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
      $temp = $this->penyebut($nilai/1000000) . " juta" . $this->penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
      $temp = $this->penyebut($nilai/1000000000) . " milyar" . $this->penyebut(fmod($nilai,1000000000));
    } else if ($nilai < 1000000000000000) {
      $temp = $this->penyebut($nilai/1000000000000) . " trilyun" . $this->penyebut(fmod($nilai,1000000000000));
    }     
    return $temp;
  }

  function terbilang($nilai) {
    if($nilai<0) {
      $hasil = "minus ". trim($this->penyebut($nilai));
    } else {
      $hasil = trim($this->penyebut($nilai));
    }         
    return $hasil;
  }
  



}
