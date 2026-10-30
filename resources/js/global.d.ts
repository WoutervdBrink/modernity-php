import '@inertiajs/core';

declare module '@inertiajs/core' {
    export interface InertiaConfig {
        sharedPageProps: {
            application_commit: string;
        };
        errorValueType: string[];
        layoutProps: {
            title: string;
        };
    }
}
