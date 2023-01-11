export default function SubscriptionCard({ type, price, onClick, active}) {
    return (
        <div onClick={() => onClick(type)} className={active ? "border-8 border-gray-400" : ""} style={{cursor: "pointer" }}>
            <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div className="p-6 text-lg text-gray-900 dark:text-gray-100">{type}</div>
                <div className="align-middle text-center py-6">
                    <div className="text-5xl text-gray-600 dark:text-gray-200">${price}</div>
                </div>
            </div>
        </div>
    );
}
