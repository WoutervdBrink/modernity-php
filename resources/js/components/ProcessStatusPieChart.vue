<script lang="ts" setup>
import {
    ArcElement,
    BarElement,
    CategoryScale,
    Chart as ChartJS,
    type ChartData,
    type ChartOptions,
    Legend,
    LinearScale,
    Title,
    Tooltip,
} from 'chart.js';
import { Pie } from 'vue-chartjs';
import type { ProcessStatusCounts } from '@/generated/types/App/Data';
import { computed } from 'vue';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement);

const props = defineProps<{
    counts: ProcessStatusCounts;
}>();

const chartData = computed<ChartData<'pie'>>(() => {
    const computedStyle = getComputedStyle(document.body);

    return {
        labels: ['Pending', 'Queued', 'Running', 'Completed', 'Failed'],
        datasets: [
            {
                backgroundColor: [
                    `rgb(${computedStyle.getPropertyValue('--bs-secondary-rgb')})`,
                    `rgb(${computedStyle.getPropertyValue('--bs-info-rgb')})`,
                    `rgb(${computedStyle.getPropertyValue('--bs-primary-rgb')})`,
                    `rgb(${computedStyle.getPropertyValue('--bs-success-rgb')})`,
                    `rgb(${computedStyle.getPropertyValue('--bs-danger-rgb')})`,
                ],
                data: [
                    props.counts.pending,
                    props.counts.queued,
                    props.counts.running,
                    props.counts.completed,
                    props.counts.failed,
                ],
            },
        ],
    };
});

const chartOptions: ChartOptions<'pie'> = {
    responsive: true,
    plugins: {
        legend: {},
    },
};
</script>

<template>
    <Pie :data="chartData" :options="chartOptions" />
</template>
