import SubscriptionCard from '@/Components/SubscriptionCard';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/inertia-react';
import { useEffect } from "react";

export default function Subscription(props) {
    const { data, setData, post, processing, errors, reset } = useForm({
        plan: '',
        payment_method: '',
    });

    useScript("https://js.braintreegateway.com/web/3.88.4/js/client.min.js")
    
    const handlePlanChange = (type) => {
        setData("plan", type);
    }

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
                        {props.subscriptionPlans.map(({type, price}) => (
                            <SubscriptionCard key={type} type={type} price={price} onClick={handlePlanChange} active={data.plan === type}/>
                        ))}
                    </div>
                </div>
            </div>

        </AuthenticatedLayout>
    );
}


const useScript = (url, async = true) => {
  useEffect(() => {
    const script = document.createElement("script")
    script.src = url
    script.async = async
    document.body.appendChild(script)
    return () => {
      document.body.removeChild(script)
    }
  }, [url, async])
}

