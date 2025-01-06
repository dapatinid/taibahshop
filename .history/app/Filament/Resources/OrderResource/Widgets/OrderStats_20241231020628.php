<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        // $unpaid = Number::abbreviate(Order::query()->where('branch_id', Auth::user()->branch_id)->where('is_paid', 0)->where('status', '!=', 'canceled')->sum('grand_total'), precision: 2);
        $unpaid = Number::format(Order::query()->where('branch_id', Auth::user()->branch_id)
            ->where('is_paid', 0)->where('total_cashback', '<', '0')->where('status', '!=', 'canceled')
            ->sum('total_cashback'), locale: 'de');
        $unpaid = "Rp" . $unpaid;

        // $paid = Number::abbreviate(Order::query()->where('branch_id', Auth::user()->branch_id)->where('is_paid', 1)->where('status', '!=', 'canceled')->where('created_at', '>=', Carbon::today())->sum('grand_total'), precision: 2);
        $paid = Number::format(Order::query()->where('branch_id', Auth::user()->branch_id)
            ->where('is_paid', 1)->where('status', '!=', 'canceled')->where('updated_at', '>=', Carbon::today())
            ->sum('grand_total'), locale: 'de');
        $paid = "Rp" . $paid;

        $today = Carbon::today()->format('Y-m-d');
        $today2 = Carbon::today()->sub('1 day')->format('Y-m-d');
        $today3 = Carbon::today()->sub('2 day')->format('Y-m-d');
        $today4 = Carbon::today()->sub('3 day')->format('Y-m-d');
        $today5 = Carbon::today()->sub('4 day')->format('Y-m-d');
        $today6 = Carbon::today()->sub('5 day')->format('Y-m-d');
        $today7 = Carbon::today()->sub('6 day')->format('Y-m-d');
        $paid_chart = Order::query()->where('branch_id', Auth::user()->branch_id)->where('is_paid', 1)->where('status', '!=', 'canceled')->where('date_order', 'like', "%$today%")->sum('grand_total');
        $paid_chart2 = Order::query()->where('branch_id', Auth::user()->branch_id)->where('is_paid', 1)->where('status', '!=', 'canceled')->where('date_order', 'like', "%$today2%")->sum('grand_total');
        $paid_chart3 = Order::query()->where('branch_id', Auth::user()->branch_id)->where('is_paid', 1)->where('status', '!=', 'canceled')->where('date_order', 'like', "%$today3%")->sum('grand_total');
        $paid_chart4 = Order::query()->where('branch_id', Auth::user()->branch_id)->where('is_paid', 1)->where('status', '!=', 'canceled')->where('date_order', 'like', "%$today4%")->sum('grand_total');
        $paid_chart5 = Order::query()->where('branch_id', Auth::user()->branch_id)->where('is_paid', 1)->where('status', '!=', 'canceled')->where('date_order', 'like', "%$today5%")->sum('grand_total');
        $paid_chart6 = Order::query()->where('branch_id', Auth::user()->branch_id)->where('is_paid', 1)->where('status', '!=', 'canceled')->where('date_order', 'like', "%$today6%")->sum('grand_total');
        $paid_chart7 = Order::query()->where('branch_id', Auth::user()->branch_id)->where('is_paid', 1)->where('status', '!=', 'canceled')->where('date_order', 'like', "%$today7%")->sum('grand_total');

        $incOrDecPaid = ($paid_chart >= $paid_chart2) ? 'increase' : 'decrease';
        $incOrDecPaidColor = ($paid_chart >= $paid_chart2) ? 'info' : 'danger';
        $incOrDecPaidIcon = ($paid_chart >= $paid_chart2) ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $incOrDecPaidPersen = Number::percentage((($paid_chart - $paid_chart2) / $paid_chart2) * 100, maxPrecision: 2);

        return [
            Stat::make('New Order', Order::query()->where('branch_id', Auth::user()->branch_id)->where('status', 'new')->count()),
            Stat::make('Order Processing', Order::query()->where('branch_id', Auth::user()->branch_id)->where('status', 'processing')->count()),
            Stat::make('Order Shipped', Order::query()->where('branch_id', Auth::user()->branch_id)->where('status', 'shipped')->count()),

            Stat::make('Payment Pending (All)', Order::query()->where('branch_id', Auth::user()->branch_id)->where('is_paid', 0)->count())
                ->icon('heroicon-m-bell'),
            Stat::make('Total Pending (All)', $unpaid)
                ->description('3% decrease')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('success')
                ->chart([10, 3, 15, 4, 17, 7, 2]),
            Stat::make('Total Paid Today', $paid)
                ->description($incOrDecPaidPersen . " " . $incOrDecPaid)
                ->descriptionIcon($incOrDecPaidIcon)
                ->color($incOrDecPaidColor)
                ->chart([$paid_chart, $paid_chart2, $paid_chart3, $paid_chart4, $paid_chart5, $paid_chart6, $paid_chart7]),
        ];
    }
}
