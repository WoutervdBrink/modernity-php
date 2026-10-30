import { TurboDocument } from '../Analysis/Turbo';
export type AnalyzePlaygroundSourceRequestData = {
    source: string;
};
export type AnalyzePlaygroundSourceResponseData = {
    analysis: TurboDocument | null;
    error: string | null;
};
