import PrimaryButton from '@/Components/PrimaryButton';
import SubscriptionCard from '@/Components/SubscriptionCard';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/inertia-react';
import { Inertia } from '@inertiajs/inertia'


export default function SubscriptionDetails(props) {
    const cancelSubscription = () => {
        Inertia.delete(route("subscription.cancel"));
    }
    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Subscription Details</h2>}
        >
            <Head title="Subscription details" />

            <div className="max-w-6xl mx-auto sm:px-6 lg:px-8 py-8">
                <div className="flex items-center">
                    <div className="ml-4 text-xl text-black dark:text-white font-semibold">
                        Subscription is {props.subscription.status}
                    </div>
                </div>

                <div className="ml-12">
                    <div className="mt-2 text-gray-600 dark:text-gray-400 text-lg">
                        You can freely use Your subscribed Twitch channel Advanced Stream Statistics until {props.subscription.expired_at}. <br />
                        {props.statusDescription}
                    </div>
                </div>
                <div className="max-w-md mx-auto pt-8">
                    <div className="mt-2 text-black dark:text-white text-lg">
                        Current subscription plan:
                    </div>
                    <SubscriptionCard type={props.subscriptionPlan.type} price={props.subscriptionPlan.price} visualOnly/>
                </div>
                
                {props.subscription.status === 'active' && <div className="max-w-md mx-auto pt-8">
                    <PrimaryButton onClick={cancelSubscription}>Cancel subscription</PrimaryButton>
                </div>}
            </div>
        </AuthenticatedLayout>
    );
}