<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class TodosController
 * @package App\Http\Controllers
 */
class TodosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ResponseFactory|Response
     */
    public function index()
    {
        return response(TodoResource::collection(Todo::where(['user_id' => auth()->id()])->get()), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TodoRequest $request
     *
     * @return ResponseFactory|Response
     */
    public function store(TodoRequest $request)
    {
        $todo = Todo::create($request->merge(['user_id' => auth()->id()])->all());

        return response(TodoResource::make($todo), Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TodoRequest $request
     * @param Todo        $todo
     *
     * @return ResponseFactory|JsonResponse|Response
     */
    public function update(TodoRequest $request, Todo $todo)
    {
        if ($request->user_id !== $todo->user_id) {
            return response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }
        $todo->update($request->all());

        return response(TodoResource::make($todo), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     *
     * @return ResponseFactory|Response
     */
    public function updateAll(Request $request)
    {
        $data = $request->validate([
            'completed' => 'required|boolean',
        ]);

        Todo::where(['user_id' => auth()->id()])->update($data);

        return response('Updated', Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Todo $todo
     *
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function destroy(Todo $todo)
    {
        if ($todo->user_id !== auth()->id()) {
            return response()->json('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $todo->delete();

        return response(TodoResource::make($todo), Response::HTTP_OK);
    }

    /**
     * Remove the completed field resource from storage.
     *
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function destroyCompleted(Request $request)
    {
        $request->validate([
            'todos' => 'required|array',
        ]);

        $todosToDelete = $request->todos;
        $userTodoIds = auth()->user()->todos->map(function ($todo) {
            return $todo->id;
        });

        $valid = collect($todosToDelete)->every(function ($value, $key) use ($userTodoIds) {
            return $userTodoIds->contains($value);
        });

        if (!$valid) {
            return response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        Todo::destroy($todosToDelete);

        return response('Destroy', Response::HTTP_OK);
    }
}
