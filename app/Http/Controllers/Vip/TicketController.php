<?php
namespace CodeDay\Http\Controllers\Vip;

use CodeDay\Http\Controllers;
use CodeDay\Models;
use JBDemonte\Barcode;
use Passbook\Pass;
use Passbook\Type\EventTicket;

class TicketController extends Controllers\Controller
{
    protected $ticket;

    public function __construct()
    {
        $this->ticket = Models\Ticket::find(\Route::input('ticket'));
        \View::share('ticket', $this->ticket);
    }

    public function getIndex()
    {
        $pdf = new \TCPDF('P', 'in', 'LETTER', true, 'UTF-8', false);

        //set margins
        $pdf->SetMargins(0.5, 0.5, 0.5);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        //set auto page breaks
        $pdf->SetAutoPageBreak(false);

        //set image scale factor
        $pdf->setImageScale(1);

        // set document information
        $pdf->SetCreator('Clear');
        $pdf->SetAuthor('StudentRND');
        $pdf->SetTitle('CodeDay Tickets');
        $pdf->SetSubject('CodeDay Tickets');
        $pdf->SetKeywords('codeday,ticket');

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('dejavusans', '', 14, '', true);

        $barcodeFile = tempnam(sys_get_temp_dir(), 'clear-barcode').'.png';
        imagepng($this->generateBarcode(), $barcodeFile); 

        $pdf->AddPage();
        $html = \View::make('vip/pdf', ['registration' => $this->ticket, 'barcode' => $barcodeFile])->render();
        $pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html);

        unlink($barcodeFile);

        $bin = $pdf->Output('codeday-tickets.pdf', 'S');

        return response($bin)
            ->header('Content-type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="codeday-tickets.pdf"');
    }

    public function getBarcode()
    {
        $response = \Response::make('', 200);
        // Images bigger than ~100x100px will cause PHP to flush the output buffer, so we need to send a header now
        // but images smaller than that won't cause any output buffering, so we need to return a response with the
        // proper header so it doesn't get overridden.
        //
        // This wouldn't be a problem if imagepng would return instead of echoing.
        header('Content-type: image/png');
        header('Cache-control: public,max-age=604800,no-transform');
        $response->header('Content-Type', 'image/png');
        $response->header('Cache-control', 'public,max-age=604800,no-transform');

        \imagepng($this->generateBarcode());
        return $response;
    }

    private function generateBarcode()
    {
        $im = \imagecreate(287, 95); 
        $black = \imagecolorallocate($im, 0, 0, 0);
        $white  = \imagecolorallocate($im, 255, 255, 255);

        imagefilledrectangle($im, 0, 0, 300, 120, $white);
        Barcode::gd($im, $black, 37, 37, 0, "datamatrix", $this->ticket->id, 4);
        Barcode::gd($im, $black, 185, 37, 0, "code128", $this->ticket->id, 1, 70);
        imagestring($im, 5, 63, 78, trim(chunk_split($this->ticket->id, 3, ' ')), $black);

        return $im;
    }

    public function getPassbook()
    {
        $pass = new EventTicket($this->ticket->id, $this->ticket->event['name']);
        $pass->setBackgroundColor('rgb(203, 121, 114)');
        $pass->setForegroundColor('rgb(255, 255, 255)');
        $pass->setSuppressStripShine(true);

        //$pass->setExpirationDate((new \Carbon\Carbon($registration->event->starts_at))->addDays(2)->timestamp());

        $structure = new Pass\Structure();

        $type = new Pass\Field('type', ucfirst($this->ticket->type));
        $type->setLabel('Ticket');
        $structure->addHeaderField($type);

        $primary = new Pass\Field('event', $this->ticket->event['name']);
        $primary->setLabel('Event');
        $structure->addPrimaryField($primary);

        $secondary = new Pass\Field('location', $this->ticket->event['venue']['name'] ? $this->ticket->event['venue']['name'] : 'TBA');
        $secondary->setLabel('Location');
        $structure->addSecondaryField($secondary);

        $sDate = new Pass\Field('date', date('M j, Y', $this->ticket->event['starts_at']));
        $sDate->setLabel('Starts');
        $sTime = new Pass\Field('time', '11am');
        $sTime->setLabel('Doors Open');
        $price = new Pass\Field('price', '$'.$this->ticket->amount_paid);
        $price->setLabel('Price');
        $structure->addAuxiliaryField($sDate);
        $structure->addAuxiliaryField($sTime);
        $structure->addAuxiliaryField($price);

        $pass->addImage(new Pass\Image(base_path().'/resources/img/pass.png', 'icon'));
        $pass->addImage(new Pass\Image(base_path().'/resources/img/logo.png', 'logo'));
        $pass->addImage(new Pass\Image(base_path().'/resources/img/jump.png', 'thumbnail'));

        // Set pass structure
        $pass->setStructure($structure);

        // Add barcode
        $barcode = new Pass\Barcode(Pass\Barcode::TYPE_QR, $this->ticket->id);
        $pass->setBarcode($barcode);

        $outDir = sys_get_temp_dir().'/'.str_random(10);
        $outFile = $outDir.'/'.$this->ticket->id.'.pkpass';
        $factory = new \Passbook\PassFactory(\Config::get('apple.passid'), \Config::get('apple.teamid'),
            \Config::get('apple.team'), base_path().'/resources/signing/'.\Config::get('apple.passid').'.p12',
            \Config::get('apple.passp12password'), base_path().'/resources/signing/apple_wwdrca.pem');
        $factory->setOutputPath($outDir);
        $factory->package($pass);

        $out = file_get_contents($outFile);

        unlink($outFile);
        rmdir($outDir);


        return response($out)
            ->header('Content-type', 'application/vnd.apple.pkpass')
            ->header('Content-Disposition', 'attachment; filename="codeday-tickets.pkpass"');
    }
}