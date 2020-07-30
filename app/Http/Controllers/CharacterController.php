<?php

declare (strict_types=1);

namespace BreakingBad\Http\Controllers;

use Illuminate\Http\Request;
use BreakingBad\Http\Resources\CharacterResource;
use BreakingBad\Data\Lifecycle\CharacterLifecycle;

/**
 * Class CharacterController
 */
class CharacterController extends Controller
{
    public function index(Request $request)
    {
        $characters = CharacterLifecycle::search($request->only('status', 'portrayed', 'name'), [
            'column' => $request->sortColumn ?? 'name',
            'direction' => $request->sortDirection ?? 'ASC'
        ])->paginate(10);

        return CharacterResource::collection($characters);
    }
}
