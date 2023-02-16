<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Exceptions\ValidationException;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Cuytamvan\BasePattern\Services\UploadFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $repository;
    protected $resource;
    protected $input = [
        'name',
        'email',
        'password',
        'role_id',
        'image',
    ];
    protected $permission = 'User';
    protected $disk = 'public';
    protected $path = 'users';

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
        $this->resource = UserResource::class;

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
            $data = $this->repository->getData(null, ['role']);

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
                'name' => 'required|string|max:100',
                'email' => 'required|email',
                'password' => 'required|string',
                'image' => 'nullable|image',
                'role_id' => 'required',
            ]);
            if ($validator->fails())
                throw new ValidationException($validator->errors()->first());

            if ($request->image) {
                $file = (new UploadFile($request->image))
                    ->setDisk($this->disk)
                    ->setPath($this->path);

                $payload['image'] = $file->getFilename();
            } else unset($payload['image']);

            $payload['password'] = Hash::make($payload['password']);

            $data = $this->repository->store(null, $payload);

            if (isset($file)) $file->upload();

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
            if ($data) $data->load('role');

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
                'name' => 'required|string|max:100',
                'email' => 'required|email',
                'password' => 'nullable|string',
                'image' => 'nullable|image',
                'role_id' => 'required',
            ]);
            if ($validator->fails())
                throw new ValidationException($validator->errors()->first());

            if ($request->image) {
                $file = (new UploadFile($request->image))
                    ->setDisk($this->disk)
                    ->setPath($this->path);

                $payload['image'] = $file->getFilename();
            } else unset($payload['image']);

            if ($request->password) $payload['password'] = Hash::make($payload['password']);
            else unset($payload['password']);

            $data = $this->repository->store($id, $payload);

            if (isset($file)) $file->upload();

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
