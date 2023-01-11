import StatisticsCard from '@/Components/StatisticsCard';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/inertia-react';

export default function Dashboard(props) {

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <div className='py-12'>
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="text-2xl text-gray-600 dark:text-gray-200">Your Twitch statistics:</div>
                </div>
            </div>

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className='grid grid-cols-4 gap-4 auto-rows-max pb-4'>

                        <StatisticsCard header="Live viewers now">
                            {props.statistics.liveViewersNow}
                        </StatisticsCard>
                        <StatisticsCard header="Today follows">
                            {props.statistics.todayFollows}
                        </StatisticsCard>
                        <StatisticsCard header="Today subscriptions">
                            {props.statistics.todaySubscriptions}
                        </StatisticsCard>
                        <StatisticsCard header="Current stream time">
                            {props.statistics.currentStreamTime}
                        </StatisticsCard>

                    </div>

                    {props.subscribedUser ? (
                        <div className='grid grid-cols-4 gap-4 auto-rows-max'>

                            <StatisticsCard header="Average daily viewers">
                                {props.statistics.averageDailyViewers}
                            </StatisticsCard>
                            <StatisticsCard header="Average daily follows">
                                {props.statistics.averageDailyFollows}
                            </StatisticsCard>
                            <StatisticsCard header="Average daily subscriptions">
                                {props.statistics.averageDailySubscriptions}
                            </StatisticsCard>
                            <StatisticsCard header="Average daily stream time">
                                {props.statistics.averageDailyStreamTime}
                            </StatisticsCard>

                            <StatisticsCard header="All viewers">
                                {props.statistics.allViewers}
                            </StatisticsCard>
                            <StatisticsCard header="All follows">
                                {props.statistics.allFollows}
                            </StatisticsCard>
                            <StatisticsCard header="All subscriptions">
                                {props.statistics.allSubscriptions}
                            </StatisticsCard>
                            <StatisticsCard header="All stream time">
                                {props.statistics.allStreamTime}
                            </StatisticsCard>

                        </div>
                    ) : (
                        <div className='align-middle text-center py-12'>
                            <div className='text-xl text-gray-600 dark:text-gray-200'>
                                You have to be subscribed to see more Twitch statistics
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}