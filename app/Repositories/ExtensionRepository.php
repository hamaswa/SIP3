<?php

namespace App\Repositories;

use App\Models\Extension;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ExtensionRepository
 * @package App\Repositories
 * @version May 11, 2018, 7:11 am UTC
 *
 * @method Extension findWithoutFail($id, $columns = ['*'])
 * @method Extension find($id, $columns = ['*'])
 * @method Extension first($columns = ['*'])
*/
class ExtensionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'sub_user_id',
        'did_no',
        'extension_no'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Extension::class;
    }
}
