<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

<h1>Facturado, Fecha seleccionadas : {{$fehcaReporteSelecionado}}</h1>

<div class="table-responsive">
        <table class="table table-striped">
  <thead>
    <tr>
            <th>Factura</th>
            <th>Usuario</th>
                                                <th>Total</th>
                                                <th>Fecha</th>
    </tr>
  </thead>
  <tbody>
    @foreach($data as $customer)
      <tr>
        <td>{{ $customer['factura_id'] }}</td>
        <td>{{ $customer['usuario'] }}</td>
        <td>¢{{ $customer['total'] }}</td>
        <td>{{ $customer['fecha'] }}</td>

      </tr>
    @endforeach
    <tr>
        <td colspan='2'><h4><b>Total</b></h4></td>
        <td colspan ='2'><h4> ¢ {{ $customer['sumatotal'] }}</h4></td>
    </tr>
  </tbody>
</table>
</div>
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
