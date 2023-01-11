import { Link, Head } from '@inertiajs/inertia-react';

export default function Welcome(props) {
    return (
        <>
            <Head title="Welcome" />
            <div className="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
                <div className="fixed top-0 right-0 px-6 py-4 sm:block">
                    {props.auth.user ? (
                        <Link href={route('dashboard')} className="text-sm text-gray-700 dark:text-gray-500 underline">
                            Dashboard
                        </Link>
                    ) : (
                        <>
                            <Link href={route('login')} className="text-sm text-gray-700 dark:text-gray-500 underline">
                                Log in
                            </Link>

                            <Link
                                href={route('register')}
                                className="ml-4 text-sm text-gray-700 dark:text-gray-500 underline"
                            >
                                Register
                            </Link>
                        </>
                    )}
                </div>

                <div className="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    <div className="flex items-center">
                        <div className="ml-4 text-xl text-black dark:text-white font-semibold">
                            {props.appName}
                        </div>
                    </div>

                    <div className="ml-12">
                        <div className="mt-2 text-gray-600 dark:text-gray-400 text-lg">
                            {props.auth.user ? 
                                "Go to dashboard page to see Your Twitch channel statistics."
                                : "Create an account to see Your Twitch channel statistics."
                            }
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
