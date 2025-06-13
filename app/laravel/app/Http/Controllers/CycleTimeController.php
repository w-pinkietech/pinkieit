<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCycleTimeRequest;
use App\Http\Requests\UpdateCycleTimeRequest;
use App\Models\CycleTime;
use App\Models\Process;
use App\Services\CycleTimeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * サイクルタイムコントローラー
 */
class CycleTimeController extends AbstractController
{
    /**
     * コンストラクタ
     *
     * @param  CycleTimeService  $service  サイクルタイムサービス
     */
    public function __construct(private readonly CycleTimeService $service)
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
        return __('pinkieit.cycle_time');
    }

    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return Response
    //  */
    // public function index()
    // {
    //     //
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Process  $process  工程 工程
     */
    public function create(Process $process): View
    {
        $this->authorizeAdmin();
        $this->throwExceptionIfRunning($process);
        $partNumbers = $this->service->unusedPartNumberOptions($process->process_id);

        return view('process.cycle-time.create', ['process' => $process, 'partNumbers' => $partNumbers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCycleTimeRequest  $request  リクエスト
     * @param  Process  $process  工程 工程
     */
    public function store(StoreCycleTimeRequest $request, Process $process): RedirectResponse
    {
        $this->throwExceptionIfRunning($process);
        $result = $this->service->store($request);

        return $this->redirectWithStore($result, 'process.show', ['process' => $process, 'tab' => 'part-number']);
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param CycleTime $cycleTime
    //  * @return Response
    //  */
    // public function show(CycleTime $cycleTime)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  CycleTime  $cycleTime  サイクルタイム
     * @param  Process  $process  工程 工程
     */
    public function edit(Process $process, CycleTime $cycleTime): View
    {
        $this->authorizeAdmin();
        $this->throwExceptionIfRunning($process);

        return view('process.cycle-time.edit', ['process' => $process, 'cycleTime' => $cycleTime]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCycleTimeRequest  $request  リクエスト
     * @param  Process  $process  工程 工程
     * @param  CycleTime  $cycleTime  サイクルタイム
     */
    public function update(UpdateCycleTimeRequest $request, Process $process, CycleTime $cycleTime): RedirectResponse
    {
        $this->throwExceptionIfRunning($process);
        $result = $this->service->update($request, $cycleTime);

        return $this->redirectWithUpdate($result, 'process.show', ['process' => $process, 'tab' => 'part-number']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Process  $process  工程 工程
     * @param  CycleTime  $cycleTime  サイクルタイム
     */
    public function destroy(Process $process, CycleTime $cycleTime): RedirectResponse
    {
        $this->authorizeAdmin();
        $this->throwExceptionIfRunning($process);
        $result = $this->service->destroy($cycleTime);

        return $this->redirectWithDestroy($result, 'process.show', ['process' => $process, 'tab' => 'part-number']);
    }
}
