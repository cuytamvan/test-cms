<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Exceptions\ValidationException;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\PermissionResource;
use App\Repositories\PermissionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    protected $repository;
    protected $resource;
    protected $input = ['name'];
    protected $permission = 'Permission';

    public function __construct(PermissionRepository $repository)
    {
        $this->repository = $repository;
        $this->resource = PermissionResource::class;

        $this->middleware('permission:Read ' . $this->permission)->only('index', 'show');
        $this->middleware('permission:Create ' . $this->permission)->only('store');
        $this->middleware('permission:Update ' . $this->permission)->only('update');
        $this->middleware('permission:Delete ' . $this->permission)->only('destroy');
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $params = $request->query();
            $payload = $this->repository->setPayload($params);
            $data = $this->repository->getData();

            $res = $payload['withPagination'] ?
                new BaseCollection($data, $this->resource) :
                $this->resource::collection($data);

            return response()->json([
                'data' => $res,
                'message' => 'success',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $payload = $request->only($this->input);
            $validator = Validator::make($payload, [
                'name' => 'required|max:100',
            ]);
            if ($validator->fails())
                throw new ValidationException($validator->errors()->first());

            $data = $this->repository->store(null, $payload);

            DB::commit();
            return response()->json([
                'data' => new $this->resource($data),
                'message' => 'success',
            ]);
        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $data = $this->repository->findById($id);
            return response()->json([
                'data' => $data ? new $this->resource($data) : null,
                'message' => 'success',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $payload = $request->only($this->input);
            $validator = Validator::make($payload, [
                'name' => 'required|max:100',
            ]);
            if ($validator->fails())
                throw new ValidationException($validator->errors()->first());

            $data = $this->repository->store($id, $payload);

            DB::commit();
            return response()->json([
                'data' => new $this->resource($data),
                'message' => 'success',
            ]);
        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->repository->delete($id);

            DB::commit();
            return response()->json([
                'message' => 'deleted',
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
