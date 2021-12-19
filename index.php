<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello</title>
</head>
<body>
    <h1>O, hai!</h1>

    <p>
        <?php
        require_once(__DIR__ . '/models/customer.php');

        $invoices = CustomerDAO::getInstance();
        $data = [
            'FirstName' => 'Test',
            'LastName' => 'Test',
            'Password' => 'pass',
            'Email' => 'test',
        ];
        $test = $invoices->create($data);
        $test['FirstName'] = 'TETAALKHDFKJAHDFLASDHF';
        $invoices->update($test['CustomerId'], $test);

        $test2 = $invoices->findByPk($test['CustomerId']);
        print_r($test2);
        ?>
    </p>
</body>
</html>