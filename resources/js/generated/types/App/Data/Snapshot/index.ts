export type SnapshotData = {
    id: number;
    tag: string;
    commit_sha: string;
    semver: string | null;
    commited_at: string | null;
    downloaded_at: string | null;
    indexed_at: string | null;
    created_at: string | null;
    updated_at: string | null;
};
