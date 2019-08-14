<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ExtensionDataTable;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\CreateExtensionRequest;
use App\Http\Requests\UpdateExtensionRequest;
use App\Repositories\ExtensionRepository;
use App\Models\Extension;
use App\Models\User;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class ExtensionController extends AppBaseController
{
    /** @var  ExtensionRepository */
    private $extensionRepository;

    public function __construct(ExtensionRepository $extensionRepo)
    {
        $this->extensionRepository = $extensionRepo;
    }

    /**
     * Display a listing of the Extension.
     *
     * @param ExtensionDataTable $extensionDataTable
     * @return Response
     */
    public function index(ExtensionDataTable $extensionDataTable)
    {
        return $extensionDataTable->render('admin.extensions.index');
    }
	
	
	public function getExt(Request $request)
    {
		$extensions = Extension::where('user_id', "=" ,$request->user_id)->Get();
		$html = '<table style="width:100%">';
		$html .= '<tr><th>Extension</th><th>Action</th></tr>';
		foreach($extensions as $extension)
		{
			$html .= '<tr><td>'.$extension->extension_no.'</td><td><a data-remote="'.$extension->id.'" id="deleteExtension" class="btn btn-default btn-xs">
        <i class="glyphicon glyphicon-trash"></i>
    </a></td></tr>';
		}
		$html .= '</table>';
		return $html;
    }
	
	
	public function addExt(Request $request, ExtensionDataTable $extensionDataTable)
    {
		$input = $request->all();
        $extension = $this->extensionRepository->create($input);
    }
	
	public function deleteExt(Request $request, ExtensionDataTable $extensionDataTable)
    {
		$extension = $this->extensionRepository->findWithoutFail($request->extension_no);
        $this->extensionRepository->delete($request->extension_no);
    }

    /**
     * Show the form for creating a new Extension.
     *
     * @return Response
     */
    public function create()
    {
		$users = User::all();
        return view('admin.extensions.create')->with('users', $users);
    }

    /**
     * Store a newly created Extension in storage.
     *
     * @param CreateExtensionRequest $request
     *
     * @return Response
     */
    public function store(CreateExtensionRequest $request)
    {
        $input = $request->all();
				
        $extension = $this->extensionRepository->create($input);

        Flash::success('Extension saved successfully.');

        return redirect(route('extensions.index'));
    }

    /**
     * Display the specified Extension.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $extension = $this->extensionRepository->findWithoutFail($id);

        if (empty($extension)) {
            Flash::error('Extension not found');

            return redirect(route('extensions.index'));
        }

        return view('admin.extensions.show')->with('extension', $extension);
    }

    /**
     * Show the form for editing the specified Extension.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
		$users = User::all();
        $extension = $this->extensionRepository->findWithoutFail($id);

        if (empty($extension)) {
            Flash::error('Extension not found');

            return redirect(route('extensions.index'));
        }

        return view('admin.extensions.edit',array('users' => $users))->with('extension', $extension);
    }

    /**
     * Update the specified Extension in storage.
     *
     * @param  int              $id
     * @param UpdateExtensionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateExtensionRequest $request)
    {
		$input = $request->all();
		
        $extension = $this->extensionRepository->findWithoutFail($id);

        if (empty($extension)) {
            Flash::error('Extension not found');

            return redirect(route('extensions.index'));
        }

        $extension = $this->extensionRepository->update($request->all(), $id);

        Flash::success('Extension updated successfully.');

        return redirect(route('extensions.index'));
    }

    /**
     * Remove the specified Extension from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $extension = $this->extensionRepository->findWithoutFail($id);

        if (empty($extension)) {
            Flash::error('Extension not found');

            return redirect(route('extensions.index'));
        }

        $this->extensionRepository->delete($id);

        Flash::success('Extension deleted successfully.');

        return redirect(route('extensions.index'));
    }
}
