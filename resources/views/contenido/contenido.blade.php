@extends('principal')
@section('contenido')

    @if(Auth::check())
        @if(Auth::user()->idrol == 1)
            <template v-if="menu==0">
                <dashboard></dashboard>
            </template>

            <template v-if="menu==1">
                <categoria></categoria>
            </template>

            <template v-if="menu==2">
                <articulo></articulo>
            </template>

            <template v-if="menu==3">
                <ingreso></ingreso>
            </template>

            <template v-if="menu==4">
                <proveedor></proveedor>
            </template>

            <template v-if="menu==5">
                <venta></venta>
            </template>

            <template v-if="menu==13">
                <cotizacion></cotizacion>
            </template>

            <template v-if="menu==14">
                <entrega></entrega>
            </template>

            <template v-if="menu==15">
                <tarea></tarea>
            </template>

            <template v-if="menu==6">
                <cliente></cliente>
            </template>

            <template v-if="menu==7">
                <user></user>
            </template>

            <template v-if="menu==8">
                <rol></rol>
            </template>

            <template v-if="menu==9">
                <consultaingreso></consultaingreso>
            </template>

            <template v-if="menu==10">
                <consultaventa></consultaventa>
            </template>

            <template v-if="menu==16">
                <calendario></calendario>
            </template>

            <template v-if="menu==17">
                <traslado></traslado>
            </template>

            <template v-if="menu==18">
                <facturacion></facturacion>
            </template>

            <template v-if="menu==19">
                <consultaactividad></consultaactividad>
            </template>

            <template v-if="menu==20">
                <actividad></actividad>
            </template>

            <template v-if="menu==11">
                <ayuda></ayuda>
            </template>

            <template v-if="menu==12">
                <acerca></acerca>
            </template>

            <template v-if="menu==21">
                <recadero></recadero>
            </template>

            <template v-if="menu==22">
                <project></project>
            </template>

            <template v-if="menu==23">
                <galeria></galeria>
            </template>

            <template v-if="menu==24">
                <credito></credito>
            </template>

        @elseif(Auth::user()->idrol == 2)

            <template v-if="menu==0">
                <dashboard></dashboard>
            </template>

            <template v-if="menu==2">
                <articulo></articulo>
            </template>

            <template v-if="menu==3">
                <ingreso></ingreso>
            </template>

            <template v-if="menu==4">
                <proveedor></proveedor>
            </template>

            <template v-if="menu==5">
                <venta></venta>
            </template>

            <template v-if="menu==14">
                <entrega></entrega>
            </template>

            <template v-if="menu==13">
                <cotizacion></cotizacion>
            </template>

            <template v-if="menu==15">
                <tarea></tarea>
            </template>

            <template v-if="menu==6">
                <cliente></cliente>
            </template>

            <template v-if="menu==10">
                <consultaventa></consultaventa>
            </template>

            <template v-if="menu==16">
                <calendario></calendario>
            </template>

            <template v-if="menu==17">
                <traslado></traslado>
            </template>

            <template v-if="menu==11">
                <ayuda></ayuda>
            </template>

            <template v-if="menu==12">
                <acerca></acerca>
            </template>

            <template v-if="menu==18">
                <facturacion></facturacion>
            </template>

            <template v-if="menu==20">
                <actividad></actividad>
            </template>

            <template v-if="menu==21">
                <recadero></recadero>
            </template>

            <template v-if="menu==22">
                <project></project>
            </template>

            <template v-if="menu==23">
                <galeria></galeria>
            </template>

        @elseif(Auth::user()->idrol == 3)

            <template v-if="menu==0">
                <dashboard></dashboard>
            </template>

            <template v-if="menu==1">
                <categoria></categoria>
            </template>

            <template v-if="menu==2">
                <articulo></articulo>
            </template>

            <template v-if="menu==3">
                <ingreso></ingreso>
            </template>

            <template v-if="menu==4">
                <proveedor></proveedor>
            </template>

            <template v-if="menu==9">
                <consultaingreso></consultaingreso>
            </template>

            <template v-if="menu==14">
                <entrega></entrega>
            </template>

            <template v-if="menu==16">
                <calendario></calendario>
            </template>

            <template v-if="menu==17">
                <traslado></traslado>
            </template>

            <template v-if="menu==11">
                <ayuda></ayuda>
            </template>

            <template v-if="menu==12">
                <acerca></acerca>
            </template>

            <template v-if="menu==20">
                <actividad></actividad>
            </template>
            <template v-if="menu==21">
                <recadero></recadero>
            </template>

            <template v-if="menu==23">
                <galeria></galeria>
            </template>
            @elseif(Auth::user()->idrol == 4)

            <template v-if="menu==0">
                <dashboard></dashboard>
            </template>

            <template v-if="menu==2">
                <articulo></articulo>
            </template>

            <template v-if="menu==5">
                <venta></venta>
            </template>

            <template v-if="menu==13">
                <cotizacion></cotizacion>
            </template>

            <template v-if="menu==6">
                <cliente></cliente>
            </template>



        @else

        @endif
    @endif

@endsection

