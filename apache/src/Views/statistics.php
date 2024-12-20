<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
</head>
<body>
    <h1>Statistics</h1>
    <div>
        <?php foreach ($chartsWithWatermark as $chart): ?>
            <img src="data:image/png;base64,<?= base64_encode($chart) ?>" alt="Chart" width="1000">
        <?php endforeach; ?>
    </div>
</body>
</html>