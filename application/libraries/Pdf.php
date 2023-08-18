<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

class Pdf extends TCPDF {

    private $CI;
    var $nsi_header = FALSE;
    var $header_title = 'PT. BIA BUMI JAYENDRA';
    function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->database();
        parent::__construct();

        $this->SetTopMargin(35);
        $this->setRightMargin(0);
        $this->setLeftMargin(5);
        $this->setFooterMargin(10);

        $this->SetHeaderMargin(5);
        $this->SetAutoPageBreak(TRUE, 20);
        $this->SetAuthor('NSI');
        $this->SetDisplayMode('real', 'default');
        $this->SetFont('times','',8);

    }

    public function set_nsi_header() {

        $kop_surat = $this->CI->db->get('pmm_setting_production')->row_array();
        $this->nsi_header = '<img src="uploads/kop_surat/'.$kop_surat['kop_surat'].'">';
    }

    public function set_header_title($header_title) {
        
        $this->header_title = $header_title;
    }

    public function nsi_html($html) {
        $this->SetFont('times', '', 8);
        $this->writeHTML($html, true, false, true, false, '');
    }

    public function nsi_box($text = '', $width = '100%', $spacing = '0', $padding = '10', $border = '0', $align = 'center') {
        $out = '
            <table width="'.$width.'" cellspacing="'.$spacing.'" cellpadding="'.$padding.'" border="'.$border.'">
                <tr>
                    <td align="'.$align.'">'.$text.'</td>
                </tr>
            </table>
        ';
        return $out;
    }


    public function Header() {

        $this->set_nsi_header();
        $this->SetFont('times', '', 8);
        $this->writeHTMLCell(
            $w = 0, $h = 0, $x = 0, $y = 0,
            $this->nsi_header, $border = 0, $ln = 1, $fill = 0,
            $reseth = true, $align = 'top', $autopadding = true);
        $posisi_y = $this->getY();
        $this->SetTopMargin(40);

    }

    public function Footer() {
        $style = array();
        $this->SetFont('times', 'I', 8);
        $this->Cell(0, 10, 'Do Something Big Today', 0, 0,'C');
    }
    
} 