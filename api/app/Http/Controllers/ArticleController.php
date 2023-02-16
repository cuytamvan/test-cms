<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Exceptions\ValidationException;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\ArticleResource;
use App\Repositories\ArticleRepository;
use Cuytamvan\BasePattern\Services\UploadFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    protected $repository;
    protected $resource;
    protected $input = [
        'title',
        'slug',
        'article_category_id',
        'content',
        'banner',
    ];
    protected $permission = 'Article';
    protected $disk = 'public';
    protected $path = 'articles';

    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
        $this->resource = ArticleResource::class;

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
            $data = $this->repository->getData(null, ['article_category']);

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
                'title' => 'required|string|max:100',
                'article_category_id' => 'required|numeric',
                'content' => 'required|string',
                'banner' => 'required|image',
            ]);
            if ($validator->fails())
                throw new ValidationException($validator->errors()->first());

            if ($request->banner) {
                $file = (new UploadFile($request->banner))
                    ->setDisk($this->disk)
                    ->setPath($this->path);

                $payload['banner'] = $file->getFilename();
            } else unset($payload['banner']);

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

    public function show(string $slug): JsonResponse
    {
        try {
            $data = $this->repository->findBySlug($slug);
            if ($data) $data->load('article_category');

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
                'title' => 'required|string|max:100',
                'article_category_id' => 'required|numeric',
                'content' => 'required|string',
                'banner' => 'nullable|image',
            ]);
            if ($validator->fails())
                throw new ValidationException($validator->errors()->first());

            if ($request->banner) {
                $file = (new UploadFile($request->banner))
                    ->setDisk($this->disk)
                    ->setPath($this->path);

                $payload['banner'] = $file->getFilename();
            } else unset($payload['banner']);

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
