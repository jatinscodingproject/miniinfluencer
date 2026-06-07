interface Props {
    title: string;
    value: string | number;
}

export default function MetricsCard({
    title,
    value,
}: Props) {
    return (
        <div className="bg-white rounded-lg p-6 shadow">
            <p className="text-gray-500">
                {title}
            </p>

            <h2 className="text-2xl font-bold">
                {value}
            </h2>
        </div>
    );
}