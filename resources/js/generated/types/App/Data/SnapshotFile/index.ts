import { SourceFileData } from '../SourceFile';
import { SnapshotData } from '../Snapshot';
export type SnapshotFileData = {
    id: number;
    path: string;
    sha256: string;
    sourceFile?: SourceFileData;
    snapshot?: SnapshotData;
    created_at: string;
    updated_at: string;
};
