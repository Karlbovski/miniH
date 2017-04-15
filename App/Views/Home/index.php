<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <body>
    <h1>Welcome</h1>    
    <p>Hello <?= htmlspecialchars($name) ?></p>
    <p>This is the index inside App/Views/Home folder.</p>
    <ul>
        <?php foreach ($colours as $color): ?>
            <li><?php echo htmlspecialchars($color) ?></li>
        <?php endforeach; ?>
    </ul>
    </body>    
</html>