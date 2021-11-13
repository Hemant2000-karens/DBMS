<?php

require('../dbconnection.php');
require('fpdf/fpdf.php');

session_start();
if(!isset($_SESSION["s_name"]))
{
    header("location: login.php");
}

if(isset($_POST["DOWNLOAD"])){

	$sale_id=$_POST["saleId"];
	
	$getorders= mysqli_query($db, "SELECT * from sale2 where sale_id = '".$sale_id."'");
    $sale =mysqli_fetch_array($getorders);

	$datetime=$sale['ordertime'];
    $date=date("d/M/Y",strtotime($datetime));
	$clid=$sale['customer_id'];
	$cost=$sale['cost'];
	$tax=$sale['tax'];
	$model=$sale['carmodel'];

	$get_cl=mysqli_query($db, "SELECT * from customer where c_id = '".$clid."'");
	$cl=mysqli_fetch_array($get_cl);

	$name=$cl['name'];
	$email=$cl['email'];
	$address=$cl['address'];
	$phone=$cl['phone'];
	
    $checkcar= mysqli_query($db, "SELECT * from model where model = '".$model."'");
    $car =mysqli_fetch_array($checkcar);

	$itemname=$car['name'];
	$m_id=$car['m_id'];

	$chk_brand=mysqli_query($db, "SELECT * from manufacturer where m_id = '".$m_id."'");
	$brand =mysqli_fetch_array($chk_brand);

	$b_name=$brand['name'];

class PDF extends FPDF {

	// Page header
	function Header() {
		
		// Add logo to page
		$this->Image('png1.png',10,10,33);
		
		// Set font family to Arial bold
		$this->SetFont('Arial','B',20);
		
		// Move to the right
		$this->Cell(80);
		
		// Header
		$this->Cell(45,10,'INVOICE',1,0,'C');
		
		// Line break
		$this->Ln(20);
	}

	// Page footer
	function Footer() {
		
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		
		// Page number
		$this->Cell(0,10,'Page ' .
			$this->PageNo() . '/{nb}',0,0,'C');
	}
}

// Instantiation of FPDF class
$pdf = new PDF();

// Define alias for number of pages
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',14);

$pdf->Cell(130	,5,'Showboys Adda',0,1);//end of line

//set font to arial, regular, 12pt
$pdf->SetFont('Arial','',12);

$pdf->Cell(130	,5,'Swami Shradhanand Marg',0,0);
$pdf->Cell(59	,5,'',0,1);//end of line

$pdf->Cell(130	,5,'New Delhi, Delhi, 110006',0,0);
$pdf->Cell(25	,5,'Date',0,0);
$pdf->Cell(34	,5,$date,0,1);//end of line

$pdf->Cell(130	,5,'Phone [+91-8319533126]',0,0);
$pdf->Cell(25	,5,'Invoice #',0,0);
$pdf->Cell(34	,5,$sale_id,0,1);//end of line

$pdf->Cell(130	,5,'Email [contact@showboyz.com]',0,0);
$pdf->Cell(25	,5,'Customer ID',0,0);
$pdf->Cell(34	,5,$clid,0,1);//end of line

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189	,10,'',0,1);//end of line

//billing address
$pdf->Cell(100	,5,'Bill to',0,1);//end of line

//add dummy cell at beginning of each line for indentation
$pdf->Cell(10	,5,'',0,0);
$pdf->Cell(90	,5,$name,0,1);

$pdf->Cell(10	,5,'',0,0);
$pdf->Cell(90	,5,$address,0,1);

$pdf->Cell(10	,5,'',0,0);
$pdf->Cell(90	,5,$phone,0,1);

$pdf->Cell(10	,5,'',0,0);
$pdf->Cell(90	,5,$email,0,1);

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189	,10,'',0,1);//end of line

//invoice contents
$pdf->SetFont('Arial','B',12);

$pdf->Cell(130	,5,'Description',1,0);
$pdf->Cell(25	,5,'Taxable',1,0);
$pdf->Cell(34	,5,'Amount',1,1);//end of line

$pdf->SetFont('Arial','',12);

//Numbers are right-aligned so we give 'R' after new line parameter

//items


	$pdf->Cell(130	,5,"$b_name $itemname",1,0);
	$pdf->Cell(25	,5,'tax',1,0);
	$pdf->Cell(34	,5,'amount',1,1,'R');//end of line
	


//summary
$pdf->Cell(130	,5,'',0,0);
$pdf->Cell(25	,5,'Subtotal',1,0);
$pdf->Cell(4	,5,'$',1,0);
$pdf->Cell(30	,5,$cost,1,1,'R');//end of line

$pdf->Cell(130	,5,'',0,0);
$pdf->Cell(25	,5,'Taxable',1,0);
$pdf->Cell(4	,5,'$',1,0);
$pdf->Cell(30	,5,$tax,1,1,'R');//end of line

$pdf->Cell(130	,5,'',0,0);
$pdf->Cell(25	,5,'Tax Rate',1,0);
$pdf->Cell(4	,5,'$',1,0);
$pdf->Cell(30	,5,'10%',1,1,'R');//end of line

$pdf->Cell(130	,5,'',0,0);
$pdf->Cell(25	,5,'Total Due',1,0);
$pdf->Cell(4	,5,'$',1,0);
$pdf->Cell(30	,5,$cost + $tax,1,1,'R');//end of line
$pdf->Output();
}
?>
