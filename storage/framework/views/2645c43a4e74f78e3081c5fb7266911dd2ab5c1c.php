<h1 style="text-align: center;">5 Day Weather Forcast for <?php echo e($location); ?></h1>

<form method="get" action="<?php echo e(url('/')); ?>" style="text-align: center;">
    <input type="text" value="<?php echo e($ip); ?>" name="customip" />
    <input type="submit" value="Submit">
</form>


<?php if( $weather["weatherdata"] != 'false' ): ?>
 <?php $__currentLoopData = json_decode( $weather["weatherdata"]); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $weatherdata): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div style="width: 180px; float: left;border: 2px solid grey; margin: 20px; padding: 20px;">
        <img src="http://openweathermap.org/img/w/<?php echo e($weatherdata->icon); ?>.png">
        <br/>
        <?php echo e($weatherdata->day); ?>

        <br/>
        <?php echo e($weatherdata->description); ?>

    </div>

 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php else: ?>
<p>Sorry, there's no weather data for your location</p>
<?php endif; ?>


<?php /**PATH /var/www/html/resources/views/ippage.blade.php ENDPATH**/ ?>