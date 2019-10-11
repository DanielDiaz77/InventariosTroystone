<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>Reporte de venta</title>
    <style>
        body {
        /*position: relative;*/
        /*width: 16cm;  */
        /*height: 29.7cm; */
        margin:0;
        padding:0;
        top:0;
        bottom:0;

        /*color: #555555;*/
        /*background: #FFFFFF; */
        font-family: Arial, sans-serif;
        font-size: 14px;
        /*font-family: SourceSansPro;*/
        }

        #logo{
            display:block;
            margin-top: 0%;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        #imagen{
        width: 100px;
        }

        #datos{
        float: left;
        margin-top: 0%;
        margin-left: 2%;
        margin-right: 2%;
        /*text-align: justify;*/
        }

        #encabezado{
        text-align: center;
        margin-left: 5%;
        margin-right: 35%;
        font-size: 15px;
        }

        #fact{
        /*position: relative;*/
        float: right;
        margin-top: 0%;
        margin-left: 2%;
        margin-right: 2%;
        font-size: 20px;
        }

        section{
        clear: left;
        }

        #cliente{
        text-align: left;
        }

        #facliente{
            width: 40%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 15px;
            border-color: black;
        }

        #fac, #fv, #fa{
        color: #FFFFFF;
        font-size: 15px;
        }

        #facliente thead{
        padding: 20px;
        background: #f3861c;
        text-align: left;
        border-bottom: 1px solid #FFFFFF;
        }

        #facvendedor{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #facvendedor thead{
        padding: 20px;
        background: #f3861c;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;
        }

        #facarticulo{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #facarticulo thead{
        padding: 20px;
        background: #f3861c;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;
        }

        #gracias{
            text-align: center;
            color: #FFFFFF;
            background-color: #333337;
            position: fixed;
            bottom: 65;
            width: 100%;
        }
        #hr{
            color: #f3861c;
        }
        #divPiedra{
            color: #FFFFFF;
            background-color: #333337;
            position: relative;
            top: 65;
            text-align: right;
            width: 100%;
            margin: 0;
        }
        #divIzq{
            background-color: #f3861c;
            color: #e4b178;
            height: 100%;
            float: left;
            /* height: 100%; */
            z-index: -100;
            /* transform: rotate(-90deg); */
        }
        td {
            text-align: center;
        },
        .table-b, .th-b, .td-b {
            border: 1px solid black;
        }
    </style>
    <body>
        @foreach ($venta as $v)
        <header>
            {{-- <div id="divIzq">aaaaaaa</div> --}}
            <div>
                <img id="logo" src="img/LogoFactura2.png" alt="TroyStoneLogo" id="imagen">
            </div>
            <div id="divPiedra"> <b> La piedra de tus proyectos </b> </div><br><br><br><br><br>
            <div id="datos">
                <p id="encabezado">
                    {{-- <b>TroyStone&reg;</b><br>Calz. Lázaro Cardenas #2080 Int. 20. Col. Del Fresno, C.P. 44900, Guadalajara, Jalisco
                    <br>Telefono:(01 33) 36 92 81 92<br>Email:ventas@troystone.com.mx --}}
                    <b>Guadalajara, Jalisco a
                    <?php
                        setlocale(LC_TIME, "spanish");
                        $mi_fecha = now();
                        $mi_fecha = str_replace("/", "-", $mi_fecha);
                        $Nueva_Fecha = date("d-m-Y", strtotime($mi_fecha));
                        $Mes_Anyo = strftime("%A, %d de %B de %Y", strtotime($Nueva_Fecha));
                        echo $Mes_Anyo;
                    ?> </b>
                </p>
            </div>
            <div id="fact">
                <p>{{ $v->tipo_comprobante }} : {{$v->num_comprobante}}</p>
            </div>
        </header>
        <br>
        <section>
                <div>
                    <table id="facvendedor">
                        <thead>
                            <tr id="fv">
                                <th>ATENDIO</th>
                                <th>FECHA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $v->usuario }}</td>
                                <td>
                                    <?php
                                        setlocale(LC_TIME, "spanish");
                                        $mi_fecha = $v->created_at;
                                        $mi_fecha = str_replace("/", "-", $mi_fecha);
                                        $Nueva_Fecha = date("d-m-Y", strtotime($mi_fecha));
                                        $Mes_Anyo = strftime("%A, %d de %B de %Y", strtotime($Nueva_Fecha));
                                        echo $Mes_Anyo;
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
            <br>
        <section>
            <div>
                <table id="facliente">
                    <thead>
                        <tr>
                            <th id="fac">Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <th><p id="cliente">{{$v->nombre}}<br>
                            RFC: {{ $v->rfc }}<br>
                            Domicilio: {{ $v->domicilio }} {{$v->ciudad}}<br>
                            Teléfono: {{ $v->telefono }}<br>
                            Email: {{ $v->email  }}</p></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        @endforeach
        <br>

        <section>
            <div>
                <table id="facarticulo" class="table-b">
                    <thead>
                        <tr id="fa">
                            <th>MATERIAL</th>
                            <th>MEDIDAS</th>
                            <th>P U</th>
                            <th>METROS <sup>2</sup></th>
                            <th>CANT.</th>
                            <th>SUBTOTAL</th>
                            <th>DESCUENTO</th>
                            <th>IVA</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detalles as $det)


                        <tr>
                            <td class="td-b">{{ $det->articulo }}</td>
                            <td class="td-b">{{ $det->largo }} : {{ $det->alto }}</td>
                            <td class="td-b">{{ $det->precio }}</td>
                            <td class="td-b">{{ $det->metros_cuadrados }}</td>
                            <td class="td-b">{{ $det->cantidad }}</td>
                            <td class="td-b">{{ ((($det->precio * $det->cantidad) * $det->metros_cuadrados) - $det->descuento) }}</td>
                            <td class="td-b">{{ $det->descuento }}</td>
                            <td class="td-b">{{ (((($det->precio * $det->cantidad) * $det->metros_cuadrados) - $det->descuento) * $ivaVenta) }}</td>
                            <td class="td-b">{{
                                (((($det->precio * $det->cantidad) * $det->metros_cuadrados) - $det->descuento)
                                +
                                (((($det->precio * $det->cantidad) * $det->metros_cuadrados) - $det->descuento) * $ivaVenta))
                            }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        @foreach ($venta as $v)
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="th-b">SUBTOTAL</th>
                            <td class="th-b">{{ round($v->total-($v->total*$v->impuesto),2)}}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="th-b">IVA</th>
                            <td class="th-b">{{ round($v->total*$v->impuesto,2) }}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="th-b">TOTAL</th>
                            <td class="th-b">{{ $v->total }}</td>
                        </tr>
                        @endforeach
                    </tfoot>

                </table>

            </div>
        </section>
        <br>
        <br>
        <footer>
            <div id="gracias">
                <p>
                    <b>Gracias por su compra!</b><br>
                    <hr id="hr">
                    <b>www.troystone.com.mx</b><br>
                        ventas@troystone.com.mx <br>
                        Tel:(01 33) 36 92 81 92
                </p>
            </div>
        </footer>
    </body>
</html>
