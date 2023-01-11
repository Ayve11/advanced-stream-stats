<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function dashboard(Request $request) : Response
    {
        $user = $request->user();
        $subscribedUser = $user->hasActiveSubscription();
        $pageProps = [
            'appName' => config("app.name"),
            'statistics' => [
                "liveViewersNow" => rand(0, 1000),
                "currentStreamTime" => rand(0, 10),
                "todayFollows" => rand(0, 1000),
                "todaySubscriptions" => rand(0, 100),
            ],
            "subscribedUser" => $subscribedUser
        ];

        if($subscribedUser){
            $pageProps['statistics'] = array_merge($pageProps['statistics'], [
            "allViewers" => rand(0, 1000000),
            "allFollows" => rand(0, 100000),
            "allSubscriptions" => rand(0, 5000),
            "allStreamTime" => rand(0, 10000),
            "averageDailyViewers" => rand(0, 5000),
            "averageDailyFollows" => rand(0, 1000),
            "averageDailySubscriptions" => rand(0, 100),
            "averageDailyStreamTime" => rand(0, 14)]);
        }

        return Inertia::render('Dashboard', $pageProps);
    }
}

