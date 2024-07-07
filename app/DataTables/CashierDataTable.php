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
                if ($item->product_id) {
                    return $item->product->kode;
                } else {
                    return $item->promoBundle->kode_barcode;
                }
            })
            ->editColumn('nama_produk', function (DetailCashier $item) {
                if ($item->product_id) {
                    return $item->product->nama_produk;
                } else {
                    return $item->promoBundle->nama_bundel;
                }
            })
            ->editColumn('jumlah', function (DetailCashier $item) {
                if ($item->product_id) {
                    return '<input type="number" name="jumlah" class="form-control input-sm quantity" data-item-id="' . $item->id   . '" data-harga="' . $item->product->harga . '" value="' . $item->jumlah . '" min="0" ' . "max=" . $item->product->stok . ' size="3"' . '>';
                } else {
                    return '<input type="number" name="jumlah" class="form-control input-sm quantity" data-item-id="' . $item->id   . '" data-harga="' . $item->promoBundle->harga_promo . '" value="' . $item->jumlah . '" min="0" ' . ' size="3"' . '>';
                }
            })
            // ->editColumn('kategori', function (DetailCashier $item) {
            //     return $item->kategori;
            // })
            ->editColumn('satuan', function (DetailCashier $item) {
                $satuans = Satuan::all();
                return view('pages.cashier.select', compact('satuans', 'item'));
            })
            ->editColumn('harga', function (DetailCashier $item) {
                if ($item->product_id) {
                    return "Rp." . convertRupiah($item->product->harga);
                } else {
                    return "Rp." . convertRupiah($item->promoBundle->harga_promo);
                }
            })
            ->editColumn('diskon', function (DetailCashier $item) {
                if ($item->product_id) {
                    return $item->product->diskon . ' %';
                } else {
                    return '<span style="text-decoration: line-through;">' . "Rp." . convertRupiah($item->promoBundle->harga_asli) . '</span>';
                }
            })
            ->editColumn('subTotal', function (DetailCashier $item) {
                if ($item->product_id) {
                    return '<span class="subTotal" data-item-id=' . $item->product->id . '>' .  'Rp.' .  number_format($item->sub_total, 0, ',', '.') . '</span>';
                } else {
                    return '<span class="subTotal" data-item-id=' . $item->promoBundle->id . '>' .  'Rp.' .  number_format($item->sub_total, 0, ',', '.') . '</span>';
                }
            })
            ->addColumn('action', function (DetailCashier $item) {
                return view('pages.cashier._action-menu', [
                    'item' => $item
                ]);
            })
            ->rawColumns(['jumlah', 'subTotal', 'diskon']);
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
