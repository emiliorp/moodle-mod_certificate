<?php

// This file is part of the Certificate module for Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * letter_non_embedded certificate type
 *
 * @package    mod_certificate
 * @copyright  Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$pdf = new PDF($certificate->orientation, 'pt', 'A4', true, 'UTF-8', false);

$pdf->SetAuthor('Ananda Gestión, S.L.');

$pdf->SetTitle('Certificado de Acreditación');
$pdf->SetSubject('Curso Prevención de Riesgos Laborales');

$pdf->SetMargins(75, 20, 75, true);

$pdf->SetProtection(array('modify'));
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(false, 0);
$pdf->AddPage(); //página 1

// Define variables
// Landscape
if ($certificate->orientation == 'L') {
    $x = 28;
    $y = 125;
    $sealx = 590;
    $sealy = 425;
    $sigx = 130;
    $sigy = 440;
    $custx = 133;
    $custy = 440;
    $wmarkx = 100;
    $wmarky = 90;
    $wmarkw = 600;
    $wmarkh = 420;
    $brdrx = 0;
    $brdry = 0;
    $brdrw = 792;
    $brdrh = 612;
    $codey = 505;
} else { // Portrait
    $x = 28;
    $y = 170;
    $sealx = 440;
    $sealy = 590;
    $sigx = 85;
    $sigy = 580;
    $custx = 88;
    $custy = 580;
    $wmarkx = 78;
    $wmarky = 130;
    $wmarkw = 450;
    $wmarkh = 480;
    $brdrx = 10;
    $brdry = 10;
    $brdrw = 594;
    $brdrh = 771;
    $codey = 660;
}

// Transparencia
$pdf->SetAlpha(0.05);
certificate_print_image($pdf, $certificate, 'watermarks', 80, 130, 700, 680); // Marca de Agua
$pdf->SetAlpha(1);
certificate_print_image($pdf, $certificate, 'seals', 40, 40, 200, 47, '', ''); // Logotipo 
//certificate_print_image($pdf, $certificate, 'signatures', 110, 540, 110, 59); // Firma

$fecha_f_str = certificate_get_date($certificate, $certrecord, $course);
$fecha_f_array = preg_split ("/[\s,]+/", $fecha_f_str); 

$fecha_f_01 = $fecha_f_array[0];
$fecha_f_02 = $fecha_f_array[1];
$fecha_f_03 = $fecha_f_array[2];

setlocale(LC_TIME,"es_ES");

$nota_str = certificate_get_grade($certificate, $course);
$nota_str_array = explode(" ", $nota_str);
$nota = $nota_str_array[2];

// Añadir texto
$pdf->SetTextColor(60, 176, 70);
certificate_print_text($pdf, 80, 140, 'C', 'Helvetica', '', 23, 'CERTIFICADO DE PARTICIPACIÓN'); // Tipo de certificado
$pdf->SetTextColor(199, 12, 70);
certificate_print_text($pdf, 80, 170, 'C', 'Helvetica', '', 20, '<b>"PREVENCIÓN DE RIESGOS LABORALES"</b>'); // Tipo de certificado
$pdf->SetTextColor(0, 0, 0);
certificate_print_text($pdf, 75, 230, 'C', 'freesans', '', 14, 'Otorgado a D/Dª <b>'.$USER->firstname.' '.$USER->lastname.'</b>, con DNI: <b>'.$USER->idnumber.'</b>, al haber superado con éxito el módulo formativo correspondiente, obteniendo una calificación en el exámen final de <b>'.$nota.'</b> puntos.');
certificate_print_text($pdf, 75, 310, 'C', 'freesans', '', 14, 'Este curso ha sido coordinado e impartido por <b>Ananda Gestión ETT</b> a través de su plataforma virtual de formación, y finalizado por el alumno/a el día <b>'.$fecha_f_01.' de '.$fecha_f_02.' de '.$fecha_f_03.'</b>.');
certificate_print_text($pdf, 75, 390, 'C', 'freesans', '', 14, 'Y para que así conste, se expide este certificado en Madrid a '.date("d").' '.strftime("de %B de %Y").'.');

certificate_print_text($pdf, 97, 500, 'L', 'freesans', '', 10, '<b>Firma y sello de la empresa</b>');
certificate_print_text($pdf, 90, 630, 'L', 'freesans', '', 9, '<i>Habiendo promovido esta formación</i>');
certificate_print_text($pdf, 110, 642, 'L', 'freesans', '', 9, '<i>para el alumno participante</i>');

certificate_print_text($pdf, 385, 500, 'L', 'freesans', '', 10, '<b>Firma del trabajador</b>');
certificate_print_text($pdf, 370, 630, 'L', 'freesans', '', 9, '<i>Reconociendo la participación y</i>');
certificate_print_text($pdf, 375, 642, 'L', 'freesans', '', 9, '<i>recepción de esta Formación</i>');

certificate_print_text($pdf, 75, 780, 'C', 'freesans', '', 10, 'Según el artículo 19 de la Ley 31/1995. de noviembre, de Prevención de Riesgos Laborales');

//página 2

$pdf->AddPage();
certificate_print_text($pdf, 75, 45, 'C', 'freesans', '', 14, '<b>Contenidos impartidos:</b>');
certificate_print_text($pdf, 70, 80, 'L', 'freesans', '', 12, '01. Conceptos básicos');
certificate_print_text($pdf, 70, 95, 'L', 'freesans', '', 12, '02. Derechos y deberes');
certificate_print_text($pdf, 70, 110, 'L', 'freesans', '', 12, '03. Riesgos ligados a los viajes laborales');
certificate_print_text($pdf, 70, 125, 'L', 'freesans', '', 12, '04. Riesgos ligados a la superficie de trabajo');
certificate_print_text($pdf, 70, 140, 'L', 'freesans', '', 12, '05. Riesgos ligados al uso de herramientas');
certificate_print_text($pdf, 70, 155, 'L', 'freesans', '', 12, '06. Riesgos ligados al uso de equipos de trabajo y electricidad');
certificate_print_text($pdf, 70, 170, 'L', 'freesans', '', 12, '07. Riesgo de incendio');
certificate_print_text($pdf, 70, 185, 'L', 'freesans', '', 12, '08. Riesgos ligados al almacenamiento, manipulación y transporte');
certificate_print_text($pdf, 70, 200, 'L', 'freesans', '', 12, '09. Riesgos ligados al medio ambiente de trabajo');
certificate_print_text($pdf, 70, 215, 'L', 'freesans', '', 12, '10. Sistemas elementales de control de riesgo');
certificate_print_text($pdf, 70, 230, 'L', 'freesans', '', 12, '11. Nociones básicas de actuación de emergencia');
certificate_print_text($pdf, 70, 245, 'L', 'freesans', '', 12, '12. Control de la salud de los trabajadores');

//$pdf->Output('example_058.pdf', 'D'); //Por si se quiere descargar directamente