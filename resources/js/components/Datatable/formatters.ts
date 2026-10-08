import type { DatatableFormat } from '@/components/Datatable/index.ts';

export function formatValue(value: unknown, format?: DatatableFormat): string {
    if (format === undefined) {
        return String(value);
    }

    if (value === null || value === undefined) {
        return '';
    }

    switch (format) {
        case 'boolean':
            return value === true ? 'Yes' : value === false ? 'No' : String(value);
        case 'number':
            return typeof value === 'number' ? new Intl.NumberFormat('en-US').format(value) : String(value);
        case 'datetime':
        case 'datetime-relative': {
            const date = new Date(String(value));
            if (Number.isNaN(date.getTime())) return String(value);
            return new Intl.DateTimeFormat('en-US').format(date);
        }
        default:
            return String(value);
    }
}
