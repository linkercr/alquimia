<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

<h1>Pedidos</h1>
<div class="table-responsive">
        <table class="table table-striped">
  <thead>
    <tr>
            <th>#</th>
            <th>Factura</th>
            <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>Estado</th>
                                                <th>Fecha de Entrega</th>
                                                <th>Entregado</th>
    </tr>
  </thead>
  <tbody>
    @foreach($data as $customer)
      <tr>
        <td>{{ $customer['id'] }}</td>
        <td>{{ $customer['factura_id'] }}</td>
        <td>{{ $customer['producto'] }}</td>
        <td>{{ $customer['cantidad'] }}</td>
        <td>{{ $customer['precio'] }}</td>
        <td>@if ($customer['estado'] ==1)
            {{'Aprobado'}}
            @endif
            @if ($customer['estado'] ==2)
            {{'Rechazado'}}
            @endif
            @if ($customer['estado'] ==3)
            {{'Pendiente'}}
            @endif
    </td>
        <td>{{ $customer['fechaaentrega'] }}</td>
        <td>{{ $customer['fechaentrega'] }}</td>

      </tr>
    @endforeach
  </tbody>
</table>
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
