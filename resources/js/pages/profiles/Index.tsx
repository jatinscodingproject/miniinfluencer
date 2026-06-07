import AppLayout from "@/layouts/app/AppLayout";
import ProfileTable from "@/components/ProfileTable";
import { Link, router } from "@inertiajs/react";
import { useState } from "react";

interface Profile {
    id: number;
    username: string;
    status: string;
    followers_count: number;
    following_count: number;
    posts_count: number;
}

interface Props {
    profiles: {
        data: Profile[];
        current_page: number;
        last_page: number;
    };
    filters: {
        q?: string;
        status?: string;
    };
}

export default function Index({
    profiles,
    filters,
}: Props) {

    const [search, setSearch] = useState(
        filters.q ?? ""
    );

    const [status, setStatus] = useState(
        filters.status ?? ""
    );

    const applyFilters = () => {

        router.get(
            "/profiles",
            {
                q: search,
                status: status,
            },
            {
                preserveState: true,
                replace: true,
            }
        );
    };

    const resetFilters = () => {

        setSearch("");
        setStatus("");

        router.get(
            "/profiles"
        );
    };

    return (
        <AppLayout>

            {/* Header */}

            <div className="flex items-center justify-between mb-8">

                <div>

                    <h1 className="text-3xl font-bold">
                        Watchlist
                    </h1>

                    <p className="text-gray-500">
                        Monitor influencer profiles
                    </p>

                </div>

                <Link
                    href="/profiles/create"
                    className="
                        bg-black
                        text-white
                        px-5
                        py-3
                        rounded-lg
                        hover:bg-gray-800
                    "
                >
                    + Add Profile
                </Link>

            </div>

            {/* Stats */}

            <div className="grid grid-cols-4 gap-4 mb-8">

                <div className="bg-white rounded-lg shadow p-5">
                    <p className="text-gray-500">
                        Total Profiles
                    </p>

                    <h2 className="text-2xl font-bold">
                        {profiles.data.length}
                    </h2>
                </div>

                <div className="bg-white rounded-lg shadow p-5">
                    <p className="text-gray-500">
                        Fetched
                    </p>

                    <h2 className="text-2xl font-bold text-green-600">
                        {
                            profiles.data.filter(
                                p =>
                                    p.status ===
                                    "fetched"
                            ).length
                        }
                    </h2>
                </div>

                <div className="bg-white rounded-lg shadow p-5">
                    <p className="text-gray-500">
                        Pending
                    </p>

                    <h2 className="text-2xl font-bold text-yellow-600">
                        {
                            profiles.data.filter(
                                p =>
                                    p.status ===
                                    "pending"
                            ).length
                        }
                    </h2>
                </div>

                <div className="bg-white rounded-lg shadow p-5">
                    <p className="text-gray-500">
                        Failed
                    </p>

                    <h2 className="text-2xl font-bold text-red-600">
                        {
                            profiles.data.filter(
                                p =>
                                    p.status ===
                                    "failed"
                            ).length
                        }
                    </h2>
                </div>

            </div>

            {/* Filters */}

            <div className="bg-white rounded-lg shadow p-5 mb-6">

                <div className="flex gap-4">

                    <input
                        value={search}
                        onChange={(e) =>
                            setSearch(
                                e.target.value
                            )
                        }
                        placeholder="Search username..."
                        className="
                            flex-1
                            border
                            rounded-lg
                            px-4
                            py-2
                        "
                    />

                    <select
                        value={status}
                        onChange={(e) =>
                            setStatus(
                                e.target.value
                            )
                        }
                        className="
                            border
                            rounded-lg
                            px-4
                            py-2
                        "
                    >
                        <option value="">
                            All Status
                        </option>

                        <option value="pending">
                            Pending
                        </option>

                        <option value="fetching">
                            Fetching
                        </option>

                        <option value="fetched">
                            Fetched
                        </option>

                        <option value="failed">
                            Failed
                        </option>

                    </select>

                    <button
                        onClick={applyFilters}
                        className="
                            bg-blue-600
                            text-white
                            px-5
                            rounded-lg
                        "
                    >
                        Search
                    </button>

                    <button
                        onClick={resetFilters}
                        className="
                            border
                            px-5
                            rounded-lg
                        "
                    >
                        Reset
                    </button>

                </div>

            </div>

            {/* Table */}

            <ProfileTable
                profiles={profiles.data}
            />

            <div className="mt-6 text-center">

                Page {profiles.current_page}
                of {profiles.last_page}

            </div>

        </AppLayout>
    );
}