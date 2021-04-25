<?php

namespace App\Services;

use App\Models\GameJob;
use DB;
use Exception;
use Illuminate\Support\Facades\Queue;
use Throwable;

/*
 * This class is needed to manage MoveTimeEndJob
 * */
class MoveTimeEndService
{
    private static array $softDeletedJob = [];

    /**
     * @param int $gameId
     * @param $serializedJob
     * @param string $data
     *
     * @return bool
     * @throws Throwable
     */
    public static function push(int $gameId, $serializedJob, $data = ''): bool
    {
        $delay = $serializedJob->delay ?? 0;
        $newJobId = Queue::later($delay, $serializedJob, $data);

        if ($newJobId === null) {
            return false;
        }

        GameJob::create([
            'job_id' => $newJobId,
            'game_id' => $gameId,
            'job_type' => 'move_time_end',
        ]);

        static::$softDeletedJob = [];
        return true;
    }

    /**
     * @param int $gameId
     * @throws Exception
     */
    public static function deleteLast(int $gameId): void
    {
        $lastGameJob = GameJob::where([
            ['job_type', 'move_time_end'],
            ['game_id', $gameId],
        ])->latest()?->first(['id', 'job_id']);

        if ($lastGameJob !== null) {
            $lastJobId = $lastGameJob->job_id;
            $lastGameJob->delete();
            DB::table('jobs')->delete($lastJobId);
        }
    }

    /**
     * @param int $gameId
     * @param $job
     * @throws Throwable
     */
    public static function deleteLastAndAddNew(int $gameId, $job): bool {
        DB::beginTransaction();

        try {
            static::deleteLast($gameId);
            $pushStatus = static::push($gameId, $job);

            if ($pushStatus === false) {
                throw new Exception();
            }

        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }

        DB::commit();
        return true;
    }

    /**
     * @param int $gameId
     * @throws Exception
     */
    public static function softDeleteLast(int $gameId): void
    {
        $lastGameJob = GameJob::where([
            ['job_type', 'move_time_end'],
            ['game_id', $gameId],
        ])->latest()?->first(['id', 'job_id']);

        if ($lastGameJob === null) {
            return;
        }

        $lastJob = DB::table('jobs')
            ->where(['id' => $lastGameJob->job_id])
            ->first(['queue', 'payload', 'attempts', 'reserved_at', 'available_at', 'created_at']);

        static::$softDeletedJob = get_object_vars($lastJob);
        static::deleteLast($gameId);
    }

    /**
     * @param int $gameId
     */
    public static function recoveryLast(int $gameId): void
    {
        if (empty(static::$softDeletedJob)) {
            return;
        }

        $jobId = DB::table('jobs')->insertGetId(static::$softDeletedJob);
        GameJob::create([
            'game_id' => $gameId,
            'job_id' => $jobId,
            'job_type' => 'move_time_end',
        ]);

        static::$softDeletedJob = [];
    }
}
