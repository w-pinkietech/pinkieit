<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlannedOutageRequest;
use App\Http\Requests\UpdatePlannedOutageRequest;
use App\Models\PlannedOutage;
use App\Services\PlannedOutageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * 計画停止時間コントローラー
 */
class PlannedOutageController extends AbstractController
{
    /**
     * コンストラクタ
     *
     * @param  PlannedOutageService  $service  計画停止時間サービス
     */
    public function __construct(private readonly PlannedOutageService $service)
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
        return __('pinkieit.planned_outage');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $plannedOutages = $this->service->all();

        return view('planned-outage.index', ['plannedOutages' => $plannedOutages]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorizeAdmin();

        return view('planned-outage.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePlannedOutageRequest  $request  リクエスト
     */
    public function store(StorePlannedOutageRequest $request): RedirectResponse
    {
        $this->authorizeAdmin();
        $result = $this->service->store($request);

        return $this->redirectWithStore($result, 'planned-outage.index');
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param PlannedOutage $plannedOutage
    //  * @return Response
    //  */
    // public function show(PlannedOutage $plannedOutage)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlannedOutage $plannedOutage): View
    {
        $this->authorizeAdmin();

        return view('planned-outage.edit', ['plannedOutage' => $plannedOutage]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePlannedOutageRequest  $request  リクエスト
     */
    public function update(UpdatePlannedOutageRequest $request, PlannedOutage $plannedOutage): RedirectResponse
    {
        $result = $this->service->update($request, $plannedOutage);

        return $this->redirectWithUpdate($result, 'planned-outage.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlannedOutage $plannedOutage): RedirectResponse
    {
        $this->authorizeAdmin();
        $result = $this->service->destroy($plannedOutage);

        return $this->redirectWithDestroy($result, 'planned-outage.index');
    }
}
