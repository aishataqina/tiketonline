<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function downloadPDF(Ticket $ticket)
    {
        // Validasi kepemilikan tiket
        if ($ticket->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403, 'Anda tidak memiliki akses ke tiket ini.');
        }

        // Generate QR Code
        $qrCode = base64_encode(QrCode::format('png')
            ->size(200)
            ->generate($ticket->ticket_code));

        // Load logo jika ada
        $logoPath = public_path('images/logo.png');
        $logo = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;

        $pdf = PDF::loadView('tickets.pdf', [
            'ticket' => $ticket,
            'qrCode' => $qrCode,
            'logo' => $logo
        ]);

        return $pdf->download('ticket-' . $ticket->ticket_code . '.pdf');
    }
}
