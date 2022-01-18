<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class PdfController extends Controller
{
    public function createPdf (Request $request) {
        //validate request
        Validator::validate($request->all(), [
            'name' => 'required|string|min:1',
            'content' => 'required|string|min:1'
        ]);

        //create PDF
        $mpdf = new Mpdf();

        $header = trim($request->get('header', ''));
        $footer = trim($request->get('footer', ''));

        if (strlen($header)) {
            $mpdf->SetHTMLHeader($header);
        }

        if (strlen($footer)) {
            $mpdf->SetHTMLFooter($footer);
        }

        if ($request->get('show_toc')) {
            $mpdf->h2toc = array(
                'H1' => 0,
                'H2' => 1,
                'H3' => 2,
                'H4' => 3,
                'H5' => 4,
                'H6' => 5
            );
            $mpdf->TOCpagebreak();
        }

        //write content
        $mpdf->WriteHTML($request->get('content'));

        //return the PDF for download
        return $mpdf->Output($request->get('name') . '.pdf', Destination::DOWNLOAD);
    }
}
