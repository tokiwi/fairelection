import { CriteriaItem } from './criteria-item.model';

export interface Assignment {
    '@id': string;
    '@type': string;
    percent: number;
    item: CriteriaItem;
}
