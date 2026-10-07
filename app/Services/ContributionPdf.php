<?php

namespace App\Services;

use App\Data\Contribution;
use FPDF;

class ContributionPdf
{
    public function generate(Contribution $record): string
    {
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(22, 20, 22);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();
        $pdf->SetTextColor(7, 30, 48);
        $pdf->SetFont('Helvetica', 'B', 20);
        $pdf->Cell(0, 12, $this->text('Mansion de Cristo'), 0, 1, 'C');
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(0, 8, $this->text('IGLESIA & COMUNIDAD'), 0, 1, 'C');
        $pdf->Ln(7);
        $pdf->SetFont('Helvetica', 'B', 15);
        $pdf->Cell(0, 10, $this->text('Comprobante de aporte'), 0, 1, 'C');
        if ($record->is_demo) {
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->Cell(0, 9, $this->text('DEMOSTRACIÓN · SIN VALIDEZ BANCARIA'), 0, 1, 'C');
        }
        $pdf->SetFont('Helvetica', 'B', 26);
        $pdf->Cell(0, 18, 'Bs. '.$record->formatted_amount, 0, 1, 'C');
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Cell(0, 9, $this->text($record->type), 0, 1, 'C');
        $pdf->Ln(6);
        $rows = [
            'N.º de aporte' => $record->number,
            'Nombre' => $record->first_name.' '.$record->last_name,
            'Cédula' => $record->identity,
            'Fecha del pago' => $record->paid_at->format('d/m/Y'),
            'Registrado' => $record->created_at->format('d/m/Y H:i'),
            'Método de pago' => $record->method,
            'Banco emisor' => $record->bank,
            'N.º de referencia' => $record->reference,
            'Estado' => match ($record->status) {
                'verified' => 'Verificado',
                'rejected' => 'Rechazado',
                default => 'En verificación',
            },
        ];
        if ($record->note) {
            $rows['Nota'] = $record->note;
        }
        foreach ($rows as $label => $value) {
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->Cell(0, 7, $this->text($label), 0, 1);
            $pdf->SetFont('Helvetica', '', 11);
            $pdf->MultiCell(0, 7, $this->text($value));
            $pdf->Ln(3);
        }
        $pdf->Ln(5);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->MultiCell(0, 6, $this->text('Este documento acredita el registro de la información. La recepción del dinero está sujeta a conciliación bancaria.'));
        $pdf->Ln(6);
        $pdf->SetFont('Helvetica', 'I', 10);
        $pdf->Cell(0, 7, $this->text('“Dios ama al dador alegre.” · 2 Corintios 9:7'), 0, 1, 'C');

        return $pdf->Output('S');
    }

    private function text(string $value): string
    {
        return mb_convert_encoding($value, 'Windows-1252', 'UTF-8');
    }
}
