<?php

namespace app\base\controller;

use think\Controller;
use think\Request;

class ModuleBaseController extends Controller
{
    /**
     * 操作成功
     */
    const ACTION_SUCCESS = 'success';

    /**
     * 操作失败
     */
    const ACTION_FAIL = 'fail';

    /**
     * 成功标示码
     */
    const ACTION_SUCCESS_CODE = 1;

    /**
     * 失败标示码
     */
    const ACTION_FAIL_CODE = 0;

    /**
     * 页码起始位置
     */
    private $offset = 0;

    /**
     * 页码大小
     */
    protected $limit = 8;

    /**
     * 构造函数
     * BaseController constructor.
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $header = Request::instance()->header();
        $this->offset = $header['offset'] ?? $this->offset;
        $this->limit = $header['limit'] ?? $this->limit;
    }

    /**
     * 操作成功
     * @param array $data
     * @param int $count
     * @param string $msg
     * @return array|void
     */
    public function actionSuccess($data = [], $count = 0, $msg = '操作成功')
    {
        return [
            'result' => self::ACTION_SUCCESS,
            'code' => self::ACTION_SUCCESS_CODE,
            'count' => $count,
            'msg' => $msg,
            'data' => $data
        ];
    }

    /**
     * 操作失败
     * @param string $msg
     * @return array
     */
    public function actionFail($msg = '操作失败')
    {
        return [
            'result' => self::ACTION_FAIL,
            'code' => self::ACTION_FAIL_CODE,
            'count' => 0,
            'msg' => $msg,
        ];
    }
}