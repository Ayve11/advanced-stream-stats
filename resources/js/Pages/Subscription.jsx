import PrimaryButton from '@/Components/PrimaryButton';
import SubscriptionCard from '@/Components/SubscriptionCard';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputError from '../Components/InputError';
import { Inertia } from '@inertiajs/inertia'
import { Head, usePage } from '@inertiajs/inertia-react';
import * as braintree from 'braintree-web-drop-in';
import { useEffect, useState } from 'react';

export default function Subscription(props) {
    const [plan, setPlan] = useState('');
    const [loading, setLoading] = useState(true);
    const [braintreeInstance, setBraintreeInstance] = useState(undefined)
    const { errors } = usePage().props;

    useEffect(() => {
        braintree.create({
            authorization: props.clientToken,
            container: '#dropin-container'
        }, function (error, instance) {
            setBraintreeInstance(instance);
            setLoading(false);
        });
    }, [])

    useEffect(() => {
        if (braintreeInstance) {
            braintreeInstance.clearSelectedPaymentMethod();
        }
    }, [errors])

    const submit = async (e) => {
        e.preventDefault();
        setLoading(true);
        let response = await braintreeInstance.requestPaymentMethod();
        if (response.nonce && response.type) {
            const formData = {
                payment_nonce: response.nonce,
                payment_type: response.type,
                plan: plan
            };
            Inertia.post(route("subscription.create"), formData);
        }
    }

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Subscription panel</h2>}
        >
            <Head title="Subscription" />

            <form onSubmit={submit}>
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

                <div className="max-w-2xl mx-auto sm:px-6 lg:px-8">
                    <div id="dropin-container"></div>
                </div>

                <div className="pt-4 max-w-2xl mx-auto sm:px-6 lg:px-8 align-middle text-center">
                    {loading ? (
                        <div className='animate-spin' />
                    ) : (
                        <PrimaryButton type='submit'>
                            Request payment
                        </PrimaryButton>
                    )}

                </div>
            </form>

        </AuthenticatedLayout >
    );
}