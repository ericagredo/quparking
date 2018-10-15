<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Park It Invoice</title>

    <!-- Fonts -->


    <!-- Styles -->
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
        }

        table {
            border: 1px solid #ccc;
            width: 100%;
            margin: 0;
            padding: 0;
            border-collapse: collapse;
            border-spacing: 0;
        }

        table tr {
            border: 1px dashed #ddd;
            padding: 5px;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            padding-left: 15px;
        }

        table th {
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
        }

        .title {
            font-size: 24px;
            width: 100%;
            text-align: center
        }

        .container {
            width: 500px;
            margin: 70px auto;
        }

        .right {
            text-align: right
        }
        .logo {
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <!--div class="logo"><img src="./logo.png"></div-->
    <h1 class="title">Park It Invoice</h1>
    <table>
        <thead>

        </thead>
        <tbody>
        <tr>
            <td>Location :</td>
            <td class="right"><?php echo e($data->spot_location); ?></td>
        </tr>
        <tr>
            <td>City :</td>
            <td class="right"><?php echo e($data->city_name); ?></td>
        </tr>
        <tr>
            <td>Country :</td>
            <td class="right"><?php echo e($data->country_name); ?></td>
        </tr>
        <tr>
            <td>Postal Code :</td>
            <td class="right"><?php echo e($data->postal_code); ?></td>
        </tr>
        <tr>
            <td>Transaction Id :</td>
            <td class="right"><?php echo e($data->transaction_id); ?></td>
        </tr>
        <tr>
            <td>Booking Id :</td>
            <td class="right"><?php echo e($data->generated_booking_id); ?></td>
        </tr>
        <tr>
            <td>Booking Date :</td>
            <td class="right"><?php echo e($data->booking_start_date_time); ?></td>
        </tr>
        <tr>
            <td>Amount :</td>
            <td class="right"><?php echo e($data->paid_amount + $data->additional_credited_amount); ?></td>
        </tr>
        <tr>
            <td>Discount Amount :</td>
            <td class="right"><?php echo e($data->additional_credited_amount); ?></td>
        </tr>
        <tr>
            <td>Total Paid Amount :</td>
            <td class="right"><?php echo e($data->paid_amount); ?></td>
        </tr>

        </tbody>
    </table>

</div>

</body>
</html>
