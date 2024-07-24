<?php
require './vendor/autoload.php'; // Asegúrate de que la ruta sea correcta

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function export_to_excel() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'customers';

    // Consulta los datos que quieres exportar
    $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

    // Crear un nuevo Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Clientes');

    // Agregar encabezados
    $headers = array('ID', 'Nombre de la empresa', 'Nombre del cliente', 'Correo electrónico', 'Teléfono', 'Dirección', 'Activo', 'Tipo de paquete');
    $sheet->fromArray($headers, NULL, 'A1');

    // Agregar los datos
    $rowNumber = 2;
    foreach ($results as $row) {
        $sheet->setCellValue('A' . $rowNumber, $row['id']);
        $sheet->setCellValue('B' . $rowNumber, $row['company_name']);
        $sheet->setCellValue('C' . $rowNumber, $row['customer_name']);
        $sheet->setCellValue('D' . $rowNumber, $row['email']);
        $sheet->setCellValue('E' . $rowNumber, $row['phone_number']);
        $sheet->setCellValue('F' . $rowNumber, $row['address']);
        $sheet->setCellValue('G' . $rowNumber, $row['is_active'] ? 'Sí' : 'No');
        $sheet->setCellValue('H' . $rowNumber, $row['package_type_id']);
        $rowNumber++;
    }

    // Crear un Writer y exportar el archivo
    $writer = new Xlsx($spreadsheet);
    $filename = 'clientes.xlsx';

    // Enviar el archivo al navegador
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
}

// Hook para la acción de exportar
add_action('wp_ajax_export_to_excel', 'export_to_excel');
add_action('wp_ajax_nopriv_export_to_excel', 'export_to_excel'); // Para usuarios no autenticados si es necesario
