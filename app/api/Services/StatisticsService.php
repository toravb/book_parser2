<?php

namespace App\api\Services;

use App\Event;
use App\Helpers\Constants;
use Exception;
use App\Factories\Statistics\ViewsEventsConnector;
use App\Factories\Statistics\ViewsPostConnector;
use App\Factories\Statistics\ViewsProfileConnector;
use App\Factories\Statistics\ViewsRepostEventConnector;
use App\Factories\Statistics\ViewsRepostPostConnector;
use App\Factories\Statistics\ViewsGeneralConnector;
use App\Interfaces\StatisticsViewsConnector;
use App\Jobs\SynchronizeFrontIdJob;
use App\StatisticsEventsView;
use App\StatisticsUserProfileViewsSummary;
use App\StatisticsViewsEventSummary;
use App\User;
use App\UserStatisticsProfileViews;
use App\StatisticsPost;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsService
{

    protected static $availableStatistics = [
        'profile' => ViewsProfileConnector::class,
        'post' => ViewsPostConnector::class,
        'repost_photo' => ViewsPostConnector::class,
        'repost_post' => ViewsRepostPostConnector::class,
        'repost_video' => ViewsPostConnector::class,
        'repost_event' => ViewsRepostEventConnector::class,
        'event' => ViewsEventsConnector::class,
        'general' => ViewsGeneralConnector::class
    ];

    public static function getClass(
        $type,
        $id,
        $frontId,
        $place = null
    ): StatisticsViewsConnector {
        $statisticClass = self::$availableStatistics[$type];
        $statisticService = new StatisticsService();

        return (new $statisticClass($id, $frontId, $statisticService, $place));
    }


    public function viewProfile($userId, $frontId, $auth)
    {
        $day = Carbon::today()->format('Y-m-d');

        $postView = UserStatisticsProfileViews::where('user_id', $userId)
            ->where('front_id', $frontId)
            ->where('day', $day)
            ->first();

        if ($postView === null) {
            $today = Carbon::today();
            UserStatisticsProfileViews::create([
                'user_id' => $userId,
                'front_id' => $frontId,
                'day' => $day,
                'auth' => $auth,
                'created_at' => $today,
                'updated_at' => $today
            ]);
        }

        return true;
    }

    public function viewPost($postId, $frontId, $auth)
    {

        $postView = StatisticsPost::where('post_id', $postId)
            ->where('front_id', $frontId)
            ->first();

        if ($postView === null) {
            $today = Carbon::today();
            $postView = StatisticsPost::create([
                'post_id' => $postId,
                'front_id' => $frontId,
                'auth' => $auth,
                'created_at' => $today,
                'updated_at' => $today
            ]);
        }

        return $postView;
    }

    public function viewEvent($eventId, $frontId, $place, $auth)
    {
        $eventView = StatisticsEventsView::where('event_id', $eventId)
            ->where('front_id', $frontId)
            ->where('place', $place)
            ->first();

        if ($eventView === null) {
            $today = Carbon::today();
            $eventView = StatisticsEventsView::create([
                'event_id' => $eventId,
                'front_id' => $frontId,
                'place' => $place,
                'auth' => $auth,
                'created_at' => $today,
                'updated_at' => $today
            ]);
        }

        return $eventView;
    }

    public function synchronizeFrontId($frontId, $userId)
    {
        $frontIdRecord = DB::table('front_user')->where('user_id', $userId)
            ->first();

        if ($frontIdRecord !== null) {
            SynchronizeFrontIdJob::dispatch($frontIdRecord->front_id, $frontId);

            return $frontIdRecord->front_id;
        } else {
            try {
                DB::table('front_user')->insert([
                    ['front_id' => $frontId, 'user_id' => $userId]
                ]);
            } catch (Exception $exception) {
                if ($exception->getCode() != Constants::UNIQUE_CONSTRAINT_VIOLATION_CODE) {
                    throw new Exception($exception);
                }
            }


            return $frontId;
        }
    }

    public function countProfileViews($day)
    {
        User::where('account_type', 'organizer')
            ->orWhere('account_type', 'artist')
            ->chunk(100, function ($users) use ($day) {
                $data = [];

                foreach ($users as $user) {
                    $countOfViews = StatisticsUserProfileViewsSummary::where('user_id', $user->id)
                        ->where('created_at', $day)
                        ->first();

                    if ($countOfViews === null) {
                        $loggedInViewsCount = UserStatisticsProfileViews::where('user_id', $user->id)
                            ->where('day', $day)
                            ->where('auth', UserStatisticsProfileViews::AUTHORIZED_FRONT_ID)
                            ->count();

                        $unloggedViewsCount = UserStatisticsProfileViews::where('user_id', $user->id)
                            ->where('day', $day)
                            ->where('auth', UserStatisticsProfileViews::NOT_AUTHORIZED_FRONT_ID)
                            ->count();

                        $insertData['user_id'] = $user->id;
                        $insertData['unlogged_views'] = $unloggedViewsCount;
                        $insertData['logged_in_views'] = $loggedInViewsCount;
                        $insertData['created_at'] = $day;
                        $insertData['updated_at'] = $day;

                        $data[] = $insertData;
                    }
                }
                StatisticsUserProfileViewsSummary::insert($data);
            });
    }

    public function viewGeneral($frontId, $auth)
    {
        $day = Carbon::today()->format('Y-m-d');


        $generalView = DB::table('statistics_views_general')
            ->where('front_id', $frontId)
            ->whereDate('created_at', $day)
            ->first();

        if ($generalView === null) {
            $today = Carbon::today();
            DB::table('statistics_views_general')
                ->insert([
                    'front_id' => $frontId,
                    'auth' => $auth,
                    'created_at' => $today,
                    'updated_at' => $today
                ]);
        }
    }

    public function countGeneralViews($day)
    {
        $loggedInViewsCount = DB::table('statistics_views_general')
            ->where('auth', UserStatisticsProfileViews::AUTHORIZED_FRONT_ID)
            ->where('created_at', $day)
            ->count();
        $unloggedViewsCount = DB::table('statistics_views_general')
            ->where('auth', UserStatisticsProfileViews::NOT_AUTHORIZED_FRONT_ID)
            ->where('created_at', $day)
            ->count();

        $countOfViews = DB::table('statistics_views_general_summaries')
            ->where('created_at', $day)
            ->first();
        if ($countOfViews === null) {
            DB::table('statistics_views_general_summaries')
                ->insert([
                    'unlogged_views' => $unloggedViewsCount,
                    'logged_in_views' => $loggedInViewsCount,
                    'created_at' => $day,
                    'updated_at' => $day
                ]);
        } else {
            DB::table('statistics_views_general_summaries')->where('created_at', $day)
                ->update([
                    'unlogged_views' => $unloggedViewsCount,
                    'logged_in_views' => $loggedInViewsCount,
                ]);
        }
    }

    public function countEventsViews($day)
    {
        Event::select('id')
            ->chunk(100, function ($events) use ($day) {
                $data = [];

                foreach ($events as $event) {
                    $countOfViews = StatisticsViewsEventSummary::where('event_id', $event->id)
                        ->whereDate('created_at', $day)
                        ->first();

                    if ($countOfViews === null) {
                        $loggedInViewsCount = StatisticsEventsView::where('event_id', $event->id)
                            ->whereDate('created_at', $day)
                            ->where('auth', UserStatisticsProfileViews::AUTHORIZED_FRONT_ID)
                            ->count();

                        $unloggedViewsCount = StatisticsEventsView::where('event_id', $event->id)
                            ->whereDate('created_at', $day)
                            ->where('auth', UserStatisticsProfileViews::NOT_AUTHORIZED_FRONT_ID)
                            ->count();

                        $insertData['event_id'] = $event->id;
                        $insertData['unlogged_views'] = $unloggedViewsCount;
                        $insertData['logged_in_views'] = $loggedInViewsCount;
                        $insertData['created_at'] = $day;
                        $insertData['updated_at'] = $day;

                        $data[] = $insertData;
                    }
                }
                StatisticsViewsEventSummary::insert($data);
            });
    }

    /**
     * Get statistics in admin panel for one of statistics elements(general views, event views, profile views)
     *
     */
    public function getStatisticsForOneItem($statisticsModel, $request)
    {
        $views = $statisticsModel->select('unlogged_views', 'logged_in_views', 'created_at')
            ->when($request->begin, function ($query) use ($request) {
                return $query->whereDate('created_at', '>=', $request->begin);
            })
            ->when($request->end, function ($query) use ($request) {
                return $query->whereDate('created_at', '<=', $request->end);
            })
            ->orderBy('created_at', 'desc')
            ->get();


        $collection = collect();
        $unloggedViews = collect();
        $loggedInViews = collect();

        foreach ($views as $view) {
            $day = $view->created_at->format('Y-m-d');

            $collection->push($day);
            $unloggedViews->push($view->unlogged_views);
            $loggedInViews->push($view->logged_in_views);
        }

        return [
            'status' => 'success',
            'data' => [
                'labels' => $collection,
                'datasets' => [
                    [
                        'label' => 'Unlogged views',
                        'data' => $unloggedViews
                    ],
                    [
                        'label' => 'Logged in views',
                        'data' => $loggedInViews
                    ]
                ],

            ]
        ];
    }
}
