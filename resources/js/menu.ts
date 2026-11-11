import type { RouteDefinition } from '@/wayfinder';
import SearchController from '@/actions/App/Http/Controllers/SearchController.ts';
import RepositoryController from '@/actions/App/Http/Controllers/RepositoryController.ts';
import PlaygroundController from '@/actions/App/Http/Controllers/PlaygroundController.ts';
import SourceFileController from '@/actions/App/Http/Controllers/SourceFileController.ts';

type Href = (() => RouteDefinition<'get'>) | string;
export type MenuItem = {
    label: string;
    root: string;
    href: Href;
};

export type Menu = MenuItem[];

export const menu: Menu = [
    {
        label: 'Playground',
        root: 'Playground',
        href: PlaygroundController.index,
    },
    {
        label: 'Searches',
        root: 'Search',
        href: SearchController.index,
    },
    {
        label: 'Repositories',
        root: 'Repository',
        href: RepositoryController.index,
    },
    {
        label: 'Source files',
        root: 'SourceFile',
        href: SourceFileController.index,
    },
];

export function href(href: Href): string {
    if (typeof href === 'string') return href;
    return href().url;
}
