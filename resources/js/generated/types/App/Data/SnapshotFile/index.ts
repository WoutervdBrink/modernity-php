import { SourceFileData } from '../SourceFile';
export type SnapshotFileData = {
    id: number;
    path: string;
    sha256: string;
    sourceFile: SourceFileData;
    created_at: string;
    updated_at: string;
};
