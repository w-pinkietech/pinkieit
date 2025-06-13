<?php

namespace App\Repositories;

use App\Models\DefectiveProduction;
use App\Services\Utility;
use Carbon\Carbon;

/**
 * 不良品生産数リポジトリ
 *
 * @extends AbstractRepository<DefectiveProduction>
 */
class DefectiveProductionRepository extends AbstractRepository
{
    /**
     * モデルクラス
     *
     * @return class-string
     */
    public function model(): string
    {
        return DefectiveProduction::class;
    }

    /**
     * 不良品生産数を追加する
     *
     * @param  int  $productionLineId  生産ラインID
     * @param  int  $count  カウント
     * @param  Carbon  $date  時刻
     * @return DefectiveProduction|null 追加されたデータ
     */
    public function save(int $productionLineId, int $count, Carbon $date): ?DefectiveProduction
    {
        $defectiveProduction = new DefectiveProduction([
            'production_line_id' => $productionLineId,
            'count' => $count,
            'at' => Utility::format($date),
        ]);

        return $this->storeModel($defectiveProduction) ? $defectiveProduction : null;
    }
}
