<?php
include('phpqrcode/qrlib.php');

// outputs image directly into browser, as PNG stream


class QRHelper
{
    public function generarCodigo ($idViaje){

        $nombre = $idViaje.'-qr.png';
        QRcode::png($idViaje,  $nombre, QR_ECLEVEL_L, 3);
    }

}