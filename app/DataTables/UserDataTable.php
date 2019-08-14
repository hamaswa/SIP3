<?php
namespace App\DataTables;
use App\Models\User;
use Yajra\Datatables\Services\DataTable;
class UserDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @return \Yajra\Datatables\Engines\BaseEngine
     */
    public function dataTable()
    {
        return $this->datatables
            ->eloquent($this->query())
			->editColumn('status', function (User $info_User) {
				if ($info_User->status == '1'){
					return 'Active';
				}
				else{
					return 'Inactive';
				}
			})
			//->editColumn('Extension', 'admin.users.extension')
            ->editColumn('queue', 'admin.users.queue')
			->addColumn('action', 'admin.users.datatables_actions')
			->escapeColumns([]);
    }
    /**
     * Get the query object to be processed by dataTables.
     *
     * @return mixed
     */
    public function query()
    {
        $query = User::query();
        return $this->applyScopes($query);
    }
    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '300px'])
            ->parameters([
                'dom'     => 'Bfrtip',
                'order'   => [[0, 'desc']],
                'buttons' => [
                    'create',
                    /*'export',
                    'print',
                    'reset',
                    'reload',*/
                ],
            ]);
    }
    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id'=>['visible' => false],
            'name',
			'status',
            'email',
            'created_at',
            'queue',
            //'Extension',
        ];
    }
    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'users_' . time();
    }
}