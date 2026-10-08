export type PaginatedRows<T> = {
    data: T[];
    meta: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        from: number | null;
        to: number | null;
    };
};

export type DatatableFormat =
    'text' | 'text-overflow' | 'date' | 'date-relative' | 'datetime' | 'datetime-relative' | 'number' | 'boolean';

export type Column<T> = {
    key: Extract<keyof T, string | (string & {})>;
    width?: CSSStyleDeclaration['width'];
    label?: string;
    sortable?: boolean;
    format?: DatatableFormat;
    accessor?: (item: T) => unknown;
    formatter?: (value: unknown, item: T) => string;
    nullLabel?: string;
};

export type FilterDefinition = {
    key: string;
    label: string;
    options: Array<{ value: string; text: string }>;
};

export type RowActions<T> = {
    view?: (row: T) => string | null | undefined | { url: string };
    edit?: (row: T) => string | null | undefined | { url: string };
    delete?: (row: T) => string | null | undefined | { url: string };
    confirmDelete?: (row: T) => boolean;
    labels?: Partial<Record<'view' | 'edit' | 'delete', string>>;
};

export type QueryValues = Record<string, string | null | undefined>;
