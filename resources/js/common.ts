import type { ProcessStatus } from '@/generated/types/App/Models/Enums';
import type { ColorVariant } from 'bootstrap-vue-next';
import type { ProcessStatusCounts } from '@/generated/types/App/Data';

export const ALL_PROCESS_STATUSES: ProcessStatus[] = ['pending', 'queued', 'running', 'completed', 'failed'];

export function getStatusVariant(status: ProcessStatus): ColorVariant {
    switch (status) {
        case 'pending':
            return 'secondary';
        case 'queued':
            return 'info';
        case 'running':
            return 'primary';
        case 'completed':
            return 'success';
        case 'failed':
            return 'danger';
        default:
            return 'secondary';
    }
}

export function getStatusLabel(status: ProcessStatus): string {
    return status.charAt(0).toUpperCase() + status.slice(1);
}

export function processIs(counts: ProcessStatusCounts, statuses: ProcessStatus[]): boolean {
    return statuses.some((status) => counts[status] > 0);
}

export function processesAre(processes: ProcessStatusCounts[], statuses: ProcessStatus[]): boolean {
    return processes.some((p) => processIs(p, statuses));
}
