<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
/**
 * Pdf library for CI using TCPDF.
 *
 * @author Ashutosh Aranya
 */
class Pdf extends TCPDF {

    protected $_ci;

    /**
     * Pdf class constructor
     * 
     * @param $orientation (string) page orientation. Possible values are (case insensitive):<ul><li>P or Portrait (default)</li><li>L or Landscape</li><li>'' (empty string) for automatic orientation</li></ul>
     * @param $unit (string) User measure unit. Possible values are:<ul><li>pt: point</li><li>mm: millimeter (default)</li><li>cm: centimeter</li><li>in: inch</li></ul><br />A point equals 1/72 of inch, that is to say about 0.35 mm (an inch being 2.54 cm). This is a very common unit in typography; font sizes are expressed in that unit.
     * @param $format (mixed) The format used for pages. It can be either: one of the string values specified at getPageSizeFromFormat() or an array of parameters specified at setPageFormat().
     * @param $unicode (boolean) TRUE means that the input text is unicode (default = true)
     * @param $encoding (string) Charset encoding (used only when converting back html entities); default is UTF-8.
     * @param $pdfa (boolean) If TRUE set the document to PDF/A mode.
     */
    function __construct($orientation = PDF_PAGE_ORIENTATION, $unit = PDF_UNIT, $format = PDF_PAGE_FORMAT, $unicode = true, $encoding = 'UTF-8', $pdfa = false) {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $pdfa);
        $this->_ci = & get_instance();

        log_message('debug', 'Pdf Class Initialized');

        $this->_ci->load->config('tcpdf');

        if ($this->_ci->config->item('creator')):
            $this->SetCreator($this->_ci->config->item('creator'));
        endif;

        if ($this->_ci->config->item('author')):
            $this->SetAuthor($this->_ci->config->item('author'));
        endif;

        if ($this->_ci->config->item('title')):
            $this->SetTitle($this->_ci->config->item('title'));
        endif;

        if ($this->_ci->config->item('subject')):
            $this->SetSubject($this->_ci->config->item('subject'));
        endif;

        if ($this->_ci->config->item('keywords')):
            $this->SetKeywords($this->_ci->config->item('keywords'));
        endif;
    }

    /**
     * Overriding header of PDF
     */
    public function Header() {
        $bMargin = $this->getBreakMargin();
        $auto_page_break = $this->AutoPageBreak;
        
        // Logo
		$image_file = PDF_HEADER_LOGO;
		$this->Image($image_file, 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
	
        $this->SetAutoPageBreak(false, 0);
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
    }

    /**
     * Overriding footer of PDF
     */
    public function Footer() {
        // Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    public function htmltopdf($html){
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $this->_ci->config->item('title'), $this->_ci->config->item('keywords'));

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__).'/tcpdf/lang/eng.php')) {
            require_once(dirname(__FILE__).'/tcpdf/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->SetFont('helvetica', '', 10);

        $pdf->AddPage();

        $params = $this->serializeTCPDFtagParameters(array('CODE 39', 'C39', '', '', 80, 30, 0.4, array('position'=>'S', 'border'=>true, 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>4), 'N'));
        $html .= '<tcpdf method="write1DBarcode" params="'.$params.'" />';

        $params = $this->serializeTCPDFtagParameters(array('CODE 128', 'C128', '', '', 80, 30, 0.4, array('position'=>'S', 'border'=>true, 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>4), 'N'));
        $html .= '<tcpdf method="write1DBarcode" params="'.$params.'" />';

        $html .= '<tcpdf method="AddPage" /><h2>Graphic Functions</h2>';

        $params = $this->serializeTCPDFtagParameters(array(0));
        $html .= '<tcpdf method="SetDrawColor" params="'.$params.'" />';

        $params = $this->serializeTCPDFtagParameters(array(50, 50, 40, 10, 'DF', array(), array(0,128,255)));
        $html .= '<tcpdf method="Rect" params="'.$params.'" />';


        $pdf->writeHTML($html, true, 0, true, 0);

        $pdf->lastPage();
        $pdf->Output('example_049.pdf', 'I');
    }
}