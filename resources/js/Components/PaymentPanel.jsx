import PrimaryButton from '@/Components/PrimaryButton';
import { Inertia } from '@inertiajs/inertia';
import * as braintree from 'braintree-web-drop-in';
import { useEffect, useState } from 'react';

export default function PaymentPanel({plan, clientToken}) {
    const [loading, setLoading] = useState(true);
    const [disabled, setDisabled] = useState(true);
    const [braintreeInstance, setBraintreeInstance] = useState(undefined)

    useEffect(() => {
        braintree.create({
            authorization: clientToken,
            container: '#dropin-container',
            paypal: {
                flow: 'vault',
            }
        }, function (error, instance) {
            instance.on("paymentMethodRequestable", async (e) => {
                setDisabled(false);           
            })
            setBraintreeInstance(instance);
            setLoading(false);
        });
    }, [])

    const submit = async (e) => {
        e.preventDefault();
        setLoading(true);
        let response = await braintreeInstance.requestPaymentMethod();
        if (response.nonce && response.type) {
            const formData = {
                payment_nonce: response.nonce,
                payment_type: response.type,
                plan: plan.type
            };
            Inertia.post(route("subscription.create"), formData);
        } else {
            setLoading(false);
        }
    }

    return (
        <form onSubmit={submit}>
            <div className="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div id="dropin-container"></div>
            </div>

            <div className="pt-4 max-w-2xl mx-auto sm:px-6 lg:px-8 align-middle text-center">
                {loading ? (
                    <div className='animate-spin' />
                ) : (
                    <>
                        <PrimaryButton type='submit' processing={disabled}>
                            Request payment
                        </PrimaryButton>
                        {!disabled && <div className='text-black dark:text-white text-sm'>You can now process payment</div>}
                    </>
                )}

            </div>
        </form>
    );
}