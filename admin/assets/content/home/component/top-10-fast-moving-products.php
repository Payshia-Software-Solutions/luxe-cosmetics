<?php
$fastMovingProducts = GetFastMovingProducts($link, $firstDayOfCurrentMonth, $currentDate, $defaultLocation, 10);
?>
<div class="row">
    <div class="col-md-12">
        <div class="px-5 px-md-5">
            <canvas id="fastMovingChart" width="100" height=100"></canvas>
        </div>
    </div>
    <div class="col-md-12">
        <div class="shadow-none mb-1 p-2 border-bottom border-2">
            <div class="row g-0">
                <div class="col-9 d-flex align-items-center">
                    <h6 class="fw-bold mb-0">Product</h6>
                </div>
                <div class="col-3 d-flex justify-content-end align-items-center">
                    <h6 class="fw-bold mb-0">Quantity</h6>
                </div>
            </div>
        </div>

        <?php
        $counter = 0; // Initialize counter
        foreach ($fastMovingProducts as $product):
            // Determine background color based on counter (alternating between 'bg-light' and default)
            $rowClass = ($counter % 2 == 0) ? '' : 'bg-light';
            $counter++; // Increment counter
        ?>
            <div class="<?= $rowClass; ?>" style="border-radius:10px">
                <div class="row gx-1">
                    <div class="col-2">
                        <div class="p-1">
                            <img src="https://kdu-admin.payshia.com/pos-system/assets/images/products/<?= $product['product_id'] ?>/<?= $product['front_image_path'] ?>" alt="" class="w-100" style="border-radius: 10px;">
                        </div>
                    </div>
                    <div class="col-7 d-flex align-items-center">
                        <div>
                            <h6 class="mb-0 custom-product-name fw-bold"><?= $product['product_name']; ?></h6>
                            <p class="mb-0 custom-quantity">Revenue : <?= number_format($product['total_revenue'], 2); ?></p>
                        </div>
                    </div>
                    <div class="col-3 d-flex justify-content-end align-items-center">
                        <h6 class="mb-0 text-end fw-bold mx-2" style="font-size: 15px;"><?= number_format($product['total_quantity'], 2); ?></h6>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    var chartData = <?php echo json_encode($fastMovingProducts); ?> || [];

    if (chartData.length === 0) {
        console.error('Chart data is empty');
    } else {
        var threshold = 5; // Threshold for grouping small quantities as 'Others'

        // Filter products and group small quantities under 'Others'
        var filteredProducts = chartData.filter(product => product.total_quantity >= threshold);
        var otherProducts = chartData.filter(product => product.total_quantity < threshold);

        var productNames = filteredProducts.map(product => product.product_name || 'Unnamed Product');
        var quantities = filteredProducts.map(product => product.total_quantity || 0);

        // Adding 'Others' category
        if (otherProducts.length > 0) {
            productNames.push('Others');
            var otherQuantity = otherProducts.reduce((sum, product) => sum + product.total_quantity, 0);
            quantities.push(otherQuantity);
        }

        var ctx = document.getElementById('fastMovingChart').getContext('2d');
        var fastMovingProductsChart = new Chart(ctx, {

            type: 'pie', // You can change this to 'doughnut', 'line', or other types
            data: {
                labels: productNames, // X-axis labels
                datasets: [{
                    label: 'Total Quantity Sold',
                    data: quantities, // Y-axis values
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                        '#FF5733', // Add more colors if needed
                        '#33FF57'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom', // Position the legend at the bottom
                        align: 'start',
                        display: false
                    },
                    tooltip: {
                        enabled: true // Enable tooltips for better visibility
                    }
                }
            }
        });
    }
</script>