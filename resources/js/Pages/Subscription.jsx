import SubscriptionCard from '@/Components/SubscriptionCard';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputError from '../Components/InputError';
import { Head, usePage } from '@inertiajs/inertia-react';
import { useState } from 'react';
import PaymentPanel from '@/Components/PaymentPanel';

export default function Subscription(props) {
    const [plan, setPlan] = useState('');
    const { errors } = usePage().props;

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Subscription panel</h2>}
        >
            <Head title="Subscription" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className='grid grid-cols-4 gap-4 auto-rows-max pb-4'>
                        {props.subscriptionPlans.map(({ type, price }) => (
                            <SubscriptionCard key={type} type={type} price={price}
                                onClick={(type) => setPlan(type)}
                                active={plan === type}
                            />
                        ))}
                    </div>
                    {errors?.plan && <InputError message={errors.plan} />}
                </div>
            </div>

            {plan && <PaymentPanel clientToken={props.clientToken}
                plan={props.subscriptionPlans.find(subscriptionPlan => subscriptionPlan.type === plan)} 
            />}

        </AuthenticatedLayout >
    );
}