<script lang="ts" setup>
import type { ProcessStatusCounts } from '@/generated/types/App/Data';
import { computed, onMounted, watch } from 'vue';
import { processesAre } from '@/common.ts';
import { router, usePoll } from '@inertiajs/vue3';
import { useModal } from 'bootstrap-vue-next';
import DashboardController from '@/actions/App/Http/Controllers/DashboardController.ts';
import type { ProcessStatus } from '@/generated/types/App/Models/Enums';

defineOptions({
    layout: {
        title: 'Dashboard',
    },
});

const props = defineProps<{
    application_commit: string;
    statistics: {
        jobs: {
            queued: number;
            failed: number;
        };
        searches: {
            total: number;
            byStatus: ProcessStatusCounts;
        };
        repositories: {
            total: number;
            accepted: number;
            rejected: number;
            byStatus: ProcessStatusCounts;
        };
        snapshots: {
            total: number;
            withSemver: number;
            withoutSemver: number;
            byStatus: ProcessStatusCounts;
        };
        files: {
            snapshot: number;
            source: {
                total: number;
                analyzed: number;
                exception: number;
            };
        };
    };
}>();

function anyProcessesAre(statuses: ProcessStatus[]): boolean {
    return processesAre(
        [
            props.statistics.searches.byStatus,
            props.statistics.snapshots.byStatus,
            props.statistics.repositories.byStatus,
        ],
        statuses,
    );
}

const numberFormatter = new Intl.NumberFormat('en-US');

const systemIsBusy = computed<boolean>(() => anyProcessesAre(['queued', 'running']));
const pendingTasksAvailable = computed<boolean>(() => anyProcessesAre(['pending']));
const failedTasksAvailable = computed<boolean>(() => anyProcessesAre(['failed']));

const modal = useModal();

async function queuePendingProcesses(): Promise<void> {
    const value = await modal
        .create({
            title: 'Are you sure?',
            body: 'Do you want to queue all pending processes? This may take a long time, and cannot be undone.',
        })
        .show();

    if (value && typeof value === 'object' && value.ok) {
        router.post(DashboardController.queuePendingProcesses());
    }
}

async function queueFailedProcesses(): Promise<void> {
    const value = await modal
        .create({
            title: 'Are you sure?',
            body: 'Do you want to queue all failed processes? This may take a long time, and cannot be undone.',
        })
        .show();

    if (value && typeof value === 'object' && value.ok) {
        router.post(DashboardController.queueFailedProcesses());
    }
}

const poll = usePoll(1000, { only: ['statistics'] }, { autoStart: false });

watch(
    () => systemIsBusy.value,
    (busy: boolean) => {
        if (!busy) {
            poll.stop();
        } else {
            poll.start();
        }
    },
);

onMounted(() => {
    if (systemIsBusy.value) {
        poll.start();
    }
});
</script>

<template>
    <Toolbar title="Dashboard">
        <div v-if="systemIsBusy">
            <BSpinner class="me-2" small type="border" />
            <span class="text-secondary">
                Processes are running... ({{ numberFormatter.format(statistics.jobs.queued) }} queued job(s))
            </span>
        </div>

        <BButton :disabled="!pendingTasksAvailable" size="sm" variant="primary" @click="queuePendingProcesses">
            <i-fa6-solid-hourglass-half />
            Queue all pending processes
        </BButton>
        <BButton :disabled="!failedTasksAvailable" size="sm" variant="primary" @click="queueFailedProcesses">
            <i-fa6-solid-triangle-exclamation />
            Queue all failed processes
        </BButton>
    </Toolbar>

    <BAlert :model-value="statistics.jobs.queued === 0 && systemIsBusy" variant="warning">
        According to the database, certain processes are either queued and/or running, but zero jobs are queued.
    </BAlert>

    <BRow>
        <BCol md="4">
            <h5 class="text-center">Searches ({{ numberFormatter.format(statistics.searches.total) }})</h5>

            <ProcessStatusPieChart :counts="statistics.searches.byStatus" />
        </BCol>
        <BCol md="4">
            <h5 class="text-center">Repositories ({{ numberFormatter.format(statistics.repositories.total) }})</h5>
            <ProcessStatusPieChart :counts="statistics.repositories.byStatus" class="mb-3" />
            <BinaryStatusCountBar
                :no="statistics.repositories.rejected"
                :yes="statistics.repositories.accepted"
                class="mb-2"
                noLabel="Rejected"
                yesLabel="Accepted"
            />
        </BCol>
        <BCol md="4">
            <h5 class="text-center">Snapshots ({{ numberFormatter.format(statistics.snapshots.total) }})</h5>

            <ProcessStatusPieChart :counts="statistics.snapshots.byStatus" class="mb-3" />

            <BinaryStatusCountBar
                :no="statistics.snapshots.withoutSemver"
                :yes="statistics.snapshots.withSemver"
                class="mb-2"
                noLabel="Without SemVer"
                yesLabel="With SemVer"
            />
        </BCol>
    </BRow>

    <p class="text-center mb-3">
        <strong>{{ numberFormatter.format(statistics.files.snapshot) }}</strong> discovered snapshot files;
        <strong>{{ numberFormatter.format(statistics.files.source.total) }}</strong> unique source files ({{
            ((statistics.files.source.total / statistics.files.snapshot) * 100).toFixed(2)
        }}%), of which <strong>{{ numberFormatter.format(statistics.files.source.analyzed) }}</strong> have an analysis,
        and <strong>{{ numberFormatter.format(statistics.files.source.exception) }}</strong> could not be analyzed due
        to an exception.
    </p>
</template>
