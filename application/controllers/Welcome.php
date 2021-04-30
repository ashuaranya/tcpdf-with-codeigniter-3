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

		$html = '<h1>Welcome to tc pdf example</h1>
        <h2 style="color:red;">Ashutosh:</h2>
        <span style="color:red;">If you are using user-generated content, the tcpdf tag can be unsafe.<br />
        You can disable this tag by setting to false the <b>K_TCPDF_CALLS_IN_HTML</b> constant on TCPDF configuration file.</span>
        <h2>write1DBarcode method in HTML</h2>';

		//status => Store, Download, View ( Default )
		$this->htmltopdf($html, $status = 'View');
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

        $this->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $this->pdf->SetFont('helvetica', '', 10);

        $this->pdf->AddPage();
       
        $params = $this->pdf->serializeTCPDFtagParameters(array('CODE 39', 'C39', '', '', 80, 30, 0.4, array('position'=>'S', 'border'=>true, 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>4), 'N'));
        $html .= '<tcpdf method="write1DBarcode" params="'.$params.'" />';

        $params = $this->pdf->serializeTCPDFtagParameters(array('CODE 128', 'C128', '', '', 80, 30, 0.4, array('position'=>'S', 'border'=>true, 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>4), 'N'));
        $html .= '<tcpdf method="write1DBarcode" params="'.$params.'" />';

        $html .= '<tcpdf method="AddPage" /><h2>Graphic Functions</h2>';

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
	}
}
