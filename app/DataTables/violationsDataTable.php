<?php

namespace App\DataTables;

use App\Models\Violation;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class violationsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Violation> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            
            ->setRowId('id')
            ->editColumn('tgl_pelanggaran', function ($violation) {
                    return date('d F Y', strtotime($violation->tgl_pelanggaran));
            })
            ->addColumn('action', function ($query) {
                return view('pages.violations.action', compact('query'));
            });
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Violation>
     */
    public function query(Violation $model): QueryBuilder
    {
        return $model->newQuery()
            ->select('violations.*');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('violations-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
            Button::make('csv'),
            Button::make('pdf'),
            Button::make('print'),
            Button::make('reset'),
            Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [
            Column::make('id')
                    ->title('#')
                    ->render('meta.row + meta.settings._iDisplayStart + 1;')
                    ->width(100)
                    ->name('violations.id'),
            Column::make('nis')->name('violations.nis'),
            Column::make('nama_siswa')->name('violations.nama_siswa'),
            Column::make('kelas')->title('Kelas'),
            Column::make('tgl_pelanggaran')->title('Tanggal Pelanggaran'),
            Column::make('kategori_pelanggaran'),
            Column::make('point_pelanggaran'),
            Column::make('total_point'),
            Column::make('deskripsi_pelanggaran'),
        ];

        if (auth()->check() && in_array(optional(auth()->user()->role)->role_name, ['admin', 'guru'])) {
            $columns[] = Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(250)
                  ->addClass('text-center');
        }

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'violations_' . date('YmdHis');
    }
}
