import { Link } from "@inertiajs/react";
import StatusBadge from "./StatusBadge";
import { Profile } from "../types/profile";

interface Props {
    profiles: Profile[];
}

export default function ProfileTable({
    profiles,
}: Props) {
    return (
        <table className="w-full bg-white rounded-lg shadow">
            <thead>
                <tr className="border-b">
                    <th className="p-4 text-left">
                        Username
                    </th>

                    <th className="p-4 text-left">
                        Followers
                    </th>

                    <th className="p-4 text-left">
                        Status
                    </th>

                    <th className="p-4 text-left">
                        Action
                    </th>
                </tr>
            </thead>

            <tbody>
                {profiles.map((profile) => (
                    <tr
                        key={profile.id}
                        className="border-b"
                    >
                        <td className="p-4">
                            {profile.username}
                        </td>

                        <td className="p-4">
                            {profile.followers_count.toLocaleString()}
                        </td>

                        <td className="p-4">
                            <StatusBadge
                                status={
                                    profile.status
                                }
                            />
                        </td>

                        <td className="p-4">
                            <Link
                                href={`/profiles/${profile.id}`}
                                className="text-blue-500"
                            >
                                View
                            </Link>
                        </td>
                    </tr>
                ))}
            </tbody>
        </table>
    );
}