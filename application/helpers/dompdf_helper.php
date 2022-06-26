
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function pdf_create($html, $filename='', $paper, $orientation, $stream=TRUE) 
{
    // defined("DEBUG_LAYOUT_PADDINGBOX", false);

    require_once("application/helpers/dompdf/dompdf_config.inc.php");
    
    $dompdf = new DOMPDF();
    $dompdf->set_paper($paper,$orientation);
    $dompdf->load_html($html);
    $dompdf->render();
    if ($stream) {
        $canvas = $dompdf->get_canvas();
        // $font = Font_Metrics::get_font("helvetica", "bold");
        // $canvas->page_text(0, 0, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 8, array(0,0,0));
        
        $dompdf->stream($filename.".pdf",array("Attachment"=>0));
    } else {
        return $dompdf->output();
    }
}
?>