<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <style>
        * {
            padding: 0;
            margin: 0;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            width: 100vw;
            background-color: #080808;
        }

        div {
            padding: 16px;
        }

        h1 {
            margin-bottom: 4px;
            font-size: 80px;
            color: red;
            color: #ff3333;
        }

        p {
            font-size: 18px;
            color: #eaeaea;

        }
    </style>
</head>

<body>
    <div>
        <h1><?php echo $code ?></h1>
        <p><?php echo $message ?></p>
    </div>
</body>

</html>