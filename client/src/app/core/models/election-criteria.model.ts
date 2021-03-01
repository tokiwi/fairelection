import { Assignment } from './assignment.model';

export interface ElectionCriteria {
    '@context': string;
    '@id': string;
    '@type': string;
    criteria?: any;
    assignments?: Assignment[];
}
