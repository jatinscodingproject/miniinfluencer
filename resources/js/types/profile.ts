export interface Profile {
    id: number;
    username: string;
    status: string;

    followers_count: number;
    following_count: number;
    posts_count: number;

    bio?: string;
    profile_picture?: string;
    last_refreshed_at?: string;
}