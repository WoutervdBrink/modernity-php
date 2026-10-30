export type TurboDocument = {
    formatVersion: number;
    language: string;
    versions: string[];
    nodeTypes: string[];
    roots: TurboNode[];
};
export type TurboNode = {
    type: number;
    ranges: [number | null, number | null][];
    children: TurboNode[];
};
export type TurboRange = {
    min: number | null;
    max: number | null;
};
