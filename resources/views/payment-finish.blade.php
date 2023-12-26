<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Payment Finish</title>
</head>

<body>
  @if ($transaction)
  <h1>Payment Detail</h1>
  <h1>Status Order: {{$transaction->status}}</h1>
  <h1>Order Id: {{$transaction->transaction_code}}</h1>
  @else
  <h1>Invalid Order</h1>
  @endif
</body>

</html>