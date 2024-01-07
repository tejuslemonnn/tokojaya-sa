<?php

namespace App\DataTables;

use App\Models\Cashier;
use App\Models\Category;
use App\Models\DetailCashier;
use App\Models\Satuan;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;

class CashierDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('kode', function (DetailCashier $item) {
                return $item->product->kode;
            })
            ->editColumn('nama_produk', function (DetailCashier $item) {
                return $item->product->nama_produk;
            })
            ->editColumn('jumlah', function (DetailCashier $item) {
                return '<input type="number" name="jumlah" class="form-control input-sm quantity" data-item-id="' . $item->id . '" data-kategori="' . $item->product->kategori_id . '" data-harga="' . $item->product->harga . '" value="' . $item->jumlah . '" min="0" ' . "max=" . $item->product->stok . ' size="3"' . '>';
            })
            // ->editColumn('kategori', function (DetailCashier $item) {
            //     return $item->kategori;
            // })
            ->editColumn('satuan', function (DetailCashier $item) {
                $satuans = Satuan::all();
                return view('pages.cashier.select', compact('satuans', 'item'));
            })
            ->editColumn('harga', function (DetailCashier $item) {
                return $item->product->harga;
            })
            ->editColumn('diskon', function (DetailCashier $item) {
                return $item->product->diskon . ' %';
            })
            ->editColumn('subTotal', function (DetailCashier $item) {
                return '<p class="subTotal" data-item-id=' . $item->product->id . '>' .  'Rp.' .  number_format($item->sub_total, 0, ',', '.') . '</p>';
            })
            ->addColumn('action', function (DetailCashier $item) {
                return view('pages.cashier._action-menu', [
                    'item' => $item
                ]);
            })
            ->rawColumns(['jumlah', 'subTotal']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Cashier $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(DetailCashier $model)
    {
        $userId = Auth::id();
        $cashier =  Cashier::where('user_id', $userId)->first();
        $detailCashier = $model->newQuery()->where('cashier_id', $cashier ? $cashier->id : null);
        return $detailCashier;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('cashier-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->responsive()
            ->autoWidth(false)
            ->parameters(['scrollX' => true, 'searching' => false,])
            ->addTableClass('align-middle table-row-dashed fs-6 gy-5');
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('kode')->orderable(false),
            Column::make('nama_produk')->orderable(false),
            Column::make('jumlah')->orderable(false),
            // Column::make('kategori')->orderable(false),
            Column::make('satuan')->orderable(false),
            Column::make('harga')->orderable(false),
            Column::make('diskon')->orderable(false),
            Column::make('subTotal')->orderable(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->responsivePriority(-1),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Cashier_' . date('YmdHis');
    }
}
