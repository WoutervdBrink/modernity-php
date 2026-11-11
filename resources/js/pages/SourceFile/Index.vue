<script lang="ts" setup>
import type { LengthAwarePaginator } from '@/generated/types/Illuminate';
import type { Column, FilterDefinition, RowActions } from '@/components/Datatable';
import type { SourceFileData } from '@/generated/types/App/Data/SourceFile';
import SourceFileController from '@/actions/App/Http/Controllers/SourceFileController.ts';

defineOptions({
    layout: {
        title: 'Source files',
    },
});

defineProps<{
    sourceFiles: LengthAwarePaginator<number, SourceFileData>;
    filters: FilterDefinition[];
}>();

const columns = [
    {
        key: 'sha256',
        label: 'SHA256 hash',
        sortable: true,
    },
    {
        key: 'size',
        label: 'Size',
        format: 'number',
        sortable: true,
    },
    {
        key: 'is_analyzed',
        label: 'Analyzed',
        format: 'boolean',
    },
    {
        key: 'has_thrown_exception',
        label: 'Exception',
        format: 'boolean',
    },
] satisfies Column<SourceFileData>[];

const actions: RowActions<SourceFileData> = {
    view: (repository) => SourceFileController.show(repository),
};
</script>

<template>
    <Datatable :actions :columns :data="sourceFiles" :filters searchable title="Source files"> </Datatable>
</template>
