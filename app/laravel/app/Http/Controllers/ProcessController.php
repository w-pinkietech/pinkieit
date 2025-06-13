<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProcessRequest;
use App\Http\Requests\UpdateProcessRequest;
use App\Models\Process;
use App\Services\ProcessService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * 工程コントローラー
 */
class ProcessController extends AbstractController
{
    /**
     * コンストラクタ
     *
     * @param  ProcessService  $service  工程サービス
     */
    public function __construct(private readonly ProcessService $service)
    {
        $this->middleware('auth');
    }

    /**
     * UI表示用名称を取得する
     *
     * @return string 名称
     */
    public function name(): string
    {
        return __('pinkieit.process');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $processes = $this->service->all();

        return view('process.index', ['processes' => $processes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorizeAdmin();

        return view('process.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreProcessRequest  $request  リクエスト
     */
    public function store(StoreProcessRequest $request): RedirectResponse
    {
        $result = $this->service->store($request);

        return $this->redirectWithStore($result, 'process.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  Process  $process  工程
     */
    public function show(Process $process): View
    {
        $lines = $this->service->productionLines($process);

        return view('process.show', [
            'process' => $process,
            'lines' => $lines,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Process  $process  工程
     */
    public function edit(Process $process): View
    {
        $this->authorizeAdmin();
        $this->throwExceptionIfRunning($process);

        return view('process.edit', ['process' => $process]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProcessRequest  $request  リクエスト
     * @param  Process  $process  工程
     */
    public function update(UpdateProcessRequest $request, Process $process): RedirectResponse
    {
        $this->throwExceptionIfRunning($process);
        $result = $this->service->update($request, $process);

        return $this->redirectWithUpdate($result, 'process.show', ['process' => $process]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Process  $process  工程
     */
    public function destroy(Process $process): RedirectResponse
    {
        $this->authorizeAdmin();
        $this->throwExceptionIfRunning($process);
        $result = $this->service->destroy($process);

        return $this->redirectWithDestroy($result, 'process.index');
    }
}
