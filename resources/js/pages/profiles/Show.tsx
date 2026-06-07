import AppLayout from "@/layouts/app/AppLayout";
import MetricsCard from "@/components/MetricsCard";

interface Snapshot {
    id: string;
    followers_count: number;
    snapshot_at: string;
}

interface Profile {
    id: string;
    username: string;
    bio: string | null;
    profile_picture: string | null;
    followers_count: number;
    following_count: number;
    posts_count: number;
    status: string;
}

interface Props {
    profile: Profile;
    snapshots: Snapshot[];
}

export default function Show({
    profile,
    snapshots,
}: Props) {
    return (
        <AppLayout>

            <div className="bg-white p-6 rounded-lg shadow">

                <div className="flex gap-6">

                    <img
                        src={
                            profile.profile_picture ??
                            "https://placehold.co/100"
                        }
                        alt={profile.username}
                        className="w-24 h-24 rounded-full"
                    />

                    <div>

                        <h1 className="text-3xl font-bold">
                            @{profile.username}
                        </h1>

                        <p className="text-gray-600">
                            {profile.bio ??
                                "No bio available"}
                        </p>

                        <p className="mt-2">
                            Status:
                            <span className="ml-2 font-semibold">
                                {profile.status}
                            </span>
                        </p>

                    </div>

                </div>

                <div className="grid grid-cols-3 gap-4 mt-6">

                    <MetricsCard
                        title="Followers"
                        value={profile.followers_count.toLocaleString()}
                    />

                    <MetricsCard
                        title="Following"
                        value={profile.following_count.toLocaleString()}
                    />

                    <MetricsCard
                        title="Posts"
                        value={profile.posts_count.toLocaleString()}
                    />

                </div>
            </div>

            <div className="bg-white p-6 rounded-lg shadow mt-6">

                <h2 className="font-bold mb-4">
                    Snapshot History
                </h2>

                <table className="w-full">

                    <thead>
                        <tr>
                            <th className="text-left">
                                Date
                            </th>

                            <th className="text-left">
                                Followers
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        {snapshots.map(
                            (snapshot) => (
                                <tr
                                    key={
                                        snapshot.id
                                    }
                                    className="border-t"
                                >
                                    <td className="py-2">
                                        {new Date(
                                            snapshot.snapshot_at
                                        ).toLocaleDateString()}
                                    </td>

                                    <td>
                                        {snapshot.followers_count.toLocaleString()}
                                    </td>
                                </tr>
                            )
                        )}

                    </tbody>

                </table>

            </div>

        </AppLayout>
    );
}