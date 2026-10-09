<script generic="T extends Record<string, any>" lang="ts" setup>
import type {
    Column,
    DatatableFormat,
    FilterDefinition,
    PaginatedRows,
    RowActions,
} from '@/components/Datatable/index.ts';
import { useDatatableQuery } from '@/components/Datatable/useDatatableQuery.ts';
import { computed, onBeforeUnmount, ref, useSlots, watch } from 'vue';
import type { BTableSortBy, TableField, TableFieldRaw } from 'bootstrap-vue-next';
import { formatValue } from '@/components/Datatable/formatters.ts';
import { router } from '@inertiajs/vue3';

const props = withDefaults(
    defineProps<{
        data: PaginatedRows<T>;
        columns: Column<T>[];
        searchable?: boolean;
        filters?: FilterDefinition[];
        actions?: RowActions<T>;
        searchPlaceholder?: string;
        title?: string;
        titleComponent?: string;
    }>(),
    {
        searchable: false,
        filters: () => [],
        searchPlaceholder: 'Search...',
        titleComponent: 'h1',
    },
);

const { currentURL, param, update } = useDatatableQuery();
const slots = useSlots();
const search = ref<string>(param('search'));
const filterValues = ref<Record<string, string>>({});

function syncFilters(): void {
    filterValues.value = Object.fromEntries(props.filters.map((f) => [f.key, param(f.key)]));
}
syncFilters();

let searchTimer: ReturnType<typeof setTimeout> | undefined;
watch(currentURL, () => {
    clearTimeout(searchTimer);
    search.value = param('search');
    syncFilters();
});
watch(search, (value) => {
    clearTimeout(searchTimer);
    if (value === param('search')) return;
    searchTimer = setTimeout(() => update({ search: value.trim() }), 350);
});
onBeforeUnmount(() => clearTimeout(searchTimer));

function setFilter(key: string, value: string): void {
    filterValues.value = { ...filterValues.value, [key]: value };
    update({ [key]: value });
}

const fields = computed(() => {
    const results: TableFieldRaw[] = props.columns.map((col): TableField<T> => ({
        key: col.key,
        label: col.label,
        sortable: col.sortable ?? false,
        accessor: col.accessor ? (item) => col.accessor!(item as T) : undefined,
        formatter: ({ value, item }) =>
            col.formatter ? col.formatter(value, item as T) : formatValue(value, col.format),
        tdClass: `td-${col.format}`,
        thStyle: col.width
            ? { width: col.width, maxWidth: col.width }
            : col.format?.startsWith('date') || col.format === 'boolean'
              ? { width: '1%' }
              : undefined,
    }));

    if (props.actions) {
        results.push({
            key: '__actions',
            label: 'Actions',
            sortable: false,
            thStyle: { width: '1%' },
        });
    }

    return results;
});

const tableItems = computed<Record<string, unknown>[]>(() => props.data.data);
function asRow(item: Record<string, unknown>): T {
    return item as T;
}

function cellValue(col: Column<T>, row: T): unknown {
    return col.accessor ? col.accessor(row) : row[col.key];
}

function resolveURL(url: string | null | undefined | { url: string }): string | undefined {
    if (url === null || url === undefined) return;
    if (typeof url === 'string') return url;
    return url.url;
}

function navigate(url: string | null | undefined | { url: string }): void {
    url = resolveURL(url);
    if (url) router.visit(url);
}

function destroy(row: T): void {
    const url = resolveURL(props.actions?.delete?.(row));
    if (!url) return;
    const confirmed = props.actions?.confirmDelete
        ? props.actions?.confirmDelete(row)
        : window.confirm('Delete this record?');
    if (confirmed) router.delete(url, { preserveScroll: true });
}

const columnsWithFormat = computed<(formats: DatatableFormat[]) => Column<T>[]>(
    () => (formats: DatatableFormat[]) =>
        props.columns.filter((col) => formats.includes(col.format ?? 'text') && !(`cell(${col.key})` in slots)),
);

function timestampValue(col: Column<T>, row: T): string | Date | null {
    const value = cellValue(col, row);

    return typeof value === 'string' || value instanceof Date ? value : null;
}

const currentPage = computed<number>({
    get: () => props.data.meta.current_page,
    set: (value: number) => {
        if (value !== props.data.meta.current_page) update({ page: String(value) }, false);
    },
});

const sortBy = computed({
    get: () => {
        const value = param('sort');
        if (!value) return [];
        const order: BTableSortBy['order'] = value.startsWith('-') ? 'desc' : 'asc';
        return [{ key: value.replace(/^-/, ''), order }];
    },
    set: (value) => {
        const entry = value[0];
        update({ sort: entry?.order ? `${entry.order === 'desc' ? '-' : ''}${entry.key}` : '' });
    },
});

function sortDirection(key: string): 'asc' | 'desc' | null {
    const current = sortBy.value[0];

    if (!current || current.key !== key) {
        return null;
    }

    return current.order === 'asc' ? 'asc' : 'desc';
}
</script>

<template>
    <div class="d-flex justify-content-between align-items-center">
        <component :is="titleComponent" v-if="title">
            {{ title }}
            ({{ data.meta.total }})
        </component>
        <div v-else />
        <div v-if="searchable || filters.length" class="d-flex justify-content-end gap-2 mb-3">
            <div>
                <BInputGroup v-if="searchable" size="sm">
                    <BInputGroupText>
                        <i-fa6-solid-magnifying-glass />
                    </BInputGroupText>
                    <BFormInput
                        v-model="search"
                        :placeholder="searchPlaceholder"
                        aria-label="Search table"
                        class="datatable-search"
                        size="sm"
                        type="search"
                    />
                </BInputGroup>
            </div>

            <div v-for="filter in filters" :key="filter.key">
                <BInputGroup size="sm">
                    <BInputGroupText>
                        <label :for="`filter_${filter.key}`">{{ filter.label }}</label>
                    </BInputGroupText>
                    <BFormSelect
                        :id="`filter_${filter.key}`"
                        :model-value="filterValues[filter.key] ?? ''"
                        :options="[{ value: '', text: 'All' }, ...filter.options]"
                        @update:model-value="setFilter(filter.key, String($event ?? ''))"
                    />
                </BInputGroup>
            </div>
        </div>
    </div>

    <BTable
        v-model:sort-by="sortBy"
        :fields="fields"
        :items="tableItems"
        fixed
        hover
        no-local-sorting
        responsive
        show-empty
        sort-icon-left
        striped
    >
        <template #head()="{ label, field }">
            <span class="d-inline-flex align-items-center gap-2">
                {{ label }}

                <template v-if="field.sortable">
                    <i-fa6-solid-sort v-if="!sortDirection(field.key)" class="text-muted opacity-50" />

                    <i-fa6-solid-sort-up v-else-if="sortDirection(field.key) === 'asc'" class="text-primary" />

                    <i-fa6-solid-sort-down v-else class="text-primary" />
                </template>
            </span>
        </template>

        <template v-for="(_, name) in $slots" :key="name" #[name]="scope">
            <slot :name="name" v-bind="scope ?? {}" />
        </template>

        <template
            v-for="col in columnsWithFormat(['datetime', 'datetime-relative', 'date', 'date-relative'])"
            :key="`timestamp-${col.key}`"
            #[`cell(${col.key})`]="{ item }"
        >
            <Timestamp
                v-if="typeof cellValue(col, asRow(item)) === 'string' || cellValue(col, asRow(item)) === null"
                :relative="col.format === 'datetime-relative'"
                :timestamp="timestampValue(col, asRow(item))"
            />
        </template>

        <template
            v-for="col in columnsWithFormat(['text'])"
            :key="`timestamp-${col.key}`"
            #[`cell(${col.key})`]="{ item }"
        >
            <span v-if="cellValue(col, asRow(item)) === null" class="text-secondary">
                {{ col.nullLabel ?? 'None' }}
            </span>
            <template v-else>{{ cellValue(col, asRow(item)) }}</template>
        </template>

        <template
            v-for="col in columnsWithFormat(['text-overflow'])"
            :key="`timestamp-${col.key}`"
            #[`cell(${col.key})`]="{ item }"
        >
            <div
                :style="{ maxWidth: col.width ?? '300px' }"
                :title="String(cellValue(col, asRow(item)))"
                class="text-truncate"
            >
                <span v-if="cellValue(col, asRow(item)) === null" class="text-secondary">
                    {{ col.nullLabel ?? 'None' }}
                </span>
                <template v-else>{{ cellValue(col, asRow(item)) }}</template>
            </div>
        </template>

        <template v-if="actions && !$slots['cell(__actions)']" #cell(__actions)="{ item }">
            <div class="d-flex flex-wrap gap-1">
                <BButton
                    v-if="actions.view?.(asRow(item))"
                    size="sm"
                    variant="outline-primary"
                    @click="navigate(actions.view?.(asRow(item)))"
                >
                    <i-fa6-solid-eye />
                    <span class="visually-hidden">
                        {{ actions.labels?.view ?? 'View' }}
                    </span>
                </BButton>
                <BButton
                    v-if="actions.edit?.(asRow(item))"
                    size="sm"
                    variant="outline-secondary"
                    @click="navigate(actions.edit?.(asRow(item)))"
                >
                    <i-fa6-solid-pen />
                    <span class="visually-hidden">
                        {{ actions.labels?.edit ?? 'Edit' }}
                    </span>
                </BButton>
                <BButton
                    v-if="actions.delete?.(asRow(item))"
                    size="sm"
                    variant="outline-danger"
                    @click="destroy(asRow(item))"
                >
                    <i-fa6-solid-trash />
                    <span class="visually-hidden">
                        {{ actions.labels?.delete ?? 'View' }}
                    </span>
                </BButton>
            </div>
        </template>
    </BTable>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
        <span class="text-body-secondary">
            Showing {{ data.meta.from ?? 0 }}&ndash;{{ data.meta.to ?? 0 }} of {{ data.meta.total }}
        </span>
        <BPagination
            v-if="data.meta.last_page > 1"
            v-model="currentPage"
            :per-page="data.meta.per_page"
            :total-rows="data.meta.total"
            class="mb-0"
        />
    </div>
</template>

<style scoped>
.datatable-search {
    max-width: 20rem;
}
::v-deep(thead th) {
    white-space: nowrap;
}
::v-deep(.td-datetime),
::v-deep(.td-datetime-relative) {
    white-space: nowrap;
}
::v-deep(.td-number) {
    font-variant-numeric: tabular-nums;
}
::v-deep(.td-text-overflow) {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
