<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>Comprobante de entrega</title>
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
            margin-top: 2%;
            margin-left: 2%;
            margin-right: 2%;
            margin-bottom: 2%
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
                <p style="float: right;"> <strong >ENTREGA</strong>  : {{$v->num_comprobante}}</p>
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
                    <p><strong>Estado: </strong>
                    @php
                    if($v->estado == 'Anulada'){
                        echo "Anulado";
                    }else{
                        if($v->pagado){
                            echo "Pagado";
                        }else{
                            echo "Pendiente de pago";
                        }
                        if ($v->entregado){
                            echo " y Entregado";
                        }else{
                            echo " y no entregado";
                        }
                    }
                    @endphp
                    {{-- <p><strong>Estado: </strong> {{ $v->pagado? "Pagado" : "Pendiente de pago" }} --}}
                    {{-- @php
                        if ($v->entregado){
                            echo " y Entregado";
                        }else{
                            echo " y no entregado";
                        }
                    @endphp --}}
                </p>
            </div>
        </header>

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
                            Correo-E: {{ $v->email  }}</p></th>
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
                            <th>No° PLACA</th>
                            <th>MEDIDAS</th>
                            <th>P U</th>
                            <th>METROS <sup>2</sup></th>
                            <th>CANT.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detalles as $det)


                        <tr>
                            <td class="td-b">{{ $det->articulo }}</td>
                            <td class="td-b">{{ $det->codigo }}</td>
                            <td class="td-b">{{ $det->largo }} : {{ $det->alto }}</td>
                            <td class="td-b">{{ $det->precio }}</td>
                            <td class="td-b">{{ $det->metros_cuadrados }}</td>
                            <td class="td-b">{{ $det->cantidad }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
                @foreach ($venta as $v)
                <p>
                    <strong>Fecha de realizacion: </strong>
                    <?php
                        setlocale(LC_TIME, "spanish");
                        $mi_fecha = $v->created_at;
                        $mi_fecha = str_replace("/", "-", $mi_fecha);
                        $Nueva_Fecha = date("d-m-Y", strtotime($mi_fecha));
                        $Mes_Anyo = strftime("%A, %d de %B de %Y", strtotime($Nueva_Fecha));
                        echo $Mes_Anyo;
                    ?> <br>
                    <strong>Nota: </strong>{{ $v->observacion }} <br>
                    <strong>Forma de pago: </strong>{{ $v->forma_pago }}
                    {{-- <strong>
                    @php
                        if($v->forma_pago == 'Cheque'){
                            echo "No° Cheque: ".$v->num_cheque;
                            echo " Banco: ".$v->banco;
                        }
                    @endphp
                    </strong> --}}
                    <br>
                    <strong>Tiempo de entrega:</strong>{{ $v->tiempo_entrega }}<br>
                    <strong> Lugar de entrega: </strong>{{ $v->lugar_entrega }}
                </p> <br>
            @endforeach

            <p style="font-size:7px;">*** SUGERIMOS A NUESTROS CLIENTES SELLAR SU MATERIAL CON PRODUCTOS LATICRETE
                    *** PRECIOS LAB EN BODEGA TROYSTONE ZMG *APLICA PARA VENTA DE MATERIAL*
                    *** TIEMPO DE ENTREGA SUJETO A DISPONIBILIDAD.
                    *** NUESTROS PRODUCTOS SON DE ORIGEN NATURAL, POR LO QUE PUEDEN PRESENTAR VARIACIONES EN SU TONALIDAD O EN SUS BETAS
                    *** CAMBIOS DE PRECIO SIN PREVIO AVISO SI EL MATERIAL NO ESTA LIQUIDADO AL 100%
                    *** EL TIPO DE CAMBIO SE TOMA DE BANAMEX A LA VENTA DEL DÍA DE LA OPERACIÓN, APLICA PARA VENTA EN USD.
                    ***UNA VEZ SALIDA LA MERCANCIA EL RIESGO CORRE POR EL CLIENTE, NO NOS HACEMOS CARGO DE DAÑOS, O ALGUN OTRO PERCANCE </p>
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
