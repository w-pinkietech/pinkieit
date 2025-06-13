<?php

namespace App\Http\Controllers;

use App\Models\Process;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

/**
 * 基底コントローラークラス
 */
abstract class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 管理者権限のみ可
     *
     * @throws AuthorizationException 管理者権限がない場合に例外を投げる
     */
    protected function authorizeAdmin(): void
    {
        $this->authorize('admin');
    }

    /**
     * システム管理者権限のみ可
     *
     * @throws AuthorizationException システム管理者権限がない場合に例外を投げる
     */
    protected function authorizeSystem(): void
    {
        $this->authorize('system');
    }

    /**
     * 指定した工程が停止していれば可
     *
     * @param  Process  $process  工程
     *
     * @throws AuthorizationException 工程が実行中の場合に例外を投げる
     */
    protected function throwExceptionIfRunning(Process $process): void
    {
        if ($process->isRunning()) {
            throw new AuthorizationException;
        }
    }
}
