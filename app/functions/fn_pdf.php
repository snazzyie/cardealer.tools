<?php
/**
 * PDF Generation Functions
 * Generate PDFs for invoices, reports, etc.
 * Uses TCPDF library or HTML rendering
 */

/**
 * Generate invoice PDF
 *
 * @param int $invoiceId Invoice ID
 * @param string $type Invoice type (sales or service)
 * @param int $companyId Company ID
 * @return string|false PDF file path or false
 */
function fn_pdf_generate_invoice($invoiceId, $type, $companyId) {
    if ($type === 'sales') {
        return fn_pdf_generate_sales_invoice($invoiceId, $companyId);
    } else if ($type === 'service') {
        return fn_pdf_generate_service_invoice($invoiceId, $companyId);
    }
    return false;
}

/**
 * Generate sales invoice PDF
 *
 * @param int $invoiceId Invoice ID
 * @param int $companyId Company ID
 * @return string|false PDF file path or false
 */
function fn_pdf_generate_sales_invoice($invoiceId, $companyId) {
    // Get invoice data
    $query = "SELECT si.*, c.first_name, c.last_name, c.email, c.phone, c.address_line1, c.city, c.postcode,
              v.make, v.model, v.year, v.registration, v.vin,
              comp.company_name, comp.company_address, comp.company_phone, comp.company_email, comp.vat_number, comp.logo_url
              FROM sales_invoices si
              LEFT JOIN customers c ON si.customer_id = c.customer_id
              LEFT JOIN vehicles v ON si.vehicle_id = v.vehicle_id
              LEFT JOIN core_company comp ON si.company_id = comp.company_id
              WHERE si.invoice_id = ? AND si.company_id = ?";

    $invoice = fn_core_database_row($query, [$invoiceId, $companyId]);

    if (!$invoice) {
        return false;
    }

    // Generate HTML
    $html = fn_pdf_sales_invoice_template($invoice);

    // Convert HTML to PDF
    $pdfPath = fn_pdf_html_to_pdf($html, "invoice_{$invoice['invoice_number']}.pdf");

    if ($pdfPath) {
        // Update invoice with PDF path
        $query = "UPDATE sales_invoices SET invoice_pdf_url = ? WHERE invoice_id = ?";
        fn_core_edit_row_no_redirect($query, [$pdfPath, $invoiceId]);
    }

    return $pdfPath;
}

/**
 * Generate service invoice PDF
 *
 * @param int $invoiceId Invoice ID
 * @param int $companyId Company ID
 * @return string|false PDF file path or false
 */
function fn_pdf_generate_service_invoice($invoiceId, $companyId) {
    // Get invoice data
    $query = "SELECT si.*, c.first_name, c.last_name, c.email, c.phone, c.address_line1, c.city, c.postcode,
              comp.company_name, comp.company_address, comp.company_phone, comp.company_email, comp.vat_number, comp.logo_url
              FROM service_invoices si
              LEFT JOIN customers c ON si.customer_id = c.customer_id
              LEFT JOIN core_company comp ON si.company_id = comp.company_id
              WHERE si.service_invoice_id = ? AND si.company_id = ?";

    $invoice = fn_core_database_row($query, [$invoiceId, $companyId]);

    if (!$invoice) {
        return false;
    }

    // Decode line items
    if (!empty($invoice['line_items'])) {
        $invoice['line_items'] = json_decode($invoice['line_items'], true);
    }

    // Generate HTML
    $html = fn_pdf_service_invoice_template($invoice);

    // Convert HTML to PDF
    $pdfPath = fn_pdf_html_to_pdf($html, "service_invoice_{$invoice['invoice_number']}.pdf");

    if ($pdfPath) {
        // Update invoice with PDF path
        $query = "UPDATE service_invoices SET invoice_pdf_url = ? WHERE service_invoice_id = ?";
        fn_core_edit_row_no_redirect($query, [$pdfPath, $invoiceId]);
    }

    return $pdfPath;
}

/**
 * Convert HTML to PDF
 * Uses TCPDF if available, otherwise creates a simplified PDF
 *
 * @param string $html HTML content
 * @param string $filename Output filename
 * @return string|false PDF file path or false
 */
function fn_pdf_html_to_pdf($html, $filename) {
    // Check if TCPDF is available
    if (class_exists('TCPDF')) {
        return fn_pdf_tcpdf_generate($html, $filename);
    }

    // Fallback: Save as HTML (can be printed to PDF by browser)
    $uploadDir = BASE_PATH . 'storage/invoices/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filepath = $uploadDir . $filename;
    file_put_contents($filepath, $html);

    return '/storage/invoices/' . $filename;
}

/**
 * Generate PDF using TCPDF
 *
 * @param string $html HTML content
 * @param string $filename Output filename
 * @return string|false PDF file path or false
 */
function fn_pdf_tcpdf_generate($html, $filename) {
    try {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator('Car Dealer SaaS');
        $pdf->SetAuthor('Car Dealer SaaS');
        $pdf->SetTitle('Invoice');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);

        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');

        $uploadDir = BASE_PATH . 'storage/invoices/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filepath = $uploadDir . $filename;
        $pdf->Output($filepath, 'F');

        return '/storage/invoices/' . $filename;

    } catch (Exception $e) {
        error_log("PDF generation error: " . $e->getMessage());
        return false;
    }
}

/**
 * Sales invoice HTML template
 *
 * @param array $invoice Invoice data
 * @return string HTML
 */
function fn_pdf_sales_invoice_template($invoice) {
    ob_start();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
            .invoice-header { margin-bottom: 30px; }
            .company-info { float: left; width: 50%; }
            .invoice-info { float: right; width: 50%; text-align: right; }
            .logo { max-width: 200px; height: auto; margin-bottom: 10px; }
            .customer-info { margin: 30px 0; padding: 15px; background: #f5f5f5; }
            .invoice-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            .invoice-table th { background: #333; color: white; padding: 10px; text-align: left; }
            .invoice-table td { padding: 10px; border-bottom: 1px solid #ddd; }
            .totals { float: right; width: 300px; margin-top: 20px; }
            .totals table { width: 100%; }
            .totals td { padding: 8px; }
            .total-row { font-weight: bold; font-size: 14px; background: #f5f5f5; }
            .clear { clear: both; }
            .terms { margin-top: 40px; font-size: 10px; color: #666; }
        </style>
    </head>
    <body>
        <div class="invoice-header">
            <div class="company-info">
                <?php if ($invoice['logo_url']): ?>
                    <img src="<?= $invoice['logo_url'] ?>" alt="Logo" class="logo">
                <?php endif; ?>
                <h2><?= htmlspecialchars($invoice['company_name']) ?></h2>
                <p>
                    <?= htmlspecialchars($invoice['company_address']) ?><br>
                    <?= htmlspecialchars($invoice['company_phone']) ?><br>
                    <?= htmlspecialchars($invoice['company_email']) ?><br>
                    VAT: <?= htmlspecialchars($invoice['vat_number']) ?>
                </p>
            </div>

            <div class="invoice-info">
                <h1>SALES INVOICE</h1>
                <p>
                    <strong>Invoice #:</strong> <?= htmlspecialchars($invoice['invoice_number']) ?><br>
                    <strong>Date:</strong> <?= date('d/m/Y', strtotime($invoice['invoice_date'])) ?><br>
                    <strong>Due Date:</strong> <?= $invoice['due_date'] ? date('d/m/Y', strtotime($invoice['due_date'])) : 'N/A' ?>
                </p>
            </div>
            <div class="clear"></div>
        </div>

        <div class="customer-info">
            <h3>Bill To:</h3>
            <p>
                <strong><?= htmlspecialchars($invoice['customer_name']) ?></strong><br>
                <?= htmlspecialchars($invoice['customer_email']) ?><br>
                <?= htmlspecialchars($invoice['customer_phone']) ?><br>
                <?php if ($invoice['address_line1']): ?>
                    <?= htmlspecialchars($invoice['address_line1']) ?><br>
                    <?= htmlspecialchars($invoice['city']) ?> <?= htmlspecialchars($invoice['postcode']) ?>
                <?php endif; ?>
            </p>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($invoice['vehicle_id']): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($invoice['year'] . ' ' . $invoice['make'] . ' ' . $invoice['model']) ?></strong><br>
                            Registration: <?= htmlspecialchars($invoice['registration']) ?><br>
                            <?php if ($invoice['vin']): ?>VIN: <?= htmlspecialchars($invoice['vin']) ?><br><?php endif; ?>
                        </td>
                        <td style="text-align: right;">€<?= number_format($invoice['subtotal'], 2) ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td style="text-align: right;">€<?= number_format($invoice['subtotal'], 2) ?></td>
                </tr>
                <tr>
                    <td>VAT (<?= $invoice['vat_rate'] ?>%):</td>
                    <td style="text-align: right;">€<?= number_format($invoice['vat_amount'], 2) ?></td>
                </tr>
                <?php if ($invoice['deposit_amount'] > 0): ?>
                    <tr>
                        <td>Deposit Paid:</td>
                        <td style="text-align: right;">-€<?= number_format($invoice['deposit_amount'], 2) ?></td>
                    </tr>
                <?php endif; ?>
                <tr class="total-row">
                    <td>Total:</td>
                    <td style="text-align: right;">€<?= number_format($invoice['total'], 2) ?></td>
                </tr>
                <?php if ($invoice['balance_due'] > 0): ?>
                    <tr class="total-row">
                        <td>Balance Due:</td>
                        <td style="text-align: right;">€<?= number_format($invoice['balance_due'], 2) ?></td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
        <div class="clear"></div>

        <?php if ($invoice['notes']): ?>
            <div style="margin-top: 30px;">
                <h4>Notes:</h4>
                <p><?= nl2br(htmlspecialchars($invoice['notes'])) ?></p>
            </div>
        <?php endif; ?>

        <div class="terms">
            <h4>Terms & Conditions:</h4>
            <p><?= nl2br(htmlspecialchars($invoice['terms'] ?? 'Payment due within 30 days. Late payments subject to interest charges.')) ?></p>
        </div>
    </body>
    </html>
    <?php
    return ob_get_clean();
}

/**
 * Service invoice HTML template
 *
 * @param array $invoice Invoice data
 * @return string HTML
 */
function fn_pdf_service_invoice_template($invoice) {
    ob_start();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
            .invoice-header { margin-bottom: 30px; }
            .company-info { float: left; width: 50%; }
            .invoice-info { float: right; width: 50%; text-align: right; }
            .logo { max-width: 200px; height: auto; margin-bottom: 10px; }
            .customer-info { margin: 30px 0; padding: 15px; background: #f5f5f5; }
            .invoice-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            .invoice-table th { background: #333; color: white; padding: 10px; text-align: left; }
            .invoice-table td { padding: 10px; border-bottom: 1px solid #ddd; }
            .totals { float: right; width: 300px; margin-top: 20px; }
            .totals table { width: 100%; }
            .totals td { padding: 8px; }
            .total-row { font-weight: bold; font-size: 14px; background: #f5f5f5; }
            .clear { clear: both; }
            .terms { margin-top: 40px; font-size: 10px; color: #666; }
        </style>
    </head>
    <body>
        <div class="invoice-header">
            <div class="company-info">
                <?php if ($invoice['logo_url']): ?>
                    <img src="<?= $invoice['logo_url'] ?>" alt="Logo" class="logo">
                <?php endif; ?>
                <h2><?= htmlspecialchars($invoice['company_name']) ?></h2>
                <p>
                    <?= htmlspecialchars($invoice['company_address']) ?><br>
                    <?= htmlspecialchars($invoice['company_phone']) ?><br>
                    <?= htmlspecialchars($invoice['company_email']) ?><br>
                    VAT: <?= htmlspecialchars($invoice['vat_number']) ?>
                </p>
            </div>

            <div class="invoice-info">
                <h1>SERVICE INVOICE</h1>
                <p>
                    <strong>Invoice #:</strong> <?= htmlspecialchars($invoice['invoice_number']) ?><br>
                    <strong>Date:</strong> <?= date('d/m/Y', strtotime($invoice['invoice_date'])) ?><br>
                    <strong>Service Date:</strong> <?= date('d/m/Y', strtotime($invoice['service_date'])) ?>
                </p>
            </div>
            <div class="clear"></div>
        </div>

        <div class="customer-info">
            <div style="float: left; width: 50%;">
                <h3>Bill To:</h3>
                <p>
                    <strong><?= htmlspecialchars($invoice['customer_name']) ?></strong><br>
                    <?= htmlspecialchars($invoice['customer_email']) ?><br>
                    <?= htmlspecialchars($invoice['customer_phone']) ?>
                </p>
            </div>
            <div style="float: right; width: 50%;">
                <h3>Service Details:</h3>
                <p>
                    <strong>Type:</strong> <?= htmlspecialchars($invoice['service_type']) ?><br>
                    <?php if ($invoice['vehicle_registration']): ?>
                        <strong>Vehicle:</strong> <?= htmlspecialchars($invoice['vehicle_registration']) ?><br>
                    <?php endif; ?>
                    <?php if ($invoice['mileage']): ?>
                        <strong>Mileage:</strong> <?= number_format($invoice['mileage']) ?> km
                    <?php endif; ?>
                </p>
            </div>
            <div class="clear"></div>
        </div>

        <?php if ($invoice['work_performed']): ?>
            <div style="margin: 20px 0;">
                <h3>Work Performed:</h3>
                <p><?= nl2br(htmlspecialchars($invoice['work_performed'])) ?></p>
            </div>
        <?php endif; ?>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Type</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Unit Price</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoice['line_items'] as $item): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($item['item_name']) ?></strong>
                            <?php if (!empty($item['description'])): ?>
                                <br><small><?= htmlspecialchars($item['description']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?= $item['item_type'] === 'service' ? 'Labor' : 'Part' ?></td>
                        <td style="text-align: center;"><?= number_format($item['quantity'], 1) ?></td>
                        <td style="text-align: right;">€<?= number_format($item['unit_price'], 2) ?></td>
                        <td style="text-align: right;">€<?= number_format($item['quantity'] * $item['unit_price'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="totals">
            <table>
                <tr>
                    <td>Labor Total:</td>
                    <td style="text-align: right;">€<?= number_format($invoice['labor_total'], 2) ?></td>
                </tr>
                <tr>
                    <td>Parts Total:</td>
                    <td style="text-align: right;">€<?= number_format($invoice['parts_total'], 2) ?></td>
                </tr>
                <tr>
                    <td>Subtotal:</td>
                    <td style="text-align: right;">€<?= number_format($invoice['subtotal'], 2) ?></td>
                </tr>
                <tr>
                    <td>VAT (<?= $invoice['vat_rate'] ?>%):</td>
                    <td style="text-align: right;">€<?= number_format($invoice['vat_amount'], 2) ?></td>
                </tr>
                <tr class="total-row">
                    <td>Total:</td>
                    <td style="text-align: right;">€<?= number_format($invoice['total'], 2) ?></td>
                </tr>
            </table>
        </div>
        <div class="clear"></div>

        <?php if ($invoice['notes']): ?>
            <div style="margin-top: 30px;">
                <h4>Notes:</h4>
                <p><?= nl2br(htmlspecialchars($invoice['notes'])) ?></p>
            </div>
        <?php endif; ?>

        <div class="terms">
            <h4>Terms & Conditions:</h4>
            <p>Payment due within 30 days. All work guaranteed for 90 days or 5,000km, whichever comes first.</p>
        </div>
    </body>
    </html>
    <?php
    return ob_get_clean();
}

/**
 * Email invoice PDF to customer
 *
 * @param int $invoiceId Invoice ID
 * @param string $type Invoice type
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_pdf_email_invoice($invoiceId, $type, $companyId) {
    // Generate PDF
    $pdfPath = fn_pdf_generate_invoice($invoiceId, $type, $companyId);

    if (!$pdfPath) {
        return false;
    }

    // Get invoice email
    if ($type === 'sales') {
        $query = "SELECT si.invoice_number, c.email, comp.company_name
                  FROM sales_invoices si
                  LEFT JOIN customers c ON si.customer_id = c.customer_id
                  LEFT JOIN core_company comp ON si.company_id = comp.company_id
                  WHERE si.invoice_id = ? AND si.company_id = ?";
    } else {
        $query = "SELECT si.invoice_number, si.customer_email as email, comp.company_name
                  FROM service_invoices si
                  LEFT JOIN core_company comp ON si.company_id = comp.company_id
                  WHERE si.service_invoice_id = ? AND si.company_id = ?";
    }

    $invoice = fn_core_database_row($query, [$invoiceId, $companyId]);

    if (!$invoice || !$invoice['email']) {
        return false;
    }

    // Send email with PDF attachment
    $subject = "Invoice " . $invoice['invoice_number'] . " from " . $invoice['company_name'];
    $body = "Please find attached your invoice.\n\nThank you for your business.";

    // Use Postmark or standard email
    return fn_email_send_postmark($invoice['email'], $subject, nl2br($body), $body, $companyId);
}
