<?php

namespace VCComponent\Laravel\Export\Http\Controllers\Api\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use VCComponent\Laravel\Export\Exports\Export;
use VCComponent\Laravel\Export\Repositories\ExportsQueryRepository;
use VCComponent\Laravel\Export\Validators\ExportValidator;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;
use VCComponent\Laravel\Vicoders\Core\Exceptions\PermissionDeniedException;

class ExportController extends ApiController
{

    protected $validator;

    public function __construct(ExportValidator $validator, ExportsQueryRepository $repository)
    {
        $this->repository = $repository;
        $this->entity = $repository->getEntity();
        $this->validator = $validator;
        if (config('export_query.auth_middleware.admin.middleware') !== '') {
            $this->middleware(
                config('export_query.auth_middleware.admin.middleware'),
                ['except' => config('export_query.auth_middleware.admin.except')]
            );
        }
    }
    public function export($slug, Request $request)
    {
        if (config('export_query.auth_middleware.admin.middleware') !== '') {
            $user = $this->getAuthenticatedUser();
            if (Gate::forUser($user)->denies('view', $this->entity)) {
                throw new PermissionDeniedException();
            }
        }
        $export_query = $this->repository->findBySlug($slug);
        if ($export_query == null) {
            throw new Exception("Dữ liệu không tồn tại");
        }
        $data_request = $request->all();

        $trans = [];
        if ($request->has('status') && $request->status != '') {
            $trans += [":status_condition" => "="];
        } else {
            $trans += [":status_condition" => "<>"];
        }

        if ($request->has('from_date') && $request->from_date != '') {
            $trans += [":from_date_condition" => ">="];
        } else {
            $trans += [":from_date_condition" => "<>"];
        }

        if ($request->has('to_date') && $request->to_date != '') {
            $trans += [":to_date_condition" => "<="];
        } else {
            $trans += [":to_date_condition" => "<>"];
        }

        if ($request->has('position') && $request->position != '') {
            $trans += [":position_condition" => "="];
        } else {
            $trans += [":position_condition" => "<>"];
        }
        foreach ($data_request as $item => $val) {
            if ($val != null) {
                $trans[":" . $item] = $val;
            } else {
                $trans[":" . $item] = "''";
            }
        }

        $tring_query = strtr($export_query->query, $trans);

        try {
            $query = DB::select($tring_query);
        } catch (Exception $e) {
            throw new Exception($e);
        }
        if ($query == null) {
            throw new Exception("Dữ liệu không tồn tại");
        }
        $label = "export_" . $slug . "_" . date('Y_m_d');
        return Excel::download(new Export($query), $label . '.xlsx');
    }
}
