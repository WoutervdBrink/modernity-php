export type GitHubRepository = {
    id: number;
    full_name: string;
    description: string | null;
    created_at: string;
    updated_at: string;
    stargazers_count: number;
    phpShare: number | null;
};
export type RepositoryData = {
    id: number;
    github_id: number;
    name: string;
    description: string | null;
    snapshots_discovered_at: string | null;
    created_at: string | null;
    updated_at: string | null;
};
