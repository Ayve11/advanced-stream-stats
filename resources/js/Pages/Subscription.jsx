import PrimaryButton from '@/Components/PrimaryButton';
import SubscriptionCard from '@/Components/SubscriptionCard';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/inertia-react';
import * as braintree from 'braintree-web-drop-in';

export default function Subscription(props) {
    const { data, setData, post, processing, errors, reset } = useForm({
        plan: '',
        payment_nonce: '',
        payment_type: '',
    });

    const handlePlanChange = (type) => {
        setData("plan", type);
    }

    const submit = (e) => {
        e.preventDefault();
    }

    braintree.create({
        authorization: props.clientToken,
        container: '#dropin-container'
    }, function (error, instance) {
        if (error) console.error(error);
        const form = document.getElementById('payment-form');
        form.addEventListener('submit', event => {
            event.preventDefault();            
            instance.requestPaymentMethod(function (error, payload) {
                if (error) console.error(error);
                if(payload){
                    console.log(payload);
                    setData('payment_nonce', payload.nonce)
                    setData('payment_type', payload.type)
                    post(route('subscription.create'));
                }
            });
        });
    });

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Subscription panel</h2>}
        >
            <Head title="Subscription" />

            <form onSubmit={submit} id="payment-form">
                <div className="py-12">
                    <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div className='grid grid-cols-4 gap-4 auto-rows-max pb-4'>
                            {props.subscriptionPlans.map(({ type, price }) => (
                                <SubscriptionCard key={type} type={type} price={price} onClick={handlePlanChange} active={data.plan === type} />
                            ))}
                        </div>
                    </div>
                </div>
                <div className="max-w-2xl mx-auto sm:px-6 lg:px-8">
                    <div id="dropin-container"></div>
                </div>

                <div className="pt-4 max-w-2xl mx-auto sm:px-6 lg:px-8 align-middle text-center">
                    <PrimaryButton type='submit'>
                        Request payment
                    </PrimaryButton>
                </div>
            </form>

        </AuthenticatedLayout>
    );
}