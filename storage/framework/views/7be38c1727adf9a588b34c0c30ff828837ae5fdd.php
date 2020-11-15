<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.5.9/dist/css/uikit.min.css"/>

    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.5.9/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.5.9/dist/js/uikit-icons.min.js"></script>
</head>

<body>
<?php


?>


<div class="uk-container">
    <h1 class="uk-heading-line"><span>AWS Services</span></h1>
    <div class="uk-child-width-expand@s" uk-grid>
        <div class="uk-grid-item-match">
            <div class="uk-card uk-card-default uk-card-body">
                <h3>Uploaded Image</h3>
                <p>
                <div><img src="<?php echo e($imgUrl); ?>" style="max-width: 500px"></div>
                </p>
            </div>
        </div>
        <div class=".uk-text-lead">
            <h3>Rekognition Results</h3>
            <div>Search for "Dog" Lable <?php echo e(($isPresent===true)?'Dog present':'Dog is absent'); ?></div>
            <div><a href="/">Try another one</a></div>
           <?php if(isset($coincidences)): ?>
            <?php $__currentLoopData = $coincidences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coincidence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo e($coincidence['Name']); ?> - <?php echo e(round($coincidence['Confidence'], 2 )); ?>%
                <div style="background-color: dodgerblue; width:<?php echo e($coincidence['Confidence']); ?>% ">&nbsp;</div>
                <br>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
        </div>
    </div>
</div>


</body>
</html>
<?php /**PATH /var/www/aws/resources/views/show.blade.php ENDPATH**/ ?>