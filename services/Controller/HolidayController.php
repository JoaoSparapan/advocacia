<?php

class HolidayController
{
    private string $cachePath = "../../files/";
    private string $token = "2587%7Cg5ZUyNmCx098wx0ktPZ8AHrPki8xIosR";
    private string $state = "SP";

    /* ===============================
       API
    =============================== */

    private function requestHolidayInAPI(int $year): array
    {
        $url = "https://api.invertexto.com/v1/holidays/{$year}?token={$this->token}&state={$this->state}";
        $response = file_get_contents($url);

        return $response ? json_decode($response, true) : [];
    }

    /* ===============================
       CACHE
    =============================== */

    private function getCacheFile(int $year): string
    {
        return $this->cachePath . "holiday_{$year}.json";
    }

    private function getHolidaysByYear(int $year): array
    {
        $file = $this->getCacheFile($year);

        if (!file_exists($file)) {
            $holidays = $this->requestHolidayInAPI($year);

            if (!empty($holidays)) {
                file_put_contents($file, json_encode($holidays));
            }
        }

        return file_exists($file)
            ? json_decode(file_get_contents($file), true)
            : [];
    }

    /* ===============================
       BUSINESS
    =============================== */

    private function isWeekend(string $date): bool
    {
        $weekDay = date('w', strtotime($date));
        return $weekDay == 0 || $weekDay == 6;
    }

    public function isHoliday(
        string $date,
        bool $considerState = false,
        bool $considerNational = true
    ): bool {
        $year = (int) date('Y', strtotime($date));
        $holidays = $this->getHolidaysByYear($year);

        foreach ($holidays as $holiday) {
            if ($holiday['date'] !== $date) {
                continue;
            }

            if ($holiday['level'] === 'nacional' && $considerNational) {
                return true;
            }

            if ($holiday['level'] === 'estadual' && $considerState) {
                return true;
            }
        }

        return false;
    }

    public function estimateTerm(
        string $startDate,
        int $days,
        bool $considerState = false,
        bool $considerNational = true,
        int $type = 0
    ): string {
        $current = $startDate;
        $count = 0;

        while ($count < $days) {
            $current = date("Y-m-d", strtotime("+1 day", strtotime($current)));

            if ($type === 0) {
                if (
                    $this->isWeekend($current) ||
                    $this->isHoliday($current, $considerState, $considerNational)
                ) {
                    continue;
                }
            }

            $count++;
        }

        // Ajuste final se cair no fim de semana
        while ($this->isWeekend($current)) {
            $current = date("Y-m-d", strtotime("+1 day", strtotime($current)));
        }

        return $current;
    }
}