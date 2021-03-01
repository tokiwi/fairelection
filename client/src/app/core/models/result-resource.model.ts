import { ResultItemResource } from './result-item-resource.model';

export interface ResultResource {
    '@id': string;
    '@type': string;
    rows: ResultItemResource[];
    stats: {key: string; value: {key: string; value: number}[]}[];
}
