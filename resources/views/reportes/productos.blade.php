<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

<h1>Lista de Productos</h1>
<div class="table-responsive">
        <table class="table table-striped">
  <thead>
    <tr>
            <th>#</th>
            <th>Codigo</th>
            <th>Categoria</th>
                                                <th>Precio Costo</th>
                                                <th>Precio Venta</th>
                                                <th>Cantidad</th>
                                                <th>Tipo</th>
                                                <th>Descripcion</th>
                                                <th>Creado</th>
    </tr>
  </thead>
  <tbody>
    @foreach($data as $customer)
      <tr>
        <td>{{ $customer['id'] }}</td>
        <td>{{ $customer['p_codigo'] }}</td>
        <td>{{ $customer['categoria'] }}</td>
        <td>¢ {{ $customer['p_precio_costo'] }}</td>
        <td>{{ $customer['p_precio_venta'] }}</td>
        <td>{{ $customer['cantidad'] }}</td>
        <td>@if ($customer['p_tipo'] ==1)
            {{'Servicio'}}
            @endif
            @if ($customer['p_tipo'] ==2)
            {{'Mercaderia'}}
            @endif
    </td>
        <td>{{ $customer['p_descripcion'] }}</td>
        <td>{{ $customer['created_at'] }}</td>

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
