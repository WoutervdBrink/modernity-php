<script lang="ts" setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const props = withDefaults(
    defineProps<{
        timestamp: Date | string | null;
        relative?: boolean;
        type?: 'date' | 'datetime';
    }>(),
    {
        relative: false,
        type: 'datetime',
    },
);

const DATE_ONLY_RE = /^(\d{4})-(\d{2})-(\d{2})$/;

const WEEKDAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as const;

const MONTHS = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
] as const;

function parseTimestamp(value: Date | string): {
    date: Date;
    hasTime: boolean;
} {
    if (value instanceof Date) {
        if (Number.isNaN(value.getTime())) {
            throw new TypeError('Timestamp received an invalid Date');
        }

        return {
            date: new Date(value.getTime()),
            hasTime: true,
        };
    }

    const dateOnlyMatch = DATE_ONLY_RE.exec(value);

    if (dateOnlyMatch) {
        const year = Number(dateOnlyMatch[1]);
        const month = Number(dateOnlyMatch[2]);
        const day = Number(dateOnlyMatch[3]);

        // Don't use new Date('YYYY-MM-DD') here: JS interprets that as UTC.
        const date = new Date(0);

        date.setFullYear(year, month - 1, day);
        date.setHours(0, 0, 0, 0);

        // Reject things such as 2026-02-31.
        if (date.getFullYear() !== year || date.getMonth() !== month - 1 || date.getDate() !== day) {
            throw new TypeError(`Timestamp received an invalid date: ${value}`);
        }

        return {
            date,
            hasTime: false,
        };
    }

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
        throw new TypeError(`Timestamp received an invalid datetime: ${value}`);
    }

    return {
        date,
        hasTime: true,
    };
}

function pad(value: number): string {
    return String(value).padStart(2, '0');
}

function formatDate(date: Date): string {
    return [String(date.getFullYear()).padStart(4, '0'), pad(date.getMonth() + 1), pad(date.getDate())].join('-');
}

function formatTime(date: Date, seconds = false): string {
    const value = `${date.getHours()}:${pad(date.getMinutes())}`;

    return seconds ? `${value}:${pad(date.getSeconds())}` : value;
}

function formatDateTime(date: Date): string {
    return `${formatDate(date)} ${formatTime(date, true)}`;
}

function ordinal(day: number): string {
    if (day >= 11 && day <= 13) {
        return `${day}th`;
    }

    switch (day % 10) {
        case 1:
            return `${day}st`;
        case 2:
            return `${day}nd`;
        case 3:
            return `${day}rd`;
        default:
            return `${day}th`;
    }
}

function formatHumanDate(date: Date, includeTime: boolean): string {
    const value =
        `${WEEKDAYS[date.getDay()]}, ` +
        `${MONTHS[date.getMonth()]} ${ordinal(date.getDate())}, ` +
        `${date.getFullYear()}`;

    return includeTime ? `${value} at ${formatTime(date)}` : value;
}

/**
 * Calendar-day difference rather than millisecond difference.
 *
 * Using UTC purely for this calculation means DST changes don't turn
 * "tomorrow" into a difference of 23 or 25 hours.
 */
function calendarDayDiff(date: Date, now: Date): number {
    const dateDay = Date.UTC(date.getFullYear(), date.getMonth(), date.getDate());

    const nowDay = Date.UTC(now.getFullYear(), now.getMonth(), now.getDate());

    return Math.round((dateDay - nowDay) / 86_400_000);
}

function relativeUnit(amount: number, unit: 'second' | 'minute' | 'hour', future: boolean): string {
    const value = `${amount} ${unit}${amount === 1 ? '' : 's'}`;

    return future ? `in ${value}` : `${value} ago`;
}

function formatRelative(date: Date, includeTime: boolean, now: Date): string {
    const dayDiff = calendarDayDiff(date, now);

    /*
     * A date without a time represents a calendar day, not midnight.
     * Saying "8 hours ago" for "2026-10-01" would therefore be misleading.
     */
    if (!includeTime) {
        if (dayDiff === 0) {
            return 'today';
        }

        if (dayDiff === -1) {
            return 'yesterday';
        }

        if (dayDiff === 1) {
            return 'tomorrow';
        }

        if (dayDiff >= -6 && dayDiff <= -2) {
            return `last ${WEEKDAYS[date.getDay()]}`;
        }

        if (dayDiff >= 2 && dayDiff <= 6) {
            return `this ${WEEKDAYS[date.getDay()]}`;
        }

        return formatHumanDate(date, false);
    }

    const diffMs = date.getTime() - now.getTime();
    const future = diffMs > 0;
    const absoluteMs = Math.abs(diffMs);

    const second = 1_000;
    const minute = 60 * second;
    const hour = 60 * minute;

    if (absoluteMs < second) {
        return 'now';
    }

    if (absoluteMs < minute) {
        return relativeUnit(Math.max(1, Math.floor(absoluteMs / second)), 'second', future);
    }

    if (absoluteMs < hour) {
        return relativeUnit(Math.max(1, Math.floor(absoluteMs / minute)), 'minute', future);
    }

    /*
     * For nearby times, elapsed time is clearer:
     *
     *   1 hour ago
     *   in 3 hours
     *
     * Once the difference grows, calendar language tends to be easier
     * for humans to parse.
     */
    if (absoluteMs < 6 * hour) {
        return relativeUnit(Math.max(1, Math.floor(absoluteMs / hour)), 'hour', future);
    }

    if (dayDiff === 0) {
        return formatTime(date);
    }

    if (dayDiff === -1) {
        return `yesterday at ${formatTime(date)}`;
    }

    if (dayDiff === 1) {
        return `tomorrow at ${formatTime(date)}`;
    }

    if (dayDiff >= -6 && dayDiff <= -2) {
        return `last ${WEEKDAYS[date.getDay()]} at ${formatTime(date)}`;
    }

    if (dayDiff >= 2 && dayDiff <= 6) {
        return `this ${WEEKDAYS[date.getDay()]} at ${formatTime(date)}`;
    }

    return formatHumanDate(date, true);
}

const parsed = computed(() => (props.timestamp === null ? null : parseTimestamp(props.timestamp)));

const includesTime = computed(() => parsed.value !== null && props.type === 'datetime' && parsed.value.hasTime);

/*
 * `datetime` accepts either a valid date string or a global date/time.
 *
 * Date objects and timezone-aware strings are normalized to UTC with
 * toISOString(), which is a valid HTML global date/time representation.
 */
const dateTimeStr = computed(() => {
    if (parsed.value === null) {
        return '';
    }

    if (!includesTime.value) {
        return formatDate(parsed.value.date);
    }

    return parsed.value.date.toISOString();
});

const title = computed(() => {
    if (parsed.value === null) {
        return '';
    }

    return formatHumanDate(parsed.value.date, includesTime.value);
});

/*
 * Keep relative timestamps alive. Without this, "5 seconds ago" would
 * remain "5 seconds ago" until something else caused the component to
 * re-render.
 */
const now = ref(new Date());
let timer: ReturnType<typeof setInterval> | undefined;

onMounted(() => {
    now.value = new Date();

    timer = setInterval(() => {
        now.value = new Date();
    }, 1_000);
});

onBeforeUnmount(() => {
    if (timer !== undefined) {
        clearInterval(timer);
    }
});

const text = computed(() => {
    if (parsed.value === null) {
        return '';
    }

    const date = parsed.value.date;

    if (!props.relative) {
        return includesTime.value ? formatDateTime(date) : formatDate(date);
    }

    return formatRelative(date, includesTime.value, now.value);
});
</script>

<template>
    <span v-if="timestamp === null" class="text-secondary"> Never </span>

    <time v-else :datetime="dateTimeStr" :title="title">
        {{ text }}
    </time>
</template>
