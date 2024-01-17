<?php

require_once "../../../controllers/sales.controller.php";
require_once "../../../models/sales.model.php";

require_once "../../../controllers/customers.controller.php";
require_once "../../../models/customers.model.php";

require_once "../../../controllers/users.controller.php";
require_once "../../../models/users.model.php";

require_once "../../../controllers/products.controller.php";
require_once "../../../models/products.model.php";

class printBill{

public $code;

public function getBillPrinting(){

//WE BRING THE INFORMATION OF THE SALE

$itemSale = "code";
$valueSale = $this->code;

$answerSale = ControllerSales::ctrShowSales($itemSale, $valueSale);

$saledate = substr($answerSale["saledate"],0,-8);
$products = json_decode($answerSale["products"], true);
$discount = number_format($answerSale["discount"],2);
$discountPercentage = number_format($answerSale["discountPercentage"],2);
$totalPrice = number_format($answerSale["totalPrice"],2);
$netPrice = number_format($answerSale["netItemsPrice"],2);

//TRAEMOS LA INFORMACIÓN DEL Customer

$itemCustomer = "id";
$valueCustomer = $answerSale["idCustomer"];

$answerCustomer = ControllerCustomers::ctrShowCustomers($itemCustomer, $valueCustomer);

//TRAEMOS LA INFORMACIÓN DEL Seller

$itemSeller = "id";
$valueSeller = $answerSale["idSeller"];

$answerSeller = ControllerUsers::ctrShowUsers($itemSeller, $valueSeller);

//REQUERIMOS LA CLASE TCPDF

require_once('tcpdf_include.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->AddPage('P', 'A7');

//---------------------------------------------------------


// ---------------------------------------------------------
// ...

// Calculate the height needed for product details
$productHeight = count($products) * 15; // Adjust the height based on your content and styling

// Set the maximum height for products on a page
$maxProductHeightPerPage = 500; // Adjust this based on your layout

// Define $block1 and $block3 variables
$block1 = '';
$block3 = '';

// Check if the product height exceeds the maximum height per page
if ($productHeight > $maxProductHeightPerPage) {
    // If the product height exceeds the maximum, you may want to handle this differently
    // (e.g., display a message, split into multiple pages, etc.)
    // Currently, it will still try to fit all products on a single page
}

// Add a new page
// $pdf->AddPage('P', 'A7');

// Header: Guru Gedara Publication and Bookshop
$blockHeader = <<<HTML
    <table style="font-size:10px; text-align:center; width:100%;">
        <tr>
            <td>Guru Gedara Publication and Bookshop</td>
        </tr>
    </table>
HTML;

$pdf->writeHTML($blockHeader, false, false, false, false, '');

// Logo (Assuming you have an image file named 'logo.png' in the same directory as your script)
/*$logoPath = 'logo.png';
$blockLogo = <<<HTML
    <table style="width:100%;">
        <tr>
            <td style="text-align:center;"><img src="$logoPath" alt="Logo" style="width:100px; height:100px;"></td>
        </tr>
    </table>
HTML;

$pdf->writeHTML($blockLogo, false, false, false, false, ''); */

// Address: Negombo rd, Dambadeniya
$blockAddress = <<<HTML
    <table style="font-size:10px; text-align:center; width:100%;">
        <tr>
            <td>Negombo rd, Dambadeniya</td>
        </tr>
    </table>
HTML;

$pdf->writeHTML($blockAddress, false, false, false, false, '');

// Main Branch Polgahawela
$blockBranch = <<<HTML
    <table style="font-size:10px; text-align:center; width:100%;">
        <tr>
            <td>Main Branch Polgahawela<br></td>
        </tr>
    </table>
HTML;

$pdf->writeHTML($blockBranch, false, false, false, false, '');

// Contact: 070 3 273 747 / 077 2 213793
$blockContact = <<<HTML
    <table style="font-size:10px; text-align:center; width:100%;">
        <tr>
            <td>070 3 273 747 / 077 2 213793 <br></td>
        </tr>
    </table>
HTML;

$pdf->writeHTML($blockContact, false, false, false, false, '');

// Customer Names: Replace with actual variables
$blockCustomerNames = <<<HTML
    <table style="font-size:8px;  width:100%;">
        <tr>
            <td>Customer name: {$answerCustomer['name']} &nbsp;</td>
            <td>Seller: {$answerSeller['name']}<br></td>
        </tr>
    </table>
HTML;

$pdf->writeHTML($blockCustomerNames, false, false, false, false, '');

// Item details header
$blockItemHeader = <<<HTML
    <table style="font-size:8px; width:100%;">
        <tr>
            <td>Itemcode</td>
            <td>Qty</td>
            <td>Unit Price</td>
            <td>Net Amount</td>
        </tr>
    </table>
HTML;

$pdf->writeHTML($blockItemHeader, false, false, false, false, '');

// Loop through products and display details
foreach ($products as $key => $item) {
    // Check if the keys exist before accessing them
    $itemcode = isset($item['itemcode']) ? $item['itemcode'] : '';
    $qty = isset($item['qty']) ? $item['qty'] : '';

    $unitValue = number_format($item["price"], 2);
    $totalPrice = number_format($item["totalPrice"], 2);

    $blockItemDetails = <<<HTML
        <table style="font-size:8px; width:100%;">
            <tr>
                <td>{$itemcode}</td>
                <td>{$qty}</td>
                <td>{$unitValue}</td>
                <td>{$totalPrice}</td>
            </tr>
        </table>
HTML;

    $pdf->writeHTML($blockItemDetails, false, false, false, false, '');
}

// Additional details from the database (replace with actual database values)
$totalAmount = "100.00";
$discount = "10.00";
$netAmount = "90.00";
$cash = "50.00";
$balance = "40.00";

// Display total amount, discount, net amount, cash, balance
$blockAmountDetails = <<<HTML
    <table style="font-size:8px; text-align:right; width:100%;">
        <tr>
            <td>Total Amount: {$totalAmount}</td>
            <td>Discount: {$discount}</td>
        </tr>
        <tr>
            <td>Net Amount: {$netAmount}</td>
            <td>Cash: {$cash}</td>
        </tr>
        <tr>
            <td>Balance: {$balance}</td>
        </tr>
    </table>
HTML;

$pdf->writeHTML($blockAmountDetails, false, false, false, false, '');

// Footer: Thank you


// Footer: Thank you come again!
$blockFooter = <<<EOF
<table style="font-size:8px; text-align:center; width:100%;">
    <tr>
        <td>Thank you come again!</td>
    </tr>
</table>
EOF;

$pdf->writeHTML($blockFooter, false, false, false, false, '');

// Output the PDF
$pdf->Output('bill.pdf');
}

}

$bill = new printBill();
$bill -> code = $_GET["code"];
$bill -> getBillPrinting();

?>