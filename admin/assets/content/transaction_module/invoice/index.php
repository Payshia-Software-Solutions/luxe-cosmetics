<?php
require_once('../../../../include/config.php');
include '../../../../include/function-update.php';

$ActiveStatus = 0;
$Locations = GetLocations($link);
$Invoices = GetInvoices($link);
$processedInvoices = GetFinalInvoices($link);
$CodInvoices = GetInvoicesByPaymentStatus($link, "COD", 2);
$PaidInvoices = GetInvoicesByPaymentStatus($link, "Paid", 2);
$NotPaidInvoices = GetInvoicesByPaymentStatus($link, "Not Paid", 1);
$ArrayCount = count($Invoices);

$LoggedUser = $_POST['LoggedUser'];
$ActiveCount = $ArrayCount;
$InactiveCount = 0;
?>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Invoices</p>
                <h1><?= count($processedInvoices) ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Paid</p>
                <h1><?= count($PaidInvoices) ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of COD</p>
                <h1><?= count($CodInvoices) ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card item-card">
            <div class="overlay-box">
                <i class="fa-solid fa-file-contract icon-card"></i>
            </div>
            <div class="card-body">
                <p>No of Failed Orders</p>
                <h1><?= count($NotPaidInvoices) ?></h1>
            </div>
        </div>
    </div>
    <?php
    $pageID = 13;
    $userPrivilege = GetUserPrivileges($link, $LoggedUser,  $pageID);

    if (!empty($userPrivilege)) {
        $readAccess = $userPrivilege[$LoggedUser]['read'];
        $writeAccess = $userPrivilege[$LoggedUser]['write'];
        $AllAccess = $userPrivilege[$LoggedUser]['all'];

        if ($writeAccess == 1) {
    ?>
            <div class="col-md-12 text-end mt-4">
                <button class="btn btn-dark" type="button" onclick="NewInvoice()"><i class="fa-solid fa-plus"></i> New Invoice</button>
            </div>
    <?php
        }
    }
    ?>
</div>
<style>
    #order-table tr {
        height: auto !important
    }

    .recent-po-container {
        max-height: 70vh;
        overflow: auto;
    }
</style>

<div class="row mt-4">
    <div class="col-md-8">
        <div class="table-title font-weight-bold mb-4 mt-0">Invoices</div>

        <div class="row">
            <div class="col-12 mb-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body ">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="purchase-order-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Invoice #</th>
                                        <th scope="col">Order #</th>
                                        <th scope="col">Action</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Value</th>
                                        <th scope="col">Payment</th>
                                        <th scope="col">Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($Invoices)) {
                                        $RowNumber = 0;
                                        foreach ($Invoices as $selectedArray) {
                                            $active_status = "Deleted";
                                            $color = "warning";
                                            if ($selectedArray['is_active'] == 1) {
                                                $active_status = "Active";
                                                $color = "primary";
                                            } else {
                                                continue;
                                            }

                                            if ($selectedArray['invoice_status'] != "2") {
                                                continue;
                                            }

                                            $LocationName = $Locations[$selectedArray['location_id']]['location_name'];
                                            $invoice_date = $selectedArray['invoice_date'];

                                            $invoice_number = $selectedArray['invoice_number'];
                                            $CustomerID = $selectedArray['customer_code'];
                                            $invoiceValue = $selectedArray['grand_total'];
                                            $customerName =  GetCustomerName($link, $CustomerID);
                                            $RowNumber++;


                                            $InvoiceDate = $selectedArray['current_time'];
                                            $dateTime = new DateTime($InvoiceDate);
                                            $formattedDate = $dateTime->format('d/m/Y H:i:s');

                                            $ref_hold = $selectedArray['ref_hold'];
                                            if ($ref_hold == '0') {
                                                // $referenceText = "Take Away";
                                                $referenceText = "Direct";
                                            } else if ($ref_hold == '-1') {
                                                // $referenceText = "Retail";
                                                $referenceText = "Direct";
                                            } else if ($ref_hold == '-2') {
                                                // $referenceText = "Delivery";
                                                $referenceText = "Direct";
                                            } else if ($ref_hold == "") {
                                                // $referenceText = "None";
                                                $referenceText = "Direct";
                                            } else {
                                                $referenceText = $ref_hold;
                                            }
                                    ?>
                                            <tr>
                                                <th><?= $invoice_number ?></th>
                                                <td><?= strtoupper($referenceText) ?></td>
                                                <td class="text-end">
                                                    <button class="mt-0 btn btn-sm btn-dark view-button" type="button" onclick="PrintInvoice ('<?= $invoice_number ?>')"><i class="fa-solid fa-print"></i> Print</button>
                                                </td>
                                                <td><?= $formattedDate ?></td>
                                                <td><?= $CustomerID ?></td>
                                                <th class="text-end"><?= number_format($invoiceValue, 2) ?></th>
                                                <th><?= $selectedArray['payment_status'] ?></th>
                                                <td><?= $LocationName ?></td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="row">

            <div class="col-12">
                <div class="table-title font-weight-bold mb-4 mt-0">Failed Order Attempts</div>
            </div>

            <div class="recent-po-container">
                <?php
                if (!empty($Invoices)) {
                    foreach ($Invoices as $selectedArray) {
                        $active_status = "Deleted";
                        $color = "warning";
                        if ($selectedArray['is_active'] == 1) {
                            $active_status = "Active";
                            $color = "primary";
                        } else {
                            continue;
                        }

                        if ($selectedArray['invoice_status'] == "2") {
                            continue;
                        }

                        if ($selectedArray['payment_status'] != "Not Paid") {
                            continue;
                        }

                        $LocationName = $Locations[$selectedArray['location_id']]['location_name'];
                        $invoice_date = $selectedArray['invoice_date'];

                        $invoice_number = $selectedArray['invoice_number'];
                        $CustomerID = $selectedArray['customer_code'];
                        $invoiceValue = $selectedArray['grand_total'];
                        $customerName =  GetCustomerName($link, $CustomerID);
                        $RowNumber++;


                        $InvoiceDate = $selectedArray['current_time'];
                        $dateTime = new DateTime($InvoiceDate);
                        $formattedDate = $dateTime->format('d/m/Y H:i:s');

                ?>
                        <div class="col-12 mb-3 d-flex">
                            <div class="card flex-fill">
                                <div class="card-body p-2 pb-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h1 class="tutor-name my-0"><?= strtoupper($invoice_number) ?></h1>
                                            <p class="text-muted mb-0"><?= $formattedDate ?></p>
                                            <p class="text-muted mb-0"><?= $CustomerID ?></p>
                                            <span class="badge mt-1 bg-danger"><?= $selectedArray['payment_status'] ?></span>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-end">
                                                <h1 class="tutor-name mb-2"><?= number_format($invoiceValue, 2) ?></h1>
                                                <button class="mt-0 btn btn-sm btn-dark view-button" type="button" onclick="PrintInvoice ('<?= $invoice_number ?>')"><i class="fa-solid fa-print"></i> View</button>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-0">No Entires</p>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#purchase-order-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
                // 'colvis'
            ],
            order: [
                [0, 'desc'],
                [3, 'desc']
            ]
        });
    });
</script>