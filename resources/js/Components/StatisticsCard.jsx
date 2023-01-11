export default function StatisticsCard({ header, children }) {
    return (
        <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div className="p-6 text-lg text-gray-900 dark:text-gray-100">{header}</div>
            <div className="align-middle text-center py-6">
                <div className="text-5xl text-gray-600 dark:text-gray-200">{children}</div>
            </div>
        </div>
    );
}
