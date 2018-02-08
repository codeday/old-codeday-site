<?php

namespace CodeDay\Http\Controllers\Vip;

use CodeDay\Http\Controllers;
use CodeDay\Models;
use Carbon\Carbon;
define('FPDF_FONTPATH', base_path().'/resources/fonts');

class CertificateController extends Controllers\Controller
{
    public function getIndex()
    {
        $ticket = Models\Ticket::find(\Route::input('ticket'));
        // TODO(@tylermenezes): We should check if they've checked in, however Clear clears checkin time upon checkout.
        $ends_at = new Carbon($ticket->event['batch']['ends_at']);
        //if ($ends_at->gte(Carbon::now())) \App::abort(404);


        $pdf = new \FPDI('L', 'mm', 'Letter');
        $pdf->AddPage();
        $pdf->setSourceFile(base_path().'/resources/pdf/certificate.pdf');
        $tplIdx = $pdf->importPage(1);
        $pdf->useTemplate($tplIdx, 0, -1, 278);

        // now write some text above the imported page
        $pdf->AddFont('Avenir Next', 'B', 'hinted-AvenirNext-Bold.php');
        $pdf->SetFont('Avenir Next', 'B');
        $pdf->SetTextColor(31, 29, 30);

        $eventName = $ticket->event['region_name'].' '.$ticket->event['batch']['name'];
        $pdf->SetFontSize(16);
        $width = $pdf->GetStringWidth($eventName);
        $pdf->text(250-$width, 35, $eventName);

        $certificateName = $ticket->name;
        $pdf->SetFontSize(52);
        $pdf->Text(34, 105, $certificateName);

        $filename = 'certificate-of-completion.pdf';

        $bin = $pdf->Output($filename, 'S');
        return response($bin)
            ->header('Content-type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="'.$filename.'"');
    }
}
