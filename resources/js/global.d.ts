import '@inertiajs/core';

declare module '@inertiajs/core' {
    export interface InertiaConfig {
        errorValueType: string[];
        layoutProps: {
            title: string;
        };
    }
}
