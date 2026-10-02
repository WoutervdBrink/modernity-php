import { SearchStatus } from '../../Models/Enums';
import { RepositoryData, GitHubRepository } from '../Repository';
export type CreateSearchRequestData = {
    parameters: SearchParameters;
};
export type SearchData = {
    id: number;
    parameters: SearchParameters;
    status: SearchStatus;
    application_commit: string;
    started_at: string | null;
    finished_at: string | null;
    created_at: string;
    updated_at: string;
    results?: SearchResultData[];
    results_count: number | null;
    acceptedResults_count: number | null;
};
export type SearchParameters = {
    cutoff?: string | null;
    php?: number;
    max?: number;
};
export type SearchResultData = {
    id: number;
    repository: RepositoryData;
    rejection_reason: string | null;
    observedData: GitHubRepository;
    discovered_at: string | null;
    created_at: string | null;
    updated_at: string;
};
