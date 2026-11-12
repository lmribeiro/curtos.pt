<?php

namespace app\components;

use app\models\Link;
use Yii;
use yii\db\Expression;

class LinkStatistics
{
    public function summarize(Link $link, $days = 'all')
    {
        $days = in_array($days, [7, 30, 90], true) ? $days : 'all';
        $today = new \DateTimeImmutable('today');
        $start = $days === 'all' ? null : $today->modify('-' . ($days - 1) . ' days');
        $query = $link->getLinkStats()
            ->andWhere(['<=', 'created_at', date('Y-m-d H:i:s')]);
        $lastVisit = (clone $query)->max('created_at');
        if ($start !== null) {
            $query->andWhere(['>=', 'created_at', $start->format('Y-m-d H:i:s')]);
        }
        $summary = (clone $query)->select([
            'visits' => new Expression('COUNT(*)'),
            'uniqueIps' => new Expression("COUNT(DISTINCT NULLIF([[ip]], ''))"),
        ])->asArray()->one();

        $dailyCounts = (clone $query)->select([
            'day' => new Expression('SUBSTR([[created_at]], 1, 10)'),
            'count' => new Expression('COUNT(*)'),
        ])->groupBy('day')->indexBy('day')->asArray()->all();
        if ($start === null) {
            $start = $dailyCounts ? new \DateTimeImmutable(min(array_keys($dailyCounts))) : $today;
        }
        $daily = [];
        $weekdays = array_fill(1, 7, 0);
        for ($day = $start; $day <= $today; $day = $day->modify('+1 day')) {
            $date = $day->format('Y-m-d');
            $count = isset($dailyCounts[$date]) ? (int) $dailyCounts[$date]['count'] : 0;
            $daily[] = ['date' => $date, 'count' => $count];
            $weekdays[(int) $day->format('N')] += $count;
        }

        $hourCounts = (clone $query)->select([
            'hour' => new Expression('SUBSTR([[created_at]], 12, 2)'),
            'count' => new Expression('COUNT(*)'),
        ])->groupBy('hour')->indexBy('hour')->asArray()->all();
        $hours = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $key = sprintf('%02d', $hour);
            $hours[] = ['name' => $key . ':00', 'count' => isset($hourCounts[$key]) ? (int) $hourCounts[$key]['count'] : 0];
        }
        $weekdayNames = ['Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado', 'Domingo'];
        $weekdayData = [];
        foreach ($weekdayNames as $index => $name) {
            $weekdayData[] = ['name' => Yii::t('app', $name), 'count' => $weekdays[$index + 1]];
        }

        $countries = (clone $query)->select(['country_code', 'country_name', 'count' => new Expression('COUNT(*)')])
            ->andWhere(['not', ['country_code' => null]])->andWhere(['<>', 'country_code', ''])
            ->groupBy(['country_code', 'country_name'])->asArray()->all();
        $countryData = [];
        foreach ($countries as $country) {
            $code = strtoupper($country['country_code']);
            if (!preg_match('/^[A-Z]{2}$/', $code)) {
                continue;
            }
            if (!isset($countryData[$code])) {
                $countryData[$code] = [
                    'id' => $code,
                    'name' => $country['country_name'] ?: $code,
                    'value' => 0,
                    'color' => '#3fb7eb',
                ];
            }
            $countryData[$code]['value'] += (int) $country['count'];
        }

        return [
            'days' => $days,
            'start' => $start->format('Y-m-d'),
            'end' => $today->format('Y-m-d'),
            'visits' => (int) $summary['visits'],
            'uniqueIps' => (int) $summary['uniqueIps'],
            'lastVisit' => $lastVisit,
            'daily' => $daily,
            'hours' => $hours,
            'weekdays' => $weekdayData,
            'browsers' => $this->distribution($query, ['browser']),
            'countries' => array_values($countryData),
            'devices' => $this->distribution($query, ['platform'], [
                'desktop' => 'Computador', 'mobile' => 'Telemóvel', 'tablet' => 'Tablet', 'unknown' => 'Desconhecido',
            ]),
            'systems' => $this->distribution($query, ['os'], [
                'windows' => 'Windows', 'windowsphone' => 'Windows Phone', 'android' => 'Android',
                'ios' => 'iOS', 'macos' => 'macOS', 'linux' => 'Linux', 'unknown' => 'Desconhecido',
            ]),
            'cities' => $this->distribution($query, ['city', 'region', 'country_name'], [], 10),
            'regions' => $this->distribution($query, ['region', 'country_name'], [], 10),
            'sources' => $this->distribution($query, ['referrer'], [], 10),
        ];
    }

    private function distribution($query, array $fields, array $labels = [], $limit = null)
    {
        $rows = (clone $query)->select(array_merge($fields, ['count' => new Expression('COUNT(*)')]))
            ->groupBy($fields)->orderBy(['count' => SORT_DESC])->addOrderBy(array_fill_keys($fields, SORT_ASC))
            ->asArray()->all();
        $data = [];
        foreach ($rows as $row) {
            $parts = [];
            foreach ($fields as $field) {
                $value = $row[$field];
                if ($field === 'referrer') {
                    $value = $value === null ? Yii::t('app', 'Não recolhida') : ($value === '' ? Yii::t('app', 'Direto / desconhecido') : $value);
                }
                if ($value !== null && trim($value) !== '') {
                    $parts[] = isset($labels[$value]) ? Yii::t('app', $labels[$value]) : $value;
                } elseif ($field === $fields[0]) {
                    $parts[] = Yii::t('app', 'Desconhecido');
                }
            }
            $name = implode(' / ', array_unique($parts));
            if (!isset($data[$name])) {
                $data[$name] = ['name' => $name, 'count' => 0];
            }
            $data[$name]['count'] += (int) $row['count'];
        }
        usort($data, function ($first, $second) {
            return ($second['count'] <=> $first['count']) ?: strcmp($first['name'], $second['name']);
        });
        return $limit === null ? $data : array_slice($data, 0, $limit);
    }
}
