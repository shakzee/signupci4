<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register a New </title>
</head>
<body>
    <?php if (isset($message) && !empty($message)):
        echo $message;
    ?>

    <?php endif; ?>
    <?php echo \Config\Services::validation()->listErrors()?>
    <?php echo form_open('user/newuser'); ?>
        <p>
            Enter your name <?php echo form_input('name','',''); ?>
        </p>

        <p>
            Enter your email <?php echo form_input('email','',''); ?>
        </p>

        <p>
            Enter your password <?php echo form_password('password','',''); ?>
        </p>
        <p>
            <?php echo form_submit('mybutton','Create Now',''); ?>
        </p>
    <?php form_close(); ?>
</body>
</html>
