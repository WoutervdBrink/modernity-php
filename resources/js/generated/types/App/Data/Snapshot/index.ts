import { ProcessStatus } from '../../Models/Enums';
import { RepositoryData } from '../Repository';
export type SnapshotData = {
    id: number;
    tag: string;
    commit_sha: string;
    semver: string | null;
    analysis_status: ProcessStatus;
    analyzed_at: string | null;
    created_at: string | null;
    updated_at: string | null;
    repository?: RepositoryData;
};
