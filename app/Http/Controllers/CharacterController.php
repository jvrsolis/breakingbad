<?php

declare (strict_types=1);

namespace BreakingBad\Http\Controllers;

use Illuminate\Http\Request;
use BreakingBad\Data\Models\Character;
use BreakingBad\Libraries\Filter\Filtrate;
use BreakingBad\Http\Resources\CharacterResource;

/**
 * Class CharacterController
 */
class CharacterController extends Controller
{
    public function index(Request $request)
    {
        $characters = Filtrate::to(Character::class, 'list_characters')
        ->orderBy($request->sortColumn ?? 'name', $request->sortDirection ?? 'asc')
        ->paginate(10);
        return CharacterResource::collection($characters);
    }
}
