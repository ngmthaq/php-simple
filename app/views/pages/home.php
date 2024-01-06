<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php importPartial("common-head") ?>
</head>

<body>
    <div id="app">
        <form action="<?php route("home", "index") ?>" method="post">
            <?php generateXsrfInputTag() ?>
            <button type="submit">Submit</button>
        </form>
    </div>
    <?php importPartial("common-script") ?>
</body>

</html>