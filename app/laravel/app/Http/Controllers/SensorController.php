<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSensorRequest;
use App\Http\Requests\UpdateSensorRequest;
use App\Models\Process;
use App\Models\Sensor;
use App\Services\SensorService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * センサーコントローラー
 */
class SensorController extends AbstractController
{
    /**
     * コンストラクタ
     *
     * @param  SensorService  $service  センサーサービス
     */
    public function __construct(private readonly SensorService $service)
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
        return __('pinkieit.alarm');
    }

    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @param Process $process 工程
    //  * @return Response
    //  */
    // public function index(Process $process)
    // {
    //     //
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Process $process): View
    {
        $this->authorizeAdmin();
        $this->throwExceptionIfRunning($process);
        $raspberryPiOptions = $this->service->raspberryPiOptions();
        $sensorTypes = $this->service->sensorTypeOptions();

        return view('process.alarm.create', [
            'process' => $process,
            'raspberryPiOptions' => $raspberryPiOptions,
            'sensorTypes' => $sensorTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreSensorRequest  $request  リクエスト
     */
    public function store(StoreSensorRequest $request, Process $process): RedirectResponse
    {
        $this->throwExceptionIfRunning($process);
        $result = $this->service->store($request);

        return $this->redirectWithStore($result, 'process.show', ['process' => $process, 'tab' => 'alarm']);
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param Process $process 工程
    //  * @param Sensor $sensor
    //  * @return Response
    //  */
    // public function show(Process $process, Sensor $sensor)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Process  $process  工程
     */
    public function edit(Process $process, Sensor $sensor): View
    {
        $raspberryPiOptions = $this->service->raspberryPiOptions();
        $sensorTypes = $this->service->sensorTypeOptions();

        return view('process.alarm.edit', [
            'process' => $process,
            'sensor' => $sensor,
            'raspberryPiOptions' => $raspberryPiOptions,
            'sensorTypes' => $sensorTypes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateSensorRequest  $request  リクエスト
     * @param  Process  $process  工程
     */
    public function update(UpdateSensorRequest $request, Process $process, Sensor $sensor): RedirectResponse
    {
        $this->throwExceptionIfRunning($process);
        $result = $this->service->update($request, $sensor);

        return $this->redirectWithUpdate($result, 'process.show', ['process' => $process, 'tab' => 'alarm']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Process  $process  工程
     */
    public function destroy(Process $process, Sensor $sensor): RedirectResponse
    {
        $this->authorizeAdmin();
        $this->throwExceptionIfRunning($process);
        $result = $this->service->destroy($sensor);

        return $this->redirectWithDestroy($result, 'process.show', ['process' => $process, 'tab' => 'alarm']);
    }
}
