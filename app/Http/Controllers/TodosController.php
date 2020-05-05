<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Http\Resources\TodoResource;
use App\Todo;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
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
        return response(TodoResource::collection(Todo::all()), Response::HTTP_OK);
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
        $todo = Todo::create($request->all());

        return response(TodoResource::make($todo), Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TodoRequest $request
     * @param Todo        $todo
     *
     * @return Response
     */
    public function update(TodoRequest $request, Todo $todo)
    {
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

        Todo::query()->update($data);

        return response('Updated', Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Todo $todo
     *
     * @return Response
     * @throws Exception
     */
    public function destroy(Todo $todo)
    {
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

        Todo::destroy($request->todos);

        return response('Destroy', Response::HTTP_OK);
    }
}
