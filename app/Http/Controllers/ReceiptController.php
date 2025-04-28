<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
// Kalau pakai Linux bisa ganti dengan: use Mike42\Escpos\PrintConnectors\CupsPrintConnector;

class ReceiptController extends Controller
{
    public function print()
    {
        try {
            // Ganti 'POS-58' dengan nama printer yang terhubung (cek di Control Panel > Printers)
            $connector = new WindowsPrintConnector("POS-58");
            $printer = new Printer($connector);

            // Format struk
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("TOKO CONTOH\n");
            $printer->setEmphasis(false);

            $printer->text("Jl. Mawar No. 123\n");
            $printer->text("==============================\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Barang A    2 x 5.000\n");
            $printer->text("Barang B    1 x 10.000\n");
            $printer->text("------------------------------\n");

            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("Total: Rp20.000\n");
            $printer->text("==============================\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Terima kasih!\n");

            $printer->cut(); // Potong kertas
            $printer->close(); // Tutup koneksi printer

            return response()->json(['message' => 'Struk berhasil dicetak']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
