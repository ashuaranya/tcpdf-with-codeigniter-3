<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{

		$html = '
		<style>
        table {
            width: 300px;
        }
		
        table, th, td {
			border: solid 1px #DDD;
            border-collapse: collapse;
            padding: 10px;
            text-align: center;
        }

    </style>
		<h1>Welcome to tc pdf example</h1>
        <h2 style="color:red;">Ashutosh:</h2>
        <span style="color:red;">If you are using user-generated content, the tcpdf tag can be unsafe.<br />
        You can disable this tag by setting to false the <b>K_TCPDF_CALLS_IN_HTML</b> constant on TCPDF configuration file.</span>
        <h2>write1DBarcode method in HTML</h2>
		<div id="tab">
        <table> 
				<tr>
				<th style="background-color: goldenrod;
				color: white;">Image</th>
					<th style="background-color: goldenrod;
					color: white;">Name</th>
						<th style="background-color: goldenrod;
						color: white;">Age</th>
							<th style="background-color: goldenrod;
							color: white;">Job</th>
				</tr>
				<tr>
				<td><img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixid=MnwxMjA3fDB8MHxzZWFyY2h8M3x8cHJvZHVjdHxlbnwwfHwwfHw%3D&ixlib=rb-1.2.1&w=1000&q=80" alt="" border="3" height="100" width="100" /></td>
					<td>Brian</td>
						<td>41</td>
							<td>Blogger</td>
				</tr>
				<tr>
				<td><img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixid=MnwxMjA3fDB8MHxzZWFyY2h8M3x8cHJvZHVjdHxlbnwwfHwwfHw%3D&ixlib=rb-1.2.1&w=1000&q=80" alt="" border="3" height="100" width="100" /></td>
				
					<td>Matt</td>
						<td>25</td>
							<td>Programmer</td>
				</tr>
				<tr>
				<td><img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixid=MnwxMjA3fDB8MHxzZWFyY2h8M3x8cHJvZHVjdHxlbnwwfHwwfHw%3D&ixlib=rb-1.2.1&w=1000&q=80" alt="" border="3" height="100" width="100" /></td>

				<td>Arun</td>
						<td>39</td>
							<td>Writter</td>
				</tr>
			</table>
		</div>

		</br></br>
		';


		//status => Store, Download, View ( Default )
		// $pdfName = $this->htmltopdf($html, $status = 'View');
		$pdfName = $this->barCodePrint($barCode = 123456, $status = 'View');
	}
	
	public function barCodePrint($barCode, $status){
		// create new PDF document
		$this->load->library('pdf');
		// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// remove default header/footer
		$this->pdf->setPrintHeader(false);
		$this->pdf->setPrintFooter(false);

		// set margins
		$this->pdf->SetMargins(0, 0, 0, true);

		// set auto page breaks false
		$this->pdf->SetAutoPageBreak(false, 0);

		// add a page
		$this->pdf->AddPage('P', 'A4');
		$img_file = FCPATH . 'assets/images/blank.jpg';
		// Display image on full page
		$this->pdf->Image($img_file, 0, 0, 210, 297, 'JPG', '', '', true, 200, '', false, false, 0, false, false, true);
		$this->pdf->SetXY(200, 200);
		// $this->pdf->WriteHTMLCell(200, 0, "12345", 0, 0, 'C');
		$this->pdf->WriteHTMLCell(58, 20, 20, 201, '<h1 style="color:white;font-size:30px">'.$barCode.'</h1>', 0, 0, false, true, 'C');


		$name = 'pdf_'. time() .'.pdf';
		
		if($status == "Download") {
			$this->pdf->Output($name, 'D');
		} else if($status == "Store") {
			$this->pdf->Output(FCPATH . 'assets/pdf/'.$name, 'F');
		} else {
			$this->pdf->Output($name, 'I');
		}        
		return $name;
	}

	public function htmltopdf( String $html, $status = '')
	{
		$this->load->library('pdf');

        $this->pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $this->pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $this->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		$img_file = FCPATH . 'assets/images/blank.jpg';

		$this->pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

        $this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $this->pdf->SetFont('helvetica', '', 10);

        $this->pdf->AddPage();
       
        $params = $this->pdf->serializeTCPDFtagParameters(array('CODE 39', 'C39', '', '', 80, 30, 0.4, array('position'=>'S', 'border'=>true, 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>4), 'N'));
        $html .= '<tcpdf method="write1DBarcode" params="'.$params.'" />';

        $params = $this->pdf->serializeTCPDFtagParameters(array('CODE 128', 'C128', '', '', 80, 30, 0.4, array('position'=>'S', 'border'=>true, 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>4), 'N'));
        $html .= '<tcpdf method="write1DBarcode" params="'.$params.'" />';

        $params = $this->pdf->serializeTCPDFtagParameters(array(0));
        $html .= '<tcpdf method="SetDrawColor" params="'.$params.'" />';

        $params = $this->pdf->serializeTCPDFtagParameters(array(50, 50, 40, 10, 'DF', array(), array(0,128,255)));
        $html .= '<tcpdf method="Rect" params="'.$params.'" />';


        $this->pdf->writeHTML($html, true, 0, true, 0);

        $this->pdf->lastPage();

		$name = 'pdf_'. time() .'.pdf';
		
		if($status == "Download") {
			$this->pdf->Output($name, 'D');
		} else if($status == "Store") {
			$this->pdf->Output(FCPATH . 'assets/pdf/'.$name, 'F');
		} else {
			$this->pdf->Output($name, 'I');
		}        
		return $name;
	}
}
