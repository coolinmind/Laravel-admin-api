<?php

namespace Pl\LaravelAdminApi\Controller;

use Pl\LaravelAdminApi\Repository\OperationLogRepository;
use Illuminate\Http\Request;
use App\Http\success;

class OperationLogController extends Controller
{

    public $operationLogRepository;

    public function __construct(OperationLogRepository $operationLogRepository)
    {
        $this->operationLogRepository = $operationLogRepository;
    }


    /**
     * 日志list
     * Created by PhpStorm.
     * User: EricPan
     * Date: 2019/8/1
     * Time: 11:59
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function log_list(Request $request)
    {
        $data = $this->operationLogRepository->log_list($request);

        return success::success($data);
    }
}
