<?php

namespace StallionExpress\AuthUtility\Trait;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Setting\App\Repositories\SettingRepositoryInterface;

trait TimezoneTrait
{
    /**
     * Get user timezone & set (Used while filter)
     *
     * @param  string|null $dateTime
     * @param              $userId
     * @return string|null
     */
    public function timezoneDate(?string $dateTime, ?int $userId)
    {
        $dateTime = Carbon::parse($dateTime);
        $key = $userId.'_generalSettings';

        $settingsCachedArr = Cache::rememberForever($key, function () use ($userId) {
            return app(SettingRepositoryInterface::class)->showSettings('generalSettings', $userId);
        });

        if (isset($settingsCachedArr, $settingsCachedArr['setting']['timezone']) && $dateTime != null) {
            $this->setTimezone($settingsCachedArr['setting']['timezone']);

            return $dateTime;
        }

        return $dateTime;
    }

    /**
     * Get user timezone
     *
     * @param              $userId
     * @return string|null
     */
    public function getUserTimezone(int $userId): ?string
    {
        $key = $userId.'_generalSettings';
        $settingsCachedArr = Cache::rememberForever($key, function () use ($userId) {
            return app(SettingRepositoryInterface::class)->showSettings('generalSettings', $userId);
        });
        if (isset($settingsCachedArr, $settingsCachedArr['setting']['timezone'])) {
            return $settingsCachedArr['setting']['timezone'];
        } else {
            return null;
        }
    }

    /**
     * Set user timezone
     *
     * @param       $timezone
     * @return void
     */
    public function setTimezone(?string $timezone): void
    {
        DB::statement("SET time_zone = '{$timezone}'");
    }

    /**
     * Return timezone date (Used while filter)
     *
     * @param              $data
     * @param              $userId
     * @return string|null
     */
    public function convertToTimezoneDate(?string $date, int $userId): ?string
    {
        return $date !== null ? $this->timezoneDate($date, $userId) : null;
    }

    /**
     * Get user timezone which is set in general settings
     *
     * @param       $userId
     * @return void
     */
    public function getAndSetUserTimezone(int $userId): void
    {
        $timezone = $this->getUserTimezone($userId);
        if (isset($timezone)) {
            $this->setTimezone($timezone);
        }
    }

    /**
     * Set default timezone
     *
     * @return void
     */
    public function setDefaultTimezone(): void
    {
        $currentDatabaseTimezone = 'SYSTEM';
        DB::statement("SET time_zone = '{$currentDatabaseTimezone}'");
    }
}
