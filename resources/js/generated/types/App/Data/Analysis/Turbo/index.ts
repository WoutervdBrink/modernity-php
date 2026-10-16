export type TurboDocument = {
    formatVersion: number;
    language: string;
    versions: string[];
    nodeTypes: string[];
    roots: TurboNode[];
};
export type TurboNode = {
    type: number;
    ranges: TurboRange[];
    children: TurboNode[];
};
export type TurboRange = {
    min: number | null;
    max: number | null;
};
