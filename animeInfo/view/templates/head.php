<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../view/assets/images/logoFinal2.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../view/assets/css/style.css">
    <?php 
    if(!empty($title)){ echo '<title>'.$title.'</title>'; } else {echo '<title>Document</title>';}
    ?>
    <?php 
    if(!empty($css)){ echo '<link rel="stylesheet" href="../view/assets/css/'.$css.'">'; }
    ?>
    <link rel="stylesheet" href="../view/assets/css/hamburguerMenu.css">
    <link rel="stylesheet" href="../view/assets/css/header.css">
    <link rel="stylesheet" href="../view/assets/css/footer.css">
</head>
<body>