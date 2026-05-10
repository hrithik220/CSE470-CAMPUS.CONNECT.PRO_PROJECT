<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class SustainabilityService
{
    private const CO2_PER_ITEM = [
        'textbooks' => 7.5,
        'electronics' => 20.0,
        'furniture' => 47.0,
        'clothing' => 10.0,
        'sports' => 8.0,
        'supplies' => 2.5,
        'tickets' => 0.5,
        'other' => 5.0,
    ];

    public function totalItemsReused(): int
    {
        return Transaction::where('transactions.status', 'completed')->count();
    }

    public function estimatedCO2Saved(): float
    {
        $transactions = Transaction::where('transactions.status', 'completed')
            ->with('item')
            ->get();

        $totalCO2 = 0;

        foreach ($transactions as $transaction) {
            $category = $transaction->item->category ?? 'other';
            $totalCO2 += self::CO2_PER_ITEM[$category] ?? 5.0;
        }

        return round($totalCO2, 1);
    }

    public function totalTransactions(): int
    {
        return Transaction::where('transactions.status', 'completed')->count();
    }

    public function totalMoneySaved(): float
    {
        $totalSpent = Transaction::where('transactions.status', 'completed')->sum('amount');
        return round($totalSpent * 0.4, 2);
    }

    public function getMonthlyStats(int $months = 12): array
    {
        $stats = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);

            $count = Transaction::where('transactions.status', 'completed')
                ->whereMonth('transactions.created_at', $date->month)
                ->whereYear('transactions.created_at', $date->year)
                ->count();

            $stats[] = [
                'month' => $date->format('M Y'),
                'transactions' => $count,
            ];
        }

        return $stats;
    }

    public function getItemsByCategory(): array
    {
        return Transaction::where('transactions.status', 'completed')
            ->join('items', 'transactions.item_id', '=', 'items.id')
            ->select('items.category', DB::raw('COUNT(*) as total'))
            ->groupBy('items.category')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    Item::CATEGORIES[$item->category] ?? $item->category => $item->total
                ];
            })
            ->toArray();
    }

    public function getCO2ByCategory(): array
    {
        $result = [];

        $transactions = Transaction::where('transactions.status', 'completed')
            ->join('items', 'transactions.item_id', '=', 'items.id')
            ->select('items.category', DB::raw('COUNT(*) as total'))
            ->groupBy('items.category')
            ->get();

        foreach ($transactions as $row) {
            $category = Item::CATEGORIES[$row->category] ?? $row->category;

            $co2 = $row->total * (self::CO2_PER_ITEM[$row->category] ?? 5.0);

            $result[$category] = round($co2, 1);
        }

        return $result;
    }

    public function getDashboardSummary(): array
    {
        return [
            'items_reused' => $this->totalItemsReused(),
            'co2_saved' => $this->estimatedCO2Saved(),
            'total_transactions' => $this->totalTransactions(),
            'money_saved' => $this->totalMoneySaved(),
            'monthly_stats' => $this->getMonthlyStats(6),
            'items_by_category' => $this->getItemsByCategory(),
            'co2_by_category' => $this->getCO2ByCategory(),
        ];
    }
}