import { ContentEncoding } from '../../Models/Enums';
import { SnapshotData } from '../Snapshot';
export type SourceFileContentsData = {
    content_encoding: ContentEncoding;
    contents: string;
};
export type SourceFileData = {
    sha256: string;
    size: number;
    exception: string | null;
    is_analyzed: boolean;
    has_thrown_exception: boolean;
    created_at: string | null;
    updated_at: string | null;
    snapshot?: SnapshotData;
};
