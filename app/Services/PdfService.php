<?php

namespace App\Services;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    public function generateTransferReceipt(Transaction $transaction): string
    {
        $qrData = route('transaction.show', ['id' => $transaction->id]);

        $qr = Builder::create()
            ->writer(new PngWriter())
            ->data($qrData)
            ->size(220)
            ->margin(2)
            ->build();

        $qrDataUri = $qr->getDataUri();

        $pdf = DomPdf::loadView('pdf.transfer_receipt', [
            'transaction' => $transaction->load(['sender.user', 'receiver.user']),
            'qrDataUri' => $qrDataUri,
        ])->setPaper('a4');

        $fileName = 'receipt_'.$transaction->id.'.pdf';
        $relativePath = 'receipts/'.$fileName;
        Storage::disk('local')->put($relativePath, $pdf->output());

        return Storage::disk('local')->path($relativePath);
    }
}


