<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Column;

class UsersDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('full_name', function($user) {
                return $user->first_name . ' ' . $user->last_name;
            })
            ->editColumn('email', function($user) {
                return '<span contenteditable="true" class="editable" data-id="'.$user->id.'" data-column="email">'.$user->email.'</span>';
            })
            ->editColumn('phone_number', function($user) {
                return '<span contenteditable="true" class="editable" data-id="'.$user->id.'" data-column="phone_number">'.$user->phone_number.'</span>';
            })
            ->editColumn('plain_password', function($user) {
                return '<span contenteditable="true" class="editable" data-id="'.$user->id.'" data-column="plain_password">'.$user->plain_password.'</span>';
            })
            ->editColumn('status', function($user) {
                $colors = [
                    'active' => 'primary',
                    'inactive' => 'danger',
                    'banned' => 'dark',
                ];
                $color = $colors[$user->status] ?? 'warning';
                return '<span contenteditable="true" class="editable-status badge bg-'.$color.'" data-id="'.$user->id.'" data-column="status">'.$user->status.'</span>';
            })
            ->addColumn('action', 'users.action')
            ->rawColumns(['email', 'phone_number', 'plain_password', 'status', 'action']);
    }

    public function query()
    {
        return User::select([
            'id',
            'mat_id',
            'first_name',
            'last_name',
            'email',
            'phone_number',
            'plain_password',
            'status',
            'created_at'
        ]);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('dataTable')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('<"row align-items-center"<"col-md-2" l><"col-md-6" B><"col-md-4" f>>
                  <"table-responsive my-3" rt>
                  <"row align-items-center"<"col-md-6" i><"col-md-6" p>><"clear">')
            ->parameters([
                "processing" => true,
                "autoWidth" => false,
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::make('id')->title('ID'),
            Column::computed('full_name')->title('Full Name'),
            Column::make('mat_id')->title('Matric Number'),
            Column::make('email')->title('Email'),
            Column::make('phone_number')->title('Phone Number'),
            Column::make('plain_password')->title('Plain Password'),
            Column::make('status')->title('Status'),
            Column::make('created_at')->title('Created At'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->searchable(false)
                  ->width(60)
                  ->addClass('text-center hide-search'),
        ];
    }

    protected function filename()
    {
        return 'Users_' . date('YmdHis');
    }
}
