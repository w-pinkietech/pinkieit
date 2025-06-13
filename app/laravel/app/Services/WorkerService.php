<?php

namespace App\Services;

use App\Http\Requests\StoreWorkerRequest;
use App\Http\Requests\UpdateWorkerRequest;
use App\Models\Worker;
use App\Repositories\WorkerRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;

/**
 * 作業者サービス
 */
class WorkerService
{
    private readonly WorkerRepository $worker;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->worker = App::make(WorkerRepository::class);
    }

    /**
     * すべての作業者を取得する
     *
     * @return Collection<int, Worker>
     */
    public function all()
    {
        return $this->worker->all();
    }

    /**
     * 作業者を追加する
     *
     * @param  StoreWorkerRequest  $request  作業者追加リクエスト
     * @return bool 成否
     */
    public function store(StoreWorkerRequest $request): bool
    {
        return $this->worker->store($request);
    }

    /**
     * 作業者を更新する
     *
     * @param  UpdateWorkerRequest  $request  作業者更新リクエスト
     * @param  Worker  $worker  削除対象の作業者
     * @return bool 成否
     */
    public function update(UpdateWorkerRequest $request, Worker $worker): bool
    {
        return $this->worker->update($request, $worker);
    }

    /**
     * 作業者を削除する
     *
     * @param  Worker  $worker  削除対象の作業者
     * @return bool 成否
     */
    public function destroy(Worker $worker): bool
    {
        return $this->worker->destroy($worker);
    }
}
