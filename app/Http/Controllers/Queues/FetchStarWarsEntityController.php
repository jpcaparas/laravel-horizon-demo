<?php

namespace App\Http\Controllers\Queues;

use App\Http\Controllers\Controller;
use App\Jobs\FetchStarWarsEntity;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FetchStarWarsEntityController extends Controller
{
    public function __construct()
    {
        //
    }

    function __invoke(Request $request)
    {
        $userId = $request->query('user_id') ?? 1;

        try {
            /**
             * @var User $user
             */
            $user = User::findOrFail($userId);
        } catch (ModelNotFoundException $exception) {
            abort(Response::HTTP_BAD_REQUEST, 'User does not exist.');
        }

        $entityType = $request->query('entity') ?? 'people';
        $repeat = $request->query('repeat') ?? 1;

        do {
            dispatch(new FetchStarWarsEntity($entityId = random_int(1, 100), $entityType, $user));
            $repeat--;
        } while ($repeat > 0);

        $message = sprintf(
'An email has been dispatched to %1$s <%2$s> 
about a Star Wars entity with the type [%3$s] and ID of [%4$s].',
            $user->name,
            $user->email,
            $entityType,
            $entityId
        );

        return response($message, Response::HTTP_OK, ['Content-Type' => 'text/plain']);
    }
}
