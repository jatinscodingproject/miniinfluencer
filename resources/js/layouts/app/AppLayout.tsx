import { Link } from "@inertiajs/react";
import { ReactNode } from "react";

interface Props {
    children: ReactNode;
}

export default function AppLayout({
    children,
}: Props) {
    return (
        <div className="min-h-screen bg-gray-100">
            <nav className="bg-white border-b">
                <div className="max-w-7xl mx-auto px-6 py-4 flex justify-between">
                    <Link
                        href="/profiles"
                        className="text-xl font-bold"
                    >
                        MiniInfluencer
                    </Link>

                    <span>
                        Admin Dashboard
                    </span>
                </div>
            </nav>

            <div className="max-w-7xl mx-auto p-6">
                {children}
            </div>
        </div>
    );
}