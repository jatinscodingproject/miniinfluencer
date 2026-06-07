import { Link } from "@inertiajs/react";

export default function ServerError() {
    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-100">

            <div className="bg-white p-10 rounded-xl shadow-lg text-center max-w-lg">

                <div className="text-red-500 text-7xl font-bold">
                    500
                </div>

                <h1 className="text-3xl font-bold mt-4">
                    Internal Server Error
                </h1>

                <p className="text-gray-600 mt-4">
                    Something went wrong while processing your request.
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
                        rounded-lg">
                    Back To Dashboard
                </Link>
            </div>
        </div>
    );
}