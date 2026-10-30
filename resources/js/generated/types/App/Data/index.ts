export type FailedJobData = {
    id: number;
    uuid: string;
    connection: string;
    queue: string;
    payload: string;
    exception: string;
    failed_at: string;
};
export type ProcessStatusCounts = {
    total: number;
    pending: number;
    queued: number;
    running: number;
    completed: number;
    failed: number;
};
