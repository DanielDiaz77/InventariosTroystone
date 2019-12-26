<?php

namespace App\Exports;

use App\DetalleVenta;
use App\Venta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VentasExportDet implements FromCollection,WithHeadings
{
    protected $inicio;
    protected $fin;

    public function __construct($inicio, $fin)
    {
        $this->inicio = $inicio;
        $this->fin = $fin;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        /* return DetalleVenta::all(); */
        return DetalleVenta::join('ventas','detalle_ventas.idventa','ventas.id')
        ->join('articulos','detalle_ventas.idarticulo','articulos.id')
        ->leftJoin('categorias','articulos.idcategoria','=','categorias.id')
        ->leftJoin('personas','ventas.idcliente','personas.id')
        ->select(
            'ventas.num_comprobante',
            'ventas.fecha_hora',
            'ventas.forma_pago',
            'articulos.sku',
            'articulos.codigo',
            'categorias.nombre as categoria',
            'articulos.largo',
            'articulos.alto',
            'articulos.metros_cuadrados',
            'articulos.espesor',
            'articulos.terminado',
            'articulos.ubicacion',
            'detalle_ventas.cantidad',
            'detalle_ventas.precio',
            'detalle_ventas.descuento',
            'personas.nombre'
            )
        ->where('ventas.estado','Registrado')
        ->whereBetween('ventas.fecha_hora', [$this->inicio, $this->fin])
        ->orderBy('ventas.forma_pago', 'desc')->get();
    }

    public function headings(): array{
        return [
            'No° Presupuesto',
            'Fecha y hora',
            'Forma de pago',
            'SKU',
            'No° Placa',
            'Material',
            'Largo',
            'Alto',
            'Metros Cuadrados',
            'Espesor',
            'Terminado',
            'Ubicacion',
            'Cantidad',
            'Precio',
            'Descuento',
            'Cliente'
        ];
    }
}
