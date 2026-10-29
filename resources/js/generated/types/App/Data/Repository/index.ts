import { ProcessStatus } from '../../Models/Enums';
export type RepositoryData = {
    id: number;
    github_id: number;
    name: string;
    description: string | null;
    is_accepted: boolean;
    snapshot_discovery_status: ProcessStatus;
    is_discovering_snapshots?: boolean;
    fetched_at: string | null;
    snapshots_discovered_at: string | null;
    created_at: string | null;
    updated_at: string | null;
};
