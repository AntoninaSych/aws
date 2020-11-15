<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel + AWS Rekognition</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"/>
</head>
<body>

<div class="container">

    <div class="jumbotron">
        <h3>AWS works with Rekognition SDK Integration</h3>

    </div>
    <a href="/images">Uploaded Images</a>
    <?php if(isset($error)): ?>
        <div class="alert alert-info">
            <div class="form-group"><?php echo e($error); ?></div>

        </div>
    <?php endif; ?>



    <?php if(isset($results)): ?>
        <?php echo e(dd($results)); ?>

    <?php else: ?>
        <form action="<?php echo e(route('upload')); ?>" method="post" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label for="photo">Enter Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="form-group">
                <label for="photo">Upload a Photo</label>
                <input type="file" name="file" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" value="Submit" class="btn btn-primary btn-lg">
            </div>
        </form>
    <?php endif; ?>

</div>

</body>
</html>
<?php /**PATH /var/www/aws/resources/views/form.blade.php ENDPATH**/ ?>