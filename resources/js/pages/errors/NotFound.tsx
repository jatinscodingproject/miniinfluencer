import { Link } from "@inertiajs/react";

export default function NotFound() {
    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-100">

            <div className="bg-white p-10 rounded-xl shadow-lg text-center max-w-lg">

                <div className="text-blue-500 text-7xl font-bold">
                    404
                </div>

                <h1 className="text-3xl font-bold mt-4">
                    Page Not Found
                </h1>

                <p className="text-gray-600 mt-4">
                    The page you are looking for does not exist or has been moved.
                </p>

                <Link
                    href="/profiles"
                    className="
                        inline-block
                        mt-6
                        bg-black
                        text-white
                        px-6
                        py-3
                        rounded-lg
                    ">
                    Back To Dashboard
                </Link>

            </div>

        </div>
    );
}