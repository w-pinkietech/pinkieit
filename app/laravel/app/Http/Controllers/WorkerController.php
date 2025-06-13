<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkerRequest;
use App\Http\Requests\UpdateWorkerRequest;
use App\Models\Worker;
use App\Services\WorkerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * 作業者コントローラー
 */
class WorkerController extends AbstractController
{
    /**
     * コンストラクタ
     *
     * @param  WorkerService  $service  作業者サービス
     */
    public function __construct(private readonly WorkerService $service)
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
        return __('pinkieit.worker');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $workers = $this->service->all();

        return view('worker.index', ['workers' => $workers]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorizeAdmin();

        return view('worker.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkerRequest $request): RedirectResponse
    {
        $result = $this->service->store($request);

        return $this->redirectWithStore($result, 'worker.index');
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param Worker $worker
    //  * @return Response
    //  */
    // public function show(Worker $worker)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Worker $worker): View
    {
        $this->authorizeAdmin();

        return view('worker.edit', ['worker' => $worker]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkerRequest $request, Worker $worker): RedirectResponse
    {
        $result = $this->service->update($request, $worker);

        return $this->redirectWithUpdate($result, 'worker.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Worker $worker): RedirectResponse
    {
        $this->authorizeAdmin();
        $result = $this->service->destroy($worker);

        return $this->redirectWithDestroy($result, 'worker.index');
    }
}
