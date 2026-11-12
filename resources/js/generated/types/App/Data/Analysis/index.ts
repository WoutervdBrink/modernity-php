export type RepositoryFile = {
    size: number;
    sha256: string;
    path: string;
    contents: string;
};
export type VersionAxis = {
    readonly versions: string[];
};
