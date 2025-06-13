<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

/**
 * モデルのコントローラー抽象クラス
 */
abstract class AbstractController extends BaseController
{
    /**
     * UI表示用名称を取得する
     *
     * @return string 名称
     */
    abstract public function name(): string;

    /**
     * モデル作成後のページリダイレクト
     *
     * @param  bool  $result  成功 or 失敗
     * @param  string  $path  リダイレクトパス
     * @param  array<string, mixed>  $parameters  リダイレクトパラメータ
     */
    protected function redirectWithStore(bool $result, string $path, array $parameters = []): RedirectResponse
    {
        return $this->redirect($result, __('pinkieit.register'), $path, $parameters);
    }

    /**
     * モデル更新後のページリダイレクト
     *
     * @param  bool  $result  成功 or 失敗
     * @param  string  $path  リダイレクトパス
     * @param  array<string, mixed>  $parameters  リダイレクトパラメータ
     */
    protected function redirectWithUpdate(bool $result, string $path, array $parameters = []): RedirectResponse
    {
        return $this->redirect($result, __('pinkieit.update'), $path, $parameters);
    }

    /**
     * モデル削除後のページリダイレクト
     *
     * @param  bool  $result  成功 or 失敗
     * @param  string  $path  リダイレクトパス
     * @param  array<string, mixed>  $parameters  リダイレクトパラメータ
     */
    protected function redirectWithDestroy(bool $result, string $path, array $parameters = []): RedirectResponse
    {
        return $this->redirect($result, __('pinkieit.delete'), $path, $parameters);
    }

    /**
     * モデルのページリダイレクト
     *
     * @param  bool  $result  成功 or 失敗
     * @param  string  $action  作業名称
     * @param  string  $path  リダイレクトパス
     * @param  array<string, mixed>  $parameters  リダイレクトパラメータ
     */
    protected function redirect(bool $result, string $action, string $path, array $parameters = []): RedirectResponse
    {
        $route = redirect()->route($path, $parameters);
        if ($result) {
            $route->with('toast_success', __('pinkieit.success_toast', ['target' => $this->name(), 'action' => $action]));
        } else {
            $route->with('toast_danger', __('pinkieit.failed_toast', ['target' => $this->name(), 'action' => $action]));
        }

        return $route;
    }
}
