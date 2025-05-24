<?php
$fastMovingProductsByRevenue = GetFastMovingProductsByRevenue($link, $firstDayOfCurrentMonth, $currentDate, $defaultLocation, 6);
?>
<style>
    @media (max-width: 576px) {
        .custom-product-name {
            font-size: 12px;
            /* Adjust for mobile */
        }

        .custom-quantity {
            font-size: 10px;
            /* Adjust for mobile */
        }
    }

    @media (min-width: 577px) and (max-width: 767px) {
        .custom-product-name {
            font-size: 14px;
            /* Adjust for small screens */
        }

        .custom-quantity {
            font-size: 12px;
            /* Adjust for small screens */
        }
    }

    @media (min-width: 768px) {
        .custom-product-name {
            font-size: 16px;
            /* Adjust for medium and larger screens */
        }

        .custom-quantity {
            font-size: 14px;
            /* Adjust for medium and larger screens */
        }
    }
</style>
<div class="row">
    <div class="col-md-5">
        <div class="px-5 px-md-0">
            <canvas id="fastMovingChartByRevenue" width="100" height=100" class="mb-3"></canvas>
        </div>
    </div>
    <div class="col-md-7">
        <div class="shadow-none mb-1 p-2 border-bottom border-2">
            <div class="row g-0">
                <div class="col-9 d-flex align-items-center">
                    <h6 class="fw-bold mb-0">Product</h6>
                </div>
                <div class="col-3 d-flex justify-content-end align-items-center">
                    <h6 class="fw-bold mb-0">Revenue</h6>
                </div>
            </div>
        </div>

        <?php
        $counter = 0; // Initialize counter
        foreach ($fastMovingProductsByRevenue as $product):
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
                            <p class="mb-0 custom-quantity">Quantity : <?= number_format($product['total_quantity'], 2); ?></p>
                        </div>
                    </div>
                    <div class="col-3 d-flex justify-content-end align-items-center">
                        <h6 class="mb-0 text-end fw-bold mx-2" style="font-size: 15px;"><?= number_format($product['total_revenue'], 2); ?></h6>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>


    </div>
</div>

<script>
    // Function to determine if the screen width is below a certain size (e.g., 768px for mobile screens)
    function isMobileScreen() {
        return window.innerWidth <= 768;
    }

    (function() {
        var chartDataByRevenue = <?php echo json_encode($fastMovingProductsByRevenue); ?> || [];

        var threshold = 5000; // Minimum revenue threshold for grouping as 'Others'

        // Separate products into categories based on threshold
        var filteredProductsByRevenue = chartDataByRevenue.filter(product => product.total_revenue >= threshold);
        var otherProductsByRevenue = chartDataByRevenue.filter(product => product.total_revenue < threshold);

        // Extract names and revenues
        var productNamesByRevenue = filteredProductsByRevenue.map(product => product.product_name || 'Unnamed Product');
        var revenuesByRevenue = filteredProductsByRevenue.map(product => product.total_revenue || 0);

        // Group 'Others' category if applicable
        if (otherProductsByRevenue.length > 0) {
            productNamesByRevenue.push('Others');
            var revenuesByRevenue = otherProducts.reduce((sum, product) => sum + (product.total_revenue || 0), 0);
            revenues.push(revenuesByRevenue);
        }

        // Create the chart
        var ctx4 = document.getElementById('fastMovingChartByRevenue')?.getContext('2d');
        if (!ctx4) {
            console.error('Canvas element not found');
            return;
        }

        new Chart(ctx4, {
            type: 'pie',
            data: {
                labels: productNamesByRevenue,
                datasets: [{
                    label: 'Total Revenue (in currency)',
                    data: revenuesByRevenue,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                        '#9966FF', '#FF9F40', '#FF5733', '#33FF57', '#33C1FF'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        align: 'start',
                        display: !isMobileScreen()
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var value = context.raw || 0;
                                return `Revenue: Rs. ${value.toLocaleString()}`;
                            }
                        }
                    }
                }
            }
        });

    })(); // End of IIFE

    // Re-render chart when window is resized to handle dynamic changes
    window.addEventListener('resize', function() {
        chart.options.plugins.legend.display = !isMobileScreen();
        chart.update(); // Update the chart to reflect the change
    });
</script>