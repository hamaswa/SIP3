<?php
namespace App\DataTables;
use App\Models\User;
use Yajra\Datatables\Services\DataTable;
class SubUserDataTable extends DataTable
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
			->editColumn('extension', 'cms.users.extension')
            ->editColumn('queue', 'cms.users.queue')
			->addColumn('action', 'cms.users.datatables_actions')
			->escapeColumns([]);
    }
    /**
     * Get the query object to be processed by dataTables.
     *
     * @return mixed
     */
    public function query()
    {
        $query = User::whereRaw("parent_id = ". auth()->id());
        return $this->applyScopes($query);
    }
    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        $options = [
            'dom'     => 'Bfrtip',
            'order'   => [[0, 'desc']],
        ];
        if(auth()->user()->can("user_add")) {
            $options['buttons'] =
                [
                    'create',
                ];
        }
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '300px'])
            ->parameters($options);
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
            'extension',
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